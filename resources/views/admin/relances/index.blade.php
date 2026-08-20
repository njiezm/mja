@extends('layouts.admin')
@section('title', 'Relances')

@section('content')

{{-- L'interrupteur ne s'appuie pas sur les variantes « peer-checked » : la
     feuille Tailwind compilée du projet ne les contient pas toutes. --}}
<style>
    .sw-piste { position: relative; width: 3rem; height: 1.75rem; flex: none;
        background: #d1d5db; border-radius: 9999px; transition: background-color .2s; }
    .sw-piste::after { content: ''; position: absolute; top: .25rem; left: .25rem;
        width: 1.25rem; height: 1.25rem; background: #fff; border-radius: 9999px;
        box-shadow: 0 1px 3px rgba(0,0,0,.25); transition: transform .2s; }
    .sw-input:checked + .sw-piste { background: #E3342F; }
    .sw-input:checked + .sw-piste::after { transform: translateX(1.25rem); }
    .sw-input:focus-visible + .sw-piste { outline: 3px solid #1E93D6; outline-offset: 2px; }
    /* Page grisée quand les relances sont coupées : plus rien n'est cliquable. */
    .zone-suspendue { opacity: .45; filter: grayscale(1); pointer-events: none; }
</style>

<form method="POST" action="{{ route('admin.relances.suspendre') }}"
      class="rounded-2xl shadow-sm border px-5 py-4 mb-8 {{ $suspendues ? 'bg-red-50 border-mja-red/30' : 'bg-white border-gray-100' }}">
    @csrf
    {{-- Le champ caché porte la valeur « décoché » : c'est le dernier envoyé qui compte. --}}
    <input type="hidden" name="suspendues" value="0">
    <label class="flex items-center justify-between gap-4 cursor-pointer">
        <span class="min-w-0">
            <span class="block font-display font-bold text-gray-900">
                <i class="fas {{ $suspendues ? 'fa-ban text-mja-red' : 'fa-circle-check text-green-600' }} mr-1.5"></i>
                Désactiver les relances
                @if($suspendues)
                <span class="ml-2 align-middle text-xs font-display font-bold px-2.5 py-1 rounded-full bg-mja-red/10 text-mja-red">Suspendues</span>
                @endif
            </span>
            <span class="block text-xs text-gray-500 mt-1">
                @if($suspendues)
                Plus aucun email ne part, ni automatiquement ni à la main. Les réglages et l'historique
                restent consultables mais figés — basculez l'interrupteur pour reprendre les envois.
                @else
                Coupe immédiatement tous les envois, automatiques comme manuels. Les réglages sont conservés.
                @endif
            </span>
        </span>
        <input type="checkbox" name="suspendues" value="1" class="sw-input sr-only"
               {{ $suspendues ? 'checked' : '' }} onchange="this.form.submit()">
        <span class="sw-piste"></span>
    </label>
</form>

<div class="{{ $suspendues ? 'zone-suspendue' : '' }}" @if($suspendues) inert @endif>

<div class="flex flex-wrap items-start justify-between gap-4 mb-8">
    <div>
        <h1 class="font-display font-black text-2xl text-gray-900">Relances</h1>
        <p class="text-gray-500 text-sm mt-1">
            Cotisations en attente et renouvellements de saison, relancés automatiquement par email.
            @if($dernierEnvoi)
            Dernier envoi : {{ \Illuminate\Support\Carbon::parse($dernierEnvoi)->locale('fr')->isoFormat('D MMMM Y à HH:mm') }}.
            @endif
        </p>
    </div>
    <form method="POST" action="{{ route('admin.relances.executer') }}"
          data-confirm="Envoyer maintenant toutes les relances dues ({{ $paiements->count() + $renouvellements->count() }}) ?">
        @csrf
        <button class="bg-mja-blue hover:bg-mja-bluedark text-white font-semibold text-sm px-5 py-2.5 rounded-xl transition-colors flex items-center gap-2">
            <i class="fas fa-paper-plane"></i> Envoyer les relances maintenant
        </button>
    </form>
</div>

{{-- ── Envois dus ─────────────────────────────────────────────────────────── --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-10">
    @foreach([
        ['Cotisations en attente', $paiements, 'paiement', 'fa-money-bill-wave', 'text-amber-600', 'bg-amber-50'],
        ['Renouvellements à venir', $renouvellements, 'renouvellement', 'fa-rotate-right', 'text-mja-blue', 'bg-mja-blue/5'],
    ] as [$titre, $liste, $type, $icone, $couleur, $fond])
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-50 flex items-center justify-between">
            <h2 class="font-display font-bold text-gray-900 flex items-center gap-2">
                <span class="w-8 h-8 {{ $fond }} {{ $couleur }} rounded-lg flex items-center justify-center">
                    <i class="fas {{ $icone }} text-xs"></i>
                </span>
                {{ $titre }}
            </h2>
            <span class="font-display font-black text-lg {{ $couleur }}">{{ $liste->count() }}</span>
        </div>

        @if($liste->isEmpty())
        <p class="px-6 py-8 text-center text-sm text-gray-400">Rien à relancer pour l'instant.</p>
        @else
        <ul class="divide-y divide-gray-50 max-h-96 overflow-y-auto">
            @foreach($liste as $a)
            <li class="px-6 py-3 flex items-center justify-between gap-3">
                <div class="min-w-0">
                    <div class="font-display font-bold text-gray-900 text-sm truncate">{{ $a->prenom }} {{ $a->nom }}</div>
                    <div class="text-xs text-gray-400 truncate">
                        {{ $a->email }}
                        @php $deja = $a->relances->where('type', $type)->count(); @endphp
                        @if($deja > 0) · {{ $deja }} relance(s) déjà envoyée(s) @endif
                    </div>
                </div>
                <form method="POST" action="{{ route('admin.relances.une', $a) }}" class="shrink-0"
                      data-confirm="Relancer {{ $a->prenom }} {{ $a->nom }} maintenant ?">
                    @csrf
                    <input type="hidden" name="type" value="{{ $type }}">
                    <button class="w-8 h-8 bg-gray-100 hover:bg-mja-blue hover:text-white text-gray-600 rounded-lg flex items-center justify-center transition-colors" title="Relancer cette personne">
                        <i class="fas fa-paper-plane text-xs"></i>
                    </button>
                </form>
            </li>
            @endforeach
        </ul>
        @endif
    </div>
    @endforeach
</div>

{{-- ── Réglages ───────────────────────────────────────────────────────────── --}}
<form method="POST" action="{{ route('admin.relances.update') }}" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-10">
    @csrf @method('PUT')
    <h2 class="font-display font-bold text-gray-900 mb-5">Réglages</h2>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        @foreach([
            ['paiement', 'Relance de paiement', "Pour les cotisations réglées par chèque, espèces ou virement et non encore encaissées.", [
                ['relance_paiement_delai', "Première relance après (jours)"],
                ['relance_paiement_intervalle', 'Intervalle entre deux relances (jours)'],
                ['relance_paiement_max', 'Nombre maximum de relances'],
            ]],
            ['renouvellement', 'Relance de renouvellement', "Pour les adhérents à jour dont la saison se termine et qui n'ont pas encore réadhéré.", [
                ['relance_renouvellement_avant', 'Première relance avant la fin de saison (jours)'],
                ['relance_renouvellement_intervalle', 'Intervalle entre deux relances (jours)'],
                ['relance_renouvellement_max', 'Nombre maximum de relances'],
            ]],
        ] as [$cle, $titre, $aide, $champs])
        <div>
            <label class="flex items-center gap-3 mb-2 cursor-pointer">
                <input type="hidden" name="relance_{{ $cle }}_active" value="0">
                <input type="checkbox" name="relance_{{ $cle }}_active" value="1"
                       {{ $reglages['relance_'.$cle.'_active'] ? 'checked' : '' }}
                       class="w-5 h-5 rounded text-mja-blue cursor-pointer">
                <span class="font-display font-bold text-gray-900">{{ $titre }}</span>
            </label>
            <p class="text-xs text-gray-500 mb-4 ml-8">{{ $aide }}</p>

            <div class="space-y-3 ml-8">
                @foreach($champs as [$champ, $label])
                <div class="flex items-center justify-between gap-4">
                    <label for="r-{{ $champ }}" class="text-sm text-gray-600">{{ $label }}</label>
                    <input type="number" id="r-{{ $champ }}" name="{{ $champ }}" value="{{ old($champ, $reglages[$champ]) }}"
                           min="0" max="365" required
                           class="w-24 border border-gray-200 rounded-xl px-3 py-2 text-sm text-right focus:outline-none focus:ring-2 focus:ring-mja-blue">
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-8 pt-6 border-t border-gray-50 flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <label for="r-heure" class="text-sm text-gray-600">Ne pas envoyer avant (heure)</label>
            <input type="number" id="r-heure" name="relances_heure" value="{{ old('relances_heure', $reglages['relances_heure']) }}"
                   min="0" max="23" required
                   class="w-20 border border-gray-200 rounded-xl px-3 py-2 text-sm text-right focus:outline-none focus:ring-2 focus:ring-mja-blue">
        </div>
        <button class="bg-mja-blue hover:bg-mja-bluedark text-white font-semibold text-sm px-6 py-2.5 rounded-xl transition-colors">
            Enregistrer les réglages
        </button>
    </div>
</form>

{{-- ── Historique ─────────────────────────────────────────────────────────── --}}
<div class="flex flex-wrap items-center justify-between gap-3 mb-4">
    <h2 class="font-display font-black text-lg text-gray-900">Historique des envois</h2>
    @if($historique->total() > 0)
    <form method="POST" action="{{ route('admin.relances.historique.vider') }}"
          data-confirm="Vider tout l'historique des relances ({{ $historique->total() }} entrée(s)) ? Attention : ce journal empêche les doublons. Une fois vidé, les compteurs repartent de zéro et les personnes déjà relancées pourront l'être à nouveau.">
        @csrf @method('DELETE')
        <button class="inline-flex items-center gap-2 bg-red-50 hover:bg-red-100 text-mja-red font-semibold text-sm px-4 py-2 rounded-xl transition-colors">
            <i class="fas fa-trash-can text-xs"></i> Vider l'historique
        </button>
    </form>
    @endif
</div>

@if($historique->isEmpty())
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-10 text-center text-gray-400 text-sm">
    Aucune relance envoyée pour l'instant.
</div>
@else
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-xs uppercase tracking-wider text-gray-500 font-display font-bold">
            <tr>
                <th class="px-6 py-3 text-left">Date</th>
                <th class="px-6 py-3 text-left">Adhérent</th>
                <th class="px-6 py-3 text-left">Type</th>
                <th class="px-6 py-3 text-left">N°</th>
                <th class="px-6 py-3 text-left">Origine</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @foreach($historique as $r)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-3 text-gray-600 whitespace-nowrap">{{ $r->envoyee_le?->locale('fr')->isoFormat('D MMM Y HH:mm') }}</td>
                <td class="px-6 py-3">
                    <div class="font-display font-bold text-gray-900">{{ $r->adhesion?->prenom }} {{ $r->adhesion?->nom }}</div>
                    <div class="text-xs text-gray-400">{{ $r->email }}</div>
                </td>
                <td class="px-6 py-3">
                    <span class="inline-flex items-center gap-1.5 text-xs font-display font-bold px-2.5 py-1 rounded-full
                        {{ $r->type === 'paiement' ? 'bg-amber-50 text-amber-700' : 'bg-mja-blue/10 text-mja-dark' }}">
                        {{ $r->typeLabel() }}
                    </span>
                </td>
                <td class="px-6 py-3 text-gray-600">{{ $r->numero }}</td>
                <td class="px-6 py-3 text-gray-500 text-xs">{{ $r->automatique ? 'Automatique' : 'Manuelle' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @if($historique->hasPages())<div class="px-6 py-4 border-t border-gray-50">{{ $historique->links() }}</div>@endif
</div>
@endif

</div>{{-- /zone grisée --}}

@endsection
