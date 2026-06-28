<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- ── Titre dynamique ──────────────────────────────────────── --}}
    <title>@yield('title', "Madin'Jeunes Ambition")</title>

    {{-- ── Favicon ──────────────────────────────────────────────── --}}
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logomjat.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logomjat.png') }}">

    {{-- ── SEO ─────────────────────────────────────────────────── --}}
    <meta name="description" content="@yield('meta_description', "Madin'Jeunes Ambition — Association de jeunes bénévoles à Fort-de-France, Martinique. Initiative Fwi Ti Dèj pour le petit-déjeuner scolaire.")">
    <meta name="keywords" content="MJA, Madin'Jeunes Ambition, Martinique, jeunesse, bénévolat, Fwi Ti Dèj, Fort-de-France, santé, nutrition, sport">
    <meta name="author" content="Madin'Jeunes Ambition">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- ── OpenGraph ────────────────────────────────────────────── --}}
    <meta property="og:type"        content="@yield('og_type', 'website')">
    <meta property="og:site_name"   content="Madin'Jeunes Ambition">
    <meta property="og:title"       content="@yield('title', "Madin'Jeunes Ambition")">
    <meta property="og:description" content="@yield('meta_description', "Madin'Jeunes Ambition — Association de jeunes bénévoles à Fort-de-France, Martinique.")">
    <meta property="og:image"       content="@yield('og_image', asset('images/logomjat.png'))">
    <meta property="og:image:width"  content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:url"         content="{{ url()->current() }}">
    <meta property="og:locale"      content="fr_FR">

    {{-- ── Twitter Card ─────────────────────────────────────────── --}}
    <meta name="twitter:card"        content="@yield('twitter_card', 'summary_large_image')">
    <meta name="twitter:title"       content="@yield('title', "Madin'Jeunes Ambition")">
    <meta name="twitter:description" content="@yield('meta_description', "Association de jeunes bénévoles à Fort-de-France, Martinique.")">
    <meta name="twitter:image"       content="@yield('og_image', asset('images/logomjat.png'))">

    {{-- ── Tailwind CSS (local → CDN fallback) ────────────────── --}}
    <script src="{{ asset('vendor/tailwind/tailwind.js') }}"
            onerror="this.onerror=null;var s=document.createElement('script');s.src='https://cdn.tailwindcss.com';document.head.appendChild(s)"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        mja: {
                            blue:   '#3DAEF5',
                            bluedark: '#1E93D6',
                            yellow: '#F5A623',
                            red:    '#D0021B',
                            dark:   '#2048A4',
                            navy:   '#1A3D8A',
                            light:  '#EBF4FF',
                            gray:   '#333333',
                        }
                    },
                    fontFamily: {
                        display: ['Montserrat', 'sans-serif'],
                        sans:    ['Open Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    {{-- ── Polices Google Fonts (local → CDN fallback) ──────────── --}}
    <link rel="stylesheet" href="{{ asset('fonts/fonts.css') }}"
          onerror="this.onerror=null;var l=document.createElement('link');l.rel='stylesheet';l.href='https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800;900&family=Open+Sans:wght@400;500;600&display=swap';document.head.appendChild(l)">

    {{-- ── Font Awesome (local → CDN fallback) ────────────────── --}}
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome/css/all.min.css') }}"
          onerror="this.onerror=null;var l=document.createElement('link');l.rel='stylesheet';l.href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css';document.head.appendChild(l)">

    <style>
        html, body { margin: 0; padding: 0; }
        body { font-family: 'Open Sans', sans-serif; color: #333333; }
        h1,h2,h3,h4,h5,h6,.font-display { font-family: 'Montserrat', sans-serif; }

        .hero-gradient {
            background: linear-gradient(135deg, #1A3D8A 0%, #2048A4 45%, #3262CC 100%);
        }
        .card-hover { transition: transform .2s ease, box-shadow .2s ease; }
        .card-hover:hover { transform: translateY(-4px); box-shadow: 0 16px 40px rgba(74,144,226,.15); }

        /* Accent bar top of cards */
        .card-accent-blue  { border-top: 3px solid #3DAEF5; }
        .card-accent-yellow{ border-top: 3px solid #F5A623; }
        .card-accent-red   { border-top: 3px solid #D0021B; }

        /* Ring motif watermark - uses pseudo-element to avoid overriding background-image */
        .ring-watermark { position: relative; overflow: hidden; }
        .ring-watermark::after {
            content: '';
            position: absolute;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 200 200'%3E%3Ccircle cx='100' cy='100' r='90' fill='none' stroke='%233DAEF5' stroke-width='1' opacity='0.12'/%3E%3Ccircle cx='100' cy='100' r='70' fill='none' stroke='%23F5A623' stroke-width='1' opacity='0.09'/%3E%3Ccircle cx='100' cy='100' r='50' fill='none' stroke='%23D0021B' stroke-width='1' opacity='0.07'/%3E%3C/svg%3E");
            background-size: 280px;
            pointer-events: none;
            z-index: 0;
        }
        .ring-watermark > * { position: relative; z-index: 1; }

        .nav-link { transition: color .15s; }
        .nav-link:hover { color: #F5A623; }
        .nav-link.active { color: #F5A623; }
        .btn-blue { background: #3DAEF5; color: white; }
        .btn-blue:hover { background: #1E93D6; }
        .btn-yellow { background: #F5A623; color: #2048A4; }
        .btn-yellow:hover { background: #e0941a; }
    </style>

    @stack('head')
</head>
<body class="bg-white">

    <!-- Navbar + bandeau saisonnier dans un seul bloc sticky -->
    <div class="sticky top-0 z-50 shadow-lg">
    <nav class="bg-mja-dark">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">

                <!-- Logo (saisonnier : dépose le fichier dans public/images/logos/ pour l'activer) -->
                @php
                $mois = (int) date('n');
                $logoSaisonnier = match(true) {
                    $mois === 12                   => '/images/logos/noel.png',
                    $mois === 1                    => '/images/logos/nouvel-an.png',
                    $mois === 6                    => '/images/logos/bac-ti-dej.png',
                    in_array($mois, [7, 8])        => '/images/logos/ete.png',
                    $mois === 9                    => '/images/logos/rentree.png',
                    default                        => null,
                };
                $logoNav = ($logoSaisonnier && file_exists(public_path(ltrim($logoSaisonnier,'/'))))
                    ? $logoSaisonnier
                    : '/images/logomjat.png';
                @endphp
                <a href="{{ route('home') }}" class="flex items-center gap-3 shrink-0">
                    <img src="{{ $logoNav }}" alt="MJA Logo"
                         class="h-10 w-auto object-contain">
                    <div class="hidden sm:block leading-tight">
                        <div class="font-display font-black text-sm tracking-tight">
                            <span class="text-mja-blue">M</span><span class="text-mja-yellow">J</span><span class="text-mja-red">A</span>
                        </div>
                        <div class="text-gray-300 text-xs font-display font-semibold tracking-widest uppercase" style="font-size:9px;">Madin' Jeunes Ambition</div>
                    </div>
                </a>

                <!-- Desktop nav -->
                <div class="hidden md:flex items-center gap-1">
                    @foreach([
                        ['Accueil', 'home', ''],
                        ['À propos', 'about', ''],
                        ['Projets', 'projects.index', 'projects.*'],
                        ['SNS', 'sns', 'sns'],
                        ['Événements', 'events.index', 'events.*'],
                        ['Actualités', 'articles.index', 'articles.*'],
                        ['Ressources', 'resources.index', 'resources.*'],
                    ] as [$label, $route, $pattern])
                    <a href="{{ route($route) }}"
                       class="nav-link px-3 py-2 rounded-lg text-sm font-semibold font-display transition-colors
                              {{ request()->routeIs($pattern ?: $route) ? 'text-mja-yellow' : 'text-gray-300' }}">
                        {{ $label }}
                    </a>
                    @endforeach
                    <a href="{{ route('adhesion') }}"
                       class="ml-1 bg-mja-yellow hover:bg-yellow-400 text-mja-dark font-display font-bold text-sm px-4 py-2 rounded-full transition-colors {{ request()->routeIs('adhesion') ? 'ring-2 ring-mja-yellow ring-offset-2 ring-offset-mja-dark' : '' }}">
                        Adhérer
                    </a>
                    <a href="{{ route('contact') }}"
                       class="ml-1 btn-blue font-display font-bold text-sm px-4 py-2 rounded-full transition-colors {{ request()->routeIs('contact') ? 'ring-2 ring-mja-blue ring-offset-2 ring-offset-mja-dark' : '' }}">
                        Contact
                    </a>
                </div>

                <!-- Mobile toggle -->
                <button id="menu-btn" class="md:hidden text-gray-300 hover:text-white p-2">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>

            <!-- Mobile menu -->
            <div id="mobile-menu" class="hidden md:hidden pb-4 border-t border-mja-blue/20 mt-1 pt-3 space-y-1">
                @foreach([
                    ['Accueil', 'home'], ['À propos', 'about'], ['Projets', 'projects.index'],
                    ['SNS', 'sns'], ['Événements', 'events.index'], ['Actualités', 'articles.index'],
                    ['Ressources', 'resources.index'], ['Contact', 'contact'], ['Adhérer', 'adhesion'],
                ] as [$label, $route])
                <a href="{{ route($route) }}" class="block px-3 py-2 text-gray-300 hover:text-mja-yellow text-sm font-semibold font-display transition-colors">{{ $label }}</a>
                @endforeach
            </div>
        </div>
    </nav>@include('partials.seasonal-banner')</div>{{-- /sticky --}}

    <!-- Flash -->
    @if(is_string(session('success')))
    <div class="bg-green-50 border-l-4 border-green-500 text-green-800 px-6 py-3 text-sm flex items-center gap-2 font-display font-semibold">
        <i class="fas fa-check-circle text-green-500"></i> {{ session('success') }}
    </div>
    @endif
    @if(is_string(session('error')))
    <div class="bg-red-50 border-l-4 border-mja-red text-red-800 px-6 py-3 text-sm flex items-center gap-2 font-display font-semibold">
        <i class="fas fa-exclamation-circle text-mja-red"></i> {{ session('error') }}
    </div>
    @endif

    <main>@yield('content')</main>

    <!-- Footer -->
    <footer class="bg-mja-navy text-gray-300 pt-16 pb-6 mt-20 ring-watermark">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10 mb-12">

                <!-- Brand -->
                <div class="md:col-span-2">
                    <div class="flex items-center gap-3 mb-5">
                        <img src="{{ $logoNav ?? '/images/logomjat.png' }}" alt="MJA" class="h-14 w-auto object-contain">
                        <div>
                            <div class="font-display font-black text-2xl leading-none">
                                <span class="text-mja-blue">M</span><span class="text-mja-yellow">J</span><span class="text-mja-red">A</span>
                            </div>
                            <div class="text-gray-400 text-xs font-display tracking-widest uppercase mt-0.5">Madin' Jeunes Ambition</div>
                        </div>
                    </div>
                    <p class="text-sm leading-relaxed text-gray-400 max-w-sm">
                        Créée en 2011 à Fort-de-France, Martinique. Nous rassemblons les jeunes autour d'actions éducatives, culturelles, sociales, sportives et de santé.
                    </p>
                    <div class="flex flex-wrap gap-3 mt-5">
                        <a href="https://www.facebook.com/MadinJeunesAmbition/" target="_blank" title="Facebook"
                           class="w-9 h-9 bg-white/5 hover:bg-[#1877F2] rounded-xl flex items-center justify-center transition-colors text-sm">
                            <i class="fab fa-facebook"></i>
                        </a>
                        <a href="https://www.instagram.com/madin_jeunes_ambition/" target="_blank" title="Instagram"
                           class="w-9 h-9 bg-white/5 hover:bg-[#E1306C] rounded-xl flex items-center justify-center transition-colors text-sm">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="https://www.tiktok.com/@fwi_ti_dej" target="_blank" title="TikTok"
                           class="w-9 h-9 bg-white/5 hover:bg-[#010101] rounded-xl flex items-center justify-center transition-colors text-sm">
                            <i class="fab fa-tiktok"></i>
                        </a>
                        <a href="https://www.snapchat.com/" target="_blank" title="Snapchat"
                           class="w-9 h-9 bg-white/5 hover:bg-[#FFFC00] hover:text-black rounded-xl flex items-center justify-center transition-colors text-sm">
                            <i class="fab fa-snapchat"></i>
                        </a>
                        <a href="https://www.youtube.com/channel/UCX6nyVEv_QyFuLREyVvOMLw" target="_blank" title="YouTube"
                           class="w-9 h-9 bg-white/5 hover:bg-[#FF0000] rounded-xl flex items-center justify-center transition-colors text-sm">
                            <i class="fab fa-youtube"></i>
                        </a>
                        <a href="mailto:contact@mja-martinique.com" title="Email"
                           class="w-9 h-9 bg-white/5 hover:bg-mja-blue rounded-xl flex items-center justify-center transition-colors text-sm">
                            <i class="fas fa-envelope"></i>
                        </a>
                    </div>
                </div>

                <!-- Nav -->
                <div>
                    <h4 class="font-display font-bold text-white mb-4 text-sm uppercase tracking-wider">Navigation</h4>
                    <ul class="space-y-2 text-sm">
                        @foreach([['À propos','about'],['Nos projets','projects.index'],['SNS','sns'],['Événements','events.index'],['Actualités','articles.index'],['Ressources','resources.index']] as [$l,$r])
                        <li><a href="{{ route($r) }}" class="hover:text-mja-yellow transition-colors">{{ $l }}</a></li>
                        @endforeach
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h4 class="font-display font-bold text-white mb-4 text-sm uppercase tracking-wider">Contact</h4>
                    <ul class="space-y-3 text-sm">
                        <li class="flex gap-2 items-start">
                            <i class="fas fa-map-marker-alt text-mja-yellow mt-0.5 w-4 shrink-0"></i>
                            <span>22, passage du Cœur sur la Main<br>97200 Fort-de-France</span>
                        </li>
                        <li class="flex gap-2 items-center">
                            <i class="fas fa-envelope text-mja-yellow w-4 shrink-0"></i>
                            <a href="{{ route('contact') }}" class="hover:text-mja-yellow transition-colors">Nous contacter</a>
                        </li>
                        <li class="flex gap-2 items-center">
                            <i class="fas fa-user-plus text-mja-yellow w-4 shrink-0"></i>
                            <a href="{{ route('adhesion') }}" class="hover:text-mja-yellow transition-colors">Adhérer à MJA</a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Three-color accent bar -->
            <div class="flex h-0.5 mb-6 rounded-full overflow-hidden">
                <div class="flex-1 bg-mja-blue"></div>
                <div class="flex-1 bg-mja-yellow"></div>
                <div class="flex-1 bg-mja-red"></div>
            </div>

            <div class="flex flex-col sm:flex-row justify-between items-center gap-2 text-xs text-gray-500">
                <span>&copy; {{ date('Y') }} Madin'Jeunes Ambition — Tous droits réservés</span>
                @auth
                <a href="{{ route('admin.dashboard') }}" class="hover:text-mja-yellow transition-colors font-semibold">
                    <i class="fas fa-cog mr-1"></i>Administration
                </a>
                @else
                <a href="{{ route('login') }}" class="hover:text-mja-yellow transition-colors">
                    <i class="fas fa-lock mr-1"></i>Administration
                </a>
                @endauth
            </div>
        </div>
    </footer>

    <script>
        document.getElementById('menu-btn').addEventListener('click', () => {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });
    </script>
    @stack('scripts')
</body>
</html>
