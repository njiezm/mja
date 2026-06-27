@extends('layouts.app')
@section('title', "{$event->titre} — Madin'Jeunes Ambition")
@section('meta_description', $event->description ? \Illuminate\Support\Str::limit(strip_tags($event->description), 155) : "Événement organisé par Madin'Jeunes Ambition en Martinique.")
@section('og_type', 'event')
@if($event->image ?? null)
@section('og_image', asset('storage/'.$event->image))
@endif

@section('content')
<section class="hero-gradient text-white py-16 relative overflow-hidden">
    <div class="absolute -right-10 -top-10 w-64 h-64 opacity-10 pointer-events-none">
        <svg viewBox="0 0 200 200" fill="none"><circle cx="100" cy="100" r="90" stroke="#D0021B" stroke-width="2"/><circle cx="100" cy="100" r="60" stroke="#F5A623" stroke-width="2"/></svg>
    </div>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-sm text-gray-400 mb-4 font-display font-semibold">
            <a href="{{ route('home') }}" class="hover:text-mja-yellow">Accueil</a>
            <span class="mx-2 text-gray-600">/</span>
            <a href="{{ route('events.index') }}" class="hover:text-mja-yellow">Événements</a>
            <span class="mx-2 text-gray-600">/</span>
            {{ Str::limit($event->titre, 40) }}
        </div>
        <h1 class="font-display font-black text-3xl sm:text-4xl lg:text-5xl mb-5 leading-tight">{{ $event->titre }}</h1>
        <div class="flex flex-wrap gap-5 text-sm text-gray-300 font-display font-semibold">
            <span><i class="fas fa-calendar mr-1 text-mja-yellow"></i>{{ $event->date_debut->locale('fr')->isoFormat('dddd D MMMM Y') }}</span>
            <span><i class="fas fa-clock mr-1 text-mja-yellow"></i>{{ $event->date_debut->format('H\hi') }}</span>
            @if($event->lieu)<span><i class="fas fa-map-marker-alt mr-1 text-mja-yellow"></i>{{ $event->lieu }}</span>@endif
            @if($event->gratuit)<span class="text-green-400"><i class="fas fa-tag mr-1"></i>Entrée gratuite</span>@endif
        </div>
    </div>
</section>
<div class="flex h-1"><div class="flex-1 bg-mja-blue"></div><div class="flex-1 bg-mja-yellow"></div><div class="flex-1 bg-mja-red"></div></div>

<section class="py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($event->image)
        <img src="{{ asset("storage/{$event->image}") }}" alt="{{ $event->titre }}"
             class="w-full rounded-2xl mb-10 object-cover max-h-80 shadow-md">
        @endif

        <!-- Infos pratiques -->
        <div class="bg-gray-50 border border-gray-100 rounded-2xl p-6 mb-10 grid grid-cols-1 sm:grid-cols-3 gap-6 border-t-4 border-t-mja-blue">
            <div>
                <div class="text-xs font-display font-bold text-gray-400 uppercase tracking-wider mb-2">
                    <i class="fas fa-calendar text-mja-blue mr-1"></i>Date et heure
                </div>
                <div class="font-display font-bold text-mja-gray">{{ $event->date_debut->locale('fr')->isoFormat('ddd D MMM Y') }}</div>
                <div class="text-sm text-gray-600 mt-1">
                    {{ $event->date_debut->format('H\hi') }}
                    @if($event->date_fin) → {{ $event->date_fin->format('H\hi') }}@endif
                </div>
            </div>
            @if($event->lieu)
            <div>
                <div class="text-xs font-display font-bold text-gray-400 uppercase tracking-wider mb-2">
                    <i class="fas fa-map-marker-alt text-mja-yellow mr-1"></i>Lieu
                </div>
                <div class="font-display font-bold text-mja-gray">{{ $event->lieu }}</div>
                @if($event->adresse)<div class="text-sm text-gray-600 mt-1">{{ $event->adresse }}</div>@endif
            </div>
            @endif
            <div>
                <div class="text-xs font-display font-bold text-gray-400 uppercase tracking-wider mb-2">
                    <i class="fas fa-ticket-alt text-mja-red mr-1"></i>Tarif
                </div>
                <div class="font-display font-bold {{ $event->gratuit ? 'text-green-600' : 'text-orange-600' }}">
                    {{ $event->gratuit ? 'Gratuit' : 'Payant' }}
                </div>
                @if($event->lien_inscription)
                <a href="{{ $event->lien_inscription }}" target="_blank"
                   class="inline-flex items-center gap-1 mt-3 btn-yellow font-display font-bold text-xs px-4 py-2 rounded-full transition-colors">
                    S'inscrire <i class="fas fa-external-link-alt"></i>
                </a>
                @endif
            </div>
        </div>

        @if($event->description_courte)
        <p class="text-xl text-gray-600 font-display font-semibold leading-relaxed mb-8 border-l-4 border-mja-red pl-5 py-1">
            {{ $event->description_courte }}
        </p>
        @endif
        <div class="text-gray-700 leading-loose text-[15px]">
            {!! nl2br(e($event->description)) !!}
        </div>
        <div class="mt-12 pt-6 border-t border-gray-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <a href="{{ route('events.index') }}" class="text-mja-blue hover:text-mja-bluedark font-display font-bold flex items-center gap-2 transition-colors">
                <i class="fas fa-arrow-left"></i> Retour aux événements
            </a>
            @if($event->lien_inscription)
            <a href="{{ $event->lien_inscription }}" target="_blank"
               class="btn-yellow font-display font-bold px-6 py-3 rounded-full text-sm transition-colors inline-flex items-center gap-2">
                <i class="fas fa-pencil-alt"></i> S'inscrire à cet événement
            </a>
            @else
            <a href="{{ route('contact') }}" class="btn-blue font-display font-bold px-6 py-3 rounded-full text-sm transition-colors inline-flex items-center gap-2">
                <i class="fas fa-envelope"></i> Nous contacter
            </a>
            @endif
        </div>
    </div>
</section>
@endsection
