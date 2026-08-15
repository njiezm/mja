@extends('layouts.app')
@section('title', "Modifier mes informations — Espace membre")

@section('content')
<section class="hero-gradient text-white py-12">
    <div class="max-w-2xl mx-auto px-4 flex items-center justify-between gap-4">
        <h1 class="font-display font-black text-2xl sm:text-3xl">Modifier mes informations</h1>
        <a href="{{ route('member.dashboard') }}" class="text-gray-300 hover:text-white text-sm font-display font-semibold"><i class="fas fa-arrow-left mr-1"></i> Retour</a>
    </div>
</section>
<div class="flex h-1"><div class="flex-1 bg-mja-blue"></div><div class="flex-1 bg-mja-yellow"></div><div class="flex-1 bg-mja-red"></div></div>

<section class="py-12">
    <div class="max-w-2xl mx-auto px-4">
        @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-4 mb-6 text-sm font-display font-semibold">
            <i class="fas fa-exclamation-circle mr-1"></i> {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="{{ route('member.profile.update') }}" enctype="multipart/form-data" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8 space-y-5">
            @csrf @method('PUT')

            {{-- Photo --}}
            <div class="flex items-center gap-4">
                @if($adhesion->photo)
                <img src="{{ Storage::url($adhesion->photo) }}" class="w-16 h-16 rounded-xl object-cover shrink-0" alt="Photo actuelle">
                @else
                <div class="w-16 h-16 rounded-xl bg-gray-100 flex items-center justify-center text-gray-300"><i class="fas fa-user text-xl"></i></div>
                @endif
                <div class="flex-1">
                    <label class="block text-sm font-display font-bold text-mja-gray mb-1.5">Photo</label>
                    <input type="file" name="photo" accept="image/*"
                        class="block w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-mja-blue file:text-white hover:file:bg-mja-bluedark file:cursor-pointer border-2 border-gray-100 rounded-xl">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-display font-bold text-mja-gray mb-1.5">Prénom <span class="text-mja-red">*</span></label>
                    <input type="text" name="prenom" value="{{ old('prenom', $adhesion->prenom) }}" required class="w-full border-2 border-gray-100 focus:border-mja-blue rounded-xl px-4 py-3 text-sm outline-none">
                </div>
                <div>
                    <label class="block text-sm font-display font-bold text-mja-gray mb-1.5">Nom <span class="text-mja-red">*</span></label>
                    <input type="text" name="nom" value="{{ old('nom', $adhesion->nom) }}" required class="w-full border-2 border-gray-100 focus:border-mja-blue rounded-xl px-4 py-3 text-sm outline-none">
                </div>
                <div>
                    <label class="block text-sm font-display font-bold text-mja-gray mb-1.5">Email <span class="text-mja-red">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $adhesion->email) }}" required class="w-full border-2 border-gray-100 focus:border-mja-blue rounded-xl px-4 py-3 text-sm outline-none">
                </div>
                <div>
                    <label class="block text-sm font-display font-bold text-mja-gray mb-1.5">Téléphone <span class="text-mja-red">*</span></label>
                    <x-phone-field :value="$numero" :indicatif="$indicatif" :required="true" />
                </div>
                <div>
                    <label class="block text-sm font-display font-bold text-mja-gray mb-1.5">Profession <span class="text-mja-red">*</span></label>
                    <input type="text" name="profession" value="{{ old('profession', $adhesion->profession) }}" required class="w-full border-2 border-gray-100 focus:border-mja-blue rounded-xl px-4 py-3 text-sm outline-none">
                </div>
                <div>
                    <label class="block text-sm font-display font-bold text-mja-gray mb-1.5">Taille T-shirt <span class="text-mja-red">*</span></label>
                    <select name="taille_tshirt" required class="w-full border-2 border-gray-100 focus:border-mja-blue rounded-xl px-4 py-3 text-sm outline-none">
                        @foreach(['S','M','L','XL','2XL','3XL'] as $t)
                        <option value="{{ $t }}" @selected(old('taille_tshirt', $adhesion->taille_tshirt) === $t)>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-display font-bold text-mja-gray mb-1.5">
                    Mes réseaux sociaux <span class="text-gray-400 font-normal">(facultatif)</span>
                </label>
                <p class="text-xs text-gray-400 mb-3">Visibles uniquement des autres adhérents, sur ta fiche du trombinoscope.</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach(\App\Models\Adhesion::RESEAUX as $cle => [$label, $icone, $prefixe, $exemple])
                    <div>
                        <label for="p-res-{{ $cle }}" class="block text-xs font-display font-bold text-gray-500 mb-1">
                            <i class="{{ $icone }} mr-1"></i> {{ $label }}
                        </label>
                        <div class="flex items-stretch border-2 border-gray-100 focus-within:border-mja-blue rounded-xl overflow-hidden transition-colors">
                            @if($prefixe)<span class="pl-3 flex items-center text-sm text-gray-400 font-display font-bold">{{ $prefixe }}</span>@endif
                            <input type="text" id="p-res-{{ $cle }}" name="reseaux_sociaux[{{ $cle }}]" maxlength="150"
                                value="{{ old('reseaux_sociaux.' . $cle, data_get($adhesion->reseaux_sociaux, $cle)) }}"
                                class="flex-1 bg-transparent border-0 px-3 py-2.5 text-sm outline-none min-w-0"
                                placeholder="{{ $exemple }}">
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div>
                <label class="block text-sm font-display font-bold text-mja-gray mb-1.5">Problèmes de santé / allergies</label>
                <textarea name="problemes_sante" rows="2" class="w-full border-2 border-gray-100 focus:border-mja-blue rounded-xl px-4 py-3 text-sm outline-none resize-none">{{ old('problemes_sante', $adhesion->problemes_sante) }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-display font-bold text-mja-gray mb-1.5">Personne à contacter en cas d'urgence <span class="text-mja-red">*</span></label>
                <input type="text" name="urgence_contact" value="{{ old('urgence_contact', $adhesion->urgence_contact) }}" required class="w-full border-2 border-gray-100 focus:border-mja-blue rounded-xl px-4 py-3 text-sm outline-none">
            </div>

            <div class="border-t border-gray-100 pt-5">
                <label class="block text-sm font-display font-bold text-mja-gray mb-1.5">Nouveau mot de passe <span class="text-gray-400 font-normal">(laisser vide pour conserver)</span></label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="relative">
                        <input type="password" name="password" minlength="8" placeholder="••••••••" class="w-full border-2 border-gray-100 focus:border-mja-blue rounded-xl px-4 py-3 pr-11 text-sm outline-none">
                        <button type="button" onclick="mjaTogglePw(this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"><i class="fas fa-eye"></i></button>
                    </div>
                    <div class="relative">
                        <input type="password" name="password_confirmation" minlength="8" placeholder="Confirmer" class="w-full border-2 border-gray-100 focus:border-mja-blue rounded-xl px-4 py-3 pr-11 text-sm outline-none">
                        <button type="button" onclick="mjaTogglePw(this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"><i class="fas fa-eye"></i></button>
                    </div>
                </div>
            </div>

            <label class="flex items-start gap-3 cursor-pointer bg-mja-blue/5 border border-mja-blue/20 rounded-xl p-4">
                <input type="checkbox" name="show_in_directory" value="1" @checked(old('show_in_directory', $member->show_in_directory)) class="mt-0.5 w-5 h-5 rounded text-mja-blue shrink-0">
                <span class="text-sm text-gray-600">Apparaître dans le <strong>trombinoscope</strong> des adhérents.</span>
            </label>

            <button type="submit" class="w-full btn-blue font-display font-bold py-3.5 rounded-xl transition-colors">
                <i class="fas fa-save mr-1"></i> Enregistrer
            </button>
        </form>
    </div>
</section>
@endsection
