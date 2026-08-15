<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Setting;
use App\Services\StripeService;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    /**
      * Moyen de don retenu, et son remplaçant.
      *
      * Le formulaire par carte est la voie normale : le don se termine sans
      * quitter le site, et l'association garde la main sur le reçu. Un lien
      * renseigné en back-office prend le relais quand le paiement en ligne
      * n'est pas disponible — ou passe devant si l'association le décide,
      * par exemple pour confier les reçus fiscaux à une plateforme.
      *
      * @return array{principal: string, secondaire: ?string, lien: ?string}
      */
     private function moyens(bool $carteDemandee = false): array
     {
         $lien = Setting::get('helloasso_url');
         $carte = StripeService::enabled();
         // « ?carte=1 » : le visiteur a cliqué sur le second moyen proposé.
         $prefereLien = Setting::get('don_priorite') === 'lien' && ! $carteDemandee;

         // Une préférence ne vaut que si le moyen demandé existe réellement.
         if ($lien && ($prefereLien || ! $carte)) {
             return ['principal' => 'lien', 'secondaire' => $carte ? 'carte' : null, 'lien' => $lien];
         }

         if ($carte) {
             return ['principal' => 'carte', 'secondaire' => $lien ? 'lien' : null, 'lien' => $lien];
         }

         return ['principal' => 'aucun', 'secondaire' => null, 'lien' => null];
     }

     public function create(Request $request)
     {
         return view('don.create', $this->moyens($request->boolean('carte')) + [
             'stripeEnabled' => StripeService::enabled(),
             'presets'       => [5, 10, 20, 50, 100],
         ]);
     }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'montant' => 'required|numeric|min:1|max:100000',
            'prenom'  => 'nullable|string|max:100',
            'nom'     => 'nullable|string|max:100',
            'email'   => 'required|email|max:150',
            'message' => 'nullable|string|max:500',
        ], [
            'montant.required' => 'Indiquez un montant.',
            'montant.min'      => 'Le montant minimum est de 1 €.',
            'email.required'   => 'Votre email est requis pour le reçu.',
        ]);

        // Le formulaire n'est affiché que si la carte est active ; on revérifie
        // tout de même, un réglage ayant pu changer entre l'affichage et l'envoi.
        if (! StripeService::enabled()) {
            $moyens = $this->moyens();

            return $moyens['lien']
                ? redirect()->away($moyens['lien'])
                : back()->withInput()->with('error', "Le don en ligne n'est pas disponible pour le moment.");
        }

        $don = Donation::create($validated + ['statut' => 'en_attente']);

        $url = StripeService::createDonationCheckout(
            $don,
            route('don.merci') . '?session_id={CHECKOUT_SESSION_ID}',
            route('don') . '?annule=1',
        );

        if ($url) {
            return redirect()->away($url);
        }

        return back()->with('error', "Le paiement est momentanément indisponible, réessayez plus tard.");
    }

    public function merci(Request $request)
    {
        $sessionId = $request->query('session_id');
        $don = null;

        if ($sessionId && StripeService::enabled()) {
            $session = StripeService::retrieveSession($sessionId);
            if ($session && ($session['payment_status'] ?? null) === 'paid') {
                $id = $session['metadata']['donation_id'] ?? ($session['client_reference_id'] ?? null);
                $don = $id ? Donation::find($id) : null;
                if ($don && ! $don->isPaid()) {
                    $don->update(['statut' => 'paye', 'stripe_session_id' => $sessionId]);
                }
            }
        }

        return view('don.merci', compact('don'));
    }
}
