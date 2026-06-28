@extends('layouts.app')
@section('title', "Ressources — Madin'Jeunes Ambition")
@section('meta_description', "Documents, guides et ressources utiles mis à disposition par Madin'Jeunes Ambition pour les jeunes de Martinique et des Antilles.")

@section('content')
<section class="hero-gradient text-white py-16 relative overflow-hidden">
    <div class="absolute -right-10 -top-10 w-64 h-64 opacity-10 pointer-events-none">
        <svg viewBox="0 0 200 200" fill="none"><circle cx="100" cy="100" r="90" stroke="#3DAEF5" stroke-width="2"/><circle cx="100" cy="100" r="65" stroke="#F5A623" stroke-width="2"/><circle cx="100" cy="100" r="40" stroke="#D0021B" stroke-width="2"/></svg>
    </div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-sm text-gray-400 mb-3 font-display font-semibold">
            <a href="{{ route('home') }}" class="hover:text-mja-yellow transition-colors">Accueil</a>
            <span class="mx-2 text-gray-600">/</span> Ressources
        </div>
        <h1 class="font-display font-black text-4xl sm:text-5xl mb-4">Ressources</h1>
        <p class="text-gray-300 text-lg">Documents, guides et liens utiles mis à votre disposition.</p>
    </div>
</section>
<div class="flex h-1"><div class="flex-1 bg-mja-blue"></div><div class="flex-1 bg-mja-yellow"></div><div class="flex-1 bg-mja-red"></div></div>

<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Filtres -->
        @if($categories->count())
        <div class="flex flex-wrap gap-3 mb-10">
            <a href="{{ route('resources.index') }}"
               class="px-5 py-2 rounded-full text-sm font-display font-bold transition-colors {{ !$categorie ? 'bg-mja-blue text-white' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200' }}">
                <i class="fas fa-th-large mr-1"></i> Toutes
            </a>
            @foreach($categories as $cat)
            <a href="{{ route('resources.index', ['categorie' => $cat]) }}"
               class="px-5 py-2 rounded-full text-sm font-display font-bold transition-colors {{ $categorie === $cat ? 'bg-mja-blue text-white' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200' }}">
                {{ $cat }}
            </a>
            @endforeach
        </div>
        @endif

        @if($resources->count())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($resources as $i => $resource)
            @php
                $typeConfig = [
                    'document'    => ['bg-mja-blue/10',   'text-mja-blue',   'fa-file-alt'],
                    'pdf'         => ['bg-red-50',        'text-red-600',    'fa-file-pdf'],
                    'video'       => ['bg-mja-red/10',    'text-mja-red',    'fa-play-circle'],
                    'audio'       => ['bg-purple-50',     'text-purple-600', 'fa-music'],
                    'podcast'     => ['bg-indigo-50',     'text-indigo-600', 'fa-microphone'],
                    'guide'       => ['bg-mja-yellow/10', 'text-yellow-700', 'fa-book-open'],
                    'infographie' => ['bg-green-50',      'text-green-600',  'fa-chart-bar'],
                    'lien'        => ['bg-gray-50',       'text-gray-600',   'fa-link'],
                ];
                $tc = $typeConfig[$resource->type] ?? ['bg-gray-100','text-gray-500','fa-file'];
                $accents = ['card-accent-blue','card-accent-yellow','card-accent-red'];
            @endphp
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 card-hover {{ $accents[$i % 3] }}">
                <div class="flex items-start gap-4 mb-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 {{ $tc[0] }}">
                        <i class="fas {{ $tc[2] }} text-lg {{ $tc[1] }}"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        @if($resource->categorie)
                        <span class="text-xs font-display font-bold text-gray-400 uppercase tracking-wider">{{ $resource->categorie }}</span>
                        @endif
                        <h3 class="font-display font-bold text-mja-gray mt-0.5 leading-snug">{{ $resource->titre }}</h3>
                    </div>
                </div>
                @if($resource->description)
                <p class="text-gray-500 text-sm leading-relaxed mb-5">{{ $resource->description }}</p>
                @endif
                @if($resource->fichier)
                <a href="{{ asset("storage/{$resource->fichier}") }}" download
                   class="inline-flex items-center gap-2 btn-blue font-display font-bold text-sm px-5 py-2.5 rounded-xl transition-colors">
                    <i class="fas fa-download"></i> Télécharger
                </a>
                @elseif($resource->lien_externe)
                <a href="{{ $resource->lien_externe }}" target="_blank"
                   class="inline-flex items-center gap-2 btn-blue font-display font-bold text-sm px-5 py-2.5 rounded-xl transition-colors">
                    <i class="fas fa-external-link-alt"></i> Accéder
                </a>
                @endif
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-24">
            <img src="/images/logo.jpg" alt="" class="h-20 w-20 mx-auto mb-6 opacity-20 object-contain">
            <p class="text-xl font-display font-bold text-gray-400">Aucune ressource disponible.</p>
        </div>
        @endif
    </div>
</section>
@endsection
