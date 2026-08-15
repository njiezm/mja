@extends('layouts.app')
@section('title', "Merci pour votre don — Madin'Jeunes Ambition")
@section('og_image', asset('images/partage/don.jpg'))

@section('content')
<section class="hero-gradient text-white py-20 relative overflow-hidden ring-watermark">
    <div class="max-w-xl mx-auto px-4 text-center relative z-10">
        @if($don && $don->isPaid())
        <div class="w-20 h-20 bg-white/10 rounded-3xl flex items-center justify-center mx-auto mb-6"><i class="fas fa-heart text-3xl text-mja-yellow"></i></div>
        <h1 class="font-display font-black text-3xl sm:text-4xl mb-3">Merci infiniment ! 💛</h1>
        <p class="text-gray-200 text-lg">Votre don de <strong>{{ number_format((float) $don->montant, 2, ',', ' ') }} €</strong> a bien été reçu. Un reçu vous a été envoyé par email. Votre générosité fait la différence.</p>
        @else
        <div class="w-20 h-20 bg-white/10 rounded-3xl flex items-center justify-center mx-auto mb-6"><i class="fas fa-circle-info text-3xl text-mja-yellow"></i></div>
        <h1 class="font-display font-black text-3xl sm:text-4xl mb-3">Merci !</h1>
        <p class="text-gray-200 text-lg">Si votre paiement a bien été effectué, il sera confirmé sous peu. En cas de doute, contactez-nous.</p>
        @endif
        <a href="{{ route('home') }}" class="inline-block mt-8 bg-white/10 hover:bg-white/20 text-white font-display font-bold px-6 py-3 rounded-full transition-colors">Retour à l'accueil</a>
    </div>
</section>
@endsection
