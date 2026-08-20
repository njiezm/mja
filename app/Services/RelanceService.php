<?php

namespace App\Services;

use App\Mail\RelancePaiement;
use App\Mail\RelanceRenouvellement;
use App\Models\Adhesion;
use App\Models\AdhesionPeriod;
use App\Models\AdhesionRelance;
use App\Models\Setting;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Relances par email : cotisation en attente et renouvellement de saison.
 *
 * Le journal `adhesion_relances` est la seule référence pour savoir ce qui a
 * déjà été envoyé. Rien ne dépend d'un compteur porté par l'adhésion, ce qui
 * rend l'envoi idempotent même si le déclencheur est appelé deux fois.
 */
class RelanceService
{
    /** Réglages par défaut, écrasables en back-office. */
    public const DEFAUTS = [
        'relance_paiement_active'          => 1,
        'relance_paiement_delai'           => 7,   // jours après la demande
        'relance_paiement_intervalle'      => 14,  // jours entre deux relances
        'relance_paiement_max'             => 3,
        'relance_renouvellement_active'    => 1,
        'relance_renouvellement_avant'     => 30,  // jours avant la fin de saison
        'relance_renouvellement_intervalle' => 21,
        'relance_renouvellement_max'       => 3,
        'relances_heure'                   => 9,   // heure d'envoi (0-23)
    ];

    /**
     * Clé de l'interrupteur général. Volontairement hors de DEFAUTS : le
     * formulaire de réglages boucle sur DEFAUTS et remettrait la suspension à
     * zéro à chaque enregistrement.
     */
    public const CLE_SUSPENSION = 'relances_suspendues';

    /** Suspension globale : tant qu'elle est active, aucune relance ne part. */
    public static function suspendues(): bool
    {
        return (bool) Setting::get(self::CLE_SUSPENSION);
    }

    public static function reglage(string $cle): int
    {
        $valeur = Setting::get($cle, self::DEFAUTS[$cle] ?? 0);

        return (int) $valeur;
    }

    public static function actif(string $cle): bool
    {
        return self::reglage($cle) === 1;
    }

    /**
     * Envoie toutes les relances dues.
     *
     * @param  bool  $simulation  n'envoie rien, se contente de lister.
     * @return array{paiement: int, renouvellement: int, echecs: int, details: array<int, string>}
     */
    public function executer(bool $simulation = false, bool $automatique = true): array
    {
        $bilan = ['paiement' => 0, 'renouvellement' => 0, 'echecs' => 0, 'details' => []];

        // Interrupteur général : il court-circuite les deux types, quels que
        // soient leurs réglages propres.
        if (self::suspendues()) {
            return $bilan;
        }

        if (self::actif('relance_paiement_active')) {
            foreach ($this->paiementsARelancer() as $adhesion) {
                $numero = $this->prochainNumero($adhesion, AdhesionRelance::TYPE_PAIEMENT);

                if ($simulation) {
                    $bilan['paiement']++;
                    $bilan['details'][] = "Paiement #{$numero} → {$adhesion->email}";
                    continue;
                }

                $this->envoyer($adhesion, AdhesionRelance::TYPE_PAIEMENT, $numero, $automatique)
                    ? $bilan['paiement']++
                    : $bilan['echecs']++;
            }
        }

        if (self::actif('relance_renouvellement_active')) {
            foreach ($this->renouvellementsARelancer() as $adhesion) {
                $numero = $this->prochainNumero($adhesion, AdhesionRelance::TYPE_RENOUVELLEMENT);

                if ($simulation) {
                    $bilan['renouvellement']++;
                    $bilan['details'][] = "Renouvellement #{$numero} → {$adhesion->email}";
                    continue;
                }

                $this->envoyer($adhesion, AdhesionRelance::TYPE_RENOUVELLEMENT, $numero, $automatique)
                    ? $bilan['renouvellement']++
                    : $bilan['echecs']++;
            }
        }

        return $bilan;
    }

    // ─── Sélection des destinataires ──────────────────────────────────────────

    /**
     * Adhésions dont la cotisation est due mais non encaissée : moyens de
     * paiement hors ligne uniquement (chèque, espèces, virement) — un paiement
     * carte est encaissé immédiatement ou l'adhésion n'existe pas.
     *
     * @return Collection<int, Adhesion>
     */
    public function paiementsARelancer(): Collection
    {
        $delai       = self::reglage('relance_paiement_delai');
        $intervalle  = self::reglage('relance_paiement_intervalle');
        $maximum     = self::reglage('relance_paiement_max');

        return Adhesion::query()
            ->whereIn('statut', ['nouvelle', 'en_attente_paiement'])
            ->whereIn('moyen_paiement', ['cheque', 'espece', 'virement'])
            ->where('created_at', '<=', now()->subDays($delai))
            ->with('relances')
            ->get()
            ->filter(fn (Adhesion $a) => $this->estDue($a, AdhesionRelance::TYPE_PAIEMENT, $intervalle, $maximum))
            ->values();
    }

    /**
     * Adhérents à jour sur une saison révolue, sans adhésion sur la saison en
     * cours. La relance part quand la fin de leur saison approche.
     *
     * @return Collection<int, Adhesion>
     */
    public function renouvellementsARelancer(): Collection
    {
        $courante = AdhesionPeriod::current();

        if (! $courante) {
            return collect();
        }

        $avant      = self::reglage('relance_renouvellement_avant');
        $intervalle = self::reglage('relance_renouvellement_intervalle');
        $maximum    = self::reglage('relance_renouvellement_max');

        // Emails déjà couverts par la saison en cours : ils n'ont rien à renouveler.
        $dejaAJour = Adhesion::where('period_id', $courante->id)
            ->pluck('email')
            ->map(fn ($e) => mb_strtolower(trim((string) $e)))
            ->filter()
            ->unique()
            ->flip();

        return Adhesion::query()
            ->where('statut', 'payee')
            ->where(function ($q) use ($courante) {
                $q->whereNull('period_id')->orWhere('period_id', '!=', $courante->id);
            })
            ->with(['relances', 'period'])
            // Une seule relance par personne : sa dernière adhésion fait foi.
            ->orderByDesc('created_at')
            ->get()
            ->unique(fn (Adhesion $a) => mb_strtolower(trim((string) $a->email)))
            ->reject(fn (Adhesion $a) => $dejaAJour->has(mb_strtolower(trim((string) $a->email))))
            ->filter(fn (Adhesion $a) => $this->saisonBientotFinie($a, $avant))
            ->filter(fn (Adhesion $a) => $this->estDue($a, AdhesionRelance::TYPE_RENOUVELLEMENT, $intervalle, $maximum))
            ->values();
    }

    /**
     * La saison de cette adhésion touche-t-elle à sa fin (ou est-elle déjà
     * terminée) ? Sans période rattachée, on considère qu'il est temps.
     */
    private function saisonBientotFinie(Adhesion $adhesion, int $joursAvant): bool
    {
        $fin = $adhesion->period?->date_fin;

        if (! $fin) {
            return true;
        }

        return Carbon::parse($fin)->subDays($joursAvant)->isPast();
    }

    /** Une relance de ce type est-elle due maintenant ? */
    private function estDue(Adhesion $adhesion, string $type, int $intervalle, int $maximum): bool
    {
        $envoyees = $adhesion->relances->where('type', $type);

        if ($envoyees->count() >= $maximum) {
            return false;
        }

        $derniere = $envoyees->max('envoyee_le');

        return $derniere === null || Carbon::parse($derniere)->addDays($intervalle)->isPast();
    }

    private function prochainNumero(Adhesion $adhesion, string $type): int
    {
        return $adhesion->relances->where('type', $type)->count() + 1;
    }

    // ─── Envoi ────────────────────────────────────────────────────────────────

    /** Envoie une relance et la journalise. Renvoie false si l'envoi a échoué. */
    public function envoyer(Adhesion $adhesion, string $type, ?int $numero = null, bool $automatique = true): bool
    {
        $numero ??= $this->prochainNumero($adhesion, $type);

        try {
            $message = $type === AdhesionRelance::TYPE_PAIEMENT
                ? new RelancePaiement($adhesion, $numero)
                : new RelanceRenouvellement($adhesion, $numero, $adhesion->ensureRenouvellementToken());

            Mail::to($adhesion->email)->send($message);
        } catch (\Throwable $e) {
            Log::error("[Relance {$type}] échec pour {$adhesion->email} : " . $e->getMessage());

            return false;
        }

        AdhesionRelance::create([
            'adhesion_id' => $adhesion->id,
            'type'        => $type,
            'numero'      => $numero,
            'email'       => $adhesion->email,
            'automatique' => $automatique,
            'envoyee_le'  => now(),
        ]);

        return true;
    }
}
