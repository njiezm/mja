@extends('layouts.admin')
@section('title', 'Partenaires')
@section('page-title', 'Partenaires')

@section('content')
<div class="flex items-center justify-between mb-6 mt-4">
    <p class="text-gray-500 text-sm">{{ $partenaires->count() }} partenaire(s)</p>
    <a href="{{ route('admin.partenaires.create') }}"
       class="bg-mja-blue hover:bg-mja-bluedark text-white font-semibold text-sm px-5 py-2.5 rounded-xl transition-colors flex items-center gap-2">
        <i class="fas fa-plus"></i> Ajouter un partenaire
    </a>
</div>

@if(session('success'))
<div class="mb-5 bg-green-50 border border-green-200 text-green-800 text-sm font-semibold px-5 py-3.5 rounded-xl flex items-center gap-2">
    <i class="fas fa-check-circle text-green-500"></i> {{ session('success') }}
</div>
@endif

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
            <tr>
                <th class="px-6 py-3 text-left font-semibold w-16">Logo</th>
                <th class="px-4 py-3 text-left font-semibold">Nom</th>
                <th class="px-4 py-3 text-left font-semibold hidden md:table-cell">Description</th>
                <th class="px-4 py-3 text-center font-semibold w-16">Ordre</th>
                <th class="px-4 py-3 text-center font-semibold w-24">Statut</th>
                <th class="px-4 py-3 text-center font-semibold w-28">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($partenaires as $p)
            <tr class="hover:bg-gray-50 {{ !$p->actif ? 'opacity-50' : '' }}">
                <td class="px-6 py-4">
                    @if($p->logo)
                    <img src="{{ asset('storage/'.$p->logo) }}" alt="{{ $p->nom }}"
                         class="h-10 w-16 object-contain rounded-lg border border-gray-100 bg-gray-50 p-1">
                    @else
                    <div class="h-10 w-16 rounded-lg border border-gray-100 bg-gray-50 flex items-center justify-center">
                        <i class="fas fa-building text-gray-300 text-lg"></i>
                    </div>
                    @endif
                </td>
                <td class="px-4 py-4">
                    <div class="font-semibold text-gray-800">{{ $p->nom }}</div>
                    @if($p->url)
                    <a href="{{ $p->url }}" target="_blank"
                       class="text-xs text-mja-blue hover:underline flex items-center gap-1 mt-0.5">
                        <i class="fas fa-external-link-alt text-[10px]"></i>
                        {{ parse_url($p->url, PHP_URL_HOST) }}
                    </a>
                    @endif
                </td>
                <td class="px-4 py-4 hidden md:table-cell text-gray-400 text-xs max-w-xs">
                    {{ $p->description ?? '—' }}
                </td>
                <td class="px-4 py-4 text-center text-gray-500 font-mono text-sm">{{ $p->ordre }}</td>
                <td class="px-4 py-4 text-center">
                    <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full
                        {{ $p->actif ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-400' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $p->actif ? 'bg-green-500' : 'bg-gray-300' }}"></span>
                        {{ $p->actif ? 'Actif' : 'Inactif' }}
                    </span>
                </td>
                <td class="px-4 py-4">
                    <div class="flex items-center justify-center gap-2">
                        <a href="{{ route('admin.partenaires.edit', $p) }}"
                           class="w-8 h-8 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-edit text-xs"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.partenaires.destroy', $p) }}"
                              data-confirm="Supprimer {{ $p->nom }} ?">
                            @csrf @method('DELETE')
                            <button class="w-8 h-8 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-trash text-xs"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-16 text-gray-400">
                    <i class="fas fa-handshake text-5xl mb-4 block"></i>
                    Aucun partenaire pour l'instant.
                    <a href="{{ route('admin.partenaires.create') }}" class="text-mja-blue underline ml-1">
                        Ajouter le premier
                    </a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
