@extends('layouts.app')
@section('title', "Faire un don — Madin'Jeunes Ambition")
@section('og_image', asset('images/partage/don.jpg'))
@section('meta_description', "Soutenez les actions de Madin'Jeunes Ambition en Martinique par un don.")

@section('content')
<section class="hero-gradient text-white py-16 relative overflow-hidden ring-watermark">
    <div class="max-w-2xl mx-auto px-4 text-center relative z-10">
        <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center mx-auto mb-5"><i class="fas fa-heart text-2xl text-mja-yellow"></i></div>
        <h1 class="font-display font-black text-3xl sm:text-4xl mb-3">Faire un don</h1>
        <p class="text-gray-300 text-lg">Chaque don soutient nos actions éducatives, sportives et solidaires auprès de la jeunesse martiniquaise.</p>
    </div>
</section>
<div class="flex h-1"><div class="flex-1 bg-mja-blue"></div><div class="flex-1 bg-mja-yellow"></div><div class="flex-1 bg-mja-red"></div></div>

<section class="py-14">
    <div class="max-w-lg mx-auto px-4">
        @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-4 mb-6 text-sm font-display font-semibold"><i class="fas fa-exclamation-circle mr-1"></i> {{ session('error') }}</div>
        @endif
        @if(request('annule'))
        <div class="bg-amber-50 border border-amber-200 text-amber-800 rounded-xl p-4 mb-6 text-sm"><i class="fas fa-circle-info mr-1"></i> Paiement annulé — vous pouvez réessayer quand vous voulez.</div>
        @endif

        {{-- Moyen principal : formulaire par carte, ou renvoi vers le lien
             configuré en back-office. Le second moyen, s'il existe, est
             proposé en dessous. --}}
        @if($principal === 'carte')
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
            <form method="POST" action="{{ route('don.store') }}" class="space-y-5" id="don-form">
                @csrf
                <div aria-hidden="true" style="position:absolute;left:-9999px;height:0;overflow:hidden" tabindex="-1">
                    <label>Ne pas remplir<input type="text" name="site_web" tabindex="-1" autocomplete="off"></label>
                </div>
                <div>
                    <label for="montant" class="block text-sm font-display font-bold text-mja-gray mb-2">Montant du don <span class="text-mja-red" aria-hidden="true">*</span></label>
                    <div class="grid grid-cols-3 sm:grid-cols-5 gap-2 mb-3">
                        @foreach($presets as $amount)
                        <button type="button" onclick="setAmount({{ $amount }}, this)" class="preset border-2 border-gray-100 hover:border-mja-blue rounded-xl py-2.5 text-sm font-display font-bold text-gray-600 transition-colors">{{ $amount }} €</button>
                        @endforeach
                    </div>
                    <div class="relative">
                        <input type="number" name="montant" id="montant" step="1" min="1" value="{{ old('montant') }}" required placeholder="Autre montant"
                            class="w-full border-2 border-gray-100 focus:border-mja-blue rounded-xl px-4 py-3 text-sm outline-none transition-colors pr-8 @error('montant') border-mja-red @enderror">
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold">€</span>
                    </div>
                    @error('montant')<p class="text-mja-red text-xs mt-1 font-display font-semibold">{{ $message }}</p>@enderror
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <input type="text" name="prenom" value="{{ old('prenom') }}" placeholder="Prénom" aria-label="Prénom" autocomplete="given-name" class="border-2 border-gray-100 focus:border-mja-blue rounded-xl px-4 py-3 text-sm outline-none transition-colors">
                    <input type="text" name="nom" value="{{ old('nom') }}" placeholder="Nom" aria-label="Nom" autocomplete="family-name" class="border-2 border-gray-100 focus:border-mja-blue rounded-xl px-4 py-3 text-sm outline-none transition-colors">
                </div>
                <div>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="Email (pour le reçu)" aria-label="Adresse email pour le reçu" autocomplete="email"
                        class="w-full border-2 border-gray-100 focus:border-mja-blue rounded-xl px-4 py-3 text-sm outline-none transition-colors @error('email') border-mja-red @enderror">
                    @error('email')<p class="text-mja-red text-xs mt-1 font-display font-semibold">{{ $message }}</p>@enderror
                </div>
                <textarea name="message" rows="2" placeholder="Un message (facultatif)" aria-label="Message (facultatif)" class="w-full border-2 border-gray-100 focus:border-mja-blue rounded-xl px-4 py-3 text-sm outline-none transition-colors resize-none">{{ old('message') }}</textarea>
                <button type="submit" class="w-full btn-blue font-display font-bold py-3.5 rounded-xl transition-colors flex items-center justify-center gap-2">
                    <i class="fas fa-lock"></i> Donner par carte bancaire
                </button>
                <p class="text-[11px] text-gray-400 text-center"><i class="fas fa-lock mr-1"></i> Paiement 100 % sécurisé via Stripe.</p>
            </form>
        </div>
        @endif

        @if($principal === 'lien')
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 text-center">
            <div class="w-14 h-14 rounded-2xl bg-mja-red/10 text-mja-red flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-heart text-2xl"></i>
            </div>
            <h2 class="font-display font-bold text-xl text-mja-gray mb-2">Faire un don</h2>
            <p class="text-sm text-gray-500 mb-5 max-w-md mx-auto">
                Le don se fait sur notre plateforme partenaire, qui délivre le reçu automatiquement.
            </p>
            <a href="{{ $lien }}" target="_blank" rel="noopener"
               class="inline-flex items-center gap-2 bg-mja-blue hover:bg-mja-bluedark text-white font-display font-bold px-7 py-3.5 rounded-xl transition-colors">
                <i class="fas fa-heart"></i> Donner maintenant
            </a>
        </div>
        @endif

        @if($secondaire === 'lien')
        <div class="mt-6 bg-white rounded-2xl shadow-sm border border-gray-100 p-6 text-center">
            <p class="text-sm text-gray-500 mb-4">Vous préférez passer par notre plateforme partenaire ?</p>
            <a href="{{ $lien }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 bg-mja-dark hover:bg-mja-navy text-white font-display font-bold px-6 py-3 rounded-xl transition-colors">
                <i class="fas fa-heart"></i> Faire un don en ligne
            </a>
        </div>
        @endif

        @if($secondaire === 'carte')
        <div class="mt-6 bg-white rounded-2xl shadow-sm border border-gray-100 p-6 text-center">
            <p class="text-sm text-gray-500 mb-4">Vous préférez régler directement par carte, sans quitter le site ?</p>
            <a href="{{ route('don') }}?carte=1" class="inline-flex items-center gap-2 bg-mja-dark hover:bg-mja-navy text-white font-display font-bold px-6 py-3 rounded-xl transition-colors">
                <i class="fas fa-credit-card"></i> Donner par carte bancaire
            </a>
        </div>
        @endif

        @if($principal === 'aucun')
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 text-center text-gray-500">
            Le don en ligne sera bientôt disponible. En attendant, <a href="{{ route('contact') }}" class="text-mja-blue font-semibold hover:underline">contactez-nous</a> pour faire un don.
        </div>
        @endif
    </div>
</section>

@push('scripts')
<script>
function setAmount(val, btn) {
    document.getElementById('montant').value = val;
    document.querySelectorAll('.preset').forEach(function (b) { b.classList.remove('border-mja-blue', 'bg-mja-blue/5', 'text-mja-blue'); });
    btn.classList.add('border-mja-blue', 'text-mja-blue');
}
</script>
@endpush
@endsection
