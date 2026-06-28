@extends('layouts.app')
@section('title', "À propos — Madin'Jeunes Ambition")
@section('meta_description', "Découvrez l'histoire, les valeurs et l'équipe de Madin'Jeunes Ambition — association de jeunes bénévoles fondée en 2011 à Fort-de-France, Martinique.")

@section('content')

<!-- Hero -->
<section class="hero-gradient text-white py-16 relative overflow-hidden">
    <div class="absolute -right-20 -top-20 w-72 h-72 opacity-10">
        <svg viewBox="0 0 200 200" fill="none"><circle cx="100" cy="100" r="95" stroke="#3DAEF5" stroke-width="2"/><circle cx="100" cy="100" r="70" stroke="#F5A623" stroke-width="2"/><circle cx="100" cy="100" r="45" stroke="#D0021B" stroke-width="2"/></svg>
    </div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-sm text-gray-400 mb-3 font-display font-semibold">
            <a href="{{ route('home') }}" class="hover:text-mja-yellow transition-colors">Accueil</a>
            <span class="mx-2 text-gray-600">/</span> À propos
        </div>
        <h1 class="font-display font-black text-4xl sm:text-5xl mb-4">À propos de <span class="text-mja-blue">M</span><span class="text-mja-yellow">J</span><span class="text-mja-red">A</span></h1>
        <p class="text-gray-300 text-lg max-w-2xl">Qui nous sommes et ce qui nous anime depuis 2011.</p>
    </div>
</section>

<div class="flex h-1"><div class="flex-1 bg-mja-blue"></div><div class="flex-1 bg-mja-yellow"></div><div class="flex-1 bg-mja-red"></div></div>

<!-- Histoire -->
<section class="py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div>
                <div class="inline-flex items-center gap-2 mb-4">
                    <span class="w-8 h-0.5 bg-mja-blue"></span>
                    <span class="text-mja-blue font-display font-bold text-sm uppercase tracking-widest">Notre histoire</span>
                </div>
                <h2 class="font-display font-black text-3xl sm:text-4xl text-mja-gray mt-2 mb-6">
                    Une association née de<br>l'engagement des jeunes
                </h2>
                <div class="space-y-4 text-gray-600 leading-relaxed">
                    <p>Madin'Jeunes Ambition (MJA) a été créée le <strong class="text-mja-gray font-display font-bold">5 novembre 2011</strong> à Fort-de-France, Martinique. L'association est née de la volonté de jeunes bénévoles dynamiques de créer du lien entre les jeunes de l'île et de porter des actions concrètes en faveur de leur épanouissement.</p>
                    <p>Depuis sa création, l'association est portée entièrement par des bénévoles qui œuvrent au quotidien pour organiser des actions éducatives, culturelles, sociales, sportives et de santé, tout en entretenant des liens intergénérationnels précieux.</p>
                    <p>Avec plus de <strong class="text-mja-gray font-display font-bold">70 membres</strong> et une trentaine de sympathisants, MJA est un acteur reconnu de la jeunesse martiniquaise.</p>
                </div>
            </div>
            <!-- Timeline -->
            <div class="space-y-4">
                @foreach([
                    ['2011','bg-mja-blue','Création de l\'association MJA le 5 novembre à Fort-de-France.'],
                    ['2012','bg-mja-yellow text-mja-dark','Co-organisation des Trophées Lumina « Les grands trophées de la jeunesse ».'],
                    ['2016','bg-mja-red','Lancement de l\'opération Ti Dèj pour les candidats au baccalauréat.'],
                    ['2017','bg-mja-blue','Madin\'Santé Challenge et renouvellement de l\'opération Ti Dèj.'],
                ] as [$year,$color,$event])
                <div class="flex gap-5 items-start">
                    <div class="w-14 h-14 {{ $color }} rounded-2xl flex items-center justify-center font-display font-black text-white text-sm shrink-0 shadow-md">{{ $year }}</div>
                    <div class="flex-1 bg-gray-50 rounded-2xl p-4 border border-gray-100">
                        <p class="text-gray-700 text-sm leading-relaxed">{{ $event }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- Valeurs -->
<section class="py-20 bg-mja-dark text-white ring-watermark">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="inline-flex items-center gap-2 mb-4">
            <span class="w-8 h-0.5 bg-mja-yellow"></span>
            <span class="text-mja-yellow font-display font-bold text-sm uppercase tracking-widest">Ce qui nous guide</span>
            <span class="w-8 h-0.5 bg-mja-yellow"></span>
        </div>
        <h2 class="font-display font-black text-3xl sm:text-4xl mt-2 mb-12">Nos valeurs</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach([
                ['fa-star','text-mja-yellow bg-mja-yellow/10','Ambition','Toujours viser plus haut pour les jeunes de la Martinique.'],
                ['fa-hands-holding-heart','text-mja-blue bg-mja-blue/10','Solidarité','Agir ensemble, se soutenir, tisser des liens durables.'],
                ['fa-seedling','text-mja-red bg-mja-red/10','Engagement','Donner de son temps et de son énergie au service du collectif.'],
                ['fa-lightbulb','text-mja-yellow bg-mja-yellow/10','Innovation','Imaginer des projets originaux adaptés aux réalités locales.'],
            ] as [$icon,$class,$titre,$desc])
            <div class="bg-white/5 border border-white/10 rounded-2xl p-8">
                <div class="w-14 h-14 {{ $class }} rounded-2xl flex items-center justify-center mx-auto mb-5">
                    <i class="fas {{ $icon }} text-2xl"></i>
                </div>
                <h3 class="font-display font-bold text-xl mb-3">{{ $titre }}</h3>
                <p class="text-gray-400 text-sm leading-relaxed">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Équipe -->
@if($members->count())
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <div class="inline-flex items-center gap-2 mb-4">
                <span class="w-8 h-0.5 bg-mja-red"></span>
                <span class="text-mja-red font-display font-bold text-sm uppercase tracking-widest">Les visages de MJA</span>
                <span class="w-8 h-0.5 bg-mja-red"></span>
            </div>
            <h2 class="font-display font-black text-3xl sm:text-4xl text-mja-gray">Notre équipe</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @php
            $memberColors = [
                ['avatar'=>'bg-mja-blue',   'ring'=>'ring-mja-blue',   'role'=>'text-mja-blue'],
                ['avatar'=>'bg-mja-yellow',  'ring'=>'ring-mja-yellow', 'role'=>'text-yellow-600'],
                ['avatar'=>'bg-mja-red',     'ring'=>'ring-mja-red',    'role'=>'text-mja-red'],
            ];
            @endphp
            @foreach($members as $i => $member)
            @php $mc = $memberColors[$i % 3]; @endphp
            <div class="bg-white rounded-2xl p-6 shadow-sm text-center card-hover border border-gray-100">
                <div class="w-20 h-20 mx-auto rounded-2xl overflow-hidden mb-4 ring-2 {{ $mc['ring'] }}">
                    @if($member->photo)
                    <img src="{{ asset('storage/'.$member->photo) }}" alt="{{ $member->nom_complet }}" class="w-full h-full object-cover">
                    @else
                    <div class="w-full h-full flex items-center justify-center {{ $mc['avatar'] }} text-white font-display font-black text-2xl">
                        {{ strtoupper(substr($member->prenom,0,1).substr($member->nom,0,1)) }}
                    </div>
                    @endif
                </div>
                <h3 class="font-display font-bold text-mja-gray">{{ $member->nom_complet }}</h3>
                <p class="{{ $mc['role'] }} font-display font-semibold text-sm mt-1">{{ $member->role }}</p>
                @if($member->bio)<p class="text-gray-500 text-sm mt-3 leading-relaxed">{{ $member->bio }}</p>@endif
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- CTA -->
<section class="py-16 bg-white border-t-4 border-mja-blue text-center">
    <div class="max-w-3xl mx-auto px-4">
        <h2 class="font-display font-black text-3xl text-mja-gray mb-4">Vous souhaitez nous soutenir ?</h2>
        <p class="text-gray-500 mb-8">Partenaires, donateurs, bénévoles — toute aide est la bienvenue.</p>
        <a href="{{ route('contact') }}" class="btn-yellow font-display font-bold px-10 py-4 rounded-full inline-block transition-colors text-lg">
            Prendre contact
        </a>
    </div>
</section>

@endsection
