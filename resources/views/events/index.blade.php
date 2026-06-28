@extends('layouts.app')
@section('title', "Événements — Madin'Jeunes Ambition")
@section('meta_description', "Agenda des événements de Madin'Jeunes Ambition en Martinique — Sport Day, Fwi Ti Dèj, conférences et rencontres jeunesse.")

@section('content')
<section class="hero-gradient text-white py-16 relative overflow-hidden">
    <div class="absolute -right-10 -top-10 w-64 h-64 opacity-10 pointer-events-none">
        <svg viewBox="0 0 200 200" fill="none"><circle cx="100" cy="100" r="90" stroke="#D0021B" stroke-width="2"/><circle cx="100" cy="100" r="65" stroke="#F5A623" stroke-width="2"/><circle cx="100" cy="100" r="40" stroke="#3DAEF5" stroke-width="2"/></svg>
    </div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-sm text-gray-400 mb-3 font-display font-semibold">
            <a href="{{ route('home') }}" class="hover:text-mja-yellow transition-colors">Accueil</a>
            <span class="mx-2 text-gray-600">/</span> Événements
        </div>
        <h1 class="font-display font-black text-4xl sm:text-5xl mb-4">Événements</h1>
        <p class="text-gray-300 text-lg">Retrouvez tous nos rendez-vous à venir et passés.</p>
    </div>
</section>
<div class="flex h-1"><div class="flex-1 bg-mja-blue"></div><div class="flex-1 bg-mja-yellow"></div><div class="flex-1 bg-mja-red"></div></div>

@php
use Carbon\Carbon;
$today = Carbon::today();
// 3 mois à partir du mois en cours
$calMonths = [
    $today->copy()->startOfMonth(),
    $today->copy()->addMonth()->startOfMonth(),
    $today->copy()->addMonths(2)->startOfMonth(),
];
// Grouper les events par date
$eventsByDate = $avenir->groupBy(fn($e) => $e->date_debut->format('Y-m-d'));
$accentColors = [
    'dot'  => ['bg-mja-blue','bg-mja-yellow','bg-mja-red'],
    'ring' => ['ring-mja-blue','ring-mja-yellow','ring-mja-red'],
    'text' => ['text-mja-blue','text-yellow-600','text-mja-red'],
];
// Associer un index couleur à chaque event (par ordre d'insertion)
$eventIndex = [];
$ci = 0;
foreach ($avenir as $ev) {
    $eventIndex[$ev->id] = $ci % 3;
    $ci++;
}
@endphp

@if($avenir->count())
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-3 mb-10">
            <span class="w-9 h-9 bg-mja-red text-white rounded-xl flex items-center justify-center shrink-0">
                <i class="fas fa-calendar-alt text-sm"></i>
            </span>
            <h2 class="font-display font-black text-2xl text-mja-gray">Prochains événements</h2>
        </div>

        <!-- Calendriers -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            @foreach($calMonths as $monthStart)
            @php
                $daysInMonth = $monthStart->daysInMonth;
                $firstDay = (int)$monthStart->dayOfWeek;
                $startOffset = $firstDay === 0 ? 6 : $firstDay - 1;
                $monthKey = $monthStart->format('Y-m');
            @endphp
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <!-- En-tête du mois -->
                <div class="bg-mja-dark text-white px-4 py-3 flex items-center justify-between">
                    <span class="font-display font-bold text-sm capitalize">
                        {{ $monthStart->locale('fr')->isoFormat('MMMM YYYY') }}
                    </span>
                    @php $eventsThisMonth = $avenir->filter(fn($e) => $e->date_debut->format('Y-m') === $monthKey); @endphp
                    @if($eventsThisMonth->count())
                    <span class="text-xs bg-mja-yellow text-mja-dark font-display font-bold px-2 py-0.5 rounded-full">
                        {{ $eventsThisMonth->count() }} événement{{ $eventsThisMonth->count() > 1 ? 's' : '' }}
                    </span>
                    @endif
                </div>
                <!-- Jours de la semaine -->
                <div class="grid grid-cols-7 bg-gray-50 border-b border-gray-100">
                    @foreach(['L','M','M','J','V','S','D'] as $dn)
                    <div class="text-center py-1.5 text-xs font-display font-bold text-gray-400">{{ $dn }}</div>
                    @endforeach
                </div>
                <!-- Grille des jours -->
                <div class="grid grid-cols-7 p-2 gap-0.5">
                    @for($i = 0; $i < $startOffset; $i++)
                    <div class="h-9"></div>
                    @endfor
                    @for($day = 1; $day <= $daysInMonth; $day++)
                    @php
                        $dateStr = $monthStart->copy()->setDay($day)->format('Y-m-d');
                        $dayEvents = $eventsByDate->get($dateStr, collect());
                        $isToday = $dateStr === $today->format('Y-m-d');
                        $isPast  = $dateStr < $today->format('Y-m-d');
                        $firstEvent = $dayEvents->first();
                        $colorIdx = $firstEvent ? ($eventIndex[$firstEvent->id] ?? 0) : 0;
                    @endphp
                    <div class="relative h-9 flex items-center justify-center rounded-lg
                        {{ $isToday ? 'ring-2 ring-mja-blue' : '' }}
                        {{ $dayEvents->count() ? 'cursor-pointer' : '' }}
                        {{ $isPast && !$dayEvents->count() ? 'opacity-30' : '' }}"
                        @if($firstEvent)
                        title="{{ $dayEvents->pluck('titre')->join(' · ') }}"
                        @endif>
                        @if($dayEvents->count())
                        <div class="absolute inset-0 {{ ['bg-mja-blue/15','bg-mja-yellow/15','bg-mja-red/15'][$colorIdx] }} rounded-lg"></div>
                        @endif
                        <span class="relative z-10 text-xs font-display font-bold
                            {{ $dayEvents->count() ? (['text-mja-blue','text-yellow-700','text-mja-red'][$colorIdx]) : ($isToday ? 'text-mja-blue' : 'text-gray-600') }}">
                            {{ $day }}
                        </span>
                        @if($dayEvents->count())
                        <span class="absolute bottom-0.5 left-1/2 -translate-x-1/2 w-1 h-1 rounded-full {{ $accentColors['dot'][$colorIdx] }}"></span>
                        @endif
                    </div>
                    @endfor
                </div>
            </div>
            @endforeach
        </div>

        <!-- Liste des événements à venir -->
        <div class="divide-y divide-gray-100 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            @foreach($avenir as $event)
            <a href="{{ route('events.show', $event) }}"
               class="flex items-center gap-5 px-6 py-4 hover:bg-gray-50 transition-colors group">
                <!-- Date bloc -->
                <div class="bg-mja-dark text-white rounded-xl p-3 text-center min-w-[56px] shrink-0">
                    <div class="font-display font-black text-xl leading-none">{{ $event->date_debut->format('d') }}</div>
                    <div class="text-xs uppercase mt-0.5 font-display font-semibold text-mja-yellow">{{ $event->date_debut->locale('fr')->isoFormat('MMM') }}</div>
                </div>
                <!-- Infos -->
                <div class="flex-1 min-w-0">
                    <div class="font-display font-bold text-mja-gray group-hover:text-mja-blue transition-colors leading-snug">{{ $event->titre }}</div>
                    <div class="flex flex-wrap gap-4 text-xs text-gray-400 mt-1">
                        @if($event->lieu)<span><i class="fas fa-map-marker-alt mr-1"></i>{{ $event->lieu }}</span>@endif
                        <span><i class="fas fa-clock mr-1"></i>{{ $event->date_debut->format('H') }}h{{ $event->date_debut->format('i') }}</span>
                        <span class="{{ $event->gratuit ? 'text-green-600' : 'text-orange-500' }} font-semibold">
                            @if($event->gratuit) Gratuit
                            @elseif($event->prix) {{ number_format($event->prix, 2, ',', ' ') }} €
                            @else Payant
                            @endif
                        </span>
                    </div>
                </div>
                <!-- CTA inscription -->
                @if($event->lien_inscription)
                <a href="{{ $event->lien_inscription }}" target="_blank"
                   class="shrink-0 text-xs font-display font-bold px-4 py-2 rounded-lg bg-mja-blue/10 text-mja-blue hover:bg-mja-blue hover:text-white transition-colors"
                   onclick="event.stopPropagation()">
                    S'inscrire
                </a>
                @else
                <i class="fas fa-chevron-right text-gray-300 text-xs shrink-0"></i>
                @endif
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

@if($passes->count())
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-3 mb-8">
            <span class="w-9 h-9 bg-gray-300 text-white rounded-xl flex items-center justify-center shrink-0">
                <i class="fas fa-history text-sm"></i>
            </span>
            <h2 class="font-display font-black text-2xl text-mja-gray">Événements passés</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($passes as $event)
            <a href="{{ route('events.show', $event) }}"
               class="group bg-gray-50 hover:bg-white rounded-2xl border border-gray-100 flex gap-0 overflow-hidden transition-colors card-hover">
                <div class="bg-gray-200 text-gray-500 p-4 text-center shrink-0 min-w-[72px] flex flex-col justify-center">
                    <div class="font-display font-black text-2xl leading-none">{{ $event->date_debut->format('d') }}</div>
                    <div class="text-xs uppercase mt-1 font-display font-semibold">{{ $event->date_debut->locale('fr')->isoFormat('MMM') }}</div>
                    <div class="text-xs mt-0.5 text-gray-400">{{ $event->date_debut->format('Y') }}</div>
                </div>
                <div class="p-4 flex-1">
                    <h3 class="font-display font-semibold text-mja-gray group-hover:text-mja-blue transition-colors text-sm leading-tight">{{ $event->titre }}</h3>
                    @if($event->lieu)<p class="text-xs text-gray-400 mt-1.5 flex items-center gap-1"><i class="fas fa-map-marker-alt text-gray-300"></i>{{ $event->lieu }}</p>@endif
                </div>
            </a>
            @endforeach
        </div>
        <div class="mt-8">{{ $passes->links() }}</div>
    </div>
</section>
@endif

@if(!$avenir->count() && !$passes->count())
<div class="py-24 text-center">
    <img src="/images/logomjat.png" alt="" class="h-20 w-auto mx-auto mb-6 opacity-20 object-contain">
    <p class="text-xl font-display font-bold text-gray-400">Aucun événement pour le moment.</p>
</div>
@endif
@endsection
