@extends('layouts.app')
@section('title', "Fwi Ti Dèj · Santé Nutrition Sport — Madin'Jeunes Ambition")

@section('content')

<!-- ═══ HERO Fwi Ti Dèj ═══ -->
<section class="relative text-white py-20 overflow-hidden" style="background: linear-gradient(135deg, #0f1b33 0%, #1a2744 55%, #1e3a5f 100%);">
    <!-- Motif anneaux MJA en filigrane -->
    <div class="absolute -right-20 top-1/2 -translate-y-1/2 w-96 h-96 opacity-[0.07] pointer-events-none">
        <svg viewBox="0 0 200 200" fill="none"><circle cx="100" cy="100" r="95" stroke="#F5A623" stroke-width="2"/><circle cx="100" cy="100" r="72" stroke="#F5A623" stroke-width="1.5"/><circle cx="100" cy="100" r="49" stroke="#F5A623" stroke-width="1"/></svg>
    </div>
    <div class="absolute -left-20 bottom-0 w-72 h-72 opacity-[0.05] pointer-events-none">
        <svg viewBox="0 0 200 200" fill="none"><circle cx="100" cy="100" r="95" stroke="#4A90E2" stroke-width="2"/><circle cx="100" cy="100" r="65" stroke="#4A90E2" stroke-width="1.5"/></svg>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-sm text-gray-400 mb-5 font-display font-semibold">
            <a href="{{ route('home') }}" class="hover:text-mja-yellow">Accueil</a>
            <span class="mx-2 text-gray-600">/</span> Santé · Nutrition · Sport
        </div>

        <div class="flex flex-col lg:flex-row items-center gap-12">
            <!-- Texte -->
            <div class="flex-1">
                <div class="inline-flex items-center gap-2 bg-mja-yellow/15 border border-mja-yellow/30 text-mja-yellow text-xs font-display font-bold px-4 py-1.5 rounded-full mb-5">
                    <i class="fas fa-heartbeat"></i> PROGRAMME SANTÉ · NUTRITION · SPORT
                </div>
                <h1 class="font-display font-black text-4xl sm:text-5xl lg:text-6xl mb-5 leading-tight">
                    <span class="text-mja-blue">Santé</span> ·
                    <span class="text-mja-yellow">Nutrition</span> ·
                    <span class="text-mja-red">Sport</span>
                </h1>
                <p class="text-gray-300 text-lg leading-relaxed mb-8 max-w-xl">
                    Depuis 2016, Madin'Jeunes Ambition sensibilise les jeunes aux bons comportements alimentaires et sportifs. Notre initiative phare : <strong class="text-mja-yellow">Fwi Ti Dèj</strong> — petits-déjeuners solidaires qui essaiment dans toute la francophonie ultramarine.
                </p>
                <div class="flex flex-wrap gap-6">
                    @foreach(['500+' => 'petits-dèj organisés','1000+' => 'jeunes sensibilisés','3' => 'territoires couverts'] as $nb => $lib)
                    <div>
                        <div class="font-display font-black text-3xl text-mja-yellow">{{ $nb }}</div>
                        <div class="text-xs text-gray-400 font-display font-semibold uppercase tracking-wider">{{ $lib }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
            <!-- Logo Fwi Ti Dèj -->
            <div class="shrink-0 flex flex-col items-center gap-4">
                <div class="bg-white/5 backdrop-blur border border-white/10 rounded-3xl p-8 flex flex-col items-center gap-4">
                    <img src="/images/fwitidejllogo.png" alt="Fwi Ti Dèj" class="h-32 w-auto object-contain">
                    <div class="text-center">
                        <div class="font-display font-black text-2xl text-white">Fwi Ti Dèj</div>
                        <div class="text-mja-yellow/80 text-sm font-display font-semibold">"Le fruit du petit-déjeuner"</div>
                    </div>
                </div>
                <div class="text-center">
                    <img src="/images/logomjat.png" alt="MJA" class="h-10 w-auto object-contain mx-auto opacity-60">
                    <div class="text-xs text-gray-500 mt-1 font-display">une initiative de</div>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="flex h-1.5"><div class="flex-1 bg-mja-blue"></div><div class="flex-1 bg-mja-yellow"></div><div class="flex-1 bg-mja-red"></div></div>

<!-- ═══ LES 3 PILIERS ═══ -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <div class="inline-flex items-center gap-2 mb-3">
                <span class="w-8 h-0.5 bg-mja-blue"></span>
                <span class="text-mja-blue font-display font-bold text-sm uppercase tracking-widest">L'approche MJA</span>
                <span class="w-8 h-0.5 bg-mja-blue"></span>
            </div>
            <h2 class="font-display font-black text-3xl sm:text-4xl text-mja-gray">Trois piliers, une vision</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach([
                ['Santé','bg-mja-blue','text-mja-blue','border-mja-blue','fa-heartbeat','Prévention, sensibilisation aux maladies chroniques (diabète, obésité), bilan de santé pour les jeunes. Nous partenariat avec les acteurs de santé martiniquais.','#4A90E2'],
                ['Nutrition','bg-mja-yellow','text-mja-dark','border-mja-yellow','fa-apple-alt','Le programme Fwi Ti Dèj lutte contre le saut du petit-déjeuner. Des ateliers cuisine, des courses solidaires et une éducation alimentaire adaptée aux réalités ultramarines.','#F5A623'],
                ['Sport','bg-mja-red','text-white','border-mja-red','fa-running','Sport Day MJA, courses solidaires, ateliers bien-être. Le sport comme vecteur de cohésion sociale et d\'épanouissement pour les jeunes des quartiers.','#D0021B'],
            ] as [$titre,$bg,$fg,$border,$icon,$desc,$hex])
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 border-t-4 {{ $border }} p-8 card-hover">
                <div class="w-14 h-14 {{ $bg }} rounded-2xl flex items-center justify-center mb-5 shadow-sm">
                    <i class="fas {{ $icon }} text-2xl {{ $fg }}"></i>
                </div>
                <h3 class="font-display font-black text-2xl {{ $fg === 'text-mja-dark' ? 'text-mja-gray' : 'text-mja-gray' }} mb-3" style="color: {{ $hex }}">{{ $titre }}</h3>
                <p class="text-gray-500 leading-relaxed">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ═══ FWI TI DÈJ — LE RÉSEAU ═══ -->
<section class="py-20 bg-mja-dark text-white ring-watermark">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-14 items-center">
            <!-- Logo + texte -->
            <div class="lg:w-80 shrink-0 text-center">
                <img src="/images/fwitidejllogo.png" alt="Fwi Ti Dèj" class="h-28 w-auto object-contain mx-auto mb-4">
                <h2 class="font-display font-black text-3xl text-white mb-2">Fwi Ti Dèj</h2>
                <p class="text-mja-yellow/80 text-sm font-display font-semibold mb-4">"Fwi" = fruit en créole martiniquais</p>
                <p class="text-gray-300 text-sm leading-relaxed">
                    Née à Fort-de-France en 2016, Fwi Ti Dèj est un réseau de petits-déjeuners solidaires destinés aux lycéens et collégiens. Le concept : distribuer chaque matin un petit-déjeuner complet et équilibré, offert gratuitement aux élèves défavorisés, accompagné d'un message de prévention.
                </p>
            </div>

            <!-- Territoires -->
            <div class="flex-1">
                <div class="inline-flex items-center gap-2 mb-8">
                    <span class="w-8 h-0.5 bg-mja-yellow"></span>
                    <span class="text-mja-yellow font-display font-bold text-sm uppercase tracking-widest">Un réseau en expansion</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    @foreach([
                        ['Martinique','Madin\' Ti Dèj','Fort-de-France','🇫🇷','Territoire fondateur. Opérationnel dans plusieurs établissements de Fort-de-France depuis 2016.','bg-mja-blue/20','border-mja-blue/30','text-mja-blue'],
                        ['Guadeloupe','Karu\' Ti Dèj','Pointe-à-Pitre','🇫🇷','Karukéra — la déclinaison guadeloupéenne, portée par les jeunes bénévoles de Guadeloupe.','bg-mja-yellow/20','border-mja-yellow/30','text-mja-yellow'],
                        ['Guyane','Guia\' Ti Dèj','Cayenne','🇫🇷','La déclinaison guyanaise, adaptée aux réalités nutritionnelles de la Guyane.','bg-mja-red/20','border-mja-red/30','text-mja-red'],
                    ] as [$pays,$nom,$ville,$flag,$desc,$cardBg,$cardBorder,$textColor])
                    <div class="rounded-2xl border {{ $cardBorder }} {{ $cardBg }} p-5">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-xl">{{ $flag }}</span>
                            <span class="text-xs font-display font-bold text-gray-300 uppercase tracking-wide">{{ $pays }}</span>
                        </div>
                        <div class="font-display font-black text-lg {{ $textColor }} mb-0.5">{{ $nom }}</div>
                        <div class="text-xs text-gray-400 mb-3 font-display font-semibold">
                            <i class="fas fa-map-marker-alt mr-1 opacity-50"></i>{{ $ville }}
                        </div>
                        <p class="text-gray-300 text-xs leading-relaxed">{{ $desc }}</p>
                    </div>
                    @endforeach
                </div>
                <!-- Charte -->
                <div class="mt-6 bg-white/5 border border-white/10 rounded-2xl p-5">
                    <div class="flex items-start gap-4">
                        <i class="fas fa-scroll text-mja-yellow text-xl mt-0.5 shrink-0"></i>
                        <div>
                            <div class="font-display font-bold text-white mb-1.5">Charte Fwi Ti Dèj</div>
                            <p class="text-gray-300 text-sm leading-relaxed">
                                Chaque antenne s'engage à respecter un socle commun : gratuité totale pour les bénéficiaires, bénévolat exclusif, produits locaux privilégiés, message de sensibilisation nutritionnelle à chaque session, et ouverture à tous sans distinction.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ═══ ÉVÉNEMENTS SNS ═══ -->
@if($prochains->count())
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-3">
                <span class="w-9 h-9 bg-mja-red text-white rounded-xl flex items-center justify-center">
                    <i class="fas fa-calendar-alt text-sm"></i>
                </span>
                <h2 class="font-display font-black text-2xl text-mja-gray">Prochains rendez-vous SNS</h2>
            </div>
            <a href="{{ route('events.index') }}" class="text-mja-blue hover:text-mja-bluedark font-display font-bold text-sm flex items-center gap-1">
                Tous les événements <i class="fas fa-arrow-right text-xs"></i>
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($prochains as $i => $event)
            @php $colors = [['bg-mja-blue','text-white'],['bg-mja-yellow','text-mja-dark'],['bg-mja-red','text-white'],['bg-mja-dark','text-white']]; $c = $colors[$i % 4]; @endphp
            <a href="{{ route('events.show', $event) }}" class="flex items-center gap-4 bg-gray-50 hover:bg-white border border-gray-100 rounded-2xl p-4 card-hover transition-colors">
                <div class="{{ $c[0] }} {{ $c[1] }} rounded-xl p-3 text-center min-w-[60px] shrink-0">
                    <div class="font-display font-black text-xl leading-none">{{ $event->date_debut->format('d') }}</div>
                    <div class="text-xs uppercase font-display font-semibold opacity-80">{{ $event->date_debut->locale('fr')->isoFormat('MMM') }}</div>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="font-display font-bold text-mja-gray text-sm leading-tight">{{ $event->titre }}</div>
                    @if($event->lieu)<div class="text-xs text-gray-400 mt-1"><i class="fas fa-map-marker-alt mr-1"></i>{{ $event->lieu }}</div>@endif
                </div>
                <i class="fas fa-arrow-right text-gray-300 text-xs shrink-0"></i>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- ═══ CTA ═══ -->
<section class="py-16 bg-gray-50">
    <div class="max-w-3xl mx-auto px-4 text-center">
        <img src="/images/fwitidejllogo.png" alt="Fwi Ti Dèj" class="h-20 w-auto mx-auto mb-5 object-contain">
        <h2 class="font-display font-black text-3xl text-mja-gray mb-4">
            Rejoindre le réseau <span class="text-mja-yellow">Fwi Ti Dèj</span>
        </h2>
        <p class="text-gray-500 leading-relaxed mb-8">Vous souhaitez monter une antenne dans votre territoire ou contribuer bénévolement ? Contactez-nous.</p>
        <a href="{{ route('contact') }}" class="btn-yellow font-display font-black px-10 py-4 rounded-full text-lg inline-block transition-colors">
            <i class="fas fa-seedling mr-2"></i> Devenir bénévole
        </a>
    </div>
</section>

@endsection
