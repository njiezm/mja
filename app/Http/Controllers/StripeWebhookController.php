<?php

namespace App\Http\Controllers;

use App\Mail\AdhesionStatusUpdate;
use App\Models\Adhesion;
use App\Models\Donation;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

/**
 * Réception des évènements Stripe.
 *
 * Le retour du navigateur après paiement n'est pas fiable : une personne peut
 * fermer son onglet pendant l'authentification 3-D Secure, perdre le réseau,
 * ou voir sa banque valider l'opération quelques secondes plus tard. Stripe
 * notifie alors le serveur directement — c'est ce que cette route écoute.
 *
 * À déclarer dans le tableau de bord Stripe :
 *   https://mja-martinique.com/stripe/webhook
 *   évènements : payment_intent.succeeded, checkout.session.completed,
 *                checkout.session.async_payment_succeeded
 * puis coller le secret « whsec_… » dans Paramètres → Stripe.
 */
class StripeWebhookController extends Controller
{
    /** Tolérance sur l'horodatage de la signature, en secondes. */
    private const TOLERANCE = 300;

    public function __invoke(Request $request)
    {
        $secret = Setting::get('stripe_webhook_secret');

        // Sans secret configuré, on refuse : accepter des évènements non
        // signés reviendrait à laisser n'importe qui marquer une adhésion payée.
        if (! $secret) {
            Log::warning('Webhook Stripe reçu alors qu\'aucun secret n\'est configuré.');

            return response()->json(['error' => 'webhook non configuré'], 400);
        }

        $charge = $request->getContent();

        if (! $this->signatureValide($charge, (string) $request->header('Stripe-Signature'), $secret)) {
            Log::warning('Webhook Stripe : signature invalide.');

            return response()->json(['error' => 'signature invalide'], 400);
        }

        $evenement = json_decode($charge, true);
        $type = $evenement['type'] ?? '';
        $objet = $evenement['data']['object'] ?? [];

        match ($type) {
            'payment_intent.succeeded'                  => $this->paiementAbouti($objet),
            'checkout.session.completed',
            'checkout.session.async_payment_succeeded'  => $this->sessionAboutie($objet),
            default                                     => null,
        };

        // Toujours 200 : un code d'erreur ferait réessayer Stripe indéfiniment
        // pour un évènement qui ne nous concerne pas.
        return response()->json(['recu' => true]);
    }

    /**
     * Vérifie la signature `Stripe-Signature: t=…,v1=…`.
     *
     * La comparaison passe par hash_equals : une comparaison ordinaire fuit,
     * par son temps d'exécution, le nombre de caractères déjà devinés.
     */
    private function signatureValide(string $charge, string $entete, string $secret): bool
    {
        $horodatage = null;
        $signatures = [];

        foreach (explode(',', $entete) as $partie) {
            [$cle, $valeur] = array_pad(explode('=', trim($partie), 2), 2, null);

            if ($cle === 't') {
                $horodatage = $valeur;
            } elseif ($cle === 'v1' && $valeur) {
                $signatures[] = $valeur;
            }
        }

        if (! $horodatage || ! $signatures) {
            return false;
        }

        // Rejoue : un évènement capté puis renvoyé plus tard ne doit pas passer.
        if (abs(time() - (int) $horodatage) > self::TOLERANCE) {
            return false;
        }

        $attendue = hash_hmac('sha256', $horodatage . '.' . $charge, $secret);

        foreach ($signatures as $signature) {
            if (hash_equals($attendue, $signature)) {
                return true;
            }
        }

        return false;
    }

    /** payment_intent.succeeded — paiement carte intégré au formulaire. */
    private function paiementAbouti(array $intent): void
    {
        $metadonnees = $intent['metadata'] ?? [];
        $id = $metadonnees['adhesion_id'] ?? null;

        if ($id && ($adhesion = Adhesion::find($id))) {
            $this->marquerPayee($adhesion);

            return;
        }

        // Paiement d'adhésion abouti sans demande enregistrée : la personne a
        // réglé puis quitté avant l'envoi du formulaire. Rien à rattacher
        // automatiquement — on prévient l'association, qui a de quoi retrouver
        // la personne et l'encaissement.
        if (($metadonnees['type'] ?? null) === 'adhesion') {
            $this->signalerPaiementOrphelin($intent);
        }
    }

    /** checkout.session.* — dons, et adhésions réglées par page Stripe. */
    private function sessionAboutie(array $session): void
    {
        if (($session['payment_status'] ?? null) !== 'paid') {
            return;
        }

        $metadonnees = $session['metadata'] ?? [];

        if ($id = $metadonnees['adhesion_id'] ?? ($session['client_reference_id'] ?? null)) {
            if ($adhesion = Adhesion::find($id)) {
                $this->marquerPayee($adhesion);
            }
        }

        if ($id = $metadonnees['donation_id'] ?? null) {
            Donation::whereKey($id)->where('statut', '!=', 'paye')->update([
                'statut'            => 'paye',
                'stripe_session_id' => $session['id'] ?? null,
            ]);
        }
    }

    /**
     * Passe l'adhésion en « payée » et ouvre l'accès à l'espace adhérent.
     *
     * Idempotent : Stripe réémet un évènement tant qu'il n'a pas reçu de 200,
     * et le même paiement peut donc arriver plusieurs fois.
     */
    private function marquerPayee(Adhesion $adhesion): void
    {
        if ($adhesion->isAdherent()) {
            return;
        }

        $adhesion->update(['statut' => 'payee']);
        Log::info("Webhook Stripe : adhésion #{$adhesion->id} marquée payée.");

        if (! $adhesion->user_id) {
            $adhesion->ensureAccountToken();

            try {
                Mail::to($adhesion->email)->send(new AdhesionStatusUpdate($adhesion));
            } catch (\Throwable $e) {
                Log::error('Mail de bienvenue (webhook) échoué : ' . $e->getMessage());
            }

            return;
        }

        // Compte déjà existant : un lien d'accès, jamais un mot de passe.
        try {
            Password::broker('members')->sendResetLink(
                ['email' => Str::lower(trim($adhesion->email))],
                fn ($user, $token) => $user->notify(new \App\Notifications\MemberResetPassword($token)),
            );
        } catch (\Throwable $e) {
            Log::error("Lien d'accès (webhook) échoué : " . $e->getMessage());
        }
    }

    /** Alerte à l'association : de l'argent est arrivé sans demande associée. */
    private function signalerPaiementOrphelin(array $intent): void
    {
        $montant = number_format(((int) ($intent['amount_received'] ?? 0)) / 100, 2, ',', ' ');
        $email = $intent['receipt_email'] ?? ($intent['charges']['data'][0]['billing_details']['email'] ?? 'inconnu');
        $reference = $intent['id'] ?? 'inconnue';

        Log::warning("Paiement d'adhésion sans demande enregistrée : {$reference} ({$montant} EUR, {$email})");

        $corps = "Un paiement de cotisation a abouti sans qu'une demande d'adhésion soit enregistrée.\n\n"
            . "Cela arrive quand la personne règle puis ferme son navigateur avant d'envoyer le formulaire.\n\n"
            . "Référence Stripe : {$reference}\n"
            . "Montant : {$montant} EUR\n"
            . "Email du payeur : {$email}\n\n"
            . "À faire : contacter la personne pour recueillir ses informations, puis créer son adhésion "
            . "en back-office avec le statut « Payée ».";

        try {
            Mail::raw($corps, function ($message) {
                $message->to(Setting::notificationEmails())
                    ->subject("Paiement d'adhésion sans demande — à rattacher");
            });
        } catch (\Throwable $e) {
            Log::error('Alerte paiement orphelin échouée : ' . $e->getMessage());
        }
    }
}
