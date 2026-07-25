@extends('layouts.app')
@section('title', "Mot de passe oublié — Espace membre MJA")

@section('content')
<section class="hero-gradient text-white py-14">
    <div class="max-w-lg mx-auto px-4 text-center">
        <h1 class="font-display font-black text-3xl sm:text-4xl mb-2">Mot de passe oublié</h1>
        <p class="text-gray-300">Recevez un lien pour réinitialiser votre mot de passe.</p>
    </div>
</section>
<div class="flex h-1"><div class="flex-1 bg-mja-blue"></div><div class="flex-1 bg-mja-yellow"></div><div class="flex-1 bg-mja-red"></div></div>

<section class="py-14">
    <div class="max-w-md mx-auto px-4">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
            @if(session('status'))
            <div role="status" class="bg-green-50 border border-green-200 text-green-800 rounded-xl p-4 mb-6 text-sm font-display font-semibold">
                <i class="fas fa-check-circle mr-1"></i> {{ session('status') }}
            </div>
            @endif
            @if($errors->any())
            <div role="alert" class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-4 mb-6 text-sm font-display font-semibold">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('member.password.email') }}" class="space-y-5">
                @csrf
                <div>
                    <label for="m-fp-email" class="block text-sm font-display font-bold text-mja-gray mb-1.5">Adresse email</label>
                    <input type="email" id="m-fp-email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                        class="w-full border-2 border-gray-100 focus:border-mja-blue rounded-xl px-4 py-3 text-sm outline-none transition-colors">
                </div>
                <button type="submit" class="w-full btn-blue font-display font-bold py-3.5 rounded-xl transition-colors">
                    <i class="fas fa-paper-plane mr-1"></i> Envoyer le lien
                </button>
            </form>
            <p class="text-sm text-center mt-5">
                <a href="{{ route('member.login') }}" class="text-mja-blue hover:underline font-display font-semibold">Retour à la connexion</a>
            </p>
        </div>
    </div>
</section>
@endsection
