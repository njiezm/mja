@extends('layouts.app')
@section('title', "Nos projets — Madin'Jeunes Ambition")
@section('meta_description', "Découvrez les projets de Madin'Jeunes Ambition : Fwi Ti Dèj, Sport Day MJA, Madin'Santé Challenge et autres initiatives pour la jeunesse martiniquaise.")

@section('content')
<section class="hero-gradient text-white py-16 relative overflow-hidden">
    <div class="absolute -right-10 -top-10 w-64 h-64 opacity-10 pointer-events-none">
        <svg viewBox="0 0 200 200" fill="none"><circle cx="100" cy="100" r="95" stroke="#4A90E2" stroke-width="2"/><circle cx="100" cy="100" r="70" stroke="#F5A623" stroke-width="2"/><circle cx="100" cy="100" r="45" stroke="#D0021B" stroke-width="2"/></svg>
    </div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-sm text-gray-400 mb-3 font-display font-semibold">
            <a href="{{ route('home') }}" class="hover:text-mja-yellow transition-colors">Accueil</a>
            <span class="mx-2 text-gray-600">/</span> Projets
        </div>
        <h1 class="font-display font-black text-4xl sm:text-5xl mb-4">Nos projets</h1>
        <p class="text-gray-300 text-lg">Découvrez les initiatives portées par <span class="text-mja-blue font-bold">M</span><span class="text-mja-yellow font-bold">J</span><span class="text-mja-red font-bold">A</span> depuis 2011.</p>
    </div>
</section>
<div class="flex h-1"><div class="flex-1 bg-mja-blue"></div><div class="flex-1 bg-mja-yellow"></div><div class="flex-1 bg-mja-red"></div></div>

<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($projects->count())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($projects as $i => $project)
            @php $accents = ['card-accent-blue','card-accent-yellow','card-accent-red']; @endphp
            <a href="{{ route('projects.show', $project) }}"
               class="group bg-white rounded-2xl shadow-sm card-hover overflow-hidden border border-gray-100 {{ $accents[$i % 3] }}">
                @if($project->image)
                <div class="aspect-video bg-gray-100 overflow-hidden">
                    <img src="{{ asset("storage/{$project->image}") }}" alt="{{ $project->titre }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                </div>
                @else
                <div class="aspect-video bg-gradient-to-br from-mja-dark to-mja-blue/80 flex items-center justify-center">
                    <img src="/images/logo.jpg" alt="" class="h-16 w-16 object-contain opacity-25">
                </div>
                @endif
                <div class="p-6">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-display font-bold px-3 py-1 rounded-full
                            {{ $project->statut === 'en_cours' ? 'bg-mja-blue/10 text-mja-blue' : ($project->statut === 'a_venir' ? 'bg-mja-yellow/10 text-yellow-700' : 'bg-gray-100 text-gray-500') }}">
                            @if($project->statut === 'en_cours')<i class="fas fa-circle text-[8px] mr-1"></i> En cours
                            @elseif($project->statut === 'a_venir')<i class="far fa-circle text-[8px] mr-1"></i> À venir
                            @else<i class="fas fa-check text-[8px] mr-1"></i> Terminé@endif
                        </span>
                        @if($project->date_debut)
                        <span class="text-xs text-gray-400 font-display">{{ $project->date_debut->locale('fr')->isoFormat('MMM Y') }}</span>
                        @endif
                    </div>
                    <h2 class="font-display font-bold text-lg text-mja-gray mb-2 group-hover:text-mja-blue transition-colors">{{ $project->titre }}</h2>
                    <p class="text-gray-500 text-sm leading-relaxed line-clamp-3">{{ $project->description_courte }}</p>
                    <div class="mt-4 text-mja-blue text-sm font-display font-bold flex items-center gap-1">
                        En savoir plus <i class="fas fa-arrow-right text-xs"></i>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        <div class="mt-10">{{ $projects->links() }}</div>
        @else
        <div class="text-center py-24">
            <img src="/images/logo.jpg" alt="" class="h-20 w-20 mx-auto mb-6 opacity-20 object-contain">
            <p class="text-xl font-display font-bold text-gray-400">Aucun projet pour le moment.</p>
        </div>
        @endif
    </div>
</section>
@endsection
