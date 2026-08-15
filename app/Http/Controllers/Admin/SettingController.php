<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function edit()
    {
        $settings = [
            'stripe_enabled'       => (bool) Setting::get('stripe_enabled'),
            'stripe_public_key'    => Setting::get('stripe_public_key'),
            'stripe_secret_set'    => Setting::has('stripe_secret_key'),
            'stripe_webhook_set'   => Setting::has('stripe_webhook_secret'),
            'cotisation_amount'    => Setting::get('cotisation_amount', 20),
            'stripe_fee_passthrough' => \App\Support\Cotisation::fraisRepercutes(),
            'stripe_fee_percent'   => Setting::get('stripe_fee_percent', 1.5),
            'stripe_fee_fixed'     => Setting::get('stripe_fee_fixed', 0.25),
            'stripe_fee_round_to'  => Setting::get('stripe_fee_round_to', 0.05),
            'iban'                 => Setting::get('iban'),
            'bic'                  => Setting::get('bic'),
            'notification_emails'  => Setting::get('notification_emails'),
            'helloasso_url'        => Setting::get('helloasso_url'),
            'don_priorite'         => Setting::get('don_priorite') ?: 'stripe',
            // Les clés secrètes Stripe engagent les encaissements : leur
            // modification reste au super admin, même si la page est ouverte
            // à tous les administrateurs.
            'peut_modifier_secrets' => auth()->user()->isSuperAdmin(),
        ];

        return view('admin.settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'stripe_public_key'  => 'nullable|string|max:255',
            'stripe_secret_key'  => 'nullable|string|max:255',
            'stripe_webhook_secret' => 'nullable|string|max:255',
            'cotisation_amount'  => 'required|numeric|min:0|max:10000',
            'stripe_fee_percent' => 'required|numeric|min:0|max:20',
            'stripe_fee_fixed'   => 'required|numeric|min:0|max:10',
            'stripe_fee_round_to' => 'required|numeric|min:0|max:1',
            'iban'               => 'nullable|string|max:60',
            'bic'                => 'nullable|string|max:20',
            'notification_emails' => 'nullable|string|max:2000',
            'helloasso_url'      => 'nullable|url|max:255',
            'don_priorite'       => 'nullable|in:stripe,lien',
        ]);

        Setting::set('helloasso_url', $validated['helloasso_url']);
        Setting::set('don_priorite', $validated['don_priorite'] ?? 'stripe');

        // Normalise la liste d'emails : découpe, valide, dédoublonne.
        $emails = preg_split('/[\s,;]+/', (string) $validated['notification_emails'], -1, PREG_SPLIT_NO_EMPTY) ?: [];
        $invalides = array_filter($emails, fn ($e) => ! filter_var($e, FILTER_VALIDATE_EMAIL));
        if ($invalides) {
            return back()->withInput()->with('error', 'Adresse(s) invalide(s) : ' . implode(', ', $invalides));
        }
        Setting::set('notification_emails', implode("\n", array_values(array_unique($emails))));

        Setting::set('stripe_enabled', $request->boolean('stripe_enabled') ? '1' : '');
        Setting::set('stripe_public_key', $validated['stripe_public_key']);
        Setting::set('cotisation_amount', $validated['cotisation_amount']);

        // Frais de transaction répercutés sur le payeur : sans cela, la
        // commission Stripe est prise sur la cotisation de l'association.
        Setting::set('stripe_fee_passthrough', $request->boolean('stripe_fee_passthrough') ? '1' : '0');
        Setting::set('stripe_fee_percent', $validated['stripe_fee_percent']);
        Setting::set('stripe_fee_fixed', $validated['stripe_fee_fixed']);
        Setting::set('stripe_fee_round_to', $validated['stripe_fee_round_to']);

        // Coordonnées bancaires reprises dans les emails de confirmation et de relance.
        Setting::set('iban', $validated['iban']);
        Setting::set('bic', $validated['bic']);

        // Secrets : super admin uniquement, et seulement si une nouvelle
        // valeur est saisie (un champ vide conserve la clé en place).
        if ($request->user()->isSuperAdmin()) {
            if (! empty($validated['stripe_secret_key'])) {
                Setting::set('stripe_secret_key', $validated['stripe_secret_key']);
            }
            if (! empty($validated['stripe_webhook_secret'])) {
                Setting::set('stripe_webhook_secret', $validated['stripe_webhook_secret']);
            }
        }

        return back()->with('success', 'Paramètres enregistrés.');
    }
}
