@extends('layouts.app')
@section('title', "Madin'Jeunes Ambition – Les jeunes de la Martinique se mobilisent !")
@section('meta_description', "Association de jeunes bénévoles à Fort-de-France, Martinique. Fwi Ti Dèj, Sport, Santé, Culture — MJA mobilise la jeunesse ultramarine depuis 2011.")
@section('og_image', asset('images/logomjat.png'))

@section('content')

<!-- ═══ HERO ═══ -->
<section class="hero-gradient text-white py-24 relative overflow-hidden">
    <!-- Anneaux décoratifs inline (pas de ring-watermark pour éviter le conflit CSS) -->
    <div class="absolute -top-20 -right-20 w-96 h-96 opacity-10 pointer-events-none" aria-hidden="true">
        <svg viewBox="0 0 200 200" fill="none"><circle cx="100" cy="100" r="95" stroke="#4A90E2" stroke-width="2"/><circle cx="100" cy="100" r="75" stroke="#F5A623" stroke-width="2"/><circle cx="100" cy="100" r="55" stroke="#D0021B" stroke-width="2"/></svg>
    </div>
    <div class="absolute -bottom-16 -left-16 w-72 h-72 opacity-10 pointer-events-none" aria-hidden="true">
        <svg viewBox="0 0 200 200" fill="none"><circle cx="100" cy="100" r="90" stroke="#F5A623" stroke-width="2"/><circle cx="100" cy="100" r="65" stroke="#4A90E2" stroke-width="2"/></svg>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex flex-col lg:flex-row items-center gap-12">
            <!-- Texte -->
            <div class="flex-1 text-center lg:text-left">
                <div class="inline-flex items-center gap-2 bg-white/10 border border-white/20 text-sm font-display font-semibold px-4 py-1.5 rounded-full mb-6 text-white">
                    <span class="w-2 h-2 bg-mja-yellow rounded-full animate-pulse"></span>
                    Fort-de-France, Martinique — Depuis 2011
                </div>
                <h1 class="font-display font-black text-4xl sm:text-5xl lg:text-6xl mb-6 leading-tight text-white">
                    Les jeunes de la<br>Martinique
                    <span class="text-mja-yellow"> se mobilisent</span><span class="text-mja-red">!</span>
                </h1>
                <p class="text-lg text-gray-200 max-w-xl mb-10 leading-relaxed">
                    Madin'Jeunes Ambition rassemble les jeunes autour d'actions éducatives, culturelles, sportives et de santé pour construire une Martinique plus dynamique.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <a href="{{ route('about') }}" class="btn-blue font-display font-bold px-8 py-3.5 rounded-full transition-colors text-lg text-center">
                        Découvrir l'association
                    </a>
                    <a href="{{ route('adhesion') }}" class="btn-yellow font-display font-bold px-8 py-3.5 rounded-full transition-colors text-lg text-center">
                        Adhérer maintenant <i class="fas fa-arrow-right ml-1 text-sm"></i>
                    </a>
                </div>
                <!-- Réseaux sociaux -->
                <div class="flex gap-3 mt-8 justify-center lg:justify-start">
                    @foreach([
                        ['fab fa-facebook','https://www.facebook.com/MadinJeunesAmbition/','#1877F2'],
                        ['fab fa-instagram','https://www.instagram.com/madin_jeunes_ambition/','#E1306C'],
                        ['fab fa-tiktok','https://www.tiktok.com/@fwi_ti_dej','#010101'],
                        ['fab fa-snapchat','https://www.snapchat.com/','#FFFC00'],
                        ['fab fa-youtube','https://www.youtube.com/channel/UCX6nyVEv_QyFuLREyVvOMLw','#FF0000'],
                    ] as [$icon,$url,$color])
                    <a href="{{ $url }}" target="_blank"
                       class="w-9 h-9 bg-white/10 hover:bg-white/20 rounded-xl flex items-center justify-center transition-colors text-white text-sm">
                        <i class="{{ $icon }}"></i>
                    </a>
                    @endforeach
                </div>
            </div>

            <!-- Logo -->
            <div class="shrink-0 flex justify-center">
                <div class="relative">
                    <div class="w-56 h-56 lg:w-72 lg:h-72 bg-white rounded-3xl shadow-2xl flex items-center justify-center p-6">
                        <img src="/images/logo.jpg" alt="Madin'Jeunes Ambition" class="w-full h-full object-contain">
                    </div>
                    <div class="absolute -top-4 -right-4 bg-mja-yellow text-mja-dark font-display font-black text-xs px-3 py-1.5 rounded-full shadow-lg">
                        70+ membres
                    </div>
                    <div class="absolute -bottom-4 -left-4 bg-mja-red text-white font-display font-bold text-xs px-3 py-1.5 rounded-full shadow-lg">
                        Depuis 2011
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ═══ STATS ═══ -->
<section class="bg-mja-dark py-10 relative z-10" id="stats-section">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center text-white">
            <div>
                <div class="font-display font-black text-4xl text-mja-blue">
                    <span class="stat-counter" data-target="70">0</span>+
                </div>
                <div class="text-sm mt-1 text-gray-400 font-display font-semibold">Membres actifs</div>
            </div>
            <div>
                <div class="font-display font-black text-4xl text-mja-yellow">2011</div>
                <div class="text-sm mt-1 text-gray-400 font-display font-semibold">Année de création</div>
            </div>
            <div>
                <div class="font-display font-black text-4xl text-mja-red">
                    <span class="stat-counter" data-target="30">0</span>+
                </div>
                <div class="text-sm mt-1 text-gray-400 font-display font-semibold">Sympathisants</div>
            </div>
            <div>
                <div class="font-display font-black text-4xl text-mja-blue">
                    <span class="stat-counter" data-target="500">0</span>+
                </div>
                <div class="text-sm mt-1 text-gray-400 font-display font-semibold">Petits-déj organisés</div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
(function () {
    var counters = document.querySelectorAll('.stat-counter');
    if (!counters.length || !window.IntersectionObserver) {
        counters.forEach(function(el) { el.textContent = el.dataset.target; });
        return;
    }
    var observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (!entry.isIntersecting) return;
            var el = entry.target;
            var target = parseInt(el.dataset.target, 10);
            var duration = 1400;
            var steps = 50;
            var interval = duration / steps;
            var increment = target / steps;
            var current = 0;
            var timer = setInterval(function() {
                current = Math.min(current + increment, target);
                el.textContent = Math.round(current);
                if (current >= target) clearInterval(timer);
            }, interval);
            observer.unobserve(el);
        });
    }, { threshold: 0.6 });
    counters.forEach(function(el) { observer.observe(el); });
})();
</script>
@endpush

<div class="flex h-1"><div class="flex-1 bg-mja-blue"></div><div class="flex-1 bg-mja-yellow"></div><div class="flex-1 bg-mja-red"></div></div>

<!-- ═══ SNS BANNIÈRE ═══ -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-r from-mja-dark via-[#1a3a6a] to-mja-blue rounded-3xl overflow-hidden relative">
            <div class="absolute inset-0 opacity-10 pointer-events-none">
                <svg class="w-full h-full" viewBox="0 0 800 200" fill="none">
                    <circle cx="700" cy="100" r="180" stroke="#F5A623" stroke-width="2"/>
                    <circle cx="700" cy="100" r="130" stroke="#D0021B" stroke-width="2"/>
                    <circle cx="700" cy="100" r="80" stroke="#4A90E2" stroke-width="2"/>
                </svg>
            </div>
            <div class="relative z-10 p-8 md:p-12 flex flex-col md:flex-row items-center gap-8">
                <div class="flex-1 text-white">
                    <div class="inline-flex items-center gap-2 bg-mja-yellow/20 border border-mja-yellow/40 text-mja-yellow text-xs font-display font-bold px-3 py-1 rounded-full mb-4">
                        <i class="fas fa-heartbeat"></i> PROGRAMME PHARE
                    </div>
                    <h2 class="font-display font-black text-2xl sm:text-3xl mb-3">
                        Santé · Nutrition · Sport
                    </h2>
                    <p class="text-gray-200 leading-relaxed mb-5 max-w-lg">
                        Notre programme SNS accompagne les jeunes vers un mode de vie sain : alimentation équilibrée, activité physique, prévention santé. Des initiatives concrètes comme l'opération <strong class="text-mja-yellow">Ti Dèj</strong> et le <strong class="text-mja-yellow">Madin'Santé Challenge</strong>.
                    </p>
                    <a href="{{ route('sns') }}" class="btn-yellow font-display font-bold px-7 py-3 rounded-full inline-flex items-center gap-2 text-sm transition-colors">
                        Découvrir le programme SNS <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="shrink-0 grid grid-cols-3 gap-3">
                    <div class="w-20 h-20 bg-white/10 rounded-2xl flex flex-col items-center justify-center gap-1 text-white">
                        <i class="fas fa-seedling text-xl text-mja-yellow"></i>
                        <span class="text-xs font-display font-semibold">Ti Dèj</span>
                    </div>
                    @foreach([['fa-running','Sport'],['fa-shield-alt','Santé']] as [$icon,$label])
                    <div class="w-20 h-20 bg-white/10 rounded-2xl flex flex-col items-center justify-center gap-1 text-white">
                        <i class="fas {{ $icon }} text-xl text-mja-yellow"></i>
                        <span class="text-xs font-display font-semibold">{{ $label }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ═══ PROJETS ═══ -->
@if($projects->count())
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between mb-12">
            <div>
                <div class="inline-flex items-center gap-2 mb-3">
                    <span class="w-8 h-0.5 bg-mja-yellow"></span>
                    <span class="text-mja-yellow font-display font-bold text-sm uppercase tracking-widest">Ce que nous portons</span>
                </div>
                <h2 class="font-display font-black text-3xl sm:text-4xl text-mja-gray">Nos projets</h2>
            </div>
            <a href="{{ route('projects.index') }}" class="text-mja-blue hover:text-mja-bluedark font-display font-bold text-sm flex items-center gap-1 transition-colors">
                Tous <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($projects as $i => $project)
            @php $accents = ['card-accent-blue','card-accent-yellow','card-accent-red']; @endphp
            <a href="{{ route('projects.show', $project) }}" class="group bg-white rounded-2xl shadow-sm card-hover overflow-hidden border border-gray-100 {{ $accents[$i % 3] }}">
                @if($project->image)
                <div class="aspect-video bg-gray-100 overflow-hidden">
                    <img src="{{ asset("storage/{$project->image}") }}" alt="{{ $project->titre }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                </div>
                @else
                <div class="aspect-video bg-gradient-to-br from-mja-dark to-mja-blue/80 flex items-center justify-center">
                    <img src="/images/logo.jpg" alt="" class="h-16 w-16 object-contain opacity-30">
                </div>
                @endif
                <div class="p-6">
                    <span class="inline-block text-xs font-display font-bold px-3 py-1 rounded-full mb-3
                        {{ $project->statut === 'en_cours' ? 'bg-mja-blue/10 text-mja-blue' : ($project->statut === 'a_venir' ? 'bg-mja-yellow/10 text-yellow-700' : 'bg-gray-100 text-gray-500') }}">
                        {{ $project->statut === 'en_cours' ? '● En cours' : ($project->statut === 'a_venir' ? '○ À venir' : '✓ Terminé') }}
                    </span>
                    <h3 class="font-display font-bold text-lg text-mja-gray mb-2 group-hover:text-mja-blue transition-colors">{{ $project->titre }}</h3>
                    <p class="text-gray-500 text-sm leading-relaxed line-clamp-2">{{ $project->description_courte }}</p>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- ═══ PARTENAIRES ═══ -->
@if($partenaires->count())
<section class="py-14 bg-white border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <div class="inline-flex items-center gap-3 mb-3">
                <span class="w-10 h-0.5 bg-mja-yellow"></span>
                <span class="text-mja-yellow font-display font-bold text-xs uppercase tracking-widest">Ils nous font confiance</span>
                <span class="w-10 h-0.5 bg-mja-yellow"></span>
            </div>
            <h2 class="font-display font-black text-2xl sm:text-3xl text-mja-gray">Nos partenaires</h2>
        </div>
        <div class="flex flex-wrap justify-center items-center gap-5">
            @foreach($partenaires as $partenaire)
            @if($partenaire->url)
            <a href="{{ $partenaire->url }}" target="_blank" rel="noopener noreferrer"
               class="group flex items-center justify-center bg-gray-50 hover:bg-mja-blue/5 border border-gray-100 hover:border-mja-blue/25 hover:shadow-md rounded-2xl px-7 py-5 transition-all min-w-[130px] min-h-[80px]">
            @else
            <div class="group flex items-center justify-center bg-gray-50 border border-gray-100 rounded-2xl px-7 py-5 min-w-[130px] min-h-[80px]">
            @endif
                @if($partenaire->logo)
                <img src="{{ asset('storage/'.$partenaire->logo) }}" alt="{{ $partenaire->nom }}"
                     class="h-10 max-w-[120px] w-auto object-contain grayscale group-hover:grayscale-0 opacity-70 group-hover:opacity-100 transition-all"
                     loading="lazy" title="{{ $partenaire->nom }}">
                @else
                <span class="font-display font-black text-sm text-gray-400 group-hover:text-mja-blue transition-colors text-center leading-tight">
                    {{ $partenaire->nom }}
                </span>
                @endif
            @if($partenaire->url)
            </a>
            @else
            </div>
            @endif
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- ═══ ÉVÉNEMENTS ═══ -->
@if($events->count())
<section class="py-20 bg-mja-dark text-white ring-watermark">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between mb-12">
            <div>
                <div class="inline-flex items-center gap-2 mb-3">
                    <span class="w-8 h-0.5 bg-mja-red"></span>
                    <span class="text-mja-red font-display font-bold text-sm uppercase tracking-widest">Ne manquez pas</span>
                </div>
                <h2 class="font-display font-black text-3xl sm:text-4xl">Prochains événements</h2>
            </div>
            <a href="{{ route('events.index') }}" class="text-mja-yellow hover:text-yellow-400 font-display font-bold text-sm flex items-center gap-1 transition-colors">
                Tous <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        <div class="space-y-4">
            @foreach($events as $event)
            <a href="{{ route('events.show', $event) }}"
               class="bg-white/5 hover:bg-white/10 border border-white/10 rounded-2xl p-6 flex flex-col sm:flex-row gap-5 items-start transition-colors">
                <div class="bg-mja-blue text-white rounded-xl p-4 text-center min-w-[72px] shrink-0">
                    <div class="font-display font-black text-2xl leading-none">{{ $event->date_debut->format('d') }}</div>
                    <div class="text-xs uppercase mt-1 text-blue-200 font-display font-semibold">{{ $event->date_debut->locale('fr')->monthName }}</div>
                </div>
                <div class="flex-1">
                    <h3 class="font-display font-bold text-lg text-white mb-1">{{ $event->titre }}</h3>
                    <div class="flex flex-wrap gap-4 text-sm text-gray-300">
                        @if($event->lieu)<span><i class="fas fa-map-marker-alt text-mja-yellow mr-1"></i>{{ $event->lieu }}</span>@endif
                        <span><i class="fas fa-clock text-mja-yellow mr-1"></i>{{ $event->date_debut->format('H\hi') }}</span>
                        @if($event->gratuit)<span class="text-green-400 font-display font-semibold"><i class="fas fa-tag mr-1"></i>Gratuit</span>@endif
                    </div>
                </div>
                <div class="shrink-0 self-center">
                    <span class="w-10 h-10 bg-mja-yellow text-mja-dark rounded-full flex items-center justify-center font-bold">
                        <i class="fas fa-arrow-right text-sm"></i>
                    </span>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- ═══ ACTUALITÉS ═══ -->
@if($articles->count())
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between mb-12">
            <div>
                <div class="inline-flex items-center gap-2 mb-3">
                    <span class="w-8 h-0.5 bg-mja-blue"></span>
                    <span class="text-mja-blue font-display font-bold text-sm uppercase tracking-widest">Restez informés</span>
                </div>
                <h2 class="font-display font-black text-3xl sm:text-4xl text-mja-gray">Dernières actualités</h2>
            </div>
            <a href="{{ route('articles.index') }}" class="text-mja-blue hover:text-mja-bluedark font-display font-bold text-sm flex items-center gap-1 transition-colors">
                Toutes <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($articles as $i => $article)
            @php $accents = ['card-accent-blue','card-accent-yellow','card-accent-red']; @endphp
            <a href="{{ route('articles.show', $article) }}" class="group bg-white rounded-2xl shadow-sm card-hover overflow-hidden border border-gray-100 {{ $accents[$i % 3] }}">
                @if($article->image)
                <div class="aspect-video bg-gray-100 overflow-hidden">
                    <img src="{{ asset("storage/{$article->image}") }}" alt="{{ $article->titre }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                </div>
                @else
                <div class="aspect-video bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center">
                    <i class="fas fa-newspaper text-4xl text-gray-200"></i>
                </div>
                @endif
                <div class="p-6">
                    @if($article->publie_le)
                    <div class="text-xs text-gray-400 font-display font-semibold mb-2">
                        <i class="fas fa-calendar mr-1 text-mja-blue"></i>{{ $article->publie_le->locale('fr')->isoFormat('D MMMM Y') }}
                    </div>
                    @endif
                    <h3 class="font-display font-bold text-lg text-mja-gray mb-2 group-hover:text-mja-blue transition-colors line-clamp-2">{{ $article->titre }}</h3>
                    <p class="text-gray-500 text-sm leading-relaxed line-clamp-3">{{ $article->extrait }}</p>
                    <div class="mt-4 text-mja-blue text-sm font-display font-bold flex items-center gap-1">
                        Lire la suite <i class="fas fa-arrow-right text-xs"></i>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- ═══ RÉSEAU ULTRAMARIN ═══ -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-12 items-center">
            <!-- Logo + texte -->
            <div class="lg:w-96 shrink-0 text-center lg:text-left">
                <div class="inline-flex items-center gap-2 mb-4">
                    <span class="w-8 h-0.5 bg-mja-blue"></span>
                    <span class="text-mja-blue font-display font-bold text-sm uppercase tracking-widest">Au-delà de la Martinique</span>
                </div>
                <h2 class="font-display font-black text-3xl sm:text-4xl text-mja-gray mb-4">
                    Le réseau <br><span class="text-mja-yellow">Fwi Ti Dèj</span>
                </h2>
                <p class="text-gray-500 leading-relaxed mb-6">L'initiative de Madin'Jeunes Ambition a essaimé dans les Outre-mer francophones : chaque territoire s'est approprié la démarche <em>Ti Dèj</em> avec son propre nom créole.</p>
                <img src="/images/fwitidejllogo.png" alt="Fwi Ti Dèj" class="h-20 w-auto object-contain mx-auto lg:mx-0 mb-6">
                <a href="{{ route('sns') }}" class="btn-blue font-display font-bold px-7 py-3 rounded-full text-sm inline-flex items-center gap-2 transition-colors">
                    Découvrir le programme <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <!-- Cartes territoires -->
            <div class="flex-1 grid grid-cols-2 gap-4">
                @foreach([
                    ['Martinique','Madin\' Ti Dèj','Fort-de-France','fa-globe-americas','bg-mja-blue','text-white','Territoire fondateur — depuis 2016.'],
                    ['Guadeloupe','Karu\' Ti Dèj','Pointe-à-Pitre','fa-globe-americas','bg-mja-yellow','text-mja-dark','Karukéra — déclinaison guadeloupéenne.'],
                    ['Guyane','Guia\' Ti Dèj','Cayenne','fa-globe-americas','bg-mja-red','text-white','Déclinaison guyanaise du réseau.'],
                    ['France hex.','Réseau diaspora','Paris & métropole','fa-landmark','bg-mja-dark','text-white','Jeunes ultramarins engagés en métropole.'],
                ] as [$pays,$nom,$ville,$icon,$bg,$fg,$desc])
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 card-hover">
                    <div class="flex items-center gap-2 mb-3">
                        <i class="fas {{ $icon }} text-sm text-gray-400"></i>
                        <span class="text-xs font-display font-bold {{ $fg }} {{ $bg }} px-2.5 py-0.5 rounded-full">{{ $pays }}</span>
                    </div>
                    <div class="font-display font-black text-base text-mja-gray">{{ $nom }}</div>
                    <div class="text-xs text-gray-400 font-display font-semibold mb-2">
                        <i class="fas fa-map-marker-alt mr-1"></i>{{ $ville }}
                    </div>
                    <p class="text-gray-500 text-xs leading-relaxed">{{ $desc }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- ═══ CTA ═══ -->
<section class="py-20 bg-mja-dark relative overflow-hidden">
    <div class="absolute inset-0 opacity-10 pointer-events-none">
        <svg class="w-full h-full" viewBox="0 0 1000 300" fill="none">
            <circle cx="800" cy="150" r="250" stroke="#4A90E2" stroke-width="2"/>
            <circle cx="800" cy="150" r="190" stroke="#F5A623" stroke-width="2"/>
            <circle cx="800" cy="150" r="130" stroke="#D0021B" stroke-width="2"/>
            <circle cx="100" cy="150" r="180" stroke="#F5A623" stroke-width="1.5"/>
        </svg>
    </div>
    <div class="max-w-3xl mx-auto px-4 text-center relative z-10">
        <img src="/images/logomjat.png" alt="MJA" class="h-20 w-auto mx-auto mb-6 object-contain">
        <h2 class="font-display font-black text-3xl sm:text-4xl text-white mb-5">
            Rejoignez l'aventure
            <span class="text-mja-blue">M</span><span class="text-mja-yellow">J</span><span class="text-mja-red">A</span> !
        </h2>
        <p class="text-gray-300 text-lg mb-8 leading-relaxed">Bénévole, partenaire ou sympathisant — il y a une place pour vous dans notre association.</p>
        <a href="{{ route('contact') }}" class="btn-yellow font-display font-black px-10 py-4 rounded-full text-lg inline-block transition-colors">
            Nous contacter <i class="fas fa-arrow-right ml-2"></i>
        </a>
    </div>
</section>

@endsection
