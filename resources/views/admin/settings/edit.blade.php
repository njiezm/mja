@extends('layouts.admin')
@section('title', 'Paramètres')
@section('page-title', 'Paramètres')
@section('content')
<div class="max-w-5xl mt-4">
    <form method="POST" action="{{ route('admin.settings.update') }}">
        @csrf @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">

        {{-- Paiement en ligne / Stripe --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="flex h-1"><div class="flex-1 bg-mja-blue"></div><div class="flex-1 bg-mja-yellow"></div><div class="flex-1 bg-mja-red"></div></div>
            <div class="p-6 space-y-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center"><i class="fab fa-stripe-s"></i></div>
                    <div>
                        <h2 class="font-display font-bold text-gray-900">Paiement en ligne (Stripe)</h2>
                        <p class="text-xs text-gray-400">Cotisation par carte bancaire — passage automatique au statut « Payée ».</p>
                    </div>
                </div>

                <label class="flex items-center gap-3 cursor-pointer bg-gray-50 rounded-xl p-3">
                    <input type="checkbox" name="stripe_enabled" value="1" {{ $settings['stripe_enabled'] ? 'checked' : '' }} class="w-5 h-5 rounded text-mja-blue">
                    <span class="text-sm font-semibold text-gray-700">Activer le paiement en ligne</span>
                    @if($settings['stripe_enabled'])<span class="ml-auto text-xs bg-green-50 text-green-700 font-bold px-2 py-0.5 rounded-full">Actif</span>@endif
                </label>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Clé publique (Publishable key)</label>
                    <input type="text" name="stripe_public_key" value="{{ old('stripe_public_key', $settings['stripe_public_key']) }}" placeholder="pk_live_… ou pk_test_…"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-mja-blue">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Clé secrète (Secret key)
                        @if($settings['stripe_secret_set'])<span class="text-xs text-green-600 font-normal">— définie ✔ (laisser vide pour conserver)</span>@endif
                    </label>
                    <input type="password" name="stripe_secret_key" autocomplete="new-password" placeholder="{{ $settings['stripe_secret_set'] ? '•••••••••••• (inchangée)' : 'sk_live_… ou sk_test_…' }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-mja-blue">
                    <p class="text-[11px] text-gray-400 mt-1">Stockée chiffrée. Jamais réaffichée.</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Secret du webhook <span class="text-gray-400 font-normal">(optionnel)</span>
                        @if($settings['stripe_webhook_set'])<span class="text-xs text-green-600 font-normal">— défini ✔</span>@endif
                    </label>
                    <input type="password" name="stripe_webhook_secret" autocomplete="new-password" placeholder="{{ $settings['stripe_webhook_set'] ? '•••••••••••• (inchangé)' : 'whsec_…' }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-mja-blue">
                </div>
            </div>
        </div>

        {{-- Colonne droite : Cotisation + Notifications --}}
        <div class="space-y-6">
            {{-- Cotisation --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
                <h2 class="font-display font-bold text-gray-900"><i class="fas fa-euro-sign text-mja-yellow mr-1"></i> Cotisation</h2>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Montant de la cotisation (€) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" min="0" name="cotisation_amount" value="{{ old('cotisation_amount', $settings['cotisation_amount']) }}" required
                        class="w-40 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-mja-blue">
                </div>
            </div>

            {{-- Notifications --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
                <h2 class="font-display font-bold text-gray-900"><i class="fas fa-bell text-mja-red mr-1"></i> Emails de notification</h2>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Destinataires des notifications admin</label>
                    <textarea name="notification_emails" rows="4" placeholder="contact@mja-martinique.com&#10;secretariat@mja-martinique.com"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-mja-blue">{{ old('notification_emails', $settings['notification_emails']) }}</textarea>
                    <p class="text-[11px] text-gray-400 mt-1.5">Une adresse par ligne (ou séparées par des virgules). Ces adresses reçoivent les notifications : nouvelle adhésion, nouveau message de contact, etc. Si vide, l'adresse par défaut est utilisée.</p>
                </div>
            </div>

            {{-- Dons --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
                <h2 class="font-display font-bold text-gray-900"><i class="fas fa-heart text-mja-red mr-1"></i> Dons</h2>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Lien HelloAsso <span class="text-gray-400 font-normal">(optionnel)</span></label>
                    <input type="url" name="helloasso_url" value="{{ old('helloasso_url', $settings['helloasso_url']) }}" placeholder="https://www.helloasso.com/associations/…/formulaires/…"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-mja-blue">
                    <p class="text-[11px] text-gray-400 mt-1.5">Si renseigné, un bouton « Faire un don via HelloAsso » apparaît sur la page de dons (reçus fiscaux gérés par HelloAsso). Le don par carte (Stripe) reste disponible si le paiement en ligne est activé.</p>
                </div>
            </div>
        </div>

        </div>{{-- /grid --}}

        <button type="submit" class="mt-6 bg-mja-blue hover:bg-mja-bluedark text-white font-display font-bold px-6 py-3 rounded-xl transition-colors">
            <i class="fas fa-save mr-2"></i> Enregistrer les paramètres
        </button>
    </form>
</div>
@endsection
