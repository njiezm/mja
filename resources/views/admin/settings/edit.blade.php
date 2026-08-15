@extends('layouts.admin')
@section('title', 'Paramètres')
@section('page-title', 'Paramètres')
@section('content')
<div class="max-w-5xl mt-4">
    <form method="POST" action="{{ route('admin.settings.update') }}">
        @csrf @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">

        {{-- Colonne gauche : Stripe + coordonnées bancaires --}}
        <div class="space-y-6">

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
                @if($settings['peut_modifier_secrets'])
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
                @else
                {{-- Les clés secrètes engagent les encaissements : seul le super
                     admin peut les modifier. Le reste de la page est ouvert. --}}
                <div class="bg-gray-50 border border-gray-100 rounded-xl px-4 py-3 text-xs text-gray-500 flex gap-2">
                    <i class="fas fa-lock mt-0.5 shrink-0"></i>
                    <span>
                        Clé secrète {{ $settings['stripe_secret_set'] ? 'définie ✔' : 'non définie' }},
                        webhook {{ $settings['stripe_webhook_set'] ? 'défini ✔' : 'non défini' }}.
                        Leur modification est réservée au super administrateur.
                    </span>
                </div>
                @endif
            </div>
        </div>

            {{-- Coordonnées bancaires --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
                <h2 class="font-display font-bold text-gray-900"><i class="fas fa-building-columns text-mja-blue mr-1"></i> Coordonnées bancaires</h2>
                <p class="text-[11px] text-gray-400 -mt-2">Reprises automatiquement dans les emails de confirmation et de relance pour les règlements par virement.</p>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="sm:col-span-2">
                        <label for="s-iban" class="block text-sm font-semibold text-gray-700 mb-1.5">IBAN</label>
                        <input type="text" id="s-iban" name="iban" value="{{ old('iban', $settings['iban']) }}" placeholder="FR76 …"
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-mja-blue">
                    </div>
                    <div>
                        <label for="s-bic" class="block text-sm font-semibold text-gray-700 mb-1.5">BIC</label>
                        <input type="text" id="s-bic" name="bic" value="{{ old('bic', $settings['bic']) }}" placeholder="XXXXFRPP"
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-mja-blue">
                    </div>
                </div>
            </div>

        </div>{{-- /colonne gauche --}}

        {{-- Colonne droite : Cotisation + Notifications --}}
        <div class="space-y-6">
            {{-- Cotisation --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
                <h2 class="font-display font-bold text-gray-900"><i class="fas fa-euro-sign text-mja-yellow mr-1"></i> Cotisation</h2>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Montant de la cotisation (€) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" min="0" name="cotisation_amount" value="{{ old('cotisation_amount', $settings['cotisation_amount']) }}" required
                        class="w-40 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-mja-blue">
                    <p class="text-[11px] text-gray-400 mt-1.5">Montant que l'association doit percevoir, net de frais.</p>
                </div>

                <div class="border-t border-gray-50 pt-4">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="hidden" name="stripe_fee_passthrough" value="0">
                        <input type="checkbox" name="stripe_fee_passthrough" value="1"
                               {{ $settings['stripe_fee_passthrough'] ? 'checked' : '' }}
                               class="mt-0.5 w-5 h-5 rounded text-mja-blue shrink-0">
                        <span>
                            <span class="block text-sm font-semibold text-gray-700">Frais de transaction à la charge du payeur</span>
                            <span class="block text-[11px] text-gray-400 mt-0.5">
                                Le règlement par carte est majoré de la commission Stripe, pour que l'association
                                encaisse bien la cotisation entière. Sans cela, la commission est prélevée dessus.
                            </span>
                        </span>
                    </label>

                    <div class="grid grid-cols-3 gap-3 mt-4 ml-8">
                        @foreach([
                            ['stripe_fee_percent', 'Commission (%)', '0.01', '1.5'],
                            ['stripe_fee_fixed', 'Part fixe (€)', '0.01', '0.25'],
                            ['stripe_fee_round_to', 'Arrondi (€)', '0.01', '0.05'],
                        ] as [$champ, $label, $pas, $exemple])
                        <div>
                            <label for="s-{{ $champ }}" class="block text-xs font-semibold text-gray-600 mb-1">{{ $label }}</label>
                            <input type="number" id="s-{{ $champ }}" name="{{ $champ }}" step="{{ $pas }}" min="0" required
                                   value="{{ old($champ, $settings[$champ]) }}" placeholder="{{ $exemple }}"
                                   class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-mja-blue">
                        </div>
                        @endforeach
                    </div>

                    <div class="mt-4 ml-8 bg-mja-blue/5 border border-mja-blue/15 rounded-xl px-4 py-3 text-sm">
                        <span class="text-gray-600">Débité par carte aujourd'hui :</span>
                        <strong class="text-mja-dark">{{ \App\Support\Cotisation::carteFormatee() }}</strong>
                        <span class="text-gray-500">
                            ({{ \App\Support\Cotisation::formatee() }} de cotisation
                            + {{ \App\Support\Cotisation::fraisFormates() }} de frais)
                        </span>
                    </div>
                    <p class="text-[11px] text-gray-400 mt-2 ml-8">
                        Valeurs par défaut : tarification Stripe cartes européennes (1,5 % + 0,25 €).
                        Les cartes hors zone euro coûtent davantage — l'arrondi absorbe une partie de cet écart.
                    </p>
                </div>
            </div>

            {{-- Notifications --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
                <h2 class="font-display font-bold text-gray-900"><i class="fas fa-bell text-mja-red mr-1"></i> Emails de notification</h2>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Destinataires des notifications admin</label>
                    <textarea name="notification_emails" rows="4" placeholder="{{ config('mja.contact_email') }}&#10;secretariat@exemple.com"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-mja-blue">{{ old('notification_emails', $settings['notification_emails']) }}</textarea>
                    <p class="text-[11px] text-gray-400 mt-1.5">Une adresse par ligne (ou séparées par des virgules). Ces adresses reçoivent les notifications : nouvelle adhésion, nouveau message de contact, etc. Si vide, l'adresse par défaut est utilisée.</p>
                </div>
            </div>

            {{-- Dons --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
                <h2 class="font-display font-bold text-gray-900"><i class="fas fa-heart text-mja-red mr-1"></i> Dons</h2>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Lien de don externe <span class="text-gray-400 font-normal">(optionnel)</span></label>
                    <input type="url" name="helloasso_url" value="{{ old('helloasso_url', $settings['helloasso_url']) }}" placeholder="https://www.helloasso.com/associations/…/formulaires/…"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-mja-blue">
                    <p class="text-[11px] text-gray-400 mt-1.5">HelloAsso ou toute autre plateforme. Laissé vide, seul le don par carte est proposé.</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Moyen mis en avant</label>
                    @php $prio = old('don_priorite', $settings['don_priorite']); @endphp
                    <select name="don_priorite" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-mja-blue">
                        <option value="stripe" @selected($prio !== 'lien')>Le don par carte, sur le site (recommandé)</option>
                        <option value="lien" @selected($prio === 'lien')>Le lien externe ci-dessus</option>
                    </select>
                    <p class="text-[11px] text-gray-400 mt-1.5">
                        Le moyen choisi occupe la page ; l'autre est proposé en dessous. Si le moyen
                        mis en avant n'est pas disponible — paiement en ligne désactivé, ou lien vide —
                        l'autre prend automatiquement le relais.
                    </p>
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
