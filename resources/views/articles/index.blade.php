@extends('layouts.app')
@section('title', "Actualités — Madin'Jeunes Ambition")
@section('meta_description', "Retrouvez toutes les actualités de Madin'Jeunes Ambition — événements, actions bénévoles, Fwi Ti Dèj et initiatives jeunesse en Martinique.")

@section('content')
<section class="hero-gradient text-white py-16 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-sm text-gray-400 mb-3 font-display font-semibold">
            <a href="{{ route('home') }}" class="hover:text-mja-yellow">Accueil</a> <span class="mx-2 text-gray-600">/</span> Actualités
        </div>
        <h1 class="font-display font-black text-4xl sm:text-5xl mb-4">Actualités</h1>
        <p class="text-gray-300 text-lg">Les dernières nouvelles de l'association.</p>
    </div>
</section>
<div class="flex h-1"><div class="flex-1 bg-mja-blue"></div><div class="flex-1 bg-mja-yellow"></div><div class="flex-1 bg-mja-red"></div></div>

<section class="py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Filtres -->
        <div class="flex flex-wrap gap-3 mb-10">
            <a href="{{ route('articles.index') }}"
               class="px-5 py-2 rounded-full text-sm font-display font-bold transition-colors {{ !$categorie ? 'bg-mja-blue text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                Toutes
            </a>
            @foreach(['actualite'=>'Actualité','projet'=>'Projet','sante'=>'Santé','education'=>'Éducation','evenement'=>'Événement'] as $key=>$label)
            <a href="{{ route('articles.index', ['categorie'=>$key]) }}"
               class="px-5 py-2 rounded-full text-sm font-display font-bold transition-colors {{ $categorie===$key ? 'bg-mja-blue text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                {{ $label }}
            </a>
            @endforeach
        </div>

        @if($articles->count())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($articles as $i => $article)
            @php $accents = ['card-accent-blue','card-accent-yellow','card-accent-red']; @endphp
            <a href="{{ route('articles.show', $article) }}" class="group bg-white rounded-2xl shadow-sm card-hover overflow-hidden border border-gray-100 {{ $accents[$i % 3] }}">
                @if($article->image)
                <div class="aspect-video bg-gray-100 overflow-hidden">
                    <img src="{{ asset("storage/{$article->image}") }}" alt="{{ $article->titre }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                </div>
                @else
                <div class="aspect-video bg-gradient-to-br from-mja-dark to-mja-blue/80 flex items-center justify-center">
                    <img src="/images/logo.jpg" alt="" class="h-12 opacity-30 object-contain">
                </div>
                @endif
                <div class="p-6">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="text-xs font-display font-bold px-3 py-1 bg-mja-blue/10 text-mja-blue rounded-full">{{ $article->categorie }}</span>
                        @if($article->publie_le)<span class="text-xs text-gray-400 font-display">{{ $article->publie_le->locale('fr')->isoFormat('D MMM Y') }}</span>@endif
                    </div>
                    <h2 class="font-display font-bold text-lg text-mja-gray mb-2 group-hover:text-mja-blue transition-colors line-clamp-2">{{ $article->titre }}</h2>
                    <p class="text-gray-500 text-sm leading-relaxed line-clamp-3">{{ $article->extrait }}</p>
                    <div class="mt-4 text-mja-blue text-sm font-display font-bold flex items-center gap-1">
                        Lire la suite <i class="fas fa-arrow-right text-xs"></i>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        <div class="mt-10">{{ $articles->links() }}</div>
        @else
        <div class="text-center py-24 text-gray-300">
            <img src="/images/logo.jpg" alt="" class="h-20 w-20 mx-auto mb-6 opacity-20 object-contain">
            <p class="text-xl font-display font-bold text-gray-400">Aucune actualité pour le moment.</p>
        </div>
        @endif
    </div>
</section>
@endsection
