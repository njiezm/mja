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
        ]);
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
}
