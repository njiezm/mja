@extends('layouts.app')
@section('title', "Fwi Ti Dèj · Santé Nutrition Sport — Madin'Jeunes Ambition")

@section('content')

{{-- ═══════════════════════════════════════════════════════ HERO ══ --}}
<section class="relative text-white py-20 overflow-hidden"
    style="background: linear-gradient(135deg, #1a0600 0%, #3d1400 35%, #1a2744 75%, #0f1b33 100%);">

    {{-- Anneaux filigrane --}}
    <div class="absolute -right-16 top-1/2 -translate-y-1/2 w-96 h-96 opacity-[0.12] pointer-events-none">
        <svg viewBox="0 0 200 200" fill="none">
            <circle cx="100" cy="100" r="95" stroke="#F5820A" stroke-width="2"/>
            <circle cx="100" cy="100" r="72" stroke="#F5820A" stroke-width="1.5"/>
            <circle cx="100" cy="100" r="49" stroke="#F5820A" stroke-width="1"/>
        </svg>
    </div>
    <div class="absolute -left-20 bottom-0 w-72 h-72 opacity-[0.06] pointer-events-none">
        <svg viewBox="0 0 200 200" fill="none">
            <circle cx="100" cy="100" r="95" stroke="#F5820A" stroke-width="2"/>
            <circle cx="100" cy="100" r="65" stroke="#F5820A" stroke-width="1.5"/>
        </svg>
    </div>
    {{-- Lueur orange centre-gauche --}}
    <div class="absolute left-1/4 top-0 w-96 h-96 bg-orange-600/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-sm text-gray-400 mb-5 font-display font-semibold">
            <a href="{{ route('home') }}" class="hover:text-orange-400 transition-colors">Accueil</a>
            <span class="mx-2 text-gray-600">/</span>
            <span class="text-orange-400">Santé · Nutrition · Sport</span>
        </div>

        <div class="flex flex-col lg:flex-row items-center gap-12">
            <div class="flex-1">
                <div class="inline-flex items-center gap-2 bg-orange-500/20 border border-orange-500/40 text-orange-300 text-xs font-display font-bold px-4 py-1.5 rounded-full mb-5">
                    <i class="fas fa-heartbeat"></i> PROGRAMME SANTÉ · NUTRITION · SPORT
                </div>
                <h1 class="font-display font-black text-4xl sm:text-5xl lg:text-6xl mb-5 leading-tight">
                    <span class="text-mja-blue">Santé</span> ·
                    <span class="text-orange-400">Nutrition</span> ·
                    <span class="text-mja-red">Sport</span>
                </h1>
                <p class="text-gray-300 text-lg leading-relaxed mb-8 max-w-xl">
                    Depuis 2016, Madin'Jeunes Ambition sensibilise les jeunes aux bons comportements alimentaires et sportifs. Notre initiative phare&nbsp;: <strong class="text-orange-400">Fwi Ti Dèj</strong> — petits-déjeuners solidaires qui essaiment dans toute la francophonie ultramarine.
                </p>
                <div class="flex flex-wrap gap-8">
                    @foreach(['500+' => 'petits-dèj organisés', '1 000+' => 'jeunes sensibilisés', '3' => 'territoires couverts'] as $nb => $lib)
                    <div>
                        <div class="font-display font-black text-3xl text-orange-400">{{ $nb }}</div>
                        <div class="text-xs text-gray-400 font-display font-semibold uppercase tracking-wider mt-0.5">{{ $lib }}</div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="shrink-0 flex flex-col items-center gap-4">
                <div class="bg-white/5 backdrop-blur border border-orange-500/20 rounded-3xl p-8 flex flex-col items-center gap-4 shadow-2xl shadow-orange-900/20">
                    <img src="/images/fwitidejllogo.png" alt="Fwi Ti Dèj" class="h-32 w-auto object-contain drop-shadow-lg">
                    <div class="text-center">
                        <div class="font-display font-black text-2xl text-white">Fwi Ti Dèj</div>
                        <div class="text-orange-400/80 text-sm font-display font-semibold">"Le fruit du petit-déjeuner"</div>
                    </div>
                </div>
                <div class="text-center">
                    <img src="/images/logomjat.png" alt="MJA" class="h-10 w-auto object-contain mx-auto opacity-50">
                    <div class="text-xs text-gray-500 mt-1 font-display">une initiative de</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Bande orange signature --}}
<div class="h-1.5 bg-gradient-to-r from-orange-700 via-orange-400 to-orange-700"></div>

{{-- ═══════════════════════════════════════════════════ 3 PILIERS ══ --}}
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <div class="inline-flex items-center gap-2 mb-3">
                <span class="w-8 h-0.5 bg-orange-400"></span>
                <span class="text-orange-500 font-display font-bold text-sm uppercase tracking-widest">L'approche MJA</span>
                <span class="w-8 h-0.5 bg-orange-400"></span>
            </div>
            <h2 class="font-display font-black text-3xl sm:text-4xl text-mja-gray">Trois piliers, une vision</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach([
                ['Santé',     '#4A90E2', 'border-l-blue-500',   'bg-blue-500',   'text-blue-500',   'fa-heartbeat', 'Prévention, sensibilisation aux maladies chroniques (diabète, obésité), bilan de santé pour les jeunes. Partenariat avec les acteurs de santé martiniquais.'],
                ['Nutrition', '#F5820A', 'border-l-orange-500', 'bg-orange-500', 'text-orange-500', 'fa-apple-alt', 'Le programme Fwi Ti Dèj lutte contre le saut du petit-déjeuner. Ateliers cuisine, courses solidaires et éducation alimentaire adaptée aux réalités ultramarines.'],
                ['Sport',     '#D0021B', 'border-l-red-600',    'bg-red-600',    'text-red-600',    'fa-running',   'Sport Day MJA, courses solidaires, ateliers bien-être. Le sport comme vecteur de cohésion sociale et d\'épanouissement pour les jeunes des quartiers.'],
            ] as [$titre, $hex, $border, $bg, $fg, $icon, $desc])
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 border-l-4 {{ $border }} p-8 card-hover group">
                <div class="w-14 h-14 {{ $bg }} rounded-2xl flex items-center justify-center mb-5 shadow-sm group-hover:scale-110 transition-transform">
                    <i class="fas {{ $icon }} text-2xl text-white"></i>
                </div>
                <h3 class="font-display font-black text-2xl mb-3" style="color:{{ $hex }}">{{ $titre }}</h3>
                <p class="text-gray-500 leading-relaxed text-sm">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════ BANANE ══ --}}
<section class="py-20 bg-gradient-to-br from-yellow-50 via-amber-50 to-orange-50 overflow-hidden relative">
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -right-24 -top-24 w-96 h-96 rounded-full bg-yellow-200/40 blur-3xl"></div>
        <div class="absolute -left-24 -bottom-24 w-72 h-72 rounded-full bg-orange-200/30 blur-3xl"></div>
    </div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">

        {{-- Header --}}
        <div class="text-center mb-14">
            <span class="inline-block bg-yellow-400 text-yellow-900 text-xs font-display font-black tracking-widest uppercase px-4 py-1.5 rounded-full mb-4">La vedette de Fwi Ti Dèj</span>
            <h2 class="font-display font-black text-4xl sm:text-5xl text-mja-gray mb-4">
                La <span class="text-yellow-500">Banane</span> des Antilles
            </h2>
            <p class="text-gray-500 text-lg max-w-2xl mx-auto leading-relaxed">
                Fruit emblématique de la Martinique, la banane est le cœur de Fwi Ti Dèj —
                un concentré d'énergie naturelle idéal pour bien démarrer la journée.
            </p>
        </div>

        {{-- Image + valeurs nutritionnelles --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mb-14">

            {{-- Visuel banane --}}
            <div class="flex items-center justify-center">
                <div class="relative">
                    <div class="absolute inset-0 bg-yellow-300/50 blur-3xl rounded-full scale-125"></div>

                        {{-- Pour utiliser une image Freepik (banane sur fond transparent) :
                        → Remplacez le bloc emoji ci-dessous par : --}}
                        <img src="{{ asset('images/banane.png') }}" alt="Banane des Antilles"
                             class="relative w-82 h-82 object-contain drop-shadow-2xl">

                    {{-- <div class="relative text-center select-none" style="font-size:160px;line-height:1;filter:drop-shadow(0 20px 40px rgba(234,179,8,0.5))">
                        🍌
                    </div>
                    <div class="absolute -bottom-5 left-1/2 -translate-x-1/2 bg-yellow-500 text-yellow-900 font-display font-black text-sm px-5 py-2 rounded-full shadow-lg whitespace-nowrap">
                        🇲🇶 Banane Martinique
                    </div> --}}
                </div>
            </div>

            {{-- Tableau nutritionnel --}}
            <div>
                <h3 class="font-display font-black text-2xl text-mja-gray mb-6 flex items-center gap-2">
                    <span>⚡</span> Valeurs nutritionnelles
                    <span class="text-sm font-normal text-gray-400 ml-1">pour 100 g</span>
                </h3>
                <div class="grid grid-cols-2 gap-3 mb-8">
                    @foreach([
                        ['🔥','89 kcal','Énergie','bg-yellow-50'],
                        ['🌾','23 g','Glucides','bg-amber-50'],
                        ['⚡','358 mg','Potassium','bg-orange-50'],
                        ['🌿','2,6 g','Fibres','bg-green-50'],
                        ['🧬','Vit. B6','20 % des AJR','bg-blue-50'],
                        ['😊','Sérotonine','Régulateur d\'humeur','bg-purple-50'],
                    ] as [$ico, $val, $label, $bg])
                    <div class="rounded-2xl p-4 border border-yellow-100 flex items-center gap-3 {{ $bg }}">
                        <div class="w-11 h-11 bg-white rounded-xl flex items-center justify-center text-xl shrink-0 shadow-sm">{{ $ico }}</div>
                        <div>
                            <div class="font-display font-black text-base text-mja-gray leading-tight">{{ $val }}</div>
                            <div class="text-xs text-gray-400 font-display leading-tight">{{ $label }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="rounded-2xl p-5 text-yellow-900 font-display" style="background:linear-gradient(135deg,#fbbf24,#f97316)">
                    <h4 class="font-black text-base mb-2">💡 Pourquoi la banane au petit-déjeuner ?</h4>
                    <p class="text-sm leading-relaxed font-medium">
                        Ses sucres naturels (glucose, fructose, saccharose) libèrent de l'énergie
                        progressivement, sans pic glycémique. Elle cale, elle booste, elle se mange
                        sans préparation — parfaite avant le lycée !
                    </p>
                </div>
            </div>
        </div>

        {{-- Histoire + Le savais-tu --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-2 bg-white rounded-3xl p-7 shadow-sm border border-yellow-100">
                <h3 class="font-display font-black text-xl text-mja-gray mb-4">📜 La banane en Martinique : une histoire profonde</h3>
                <p class="text-gray-600 text-sm leading-relaxed mb-3">
                    Introduite aux Antilles au XVIII<sup>e</sup> siècle, la banane est aujourd'hui l'un des piliers
                    de l'agriculture martiniquaise. La Martinique exporte ses bananes vers la France métropolitaine
                    sous le label <strong>« Bananes des Antilles »</strong>, reconnues pour leur qualité issue
                    d'un terroir volcanique fertile.
                </p>
                <p class="text-gray-600 text-sm leading-relaxed mb-3">
                    On distingue deux grandes familles : la <strong>banane dessert</strong> (douce, crue) et la
                    <strong>banane ti-nain</strong> ou plantain (cuite, en légume). La ti-nain verte est un
                    ingrédient incontournable de la cuisine créole — bouillie, en alloko ou en gratin.
                </p>
                <p class="text-gray-600 text-sm leading-relaxed">
                    Chez <strong>Madin'Jeunes Ambition</strong>, la banane est le symbole de Fwi Ti Dèj : locale,
                    accessible à tous, nourrissante et naturelle. Un fruit qui incarne nos valeurs —
                    ancré dans notre territoire, bon pour la santé et porteur d'avenir pour notre jeunesse.
                </p>
            </div>

            <div class="bg-yellow-400 rounded-3xl p-7">
                <h3 class="font-display font-black text-xl text-yellow-900 mb-5">🌟 Le savais-tu ?</h3>
                <ul class="space-y-4">
                    @foreach([
                        'La Martinique produit +80 000 tonnes de bananes par an',
                        'La banane est techniquement une baie, pas un fruit charnu',
                        'Plus une banane est mûre, plus elle est facile à digérer',
                        'Elle contient du tryptophane, précurseur de la sérotonine',
                    ] as $fact)
                    <li class="flex gap-3">
                        <span class="text-yellow-700 font-black text-lg leading-none mt-0.5 shrink-0">→</span>
                        <p class="text-yellow-900 text-sm font-display font-semibold leading-snug">{{ $fact }}</p>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>

    </div>
</section>

{{-- ═══════════════════════════════════════════════════════ QUIZ ══ --}}
<section class="py-20 bg-orange-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <div class="inline-flex items-center gap-2 mb-3">
                <span class="w-8 h-0.5 bg-orange-400"></span>
                <span class="text-orange-500 font-display font-bold text-sm uppercase tracking-widest">Jeu interactif</span>
                <span class="w-8 h-0.5 bg-orange-400"></span>
            </div>
            <h2 class="font-display font-black text-3xl sm:text-4xl text-mja-gray mb-3">Teste tes connaissances !</h2>
            <p class="text-gray-500 max-w-xl mx-auto text-sm leading-relaxed">Santé, nutrition, sport… combien de points tu décroches ? Choisis un thème et lance-toi !</p>
        </div>

        {{-- Tabs --}}
        <div class="flex flex-wrap gap-2 justify-center mb-8" id="quiz-tabs">
            <button onclick="quizInit('nutrition')" data-tab="nutrition"
                class="quiz-tab active font-display font-bold text-sm px-5 py-2.5 rounded-full border-2 transition-all duration-200">
                🍌 Nutrition
            </button>
            <button onclick="quizInit('sport')" data-tab="sport"
                class="quiz-tab font-display font-bold text-sm px-5 py-2.5 rounded-full border-2 transition-all duration-200">
                🏃 Sport & Santé
            </button>
            <button onclick="quizInit('ftd')" data-tab="ftd"
                class="quiz-tab font-display font-bold text-sm px-5 py-2.5 rounded-full border-2 transition-all duration-200">
                🍊 Fwi Ti Dèj
            </button>
        </div>

        {{-- Carte quiz --}}
        <div class="bg-white rounded-3xl shadow-sm border border-orange-100 overflow-hidden min-h-[380px] flex flex-col">
            <div class="h-1.5 bg-gradient-to-r from-orange-700 via-orange-400 to-orange-700"></div>

            {{-- Écran départ --}}
            <div id="quiz-start" class="flex-1 flex flex-col items-center justify-center text-center p-10">
                <div id="qs-emoji" class="text-6xl mb-5 leading-none">🍌</div>
                <h3 id="qs-title" class="font-display font-black text-2xl text-mja-gray mb-2"></h3>
                <p id="qs-desc" class="text-gray-400 text-sm mb-2 max-w-sm leading-relaxed"></p>
                <p id="qs-count" class="text-orange-500 font-display font-bold text-sm mb-8"></p>
                <button onclick="quizStart()"
                    class="bg-orange-500 hover:bg-orange-600 text-white font-display font-bold px-8 py-3.5 rounded-2xl transition-colors flex items-center gap-2 shadow-md shadow-orange-200">
                    <i class="fas fa-play text-xs"></i> Commencer le quiz
                </button>
            </div>

            {{-- Écran question --}}
            <div id="quiz-question" class="hidden flex-1 flex flex-col p-6 sm:p-8">
                <div class="flex items-center gap-4 mb-5">
                    <div class="text-xs font-display font-bold text-gray-400">
                        Question <span id="q-num" class="text-orange-500">1</span>/<span id="q-total">5</span>
                    </div>
                    <div class="flex-1 bg-gray-100 rounded-full h-2">
                        <div id="q-progress" class="bg-orange-400 h-2 rounded-full transition-all duration-500" style="width:0%"></div>
                    </div>
                    <div id="q-score-live" class="text-xs font-display font-bold text-orange-500">Score&nbsp;: 0</div>
                </div>
                <h3 id="q-text" class="font-display font-bold text-mja-gray text-lg sm:text-xl mb-6 leading-snug"></h3>
                <div id="q-answers" class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-5"></div>
                <div id="q-explanation" class="hidden rounded-2xl px-5 py-3.5 text-sm font-display font-semibold leading-relaxed mb-5"></div>
                <button id="q-next" onclick="quizNext()"
                    class="hidden mt-auto self-end bg-orange-500 hover:bg-orange-600 text-white font-display font-bold px-6 py-3 rounded-2xl transition-colors flex items-center gap-2">
                    Suite <i class="fas fa-arrow-right text-xs"></i>
                </button>
            </div>

            {{-- Écran résultat --}}
            <div id="quiz-result" class="hidden flex-1 flex flex-col items-center justify-center text-center p-10">
                <div id="result-emoji" class="text-6xl mb-4 leading-none"></div>
                <div class="font-display font-black text-5xl text-orange-500 mb-1" id="result-score"></div>
                <div class="text-xs font-display text-gray-400 uppercase tracking-wider mb-3" id="result-label"></div>
                <p id="result-msg" class="text-gray-500 text-sm mb-8 max-w-xs leading-relaxed"></p>
                <div class="flex flex-wrap gap-3 justify-center">
                    <button onclick="quizRestart()"
                        class="bg-orange-500 hover:bg-orange-600 text-white font-display font-bold px-6 py-3 rounded-2xl transition-colors flex items-center gap-2">
                        <i class="fas fa-redo text-xs"></i> Recommencer
                    </button>
                    <button onclick="quizNextTab()"
                        class="bg-white border-2 border-orange-200 hover:border-orange-400 text-orange-500 font-display font-bold px-6 py-3 rounded-2xl transition-colors flex items-center gap-2">
                        Quiz suivant <i class="fas fa-arrow-right text-xs"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════ FWI TI DÈJ ══ --}}
<section class="py-20 ring-watermark" style="background: linear-gradient(135deg, #0f1b33 0%, #1a2744 60%, #2d1000 100%);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-14 items-center">
            <div class="lg:w-80 shrink-0 text-center">
                <img src="/images/fwitidejllogo.png" alt="Fwi Ti Dèj" class="h-28 w-auto object-contain mx-auto mb-4 drop-shadow-xl">
                <h2 class="font-display font-black text-3xl text-white mb-2">Fwi Ti Dèj</h2>
                <p class="text-orange-400/80 text-sm font-display font-semibold mb-4">"Fwi" = fruit en créole martiniquais</p>
                <p class="text-gray-300 text-sm leading-relaxed">
                    Née à Fort-de-France en 2016, Fwi Ti Dèj est un réseau de petits-déjeuners solidaires destinés aux lycéens et collégiens. Le concept&nbsp;: distribuer chaque matin un petit-déjeuner complet et équilibré, offert gratuitement, accompagné d'un message de prévention.
                </p>
            </div>
            <div class="flex-1">
                <div class="inline-flex items-center gap-2 mb-8">
                    <span class="w-8 h-0.5 bg-orange-400"></span>
                    <span class="text-orange-400 font-display font-bold text-sm uppercase tracking-widest">Un réseau en expansion</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    @foreach([
                        ['Martinique', 'Madin\' Ti Dèj', 'Fort-de-France', '🇫🇷', 'Territoire fondateur. Opérationnel dans plusieurs établissements de Fort-de-France depuis 2016.', 'border-orange-500/40', 'bg-orange-500/10', 'text-orange-400'],
                        ['Guadeloupe', 'Karu\' Ti Dèj', 'Pointe-à-Pitre',  '🇫🇷', 'La déclinaison guadeloupéenne, portée par les jeunes bénévoles de Guadeloupe.', 'border-mja-yellow/30', 'bg-mja-yellow/10', 'text-mja-yellow'],
                        ['Guyane',     'Guia\' Ti Dèj', 'Cayenne',         '🇫🇷', 'La déclinaison guyanaise, adaptée aux réalités nutritionnelles de la Guyane.',  'border-mja-red/30',    'bg-mja-red/10',    'text-mja-red'],
                    ] as [$pays, $nom, $ville, $flag, $desc, $cardBorder, $cardBg, $textColor])
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
                <div class="mt-6 bg-orange-500/10 border border-orange-500/30 rounded-2xl p-5">
                    <div class="flex items-start gap-4">
                        <i class="fas fa-scroll text-orange-400 text-xl mt-0.5 shrink-0"></i>
                        <div>
                            <div class="font-display font-bold text-white mb-1.5">Charte Fwi Ti Dèj</div>
                            <p class="text-gray-300 text-sm leading-relaxed">
                                Chaque antenne s'engage&nbsp;: gratuité totale pour les bénéficiaires, bénévolat exclusif, produits locaux privilégiés, message de sensibilisation nutritionnelle à chaque session, et ouverture à tous sans distinction.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════ ÉVÉNEMENTS ══ --}}
@if($prochains->count())
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-3">
                <span class="w-9 h-9 bg-orange-500 text-white rounded-xl flex items-center justify-center">
                    <i class="fas fa-calendar-alt text-sm"></i>
                </span>
                <h2 class="font-display font-black text-2xl text-mja-gray">Prochains rendez-vous SNS</h2>
            </div>
            <a href="{{ route('events.index') }}" class="text-orange-500 hover:text-orange-600 font-display font-bold text-sm flex items-center gap-1 transition-colors">
                Tous les événements <i class="fas fa-arrow-right text-xs"></i>
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($prochains as $i => $event)
            @php $colors = ['bg-orange-500', 'bg-mja-blue', 'bg-mja-red', 'bg-orange-700']; @endphp
            <a href="{{ route('events.show', $event) }}" class="flex items-center gap-4 bg-gray-50 hover:bg-orange-50 border border-gray-100 hover:border-orange-200 rounded-2xl p-4 card-hover transition-all">
                <div class="{{ $colors[$i % 4] }} text-white rounded-xl p-3 text-center min-w-[60px] shrink-0">
                    <div class="font-display font-black text-xl leading-none">{{ $event->date_debut->format('d') }}</div>
                    <div class="text-xs uppercase font-display font-semibold opacity-80">{{ $event->date_debut->locale('fr')->isoFormat('MMM') }}</div>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="font-display font-bold text-mja-gray text-sm leading-tight">{{ $event->titre }}</div>
                    @if($event->lieu)<div class="text-xs text-gray-400 mt-1"><i class="fas fa-map-marker-alt mr-1 text-orange-400"></i>{{ $event->lieu }}</div>@endif
                </div>
                <i class="fas fa-arrow-right text-gray-300 text-xs shrink-0"></i>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ═══════════════════════════════════════════════════════ CTA ══ --}}
<section class="py-20 relative overflow-hidden" style="background: linear-gradient(135deg, #1a0600 0%, #3d1400 50%, #1a2744 100%);">
    <div class="absolute inset-0 opacity-[0.08] pointer-events-none">
        <svg viewBox="0 0 400 200" fill="none" class="w-full h-full"><circle cx="200" cy="100" r="190" stroke="#F5820A" stroke-width="2"/><circle cx="200" cy="100" r="140" stroke="#F5820A" stroke-width="1.5"/><circle cx="200" cy="100" r="90" stroke="#F5820A" stroke-width="1"/></svg>
    </div>
    <div class="max-w-3xl mx-auto px-4 text-center relative z-10">
        <img src="/images/fwitidejllogo.png" alt="Fwi Ti Dèj" class="h-24 w-auto mx-auto mb-6 object-contain drop-shadow-xl">
        <h2 class="font-display font-black text-3xl sm:text-4xl text-white mb-4">
            Rejoindre le réseau <span class="text-orange-400">Fwi Ti Dèj</span>
        </h2>
        <p class="text-gray-300 leading-relaxed mb-8">Tu veux monter une antenne dans ton territoire ou contribuer bénévolement ? Contacte-nous.</p>
        <a href="{{ route('contact') }}"
           class="inline-flex items-center gap-2 bg-orange-500 hover:bg-orange-400 text-white font-display font-black px-10 py-4 rounded-full text-lg transition-colors shadow-xl shadow-orange-900/30">
            <i class="fas fa-seedling"></i> Devenir bénévole
        </a>
    </div>
</section>

{{-- ════════════════════════════════════════ STYLES QUIZ ══ --}}
<style>
    .quiz-tab {
        border-color: #fed7aa; /* orange-200 */
        color: #9a3412; /* orange-800 */
        background: white;
    }
    .quiz-tab.active, .quiz-tab:hover {
        background: #f97316; /* orange-500 */
        border-color: #f97316;
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(249,115,22,.3);
    }
    .quiz-answer {
        display: flex;
        align-items: center;
        gap: 12px;
        width: 100%;
        text-align: left;
        padding: 12px 16px;
        border-radius: 14px;
        border: 2px solid #fed7aa;
        background: white;
        cursor: pointer;
        font-family: 'Montserrat', sans-serif;
        font-size: 13px;
        font-weight: 600;
        color: #333;
        transition: all .15s ease;
    }
    .quiz-answer:hover:not(:disabled) {
        border-color: #f97316;
        background: #fff7ed;
        transform: translateY(-1px);
    }
    .quiz-answer:disabled { cursor: default; }
    .quiz-answer .answer-letter {
        width: 28px; height: 28px;
        border-radius: 8px;
        background: #ffedd5;
        color: #c2410c;
        font-size: 11px;
        font-weight: 800;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .quiz-answer.correct {
        border-color: #22c55e;
        background: #f0fdf4;
    }
    .quiz-answer.correct .answer-letter {
        background: #22c55e; color: white;
    }
    .quiz-answer.wrong {
        border-color: #ef4444;
        background: #fef2f2;
    }
    .quiz-answer.wrong .answer-letter {
        background: #ef4444; color: white;
    }
    .quiz-answer.reveal-correct {
        border-color: #22c55e;
        background: #f0fdf4;
        opacity: .8;
    }
    .quiz-answer.reveal-correct .answer-letter {
        background: #22c55e; color: white;
    }
    #q-explanation.correct-expl {
        background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534;
    }
    #q-explanation.wrong-expl {
        background: #fef2f2; border: 1px solid #fecaca; color: #991b1b;
    }
</style>

{{-- ════════════════════════════════════════ JAVASCRIPT QUIZ ══ --}}
<script>
const QUIZZES = {
    nutrition: {
        label: "Nutrition & Alimentation",
        emoji: "🍌",
        desc: "Alimentation saine, fruits locaux, habitudes alimentaires… es-tu incollable ?",
        questions: [
            {
                q: "Combien de portions de fruits et légumes faut-il consommer par jour selon les recommandations ?",
                options: ["2 portions", "3 portions", "5 portions", "8 portions"],
                correct: 2,
                explanation: "5 fruits et légumes par jour - c'est la recommandation internationale du PNNS !"
            },
            {
                q: "Quel repas est le plus important de la journée pour les enfants et adolescents ?",
                options: ["Le déjeuner", "Le dîner", "Le petit-déjeuner", "Le goûter"],
                correct: 2,
                explanation: "Le petit-déjeuner - c'est la raison d'être de Fwi Ti Dèj ! Un élève qui le saute a du mal à se concentrer le matin."
            },
            {
                q: "Lequel de ces fruits locaux des Antilles est le plus riche en vitamine C ?",
                options: ["La mangue", "Le citron vert", "La goyave", "L'ananas"],
                correct: 2,
                explanation: "La goyave ! Elle contient 4 fois plus de vitamine C que l'orange. Un super-fruit antillais !"
            },
            {
                q: "L'eau de coco est réputée pour être une source naturelle de quoi ?",
                options: ["Protéines", "Fibres", "Électrolytes et potassium", "Vitamine D"],
                correct: 2,
                explanation: "L'eau de coco est riche en potassium et électrolytes - idéale pour la réhydratation après un effort sportif !"
            },
            {
                q: "Dans quel délai après le réveil est-il idéal de prendre le petit-déjeuner ?",
                options: ["Immédiatement", "Dans l'heure qui suit", "Après 2 heures", "Seulement si on a faim"],
                correct: 1,
                explanation: "Dans l'heure qui suit le réveil - cela relance le métabolisme et apporte l'énergie pour bien démarrer la journée !"
            },
        ]
    },

    sport: {
        label: "Sport & Santé",
        emoji: "🏃",
        desc: "Activité physique, récupération, bien-être… à toi de jouer !",
        questions: [
            {
                q: "Combien de minutes d'activité physique modérée l'OMS recommande-t-elle par semaine pour un adulte ?",
                options: ["60 minutes", "100 minutes", "150 minutes", "400 minutes"],
                correct: 2,
                explanation: "150 minutes par semaine - c'est la recommandation de l'OMS. Ou 75 min d'activité intense pour un résultat équivalent."
            },
            {
                q: "Avant un effort physique, que faut-il absolument faire ?",
                options: ["Manger un repas copieux", "S'étirer à froid", "S'échauffer progressivement", "Boire beaucoup d'eau"],
                correct: 2,
                explanation: "L'échauffement prépare les muscles, les articulations et le système cardiovasculaire - essentiel pour prévenir les blessures !"
            },
            {
                q: "Le sport pratiqué régulièrement aide-t-il à réduire le risque de diabète de type 2 ?",
                options: ["Non, aucun lien prouvé", "Oui, mais seulement chez les seniors", "Oui, il améliore la sensibilité à l'insuline", "Seulement le sport de haut niveau"],
                correct: 2,
                explanation: "Oui ! L'exercice régulier améliore la sensibilité à l'insuline et peut réduire jusqu'à 58 % le risque de diabète."
            },
            {
                q: "Après un effort intense, dans quelle fenêtre de temps faut-il idéalement se nourrir ?",
                options: ["Dans les 10 premières minutes", "Dans les 30 à 45 minutes", "Après 2 heures de repos", "Jamais dans les 3 premières heures"],
                correct: 1,
                explanation: "C'est la fenêtre métabolique : dans les 30 à 45 min après l'effort, le corps assimile mieux glucides et protéines pour la récupération !"
            },
            {
                q: "Quelle discipline est particulièrement recommandée pour les personnes en surpoids débutantes ?",
                options: ["La musculation lourde", "Le sprint", "La natation", "Le rugby"],
                correct: 2,
                explanation: "La natation ! Portée par l'eau, elle préserve les articulations. Elle fait travailler tout le corps avec un faible risque de blessure."
            },
        ]
    },

    ftd: {
        label: "Fwi Ti Dèj",
        emoji: "🍊",
        desc: "Tu connais bien l'initiative des petits-déjeuners solidaires de MJA ?",
        questions: [
            {
                q: "En quelle année Madin'Jeunes Ambition a-t-elle lancé Fwi Ti Dèj ?",
                options: ["2012", "2014", "2016", "2019"],
                correct: 2,
                explanation: "2016 - Fwi Ti Dèj est né à Fort-de-France en 2016 et n'a cessé de grandir depuis !"
            },
            {
                q: "Que signifie « Fwi » en créole martiniquais ?",
                options: ["Eau", "Soleil", "Fruit", "Jeune"],
                correct: 2,
                explanation: "« Fwi » = « fruit » en créole - parce qu'on mange des fruits au petit-déjeuner pour bien démarrer la journée !"
            },
            {
                q: "Dans combien de territoires ultramarins Fwi Ti Dèj est-il implanté ?",
                options: ["1", "2", "3", "5"],
                correct: 2,
                explanation: "3 territoires : Martinique (Madin' Ti Dèj), Guadeloupe (Karu' Ti Dèj) et Guyane (Guia' Ti Dèj) !"
            },
            {
                q: "Quel est le public cible principal de l'initiative Fwi Ti Dèj ?",
                options: ["Les personnes âgées", "Les sportifs de haut niveau", "Les lycéens et collégiens", "Les femmes enceintes"],
                correct: 2,
                explanation: "Les lycéens et collégiens - Fwi Ti Dèj lutte contre le saut du petit-déjeuner chez les jeunes scolaires."
            },
        ]
    }
};

const TABS_ORDER = ["nutrition", "sport", "ftd"];
let qState = { quiz: "nutrition", current: 0, score: 0, answered: false };

function quizInit(id) {
    qState = { quiz: id, current: 0, score: 0, answered: false };
    document.querySelectorAll(".quiz-tab").forEach(t => {
        t.classList.toggle("active", t.dataset.tab === id);
    });
    const q = QUIZZES[id];
    document.getElementById("qs-emoji").textContent = q.emoji;
    document.getElementById("qs-title").textContent = q.label;
    document.getElementById("qs-desc").textContent  = q.desc;
    document.getElementById("qs-count").textContent = q.questions.length + " questions";
    showScreen("start");
}

function quizStart() {
    showScreen("question");
    renderQuestion();
}

function renderQuestion() {
    const quiz     = QUIZZES[qState.quiz];
    const question = quiz.questions[qState.current];
    const total    = quiz.questions.length;
    qState.answered = false;

    const pct = (qState.current / total) * 100;
    document.getElementById("q-progress").style.width   = pct + "%";
    document.getElementById("q-num").textContent        = qState.current + 1;
    document.getElementById("q-total").textContent      = total;
    document.getElementById("q-score-live").textContent = "Score : " + qState.score;
    document.getElementById("q-text").textContent       = question.q;

    document.getElementById("q-answers").innerHTML = question.options.map(function(opt, i) {
        return '<button class="quiz-answer" onclick="quizAnswer(' + i + ')" data-idx="' + i + '">'
             + '<span class="answer-letter">' + ["A","B","C","D"][i] + '</span>'
             + '<span class="answer-text">' + opt + '</span>'
             + '</button>';
    }).join("");

    const expl = document.getElementById("q-explanation");
    expl.className = "hidden rounded-2xl px-5 py-3.5 text-sm font-display font-semibold leading-relaxed mb-5";
    document.getElementById("q-next").classList.add("hidden");
}

function quizAnswer(idx) {
    if (qState.answered) return;
    qState.answered = true;

    const question = QUIZZES[qState.quiz].questions[qState.current];
    const correct  = question.correct;
    const isOk     = idx === correct;

    if (isOk) qState.score++;

    document.querySelectorAll(".quiz-answer").forEach(function(btn, i) {
        btn.disabled = true;
        if (i === correct && !isOk) btn.classList.add("reveal-correct");
        else if (i === correct)     btn.classList.add("correct");
        else if (i === idx)         btn.classList.add("wrong");
    });

    const expl = document.getElementById("q-explanation");
    expl.classList.remove("hidden");
    expl.classList.add(isOk ? "correct-expl" : "wrong-expl");
    expl.textContent = (isOk ? "✅ " : "❌ ") + question.explanation;

    const nextBtn = document.getElementById("q-next");
    const isLast  = qState.current + 1 >= QUIZZES[qState.quiz].questions.length;
    nextBtn.classList.remove("hidden");
    if (isLast) {
        nextBtn.innerHTML = 'Voir les résultats <i class="fas fa-trophy text-xs ml-1"></i>';
    } else {
        nextBtn.innerHTML = 'Suite <i class="fas fa-arrow-right text-xs ml-1"></i>';
    }
}

function quizNext() {
    qState.current++;
    if (qState.current >= QUIZZES[qState.quiz].questions.length) {
        showResult();
    } else {
        renderQuestion();
    }
}

function showResult() {
    showScreen("result");
    const total = QUIZZES[qState.quiz].questions.length;
    const s     = qState.score;
    const pct   = s / total;

    var emoji, msg;
    if (pct === 1)        { emoji = "🏆"; msg = "Parfait ! Tu es un expert !"; }
    else if (pct >= 0.75) { emoji = "🌟"; msg = "Excellent ! Tu maîtrises très bien le sujet !"; }
    else if (pct >= 0.5)  { emoji = "👍"; msg = "Bien joué ! Tu as de bonnes bases."; }
    else if (pct >= 0.25) { emoji = "📚"; msg = "Pas mal ! Il y a encore des choses à découvrir."; }
    else                  { emoji = "💪"; msg = "Ne te décourage pas - l'essentiel est d'apprendre !"; }

    document.getElementById("result-emoji").textContent = emoji;
    document.getElementById("result-score").textContent = s + "/" + total;
    document.getElementById("result-label").textContent = s === total
        ? "Score parfait !"
        : s + " bonne" + (s > 1 ? "s" : "") + " réponse" + (s > 1 ? "s" : "");
    document.getElementById("result-msg").textContent   = msg;
    document.getElementById("q-progress").style.width  = "100%";
}

function quizRestart() {
    qState.current  = 0;
    qState.score    = 0;
    qState.answered = false;
    document.getElementById("q-progress").style.width = "0%";
    showScreen("question");
    renderQuestion();
}

function quizNextTab() {
    var current = TABS_ORDER.indexOf(qState.quiz);
    var next    = TABS_ORDER[(current + 1) % TABS_ORDER.length];
    quizInit(next);
}

function showScreen(name) {
    ["start", "question", "result"].forEach(function(s) {
        document.getElementById("quiz-" + s).classList.toggle("hidden", s !== name);
    });
}

document.addEventListener("DOMContentLoaded", function() {
    quizInit("nutrition");
});
</script>

@endsection
