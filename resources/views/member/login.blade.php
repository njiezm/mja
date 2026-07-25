@extends('layouts.app')
@section('title', "Espace membre — Connexion — Madin'Jeunes Ambition")
@section('meta_description', "Connexion à l'espace membre adhérent MJA.")

@section('content')
<section class="hero-gradient text-white py-14">
    <div class="max-w-lg mx-auto px-4 text-center">
        <h1 class="font-display font-black text-3xl sm:text-4xl mb-2">Espace membre</h1>
        <p class="text-gray-300">Connectez-vous pour accéder à vos informations et au trombinoscope.</p>
    </div>
</section>
<div class="flex h-1"><div class="flex-1 bg-mja-blue"></div><div class="flex-1 bg-mja-yellow"></div><div class="flex-1 bg-mja-red"></div></div>

<section class="py-14">
    <div class="max-w-md mx-auto px-4">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
            @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-4 mb-6 text-sm font-display font-semibold">
                <i class="fas fa-exclamation-circle mr-1"></i> {{ session('error') }}
            </div>
            @endif
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-4 mb-6 text-sm font-display font-semibold">
                <i class="fas fa-exclamation-circle mr-1"></i> {{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('member.login.post') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-display font-bold text-mja-gray mb-1.5">Adresse email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full border-2 border-gray-100 focus:border-mja-blue rounded-xl px-4 py-3 text-sm outline-none transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-display font-bold text-mja-gray mb-1.5">Mot de passe</label>
                    <div class="relative">
                        <input type="password" name="password" required
                            class="w-full border-2 border-gray-100 focus:border-mja-blue rounded-xl px-4 py-3 pr-11 text-sm outline-none transition-colors">
                        <button type="button" onclick="mjaTogglePw(this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"><i class="fas fa-eye"></i></button>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 text-sm text-gray-600">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-mja-blue"> Se souvenir de moi
                    </label>
                    <a href="{{ route('member.password.request') }}" class="text-sm text-mja-blue hover:underline font-display font-semibold">Mot de passe oublié ?</a>
                </div>
                <button type="submit" class="w-full btn-blue font-display font-bold py-3.5 rounded-xl transition-colors">
                    <i class="fas fa-sign-in-alt mr-1"></i> Se connecter
                </button>
            </form>
            <p class="text-xs text-gray-400 text-center mt-5">
                Pas encore de compte ? Il est créé via le lien reçu par email après validation de votre adhésion.
            </p>
        </div>
    </div>
</section>
@endsection
