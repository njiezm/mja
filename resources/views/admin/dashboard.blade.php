@extends('layouts.admin')
@section('title', 'Tableau de bord')
@section('page-title', 'Tableau de bord')

@section('content')
<!-- Stats -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-8 mt-4">
    @foreach([
        ['label'=>'Actualités','value'=>$stats['articles'],'icon'=>'fa-newspaper','color'=>'text-blue-600 bg-blue-50','route'=>'admin.articles.index'],
        ['label'=>'Projets','value'=>$stats['projets'],'icon'=>'fa-project-diagram','color'=>'text-purple-600 bg-purple-50','route'=>'admin.projects.index'],
        ['label'=>'Événements','value'=>$stats['evenements'],'icon'=>'fa-calendar-alt','color'=>'text-orange-600 bg-orange-50','route'=>'admin.events.index'],
        ['label'=>'Ressources','value'=>$stats['ressources'],'icon'=>'fa-folder-open','color'=>'text-green-600 bg-green-50','route'=>'admin.resources.index'],
    ] as $stat)
    <a href="{{ route($stat['route']) }}" class="bg-white rounded-2xl shadow-sm p-5 flex items-center gap-4 hover:shadow-md transition-shadow border border-gray-100">
        <div class="w-12 h-12 {{ $stat['color'] }} rounded-xl flex items-center justify-center flex-shrink-0">
            <i class="fas {{ $stat['icon'] }} text-lg"></i>
        </div>
        <div>
            <div class="font-display font-bold text-2xl text-gray-900">{{ $stat['value'] }}</div>
            <div class="text-sm text-gray-500">{{ $stat['label'] }}</div>
        </div>
    </a>
    @endforeach
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Messages non lus -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
        <div class="w-12 h-12 bg-red-50 text-red-600 rounded-xl flex items-center justify-center flex-shrink-0">
            <i class="fas fa-envelope text-lg"></i>
        </div>
        <div>
            <div class="font-display font-bold text-2xl text-gray-900">{{ $stats['messages_non_lus'] }}</div>
            <div class="text-sm text-gray-500">Messages non lus</div>
        </div>
        <a href="{{ route('admin.contacts.index') }}" class="ml-auto text-sm text-mja-blue font-semibold hover:text-mja-dark">Voir →</a>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
        <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center flex-shrink-0">
            <i class="fas fa-users text-lg"></i>
        </div>
        <div>
            <div class="font-display font-bold text-2xl text-gray-900">{{ $stats['membres'] }}</div>
            <div class="text-sm text-gray-500">Membres de l'équipe</div>
        </div>
        <a href="{{ route('admin.team.index') }}" class="ml-auto text-sm text-mja-blue font-semibold hover:text-mja-dark">Gérer →</a>
    </div>
    <div class="bg-mja-blue rounded-2xl p-5 text-white flex flex-col justify-between">
        <div class="text-sm font-semibold text-green-100 mb-1">Accès rapide</div>
        <div class="space-y-2">
            <a href="{{ route('admin.articles.create') }}" class="flex items-center gap-2 text-sm hover:text-mja-gold transition-colors"><i class="fas fa-plus w-4"></i> Nouvel article</a>
            <a href="{{ route('admin.events.create') }}" class="flex items-center gap-2 text-sm hover:text-mja-gold transition-colors"><i class="fas fa-plus w-4"></i> Nouvel événement</a>
            <a href="{{ route('admin.projects.create') }}" class="flex items-center gap-2 text-sm hover:text-mja-gold transition-colors"><i class="fas fa-plus w-4"></i> Nouveau projet</a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Derniers messages -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="flex items-center justify-between p-5 border-b border-gray-50">
            <h2 class="font-display font-bold text-gray-900">Derniers messages</h2>
            <a href="{{ route('admin.contacts.index') }}" class="text-sm text-mja-blue font-semibold">Voir tout</a>
        </div>
        @if($derniers_messages->count())
        <div class="divide-y divide-gray-50">
            @foreach($derniers_messages as $msg)
            <a href="{{ route('admin.contacts.show', $msg) }}" class="flex items-start gap-3 p-4 hover:bg-gray-50 transition-colors">
                <div class="w-8 h-8 bg-mja-blue/10 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                    <span class="text-mja-blue font-bold text-xs">{{ strtoupper(substr($msg->nom, 0, 1)) }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <span class="font-semibold text-sm text-gray-900 {{ !$msg->lu ? 'text-mja-blue' : '' }}">{{ $msg->nom }}</span>
                        @if(!$msg->lu)<span class="w-2 h-2 bg-red-500 rounded-full ml-2 flex-shrink-0 inline-block"></span>@endif
                    </div>
                    <p class="text-xs text-gray-500 truncate">{{ $msg->sujet }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $msg->created_at->locale('fr')->diffForHumans() }}</p>
                </div>
            </a>
            @endforeach
        </div>
        @else
        <div class="p-8 text-center text-gray-400 text-sm">Aucun message</div>
        @endif
    </div>

    <!-- Prochains événements -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="flex items-center justify-between p-5 border-b border-gray-50">
            <h2 class="font-display font-bold text-gray-900">Prochains événements</h2>
            <a href="{{ route('admin.events.index') }}" class="text-sm text-mja-blue font-semibold">Gérer</a>
        </div>
        @if($prochains_events->count())
        <div class="divide-y divide-gray-50">
            @foreach($prochains_events as $event)
            <div class="flex items-center gap-4 p-4">
                <div class="bg-mja-blue text-white rounded-xl p-2.5 text-center min-w-[50px] flex-shrink-0">
                    <div class="font-bold text-lg leading-none">{{ $event->date_debut->format('d') }}</div>
                    <div class="text-xs text-green-200 mt-0.5">{{ $event->date_debut->locale('fr')->isoFormat('MMM') }}</div>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-sm text-gray-900 truncate">{{ $event->titre }}</p>
                    @if($event->lieu)<p class="text-xs text-gray-500"><i class="fas fa-map-marker-alt mr-1"></i>{{ $event->lieu }}</p>@endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="p-8 text-center text-gray-400 text-sm">Aucun événement à venir</div>
        @endif
    </div>
</div>
@endsection
