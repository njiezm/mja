@extends('layouts.app')
@section('title', "Créer mon espace membre — Madin'Jeunes Ambition")
@section('meta_description', "Créez votre espace membre adhérent MJA.")

@section('content')
<section class="hero-gradient text-white py-14">
    <div class="max-w-lg mx-auto px-4 text-center">
        <h1 class="font-display font-black text-3xl sm:text-4xl mb-2">Bienvenue, {{ $adhesion->prenom }} !</h1>
        <p class="text-gray-300">Choisissez un mot de passe pour activer votre espace membre.</p>
    </div>
</section>
<div class="flex h-1"><div class="flex-1 bg-mja-blue"></div><div class="flex-1 bg-mja-yellow"></div><div class="flex-1 bg-mja-red"></div></div>

<section class="py-14">
    <div class="max-w-md mx-auto px-4">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-4 mb-6 text-sm font-display font-semibold">
                <i class="fas fa-exclamation-circle mr-1"></i> {{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('member.account.store', $token) }}" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-display font-bold text-mja-gray mb-1.5">Adresse email <span class="text-mja-red">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $adhesion->email) }}" required
                        class="w-full border-2 border-gray-100 focus:border-mja-blue rounded-xl px-4 py-3 text-sm outline-none transition-colors">
                    <p class="text-[11px] text-gray-400 mt-1">Vous pouvez la modifier — votre compte reste lié à votre adhésion.</p>
                </div>
                <div>
                    <label class="block text-sm font-display font-bold text-mja-gray mb-1.5">Mot de passe <span class="text-mja-red">*</span></label>
                    <div class="relative">
                        <input type="password" name="password" required minlength="8"
                            class="w-full border-2 border-gray-100 focus:border-mja-blue rounded-xl px-4 py-3 pr-11 text-sm outline-none transition-colors" placeholder="Minimum 8 caractères">
                        <button type="button" onclick="mjaTogglePw(this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"><i class="fas fa-eye"></i></button>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-display font-bold text-mja-gray mb-1.5">Confirmer le mot de passe <span class="text-mja-red">*</span></label>
                    <div class="relative">
                        <input type="password" name="password_confirmation" required minlength="8"
                            class="w-full border-2 border-gray-100 focus:border-mja-blue rounded-xl px-4 py-3 pr-11 text-sm outline-none transition-colors">
                        <button type="button" onclick="mjaTogglePw(this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"><i class="fas fa-eye"></i></button>
                    </div>
                </div>
                <label class="flex items-start gap-3 cursor-pointer bg-mja-blue/5 border border-mja-blue/20 rounded-xl p-4">
                    <input type="checkbox" name="show_in_directory" value="1" checked class="mt-0.5 w-5 h-5 rounded text-mja-blue shrink-0">
                    <span class="text-sm text-gray-600">J'accepte d'apparaître dans le <strong>trombinoscope</strong> des adhérents (photo, prénom et initiale du nom, visibles uniquement par les autres adhérents connectés).</span>
                </label>
                <button type="submit" class="w-full btn-blue font-display font-bold py-3.5 rounded-xl transition-colors">
                    <i class="fas fa-user-check mr-1"></i> Activer mon espace membre
                </button>
            </form>
        </div>
    </div>
</section>
@endsection
