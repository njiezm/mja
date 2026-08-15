<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdhesionRequest;
use App\Mail\AdhesionConfirmation;
use App\Mail\AdhesionNotification;
use App\Mail\AdhesionStatusUpdate;
use App\Models\Adhesion;
use App\Models\AdhesionPeriod;
use App\Models\Setting;
use App\Services\StripeService;
use App\Support\Cotisation;
use App\Support\Telephone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AdhesionController extends Controller
{
    public function create()
    {
        // Un adhérent connecté n'a rien à ressaisir : on l'envoie directement
        // sur son écran de renouvellement pré-rempli.
        $user = Auth::user();

        if ($user?->isMember() && $user->adhesion) {
            return redirect()->route('adhesion.renouveler.espace');
        }

        return view('adhesion', $this->donneesVue());
    }

    /**
     * Renouvellement depuis l'espace adhérent : le formulaire est pré-rempli
     * avec la dernière adhésion, il n'y a plus qu'à vérifier et payer.
     */
    public function renouvelerDepuisEspace()
    {
        $user = Auth::user();
        $precedente = $user?->adhesion;

        // Compte sans adhésion rattachée (administrateur, ou adhérent dont la
        // fiche n'a pas encore été liée) : il n'y a rien à pré-remplir. On
        // l'envoie sur le formulaire vierge plutôt que sur une page 404.
        if (! $precedente) {
            return redirect()->route('adhesion')
                ->with('error', "Aucune adhésion n'est rattachée à votre compte : remplissez le formulaire ci-dessous.");
        }

        return view('adhesion', $this->donneesVue($precedente));
    }

    /**
     * Renouvellement par lien magique reçu par email : même écran, sans
     * connexion — pour les adhérents qui n'ont jamais créé de compte.
     */
    public function renouvelerParLien(string $token)
    {
        $precedente = Adhesion::where('renouvellement_token', $token)->first();

        if (! $precedente
            || ! $precedente->renouvellement_token_expires_at
            || $precedente->renouvellement_token_expires_at->isPast()) {
            return redirect()->route('adhesion')
                ->with('error', "Ce lien de renouvellement a expiré. Remplissez le formulaire ci-dessous, ou connectez-vous à votre espace adhérent.");
        }

        return view('adhesion', $this->donneesVue($precedente));
    }

    /** Variables communes aux trois façons d'ouvrir le formulaire. */
    private function donneesVue(?Adhesion $precedente = null): array
    {
        $prefill = [];

        if ($precedente) {
            $prefill = $precedente->donneesReprises();
            [$prefill['indicatif'], $prefill['telephone']] = Telephone::separer($precedente->telephone);
            $prefill['premiere_adhesion'] = 'readhesion';
        }

        return [
            'stripeEnabled'  => StripeService::enabled(),
            'stripePublicKey' => StripeService::publicKey(),
            'precedente'     => $precedente,
            'prefill'        => $prefill,
            'periode'        => AdhesionPeriod::current(),
        ];
    }

    /**
     * Crée un PaymentIntent pour le paiement carte intégré au formulaire.
     * Appelé en AJAX quand le visiteur choisit « Carte bancaire ».
     */
    public function paymentIntent()
    {
        if (! StripeService::enabled()) {
            return response()->json(['error' => 'Le paiement en ligne est indisponible.'], 422);
        }

        $intent = StripeService::createPaymentIntent(StripeService::amountCents(), ['type' => 'adhesion']);

        if (! $intent) {
            return response()->json(['error' => "Le paiement est momentanément indisponible."], 502);
        }

        return response()->json([
            'client_secret' => $intent['client_secret'],
            'public_key'    => StripeService::publicKey(),
            'amount'        => StripeService::amountCents(),
            'cotisation'    => Cotisation::formatee(),
            'frais'         => Cotisation::fraisFormates(),
            'total'         => Cotisation::carteFormatee(),
        ]);
    }

    public function store(AdhesionRequest $request)
    {
        $donnees = $request->donneesAdhesion();

        $precedente = $this->adhesionPrecedente($request);

        $donnees['source_id'] = $request->session()->get('mja_source_id');
        $donnees['period_id'] = AdhesionPeriod::current()?->id;
        $donnees['user_id']   = Auth::id() ?: $precedente?->user_id;

        if ($precedente) {
            $donnees['renouvelle_adhesion_id'] = $precedente->id;
            // À défaut de nouvelle photo, on reprend celle de l'an dernier.
            $donnees['photo'] = $precedente->photo;
        }

        if ($request->hasFile('photo')) {
            $donnees['photo'] = $request->file('photo')->store('adhesions/photos', 'public');
        }

        // Paiement carte : le règlement a lieu dans le formulaire, avant l'envoi.
        // On revérifie systématiquement le PaymentIntent auprès de Stripe — le
        // navigateur n'est jamais une source de vérité sur un paiement.
        $cartePayee = false;

        if (($donnees['moyen_paiement'] ?? null) === 'en_ligne') {
            $cartePayee = StripeService::paiementValide($request->input('payment_intent_id'));

            if (! $cartePayee) {
                return back()->withInput()
                    ->withErrors(['moyen_paiement' => "Le paiement par carte n'a pas été validé. Réglez la cotisation dans le formulaire avant d'envoyer votre demande."]);
            }
        }

        $donnees['statut'] = match (true) {
            $donnees['premiere_adhesion'] === 'information' => 'prise_infos',
            $cartePayee                                     => 'payee',
            default                                         => 'nouvelle',
        };

        $adhesion = Adhesion::create($donnees);

        // Le renouvellement devient l'adhésion courante du compte.
        if ($adhesion->user_id) {
            \App\Models\User::whereKey($adhesion->user_id)->update(['adhesion_id' => $adhesion->id]);
        }

        // Le lien de renouvellement précédent ne doit plus rouvrir un formulaire.
        $precedente?->forceFill([
            'renouvellement_token'            => null,
            'renouvellement_token_expires_at' => null,
        ])->save();

        // Notification à l'association (toujours) — liste configurable en back-office.
        try {
            Mail::to(Setting::notificationEmails())->send(new AdhesionNotification($adhesion));
        } catch (\Throwable $e) {
            Log::error('Mail notification adhésion échoué : ' . $e->getMessage());
        }

        // Email de confirmation (carte déjà réglée, moyens hors ligne, prise d'informations).
        try {
            Mail::to($adhesion->email)->send(new AdhesionConfirmation($adhesion));
        } catch (\Throwable $e) {
            Log::error('Mail confirmation adhésion échoué : ' . $e->getMessage());
        }

        return back()->with('success', true)->with('renouvellement', $precedente !== null);
    }

    /**
     * Adhésion que ce formulaire renouvelle, si le contexte le dit — jeton
     * magique posté avec le formulaire, ou adhérent connecté.
     */
    private function adhesionPrecedente(Request $request): ?Adhesion
    {
        $token = $request->input('renouvellement_token');

        if ($token) {
            $precedente = Adhesion::where('renouvellement_token', $token)
                ->whereNotNull('renouvellement_token_expires_at')
                ->where('renouvellement_token_expires_at', '>', now())
                ->first();

            if ($precedente) {
                return $precedente;
            }
        }

        $user = Auth::user();

        return $user?->adhesion;
    }

    /** Retour Stripe : paiement réussi → statut « payée » + email de bienvenue. */
    public function paiementSucces(Request $request)
    {
        $sessionId = $request->query('session_id');

        if (! $sessionId || ! StripeService::enabled()) {
            return redirect()->route('adhesion');
        }

        $session = StripeService::retrieveSession($sessionId);

        if ($session && ($session['payment_status'] ?? null) === 'paid') {
            $id = $session['metadata']['adhesion_id'] ?? ($session['client_reference_id'] ?? null);
            $adhesion = $id ? Adhesion::find($id) : null;

            if ($adhesion && ! $adhesion->isAdherent()) {
                $adhesion->update(['statut' => 'payee']);
                $adhesion->ensureAccountToken();
                try {
                    Mail::to($adhesion->email)->send(new AdhesionStatusUpdate($adhesion));
                } catch (\Throwable $e) {
                    Log::error('Mail paiement adhésion échoué : ' . $e->getMessage());
                }
            }

            return redirect()->route('adhesion')->with('success', true)->with('paye', true);
        }

        return redirect()->route('adhesion')->with('error', "Le paiement n'a pas pu être confirmé. Contactez-nous si vous avez été débité.");
    }

    /** Retour Stripe : paiement annulé. */
    public function paiementAnnule(Request $request)
    {
        return redirect()->route('adhesion')
            ->with('error', "Paiement annulé. Votre demande reste enregistrée, vous pourrez régler plus tard.");
    }
}
