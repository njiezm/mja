<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Administration') — MJA</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        mja: {
                            blue:     '#4A90E2',
                            bluedark: '#2d6dbf',
                            yellow:   '#F5A623',
                            red:      '#D0021B',
                            dark:     '#1a2744',
                            navy:     '#0f1b33',
                            light:    '#EBF4FF',
                            gray:     '#333333',
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
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&family=Open+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body { font-family: 'Open Sans', sans-serif; }
        h1,h2,h3,h4,.font-display { font-family: 'Montserrat', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex">

    <!-- Sidebar -->
    <aside id="sidebar" class="w-64 bg-mja-navy min-h-screen flex flex-col fixed left-0 top-0 z-40 hidden lg:flex">
        <!-- Logo -->
        <div class="p-5 border-b border-white/10">
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <img src="/images/logo.jpg" alt="MJA" class="h-10 w-10 object-contain bg-white rounded-lg p-0.5 shadow">
                <div>
                    <div class="font-display font-black text-lg leading-none">
                        <span class="text-mja-blue">M</span><span class="text-mja-yellow">J</span><span class="text-mja-red">A</span>
                    </div>
                    <div class="text-gray-400 text-xs font-display tracking-wider mt-0.5" style="font-size:9px;">ADMINISTRATION</div>
                </div>
            </a>
        </div>

        <!-- Three-color bar -->
        <div class="flex h-0.5">
            <div class="flex-1 bg-mja-blue"></div>
            <div class="flex-1 bg-mja-yellow"></div>
            <div class="flex-1 bg-mja-red"></div>
        </div>

        <nav class="flex-1 p-4 space-y-0.5 overflow-y-auto">
            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-display font-semibold transition-colors
                      {{ request()->routeIs('admin.dashboard') ? 'bg-mja-blue text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                <i class="fas fa-tachometer-alt w-4 text-center"></i> Tableau de bord
            </a>

            <div class="pt-4 pb-1 px-3 text-xs text-gray-500 uppercase tracking-wider font-display font-bold">Contenu</div>

            @php
            $navItems = [
                ['label'=>'Actualités', 'icon'=>'fa-newspaper', 'route'=>'admin.articles', 'count'=>\App\Models\Article::count(), 'color'=>'text-mja-blue'],
                ['label'=>'Projets',    'icon'=>'fa-project-diagram','route'=>'admin.projects','count'=>\App\Models\Project::count(), 'color'=>'text-mja-yellow'],
                ['label'=>'Événements','icon'=>'fa-calendar-alt','route'=>'admin.events',  'count'=>\App\Models\Event::count(),   'color'=>'text-mja-red'],
                ['label'=>'Ressources','icon'=>'fa-folder-open','route'=>'admin.resources','count'=>\App\Models\Resource::count(),'color'=>'text-mja-blue'],
            ];
            @endphp
            @foreach($navItems as $item)
            <a href="{{ route($item['route'].'.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-display font-semibold transition-colors
                      {{ request()->routeIs($item['route'].'.*') ? 'bg-mja-blue text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                <i class="fas {{ $item['icon'] }} w-4 text-center {{ request()->routeIs($item['route'].'.*') ? '' : $item['color'] }}"></i>
                {{ $item['label'] }}
                <span class="ml-auto bg-white/10 text-xs px-2 py-0.5 rounded-full">{{ $item['count'] }}</span>
            </a>
            @endforeach

            <div class="pt-4 pb-1 px-3 text-xs text-gray-500 uppercase tracking-wider font-display font-bold">Association</div>

            <a href="{{ route('admin.team.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-display font-semibold transition-colors
                      {{ request()->routeIs('admin.team.*') ? 'bg-mja-blue text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                <i class="fas fa-users w-4 text-center text-mja-yellow"></i> Équipe
            </a>

            @php $nonLus = \App\Models\Contact::where('lu', false)->count(); @endphp
            <a href="{{ route('admin.contacts.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-display font-semibold transition-colors
                      {{ request()->routeIs('admin.contacts.*') ? 'bg-mja-blue text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                <i class="fas fa-envelope w-4 text-center text-mja-red"></i> Messages
                @if($nonLus > 0)
                <span class="ml-auto bg-mja-red text-white text-xs px-2 py-0.5 rounded-full">{{ $nonLus }}</span>
                @endif
            </a>
        </nav>

        <!-- User / logout -->
        <div class="p-4 border-t border-white/10">
            <div class="text-xs text-gray-400 font-display font-semibold mb-2 px-1">{{ auth()->user()->name }}</div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="flex items-center gap-3 w-full px-3 py-2.5 rounded-xl text-sm font-display font-semibold text-red-400 hover:bg-red-900/20 hover:text-red-300 transition-colors">
                    <i class="fas fa-sign-out-alt w-4 text-center"></i> Déconnexion
                </button>
            </form>
        </div>
    </aside>

    <!-- Main -->
    <div class="lg:ml-64 flex-1 flex flex-col min-h-screen">

        <!-- Topbar -->
        <header class="bg-white border-b border-gray-100 px-6 py-3 flex items-center justify-between sticky top-0 z-30 shadow-sm">
            <div class="flex items-center gap-3">
                <button id="sidebar-toggle" class="lg:hidden text-gray-500 hover:text-mja-blue p-1">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <!-- Three-dot accent -->
                <div class="flex gap-1 items-center">
                    <span class="w-2 h-2 rounded-full bg-mja-blue"></span>
                    <span class="w-2 h-2 rounded-full bg-mja-yellow"></span>
                    <span class="w-2 h-2 rounded-full bg-mja-red"></span>
                </div>
                <h1 class="font-display font-bold text-mja-gray text-lg">@yield('page-title', 'Administration')</h1>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('home') }}" target="_blank"
                   class="text-sm text-gray-400 hover:text-mja-blue flex items-center gap-1.5 font-display font-semibold transition-colors">
                    <i class="fas fa-external-link-alt text-xs"></i> Voir le site
                </a>
            </div>
        </header>

        <!-- Flash messages -->
        <div class="px-6 pt-4">
            @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 text-green-800 px-4 py-3 rounded-lg text-sm flex items-center gap-2 mb-4 font-display font-semibold">
                <i class="fas fa-check-circle text-green-500"></i> {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div class="bg-red-50 border-l-4 border-mja-red text-red-800 px-4 py-3 rounded-lg text-sm flex items-center gap-2 mb-4 font-display font-semibold">
                <i class="fas fa-exclamation-circle text-mja-red"></i> {{ session('error') }}
            </div>
            @endif
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg text-sm mb-4">
                <div class="flex items-center gap-2 mb-1 font-display font-bold"><i class="fas fa-exclamation-triangle text-mja-red"></i> Erreurs de validation :</div>
                <ul class="list-disc list-inside space-y-1 ml-4">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
            @endif
        </div>

        <main class="flex-1 px-6 pb-10">
            @yield('content')
        </main>
    </div>

    <!-- Mobile sidebar overlay -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-30 hidden lg:hidden" onclick="closeSidebar()"></div>

    <script>
        function closeSidebar() {
            document.getElementById('sidebar').classList.add('hidden');
            document.getElementById('sidebar').classList.remove('flex');
            document.getElementById('sidebar-overlay').classList.add('hidden');
        }
        document.getElementById('sidebar-toggle')?.addEventListener('click', () => {
            const aside = document.getElementById('sidebar');
            aside.classList.toggle('hidden');
            aside.classList.toggle('flex');
            document.getElementById('sidebar-overlay').classList.toggle('hidden');
        });
    </script>
    @stack('scripts')
</body>
</html>
