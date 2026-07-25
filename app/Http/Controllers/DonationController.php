<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Setting;
use App\Services\StripeService;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    public function create()
    {
        return view('don.create', [
            'stripeEnabled' => StripeService::enabled(),
            'helloassoUrl'  => Setting::get('helloasso_url'),
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

        if (! StripeService::enabled()) {
            return back()->withInput()->with('error', "Le paiement par carte n'est pas disponible pour le moment.");
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
