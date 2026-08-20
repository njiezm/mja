<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Adhesion;
use App\Models\AdhesionRelance;
use App\Models\Setting;
use App\Services\RelanceService;
use Illuminate\Http\Request;

/**
 * Pilotage des relances : réglages, aperçu des envois dus, déclenchement
 * manuel et historique.
 */
class RelanceController extends Controller
{
    public function __construct(private RelanceService $relances) {}

    public function index()
    {
        $reglages = [];
        foreach (array_keys(RelanceService::DEFAUTS) as $cle) {
            $reglages[$cle] = RelanceService::reglage($cle);
        }

        return view('admin.relances.index', [
            'reglages'        => $reglages,
            'paiements'       => $this->relances->paiementsARelancer(),
            'renouvellements' => $this->relances->renouvellementsARelancer(),
            'historique'      => AdhesionRelance::with('adhesion:id,nom,prenom,email')
                ->orderByDesc('envoyee_le')
                ->paginate(20),
            'dernierEnvoi'    => AdhesionRelance::max('envoyee_le'),
            'suspendues'      => RelanceService::suspendues(),
        ]);
    }

    /** Interrupteur général : coupe ou rétablit tous les envois, auto et manuels. */
    public function suspendre(Request $request)
    {
        $suspendues = $request->boolean('suspendues');

        Setting::set(RelanceService::CLE_SUSPENSION, $suspendues ? '1' : '');

        return back()->with('success', $suspendues
            ? 'Relances suspendues : plus aucun email ne partira, ni automatiquement ni à la main.'
            : 'Relances réactivées.');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'relance_paiement_active'           => 'nullable|boolean',
            'relance_paiement_delai'            => 'required|integer|min:0|max:365',
            'relance_paiement_intervalle'       => 'required|integer|min:1|max:365',
            'relance_paiement_max'              => 'required|integer|min:1|max:10',
            'relance_renouvellement_active'     => 'nullable|boolean',
            'relance_renouvellement_avant'      => 'required|integer|min:0|max:365',
            'relance_renouvellement_intervalle' => 'required|integer|min:1|max:365',
            'relance_renouvellement_max'        => 'required|integer|min:1|max:10',
            'relances_heure'                    => 'required|integer|min:0|max:23',
        ]);

        foreach (array_keys(RelanceService::DEFAUTS) as $cle) {
            $valeur = str_ends_with($cle, '_active')
                ? (int) $request->boolean($cle)
                : (int) ($validated[$cle] ?? RelanceService::DEFAUTS[$cle]);

            Setting::set($cle, $valeur);
        }

        return back()->with('success', 'Réglages des relances enregistrés.');
    }

    /** Déclenchement manuel : envoie tout ce qui est dû, sans attendre. */
    public function executer()
    {
        if (RelanceService::suspendues()) {
            return back()->with('error', "Les relances sont suspendues. Réactivez-les avant d'envoyer.");
        }

        $bilan = $this->relances->executer(simulation: false, automatique: false);

        $total = $bilan['paiement'] + $bilan['renouvellement'];

        if ($total === 0 && $bilan['echecs'] === 0) {
            return back()->with('success', 'Aucune relance à envoyer pour le moment.');
        }

        return back()->with('success', sprintf(
            '%d relance(s) envoyée(s) : %d paiement, %d renouvellement%s.',
            $total,
            $bilan['paiement'],
            $bilan['renouvellement'],
            $bilan['echecs'] ? " — {$bilan['echecs']} échec(s), voir les logs" : '',
        ));
    }

    /** Relance immédiate d'une adhésion précise, depuis sa fiche ou la liste. */
    public function relancerUne(Request $request, Adhesion $adhesion)
    {
        if (RelanceService::suspendues()) {
            return back()->with('error', "Les relances sont suspendues. Réactivez-les avant d'envoyer.");
        }

        $type = $request->input('type') === AdhesionRelance::TYPE_RENOUVELLEMENT
            ? AdhesionRelance::TYPE_RENOUVELLEMENT
            : AdhesionRelance::TYPE_PAIEMENT;

        $envoye = $this->relances->envoyer($adhesion, $type, automatique: false);

        return back()->with(
            $envoye ? 'success' : 'error',
            $envoye
                ? "Relance envoyée à {$adhesion->email}."
                : "L'envoi à {$adhesion->email} a échoué — vérifiez la configuration email."
        );
    }

    /**
     * Purge du journal des relances.
     *
     * À manier avec prudence : ce journal est le garde-fou anti-doublon. Une
     * fois vidé, les compteurs repartent de zéro et les personnes déjà
     * relancées trois fois peuvent l'être à nouveau.
     */
    public function viderHistorique()
    {
        $supprimees = AdhesionRelance::query()->delete();

        return back()->with('success', $supprimees
            ? "Historique vidé : {$supprimees} relance(s) supprimée(s). Les compteurs repartent de zéro."
            : 'Aucune relance à supprimer.');
    }
}
