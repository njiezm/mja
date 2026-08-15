<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AdhesionStatusUpdate;
use App\Models\Adhesion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdhesionController extends Controller
{
    public function index(Request $request)
    {
        $query = Adhesion::with('period')->orderByDesc('created_at');
        if ($request->input('period') === 'aucune') {
            $query->whereNull('period_id');
        } elseif ($request->filled('period')) {
            $query->where('period_id', $request->integer('period'));
        }
        $adhesions = $query->paginate(20)->withQueryString();

        $stats = [
            'total'     => Adhesion::count(),
            'nouvelles' => Adhesion::where('statut', 'nouvelle')->count(),
            'adherents' => Adhesion::where('statut', 'payee')->count(),
        ];
        $periods = \App\Models\AdhesionPeriod::orderByDesc('date_debut')->get();

        // Adhésions rattachées à aucune saison : elles échappent aux filtres,
        // aux exports par période et aux relances de renouvellement.
        $sansPeriode = Adhesion::whereNull('period_id')->count();

        return view('admin.adhesions.index', compact('adhesions', 'stats', 'periods', 'sansPeriode'));
    }

    public function export(Request $request): StreamedResponse
    {
        $query = Adhesion::with('period')->orderByDesc('created_at');
        if ($request->input('period') === 'aucune') {
            $query->whereNull('period_id');
        } elseif ($request->filled('period')) {
            $query->where('period_id', $request->integer('period'));
        }
        $adhesions = $query->get();

        return response()->streamDownload(function () use ($adhesions) {
            $out = fopen('php://output', 'w');
            fprintf($out, "\xEF\xBB\xBF"); // BOM UTF-8 (Excel)
            fputcsv($out, [
                'Reçue le', 'Statut', 'Type', 'Civilité', 'Nom', 'Prénom', 'Date naissance',
                'Profession', 'Téléphone', 'Email', 'T-shirt', 'Permis',
                'Problèmes santé', 'Contact urgence', 'Moyen paiement', 'Période',
            ], ';');
            foreach ($adhesions as $a) {
                fputcsv($out, [
                    $a->created_at?->format('d/m/Y H:i'),
                    $a->label_statut,
                    $a->label_premiere_adhesion,
                    $a->civilite,
                    $a->nom,
                    $a->prenom,
                    $a->date_naissance,
                    $a->profession,
                    $a->telephone,
                    $a->email,
                    str_replace(["\r", "\n"], ' ', (string) $a->adresse_postale),
                    $a->taille_tshirt,
                    $a->permis,
                    str_replace(["\r", "\n"], ' ', (string) $a->problemes_sante),
                    $a->urgence_contact,
                    $a->label_moyen_paiement,
                    $a->period?->label,
                ], ';');
            }
            fclose($out);
        }, 'adhesions-mja-' . now()->format('Y-m-d') . '.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function show(Adhesion $adhesion)
    {
        $adhesion->update(['lu' => true]);
        $periods = \App\Models\AdhesionPeriod::orderByDesc('date_debut')->get();

        return view('admin.adhesions.show', compact('adhesion', 'periods'));
    }

    public function updateStatut(Request $request, Adhesion $adhesion)
    {
        $validated = $request->validate([
            'statut' => ['required', Rule::in(array_keys(Adhesion::STATUTS))],
        ]);

        $ancien = $adhesion->statut;
        $adhesion->update(['statut' => $validated['statut']]);

        // Devient adhérent : préparer le lien de création de compte.
        if ($validated['statut'] === 'payee') {
            $adhesion->ensureAccountToken();
        }

        // Email personnalisé uniquement si le statut change vers un état « notifiable ».
        $notifiables = ['payee', 'en_attente_paiement', 'refusee'];
        if ($validated['statut'] !== $ancien && in_array($validated['statut'], $notifiables, true)) {
            try {
                Mail::to($adhesion->email)->send(new AdhesionStatusUpdate($adhesion));
            } catch (\Throwable $e) {
                Log::error('Mail statut adhésion échoué : ' . $e->getMessage());
            }
        }

        return back()->with('success', 'Statut mis à jour.');
    }

    /**
     * Attestation d'adhésion et carte de membre, vues du back-office.
     *
     * C'est exactement l'écran que voit l'adhérent dans son espace, avec le
     * même téléchargement en PDF : l'équipe peut ainsi rééditer le document
     * pour quelqu'un qui n'a pas de compte, ou qui n'y arrive pas seul.
     */
    public function carte(Adhesion $adhesion)
    {
        // Une attestation certifie une adhésion à jour : l'éditer pour une
        // demande non réglée reviendrait à attester quelque chose de faux.
        abort_unless(
            $adhesion->isAdherent(),
            403,
            "L'attestation n'est éditable que pour une adhésion à jour de cotisation.",
        );

        $adhesion->loadMissing('period');

        return view('member.card', ['adhesion' => $adhesion, 'member' => $adhesion->user]);
    }

    /** Rattache une adhésion à une saison, ou l'en détache. */
    public function updatePeriode(Request $request, Adhesion $adhesion)
    {
        $validated = $request->validate(
            ['period_id' => 'nullable|integer|exists:adhesion_periods,id'],
            ['period_id.exists' => "Cette saison n'existe pas."]
        );

        $adhesion->update(['period_id' => $validated['period_id'] ?? null]);
        // La relation chargée par le route-model binding est périmée après
        // l'update : sans ça, un détachement afficherait l'ancienne saison.
        $adhesion->unsetRelation('period');

        return back()->with('success', $adhesion->period
            ? "Adhésion rattachée à la « {$adhesion->period->label} »."
            : 'Adhésion détachée de toute saison.');
    }

    /**
     * Rattache d'un coup toutes les adhésions sans saison.
     *
     * Utile après une reprise de données : sans période, une adhésion
     * n'apparaît dans aucun filtre et ne déclenche jamais de relance de
     * renouvellement. On ne touche qu'aux adhésions orphelines — celles déjà
     * rattachées gardent leur saison.
     */
    public function rattacherPeriode(Request $request)
    {
        $validated = $request->validate(
            ['period_id' => 'required|integer|exists:adhesion_periods,id'],
            [
                'period_id.required' => 'Choisissez la saison de rattachement.',
                'period_id.exists'   => "Cette saison n'existe pas.",
            ]
        );

        $periode = \App\Models\AdhesionPeriod::findOrFail($validated['period_id']);
        $nombre = Adhesion::whereNull('period_id')->update(['period_id' => $periode->id]);

        return back()->with('success', $nombre === 0
            ? 'Aucune adhésion orpheline à rattacher.'
            : "{$nombre} adhésion(s) rattachée(s) à la « {$periode->label} ».");
    }

    public function destroy(Adhesion $adhesion)
    {
        if ($adhesion->photo) {
            Storage::disk('public')->delete($adhesion->photo);
        }
        $adhesion->delete();
        return redirect()->route('admin.adhesions.index')->with('success', 'Demande supprimée.');
    }
}
