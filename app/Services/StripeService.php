<?php

namespace App\Services;

use App\Models\Adhesion;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class StripeService
{
    private const API = 'https://api.stripe.com/v1';

    /** Le paiement en ligne est-il utilisable (activé + clés présentes) ? */
    public static function enabled(): bool
    {
        return (bool) Setting::get('stripe_enabled')
            && Setting::has('stripe_secret_key')
            && Setting::has('stripe_public_key');
    }

    /** Montant de la cotisation en centimes. */
    public static function amountCents(): int
    {
        return (int) round(((float) Setting::get('cotisation_amount', 20)) * 100);
    }

    private static function secret(): ?string
    {
        return Setting::get('stripe_secret_key');
    }

    /** Crée une session Stripe Checkout et renvoie l'URL de paiement (ou null). */
    public static function createCheckoutSession(Adhesion $adhesion, string $successUrl, string $cancelUrl): ?string
    {
        $resp = Http::asForm()->withToken(self::secret())->post(self::API . '/checkout/sessions', [
            'mode'                                              => 'payment',
            'success_url'                                       => $successUrl,
            'cancel_url'                                        => $cancelUrl,
            'client_reference_id'                               => $adhesion->id,
            'customer_email'                                    => $adhesion->email,
            'metadata[adhesion_id]'                             => $adhesion->id,
            'line_items[0][quantity]'                           => 1,
            'line_items[0][price_data][currency]'               => 'eur',
            'line_items[0][price_data][unit_amount]'            => self::amountCents(),
            'line_items[0][price_data][product_data][name]'     => "Cotisation adhésion — Madin'Jeunes Ambition",
        ]);

        if ($resp->failed()) {
            Log::error('Stripe checkout session failed: ' . $resp->body());
            return null;
        }

        return $resp->json('url');
    }

    /** Récupère une session Checkout (tableau) ou null. */
    public static function retrieveSession(string $sessionId): ?array
    {
        $resp = Http::withToken(self::secret())->get(self::API . '/checkout/sessions/' . $sessionId);

        if ($resp->failed()) {
            Log::error('Stripe retrieve session failed: ' . $resp->body());
            return null;
        }

        return $resp->json();
    }
}
