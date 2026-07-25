@extends('layouts.app')
@section('title', "Nouveau mot de passe — Espace membre MJA")

@section('content')
<section class="hero-gradient text-white py-14">
    <div class="max-w-lg mx-auto px-4 text-center">
        <h1 class="font-display font-black text-3xl sm:text-4xl mb-2">Nouveau mot de passe</h1>
        <p class="text-gray-300">Choisissez un nouveau mot de passe pour votre espace membre.</p>
    </div>
</section>
<div class="flex h-1"><div class="flex-1 bg-mja-blue"></div><div class="flex-1 bg-mja-yellow"></div><div class="flex-1 bg-mja-red"></div></div>

<section class="py-14">
    <div class="max-w-md mx-auto px-4">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
            @if($errors->any())
            <div role="alert" class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-4 mb-6 text-sm font-display font-semibold">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('member.password.update') }}" class="space-y-5">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <div>
                    <label for="m-rp-email" class="block text-sm font-display font-bold text-mja-gray mb-1.5">Adresse email</label>
                    <input type="email" id="m-rp-email" name="email" value="{{ old('email', $email) }}" required autocomplete="email"
                        class="w-full border-2 border-gray-100 focus:border-mja-blue rounded-xl px-4 py-3 text-sm outline-none transition-colors">
                </div>
                <div>
                    <label for="m-rp-pw" class="block text-sm font-display font-bold text-mja-gray mb-1.5">Nouveau mot de passe</label>
                    <div class="relative">
                        <input type="password" id="m-rp-pw" name="password" required minlength="8"
                            class="w-full border-2 border-gray-100 focus:border-mja-blue rounded-xl px-4 py-3 pr-11 text-sm outline-none transition-colors" placeholder="Minimum 8 caractères">
                        <button type="button" onclick="mjaTogglePw(this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"><i class="fas fa-eye"></i></button>
                    </div>
                </div>
                <div>
                    <label for="m-rp-pw2" class="block text-sm font-display font-bold text-mja-gray mb-1.5">Confirmer le mot de passe</label>
                    <div class="relative">
                        <input type="password" id="m-rp-pw2" name="password_confirmation" required minlength="8"
                            class="w-full border-2 border-gray-100 focus:border-mja-blue rounded-xl px-4 py-3 pr-11 text-sm outline-none transition-colors">
                        <button type="button" onclick="mjaTogglePw(this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"><i class="fas fa-eye"></i></button>
                    </div>
                </div>
                <button type="submit" class="w-full btn-blue font-display font-bold py-3.5 rounded-xl transition-colors">
                    <i class="fas fa-check mr-1"></i> Réinitialiser
                </button>
            </form>
        </div>
    </div>
</section>
@endsection
