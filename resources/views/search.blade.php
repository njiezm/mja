@extends('layouts.app')
@section('title', ($q ? "Recherche : {$q}" : 'Recherche') . " — Madin'Jeunes Ambition")
@section('og_image', asset('images/partage/defaut.jpg'))
@section('meta_description', "Recherchez parmi les actualités, projets, événements et ressources de Madin'Jeunes Ambition.")

@section('content')
<section class="hero-gradient text-white py-16 relative overflow-hidden">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-sm text-gray-400 mb-3 font-display font-semibold">
            <a href="{{ route('home') }}" class="hover:text-mja-yellow">Accueil</a> <span class="mx-2 text-gray-600">/</span> Recherche
        </div>
        <h1 class="font-display font-black text-4xl sm:text-5xl mb-6">Recherche</h1>
        <form method="GET" action="{{ route('search') }}" role="search" class="flex gap-2 max-w-2xl">
            <label for="site-search" class="sr-only">Rechercher sur le site</label>
            <div class="relative flex-1">
                <i class="fas fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" aria-hidden="true"></i>
                <input id="site-search" type="search" name="q" value="{{ $q }}" autofocus
                       placeholder="Actualités, projets, événements, ressources…"
                       class="w-full rounded-full bg-white text-mja-gray pl-11 pr-4 py-3 focus:outline-none focus:ring-2 focus:ring-mja-yellow">
            </div>
            <button type="submit" class="btn-yellow font-display font-bold px-6 py-3 rounded-full shrink-0">Rechercher</button>
        </form>
    </div>
</section>
<div class="flex h-1"><div class="flex-1 bg-mja-blue"></div><div class="flex-1 bg-mja-yellow"></div><div class="flex-1 bg-mja-red"></div></div>

<section class="py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        @if(strlen($q) < 2)
        <p class="text-gray-500 text-center py-10">Saisissez au moins 2 caractères pour lancer une recherche.</p>
        @elseif($results->isEmpty())
        <div class="text-center py-16">
            <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-5 text-gray-400 text-2xl"><i class="fas fa-magnifying-glass"></i></div>
            <p class="font-display font-bold text-lg text-mja-gray mb-1">Aucun résultat pour « {{ $q }} »</p>
            <p class="text-gray-500 text-sm">Essayez avec d'autres mots-clés.</p>
        </div>
        @else
        <p class="text-sm text-gray-500 font-display font-semibold mb-6">{{ $results->count() }} résultat{{ $results->count() > 1 ? 's' : '' }} pour « {{ $q }} »</p>
        <div class="space-y-4">
            @foreach($results as $r)
            <a href="{{ $r['url'] }}" class="flex items-start gap-4 bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md transition-shadow">
                <div class="w-11 h-11 bg-mja-blue/10 text-mja-blue rounded-xl flex items-center justify-center shrink-0"><i class="fas {{ $r['icon'] }}"></i></div>
                <div class="min-w-0">
                    <span class="text-xs font-display font-bold uppercase tracking-wider text-mja-blue">{{ $r['type'] }}</span>
                    <h2 class="font-display font-bold text-mja-gray leading-snug">{{ $r['titre'] }}</h2>
                    @if($r['extrait'])<p class="text-sm text-gray-500 mt-1 line-clamp-2">{{ \Illuminate\Support\Str::limit(strip_tags($r['extrait']), 160) }}</p>@endif
                </div>
            </a>
            @endforeach
        </div>
        @endif
    </div>
</section>
@endsection
