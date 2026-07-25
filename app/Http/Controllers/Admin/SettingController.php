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
            'notification_emails'  => Setting::get('notification_emails'),
            'helloasso_url'        => Setting::get('helloasso_url'),
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
            'notification_emails' => 'nullable|string|max:2000',
            'helloasso_url'      => 'nullable|url|max:255',
        ]);

        Setting::set('helloasso_url', $validated['helloasso_url']);

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

        // Secrets : mis à jour uniquement si une nouvelle valeur est saisie.
        if (! empty($validated['stripe_secret_key'])) {
            Setting::set('stripe_secret_key', $validated['stripe_secret_key']);
        }
        if (! empty($validated['stripe_webhook_secret'])) {
            Setting::set('stripe_webhook_secret', $validated['stripe_webhook_secret']);
        }

        return back()->with('success', 'Paramètres enregistrés.');
    }
}
