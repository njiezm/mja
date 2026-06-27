@extends('layouts.app')
@section('title', "Rejoindre MJA — Adhésion Madin'Jeunes Ambition")
@section('meta_description', "Rejoins Madin'Jeunes Ambition ! Adhère en ligne pour intégrer notre équipe de jeunes bénévoles engagés en Martinique. Gratuit et ouvert à tous.")
@section('twitter_card', 'summary')

@section('content')

<section class="hero-gradient text-white py-16 relative overflow-hidden">
    <div class="absolute -right-16 -top-16 w-64 h-64 opacity-10">
        <svg viewBox="0 0 200 200" fill="none"><circle cx="100" cy="100" r="95" stroke="#4A90E2" stroke-width="2"/><circle cx="100" cy="100" r="65" stroke="#F5A623" stroke-width="2"/></svg>
    </div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-sm text-gray-400 mb-3 font-display font-semibold">
            <a href="{{ route('home') }}" class="hover:text-mja-yellow transition-colors">Accueil</a>
            <span class="mx-2 text-gray-600">/</span> Adhésion
        </div>
        <h1 class="font-display font-black text-4xl sm:text-5xl mb-4">Rejoindre <span class="text-mja-blue">M</span><span class="text-mja-yellow">J</span><span class="text-mja-red">A</span></h1>
        <p class="text-gray-300 text-lg max-w-2xl">Tu as entre 16 et 35 ans et tu veux t'engager ? Remplis ce formulaire pour rejoindre <strong class="text-white">Madin' Jeunes Ambition</strong>.</p>
    </div>
</section>

<div class="flex h-1"><div class="flex-1 bg-mja-blue"></div><div class="flex-1 bg-mja-yellow"></div><div class="flex-1 bg-mja-red"></div></div>

<section class="py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">

            <!-- Sidebar info -->
            <div class="space-y-6">
                <div class="bg-mja-dark rounded-2xl p-6 text-center ring-watermark">
                    <img src="/images/logo.jpg" alt="MJA" class="h-20 w-20 mx-auto mb-4 object-contain bg-white rounded-xl p-1">
                    <div class="font-display font-black text-2xl mb-1">
                        <span class="text-mja-blue">M</span><span class="text-mja-yellow">J</span><span class="text-mja-red">A</span>
                    </div>
                    <div class="text-gray-400 text-xs font-display font-semibold uppercase tracking-wider">Madin' Jeunes Ambition</div>
                </div>

                <div class="bg-mja-light rounded-2xl p-5 border-l-4 border-mja-yellow">
                    <h3 class="font-display font-bold text-mja-gray mb-3 flex items-center gap-2">
                        <i class="fas fa-info-circle text-mja-yellow"></i> Informations
                    </h3>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-start gap-2"><i class="fas fa-check text-mja-blue mt-0.5 shrink-0"></i> Cotisation de <strong>20 €</strong> pour finaliser l'inscription</li>
                        <li class="flex items-start gap-2"><i class="fas fa-check text-mja-blue mt-0.5 shrink-0"></i> Envoie ta photo au <strong>+596 696 43 88 21</strong> après inscription</li>
                        <li class="flex items-start gap-2"><i class="fas fa-check text-mja-blue mt-0.5 shrink-0"></i> Tu seras présenté(e) aux autres membres</li>
                    </ul>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
                    <h3 class="font-display font-bold text-mja-gray mb-3">Une question ?</h3>
                    <p class="text-gray-500 text-sm mb-3">Tu préfères nous écrire avant de t'engager ?</p>
                    <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 text-mja-blue font-display font-bold text-sm hover:underline">
                        <i class="fas fa-envelope"></i> Nous contacter
                    </a>
                </div>
            </div>

            <!-- Formulaire -->
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="flex h-1.5">
                    <div class="flex-1 bg-mja-blue"></div>
                    <div class="flex-1 bg-mja-yellow"></div>
                    <div class="flex-1 bg-mja-red"></div>
                </div>
                <div class="p-8">
                    <h2 class="font-display font-bold text-xl text-mja-gray mb-6">Formulaire d'adhésion</h2>

                    @if(session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-800 rounded-2xl p-6 mb-6 text-center">
                        <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-check text-green-500 text-2xl"></i>
                        </div>
                        <h3 class="font-display font-bold text-lg mb-2">Merci pour ta demande !</h3>
                        <p class="text-sm leading-relaxed mb-2">Nous avons bien reçu ton formulaire d'adhésion. Nous te recontacterons très prochainement.</p>
                        <p class="text-sm font-semibold">📸 N'oublie pas d'envoyer ta photo au <span class="text-green-700">+596 696 43 88 21</span> pour être présenté(e) aux autres membres !</p>
                    </div>
                    @else

                    @if($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-800 rounded-xl p-4 mb-6 text-sm font-display font-semibold flex items-start gap-3">
                        <i class="fas fa-exclamation-circle text-red-500 mt-0.5 shrink-0"></i>
                        <div>Certains champs nécessitent votre attention. Veuillez vérifier le formulaire ci-dessous.</div>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('adhesion.store') }}" class="space-y-8">
                        @csrf

                        {{-- Bloc 1 : Type d'adhésion --}}
                        <div>
                            <h3 class="font-display font-bold text-mja-gray text-sm uppercase tracking-wider mb-4 flex items-center gap-2">
                                <span class="w-6 h-6 bg-mja-blue text-white rounded-full flex items-center justify-center text-xs font-black">1</span>
                                Type de démarche
                            </h3>
                            <label class="block text-sm font-display font-bold text-mja-gray mb-2">Avant tout, est-ce ta première adhésion à MJA ? <span class="text-mja-red">*</span></label>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                @foreach(['premiere' => 'Première adhésion', 'readhesion' => 'Réadhésion', 'information' => 'Prise d\'informations'] as $val => $label)
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="premiere_adhesion" value="{{ $val }}"
                                        {{ old('premiere_adhesion') === $val ? 'checked' : '' }}
                                        class="peer sr-only">
                                    <div class="border-2 border-gray-100 peer-checked:border-mja-blue peer-checked:bg-mja-blue/5 rounded-xl p-3 text-center text-sm font-display font-bold text-gray-500 peer-checked:text-mja-blue transition-all hover:border-gray-200">
                                        {{ $label }}
                                    </div>
                                </label>
                                @endforeach
                            </div>
                            @error('premiere_adhesion')<p class="text-mja-red text-xs mt-1.5 font-display font-semibold">{{ $message }}</p>@enderror
                        </div>

                        {{-- Bloc 2 : Identité --}}
                        <div>
                            <h3 class="font-display font-bold text-mja-gray text-sm uppercase tracking-wider mb-4 flex items-center gap-2">
                                <span class="w-6 h-6 bg-mja-blue text-white rounded-full flex items-center justify-center text-xs font-black">2</span>
                                Identité
                            </h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-display font-bold text-mja-gray mb-2">Civilité <span class="text-mja-red">*</span></label>
                                    <div class="flex gap-3">
                                        @foreach(['Madame', 'Monsieur'] as $civ)
                                        <label class="relative cursor-pointer flex-1">
                                            <input type="radio" name="civilite" value="{{ $civ }}"
                                                {{ old('civilite') === $civ ? 'checked' : '' }}
                                                class="peer sr-only">
                                            <div class="border-2 border-gray-100 peer-checked:border-mja-blue peer-checked:bg-mja-blue/5 rounded-xl p-3 text-center text-sm font-display font-bold text-gray-500 peer-checked:text-mja-blue transition-all hover:border-gray-200">
                                                {{ $civ }}
                                            </div>
                                        </label>
                                        @endforeach
                                    </div>
                                    @error('civilite')<p class="text-mja-red text-xs mt-1.5 font-display font-semibold">{{ $message }}</p>@enderror
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-display font-bold text-mja-gray mb-1.5">Nom <span class="text-mja-red">*</span></label>
                                        <input type="text" name="nom" value="{{ old('nom') }}" required
                                            class="w-full border-2 border-gray-100 focus:border-mja-blue rounded-xl px-4 py-3 text-sm outline-none transition-colors @error('nom') border-mja-red @enderror"
                                            placeholder="DUPONT">
                                        @error('nom')<p class="text-mja-red text-xs mt-1 font-display font-semibold">{{ $message }}</p>@enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-display font-bold text-mja-gray mb-1.5">Prénom <span class="text-mja-red">*</span></label>
                                        <input type="text" name="prenom" value="{{ old('prenom') }}" required
                                            class="w-full border-2 border-gray-100 focus:border-mja-blue rounded-xl px-4 py-3 text-sm outline-none transition-colors @error('prenom') border-mja-red @enderror"
                                            placeholder="Jean">
                                        @error('prenom')<p class="text-mja-red text-xs mt-1 font-display font-semibold">{{ $message }}</p>@enderror
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-display font-bold text-mja-gray mb-1.5">Date de naissance <span class="text-mja-red">*</span></label>
                                        <input type="text" name="date_naissance" value="{{ old('date_naissance') }}" required
                                            class="w-full border-2 border-gray-100 focus:border-mja-blue rounded-xl px-4 py-3 text-sm outline-none transition-colors @error('date_naissance') border-mja-red @enderror"
                                            placeholder="JJ/MM/AAAA" maxlength="10">
                                        @error('date_naissance')<p class="text-mja-red text-xs mt-1 font-display font-semibold">{{ $message }}</p>@enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-display font-bold text-mja-gray mb-1.5">Profession / Secteur d'activité <span class="text-mja-red">*</span></label>
                                        <input type="text" name="profession" value="{{ old('profession') }}" required
                                            class="w-full border-2 border-gray-100 focus:border-mja-blue rounded-xl px-4 py-3 text-sm outline-none transition-colors @error('profession') border-mja-red @enderror"
                                            placeholder="Étudiant, Commerce...">
                                        @error('profession')<p class="text-mja-red text-xs mt-1 font-display font-semibold">{{ $message }}</p>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Bloc 3 : Coordonnées --}}
                        <div>
                            <h3 class="font-display font-bold text-mja-gray text-sm uppercase tracking-wider mb-4 flex items-center gap-2">
                                <span class="w-6 h-6 bg-mja-blue text-white rounded-full flex items-center justify-center text-xs font-black">3</span>
                                Coordonnées
                            </h3>
                            <div class="space-y-4">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-display font-bold text-mja-gray mb-1.5">Numéro de téléphone <span class="text-mja-red">*</span></label>
                                        <div class="relative">
                                            <div class="absolute left-0 top-0 h-full flex items-center pl-3.5 gap-1.5 pointer-events-none select-none">
                                                <span class="text-base leading-none">🇲🇶</span>
                                                <span class="text-xs font-bold text-gray-400 font-display">+596</span>
                                                <span class="text-gray-200 font-light">|</span>
                                            </div>
                                            <input type="tel" name="telephone" value="{{ old('telephone') }}" required
                                                class="w-full border-2 border-gray-100 focus:border-mja-blue rounded-xl pl-[4.5rem] pr-4 py-3 text-sm outline-none transition-colors @error('telephone') border-mja-red @enderror"
                                                placeholder="696 00 00 00">
                                        </div>
                                        @error('telephone')<p class="text-mja-red text-xs mt-1 font-display font-semibold">{{ $message }}</p>@enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-display font-bold text-mja-gray mb-1.5">Adresse mail <span class="text-mja-red">*</span></label>
                                        <input type="email" name="email" value="{{ old('email') }}" required
                                            class="w-full border-2 border-gray-100 focus:border-mja-blue rounded-xl px-4 py-3 text-sm outline-none transition-colors @error('email') border-mja-red @enderror"
                                            placeholder="jean@exemple.com">
                                        @error('email')<p class="text-mja-red text-xs mt-1 font-display font-semibold">{{ $message }}</p>@enderror
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-display font-bold text-mja-gray mb-1.5">Adresse postale complète <span class="text-mja-red">*</span></label>
                                    <textarea name="adresse_postale" rows="2" required
                                        class="w-full border-2 border-gray-100 focus:border-mja-blue rounded-xl px-4 py-3 text-sm outline-none transition-colors resize-none @error('adresse_postale') border-mja-red @enderror"
                                        placeholder="N° rue, quartier, code postal, ville">{{ old('adresse_postale') }}</textarea>
                                    @error('adresse_postale')<p class="text-mja-red text-xs mt-1 font-display font-semibold">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>

                        {{-- Bloc 4 : Informations complémentaires --}}
                        <div>
                            <h3 class="font-display font-bold text-mja-gray text-sm uppercase tracking-wider mb-4 flex items-center gap-2">
                                <span class="w-6 h-6 bg-mja-blue text-white rounded-full flex items-center justify-center text-xs font-black">4</span>
                                Informations complémentaires
                            </h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-display font-bold text-mja-gray mb-2">Taille de T-Shirt <span class="text-mja-red">*</span></label>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach(['S', 'M', 'L', 'XL', '2XL', '3XL'] as $taille)
                                        <label class="cursor-pointer">
                                            <input type="radio" name="taille_tshirt" value="{{ $taille }}"
                                                {{ old('taille_tshirt') === $taille ? 'checked' : '' }}
                                                class="peer sr-only">
                                            <div class="border-2 border-gray-100 peer-checked:border-mja-blue peer-checked:bg-mja-blue peer-checked:text-white rounded-xl px-4 py-2 text-sm font-display font-bold text-gray-500 transition-all hover:border-gray-200 min-w-[3rem] text-center">
                                                {{ $taille }}
                                            </div>
                                        </label>
                                        @endforeach
                                    </div>
                                    @error('taille_tshirt')<p class="text-mja-red text-xs mt-1.5 font-display font-semibold">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-display font-bold text-mja-gray mb-2">As-tu le permis de conduire ? <span class="text-mja-red">*</span></label>
                                    <div class="flex gap-3">
                                        @foreach(['Oui', 'Non'] as $opt)
                                        <label class="cursor-pointer flex-1 max-w-[8rem]">
                                            <input type="radio" name="permis" value="{{ $opt }}"
                                                {{ old('permis') === $opt ? 'checked' : '' }}
                                                class="peer sr-only">
                                            <div class="border-2 border-gray-100 peer-checked:border-mja-blue peer-checked:bg-mja-blue/5 rounded-xl p-3 text-center text-sm font-display font-bold text-gray-500 peer-checked:text-mja-blue transition-all hover:border-gray-200">
                                                {{ $opt }}
                                            </div>
                                        </label>
                                        @endforeach
                                    </div>
                                    @error('permis')<p class="text-mja-red text-xs mt-1.5 font-display font-semibold">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-display font-bold text-mja-gray mb-1.5">Problèmes de santé, allergies ou intolérances <span class="text-gray-400 font-normal">(facultatif)</span></label>
                                    <textarea name="problemes_sante" rows="2"
                                        class="w-full border-2 border-gray-100 focus:border-mja-blue rounded-xl px-4 py-3 text-sm outline-none transition-colors resize-none"
                                        placeholder="Précise ici si tu as des problèmes de santé, des allergies ou des intolérances...">{{ old('problemes_sante') }}</textarea>
                                </div>

                                <div>
                                    <label class="block text-sm font-display font-bold text-mja-gray mb-1.5">Personne à contacter en cas d'urgence <span class="text-mja-red">*</span></label>
                                    <input type="text" name="urgence_contact" value="{{ old('urgence_contact') }}" required
                                        class="w-full border-2 border-gray-100 focus:border-mja-blue rounded-xl px-4 py-3 text-sm outline-none transition-colors @error('urgence_contact') border-mja-red @enderror"
                                        placeholder="Nom — Prénom — Numéro de téléphone">
                                    @error('urgence_contact')<p class="text-mja-red text-xs mt-1 font-display font-semibold">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>

                        {{-- Bloc 5 : Droit à l'image --}}
                        <div>
                            <h3 class="font-display font-bold text-mja-gray text-sm uppercase tracking-wider mb-4 flex items-center gap-2">
                                <span class="w-6 h-6 bg-mja-blue text-white rounded-full flex items-center justify-center text-xs font-black">5</span>
                                Autorisation — Droit à l'image
                            </h3>
                            <div class="bg-gray-50 rounded-xl p-5 text-xs text-gray-500 leading-relaxed mb-4 border border-gray-100">
                                <p class="mb-2">Je déclare accepter de participer, à titre gracieux, aux photographies et vidéos effectuées dans le cadre des actions réalisées par <strong class="text-mja-gray">Madin' Jeunes Ambition</strong>. J'autorise, à titre gracieux, l'organisateur :</p>
                                <ul class="list-disc list-inside space-y-1 mb-2 ml-1">
                                    <li>à procéder à la diffusion des images fixes ou en mouvement me représentant ainsi que des éléments sonores associés,</li>
                                    <li>à exploiter ou autoriser l'exploitation des photographies, en tout ou partie, tant dans le secteur commercial que non commercial, public que privé, par tous modes et procédés analogique et/ou numérique, notamment par télédiffusion, réseaux numériques interactifs, reproduction sur tous supports connus ou inconnus à ce jour (vidéocassettes, DVD, CD-ROM, USB, etc.).</li>
                                </ul>
                                <p>Ceci, dans le cadre de leurs activités ou de toutes autres prestations, <strong>sans aucune limitation de durée et dans le monde entier</strong>.</p>
                            </div>
                            <label class="flex items-start gap-3 cursor-pointer group">
                                <input type="checkbox" name="droit_image" value="1"
                                    {{ old('droit_image') ? 'checked' : '' }}
                                    class="mt-0.5 w-5 h-5 rounded border-2 border-gray-300 text-mja-blue focus:ring-mja-blue shrink-0 @error('droit_image') border-mja-red @enderror">
                                <span class="text-sm font-display font-bold text-mja-gray group-hover:text-mja-blue transition-colors">
                                    J'accepte l'autorisation du droit à l'image <span class="text-mja-red">*</span>
                                </span>
                            </label>
                            @error('droit_image')<p class="text-mja-red text-xs mt-1.5 font-display font-semibold">{{ $message }}</p>@enderror
                        </div>

                        <button type="submit"
                            class="w-full btn-blue font-display font-bold py-4 rounded-xl transition-colors flex items-center justify-center gap-2 text-base">
                            <i class="fas fa-paper-plane"></i> Envoyer ma demande d'adhésion
                        </button>
                    </form>
                    @endif
                </div>
            </div>

        </div>
    </div>
</section>

@endsection
