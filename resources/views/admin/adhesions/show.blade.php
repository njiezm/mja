@extends('layouts.admin')
@section('title', "Adhésion de {$adhesion->prenom} {$adhesion->nom}")
@section('page-title', 'Dossier d\'adhésion')
@section('content')

@if(session('success'))
<div class="bg-green-50 border border-green-200 text-green-800 rounded-xl p-4 mb-4 mt-4 flex items-center gap-2 text-sm font-semibold">
    <i class="fas fa-check-circle text-green-500"></i> {{ session('success') }}
</div>
@endif

<div class="mt-4 grid grid-cols-1 xl:grid-cols-3 gap-6">

    {{-- Colonne principale --}}
    <div class="xl:col-span-2 space-y-5">

        {{-- En-tête candidat --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-mja-blue/5 border-b border-gray-100 p-6 flex items-start justify-between gap-4">
                <div>
                    <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">{{ $adhesion->label_premiere_adhesion }}</div>
                    <h2 class="font-display font-black text-2xl text-gray-900">{{ $adhesion->civilite }} {{ $adhesion->prenom }} {{ $adhesion->nom }}</h2>
                    <p class="text-sm text-gray-500 mt-1">Reçue le {{ $adhesion->created_at->locale('fr')->isoFormat('dddd D MMMM Y à H[h]mm') }}</p>
                </div>
                <span class="px-3 py-1 text-xs font-semibold rounded-full shrink-0 {{ !$adhesion->lu ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-500' }}">
                    {{ $adhesion->lu ? 'Lu' : 'Non lu' }}
                </span>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-5">
                    <div>
                        <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Date de naissance</div>
                        <div class="font-semibold text-gray-900">{{ $adhesion->date_naissance }}</div>
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Profession</div>
                        <div class="font-semibold text-gray-900">{{ $adhesion->profession }}</div>
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Permis</div>
                        <div class="font-semibold text-gray-900">{{ $adhesion->permis }}</div>
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Téléphone</div>
                        <a href="tel:{{ $adhesion->telephone }}" class="inline-flex items-center gap-2 font-semibold text-mja-blue hover:underline">
                            <span class="text-base leading-none">🇲🇶</span>
                            <i class="fas fa-phone text-xs"></i>
                            {{ $adhesion->telephone }}
                        </a>
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Email</div>
                        <a href="mailto:{{ $adhesion->email }}" class="font-semibold text-mja-blue hover:underline">{{ $adhesion->email }}</a>
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">T-Shirt</div>
                        <div class="font-semibold text-gray-900">{{ $adhesion->taille_tshirt }}</div>
                    </div>
                    <div class="col-span-2 sm:col-span-3">
                        <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Adresse postale</div>
                        <div class="font-semibold text-gray-900 whitespace-pre-line">{{ $adhesion->adresse_postale }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Santé + Urgence --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-5">
            <div>
                <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Problèmes de santé / Allergies</div>
                @if($adhesion->problemes_sante)
                    <div class="bg-yellow-50 border border-yellow-100 rounded-xl p-4 text-sm text-gray-700 whitespace-pre-wrap">{{ $adhesion->problemes_sante }}</div>
                @else
                    <div class="text-sm text-gray-400 italic">Aucun signalement</div>
                @endif
            </div>
            <div class="border-t border-gray-100 pt-5">
                <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Personne à contacter en cas d'urgence</div>
                <div class="bg-red-50 border border-red-100 rounded-xl p-4 text-sm font-semibold text-gray-800">{{ $adhesion->urgence_contact }}</div>
            </div>
        </div>

        {{-- Droit à l'image --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Droit à l'image</div>
            @if($adhesion->droit_image)
                <div class="flex items-center gap-2 text-green-700 font-semibold text-sm">
                    <i class="fas fa-check-circle text-green-500"></i> Autorisé
                </div>
            @else
                <div class="flex items-center gap-2 text-red-600 font-semibold text-sm">
                    <i class="fas fa-times-circle text-red-500"></i> Non autorisé
                </div>
            @endif
        </div>
    </div>

    {{-- Colonne latérale : statut + actions --}}
    <div class="space-y-5">

        {{-- Statut --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-display font-bold text-mja-gray mb-4">Statut de la demande</h3>
            @php
                $statutColors = ['nouvelle' => 'bg-orange-100 text-orange-700', 'traitee' => 'bg-blue-100 text-blue-700', 'acceptee' => 'bg-green-100 text-green-700', 'refusee' => 'bg-red-100 text-red-700'];
            @endphp
            <div class="mb-4">
                <span class="px-3 py-1.5 rounded-full text-sm font-bold {{ $statutColors[$adhesion->statut] ?? 'bg-gray-100 text-gray-600' }}">
                    {{ $adhesion->label_statut }}
                </span>
            </div>
            <form method="POST" action="{{ route('admin.adhesions.statut', $adhesion) }}" class="space-y-3">
                @csrf @method('PATCH')
                <select name="statut" class="w-full border-2 border-gray-100 focus:border-mja-blue rounded-xl px-3 py-2.5 text-sm outline-none">
                    @foreach(['nouvelle' => 'Nouvelle', 'traitee' => 'Traitée', 'acceptee' => 'Acceptée', 'refusee' => 'Refusée'] as $val => $label)
                        <option value="{{ $val }}" {{ $adhesion->statut === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <button type="submit" class="w-full bg-mja-blue hover:bg-mja-bluedark text-white font-semibold py-2.5 rounded-xl transition-colors text-sm">
                    Mettre à jour
                </button>
            </form>
        </div>

        {{-- Actions --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-3">
            <h3 class="font-display font-bold text-mja-gray mb-1">Actions</h3>
            <a href="mailto:{{ $adhesion->email }}?subject=Adhésion MJA — {{ $adhesion->prenom }} {{ $adhesion->nom }}"
                class="w-full bg-mja-blue hover:bg-mja-bluedark text-white font-semibold px-4 py-2.5 rounded-xl transition-colors text-sm flex items-center justify-center gap-2">
                <i class="fas fa-envelope"></i> Répondre par email
            </a>
            <a href="tel:{{ $adhesion->telephone }}"
                class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-4 py-2.5 rounded-xl transition-colors text-sm flex items-center justify-center gap-2">
                <i class="fas fa-phone"></i> Appeler
            </a>
            <a href="{{ route('admin.adhesions.index') }}" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-4 py-2.5 rounded-xl transition-colors text-sm flex items-center justify-center gap-2">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
            <form method="POST" action="{{ route('admin.adhesions.destroy', $adhesion) }}" onsubmit="return confirm('Supprimer cette demande d\'adhésion ?')" class="pt-1 border-t border-gray-100">
                @csrf @method('DELETE')
                <button class="w-full text-red-500 hover:text-red-700 text-sm font-semibold flex items-center justify-center gap-2 py-2">
                    <i class="fas fa-trash"></i> Supprimer
                </button>
            </form>
        </div>
    </div>

</div>
@endsection
