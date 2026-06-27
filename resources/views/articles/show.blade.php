@extends('layouts.app')
@section('title', "{$article->titre} — Madin'Jeunes Ambition")
@section('meta_description', $article->extrait ?? \Illuminate\Support\Str::limit(strip_tags($article->contenu ?? ''), 155))
@section('og_type', 'article')
@if($article->image)
@section('og_image', asset('storage/'.$article->image))
@endif

@section('content')

<section class="hero-gradient text-white py-16 relative overflow-hidden">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-sm text-gray-400 mb-4 font-display font-semibold">
            <a href="{{ route('home') }}" class="hover:text-mja-yellow">Accueil</a>
            <span class="mx-2 text-gray-600">/</span>
            <a href="{{ route('articles.index') }}" class="hover:text-mja-yellow">Actualités</a>
            <span class="mx-2 text-gray-600">/</span>
            {{ Str::limit($article->titre, 40) }}
        </div>
        <span class="inline-block text-xs font-display font-bold px-3 py-1 bg-mja-blue/30 border border-mja-blue/40 text-blue-200 rounded-full mb-4">
            {{ $article->categorie }}
        </span>
        <h1 class="font-display font-black text-3xl sm:text-4xl lg:text-5xl mb-4 leading-tight">{{ $article->titre }}</h1>
        <div class="flex flex-wrap gap-5 text-sm text-gray-300 font-display font-semibold">
            @if($article->auteur)<span><i class="fas fa-user mr-1 text-mja-yellow"></i>{{ $article->auteur }}</span>@endif
            @if($article->publie_le)<span><i class="fas fa-calendar mr-1 text-mja-yellow"></i>{{ $article->publie_le->locale('fr')->isoFormat('D MMMM Y') }}</span>@endif
        </div>
    </div>
</section>
<div class="flex h-1"><div class="flex-1 bg-mja-blue"></div><div class="flex-1 bg-mja-yellow"></div><div class="flex-1 bg-mja-red"></div></div>

<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">

            <article class="lg:col-span-2">
                @if($article->image)
                <img src="{{ asset("storage/{$article->image}") }}" alt="{{ $article->titre }}" class="w-full rounded-2xl mb-8 object-cover max-h-80">
                @endif
                @if($article->extrait)
                <p class="text-xl text-gray-600 font-display font-semibold leading-relaxed mb-8 border-l-4 border-mja-blue pl-5 py-1">{{ $article->extrait }}</p>
                @endif
                <div class="text-gray-700 leading-loose text-[15px]">{!! nl2br(e($article->contenu)) !!}</div>
                <div class="mt-10 pt-6 border-t border-gray-100 flex items-center justify-between">
                    <a href="{{ route('articles.index') }}" class="text-mja-blue hover:text-mja-bluedark font-display font-bold flex items-center gap-2 transition-colors">
                        <i class="fas fa-arrow-left"></i> Retour aux actualités
                    </a>
                </div>
            </article>

            <!-- Sidebar -->
            <aside class="space-y-6">
                @if($recents->count())
                <div class="bg-gray-50 rounded-2xl p-6 border-t-4 border-mja-blue">
                    <h3 class="font-display font-bold text-mja-gray mb-5">Autres actualités</h3>
                    <div class="space-y-4">
                        @foreach($recents as $recent)
                        <a href="{{ route('articles.show', $recent) }}" class="flex gap-3 group">
                            @if($recent->image)
                            <img src="{{ asset("storage/{$recent->image}") }}" alt="" class="w-14 h-14 rounded-xl object-cover shrink-0">
                            @else
                            <div class="w-14 h-14 rounded-xl bg-mja-blue/10 flex items-center justify-center shrink-0">
                                <i class="fas fa-newspaper text-mja-blue"></i>
                            </div>
                            @endif
                            <div>
                                <h4 class="text-sm font-display font-bold text-mja-gray group-hover:text-mja-blue transition-colors line-clamp-2">{{ $recent->titre }}</h4>
                                @if($recent->publie_le)<p class="text-xs text-gray-400 mt-1 font-display">{{ $recent->publie_le->locale('fr')->isoFormat('D MMM Y') }}</p>@endif
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="bg-mja-dark rounded-2xl p-6 text-center ring-watermark">
                    <img src="/images/logo.jpg" alt="MJA" class="h-16 w-16 mx-auto mb-4 object-contain bg-white rounded-xl p-1">
                    <h3 class="font-display font-bold text-white mb-2">Envie de s'engager ?</h3>
                    <p class="text-gray-400 text-sm mb-4">Rejoignez notre équipe de bénévoles.</p>
                    <a href="{{ route('contact') }}" class="btn-yellow font-display font-bold text-sm px-5 py-2.5 rounded-full inline-block transition-colors">
                        Nous contacter
                    </a>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection
