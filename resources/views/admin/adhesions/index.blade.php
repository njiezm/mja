@extends('layouts.admin')
@section('title', 'Demandes d\'adhésion')
@section('page-title', 'Demandes d\'adhésion')
@section('content')

<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4 mb-6">
    <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 bg-mja-blue/10 rounded-xl flex items-center justify-center shrink-0">
            <i class="fas fa-users text-mja-blue"></i>
        </div>
        <div>
            <div class="text-2xl font-display font-black text-mja-gray">{{ $stats['total'] }}</div>
            <div class="text-xs text-gray-400 font-semibold uppercase tracking-wider">Total</div>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 bg-orange-50 rounded-xl flex items-center justify-center shrink-0">
            <i class="fas fa-clock text-orange-400"></i>
        </div>
        <div>
            <div class="text-2xl font-display font-black text-mja-gray">{{ $stats['nouvelles'] }}</div>
            <div class="text-xs text-gray-400 font-semibold uppercase tracking-wider">Nouvelles</div>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center shrink-0">
            <i class="fas fa-check-circle text-green-500"></i>
        </div>
        <div>
            <div class="text-2xl font-display font-black text-mja-gray">{{ $stats['acceptees'] }}</div>
            <div class="text-xs text-gray-400 font-semibold uppercase tracking-wider">Acceptées</div>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
            <tr>
                <th class="px-6 py-3 text-left font-semibold w-4"></th>
                <th class="px-4 py-3 text-left font-semibold">Candidat</th>
                <th class="px-4 py-3 text-left font-semibold">Type</th>
                <th class="px-4 py-3 text-left font-semibold">Statut</th>
                <th class="px-4 py-3 text-left font-semibold">Date</th>
                <th class="px-4 py-3 text-center font-semibold">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($adhesions as $adhesion)
            <tr class="hover:bg-gray-50 {{ !$adhesion->lu ? 'bg-blue-50/40' : '' }}">
                <td class="px-6 py-4">
                    @if(!$adhesion->lu)<span class="w-2 h-2 bg-blue-500 rounded-full inline-block"></span>@endif
                </td>
                <td class="px-4 py-4">
                    <div class="font-semibold {{ !$adhesion->lu ? 'text-gray-900' : 'text-gray-600' }}">
                        {{ $adhesion->civilite }} {{ $adhesion->prenom }} {{ $adhesion->nom }}
                    </div>
                    <div class="text-xs text-gray-400">{{ $adhesion->email }}</div>
                </td>
                <td class="px-4 py-4">
                    @php $typeColors = ['premiere' => 'bg-blue-100 text-blue-700', 'readhesion' => 'bg-purple-100 text-purple-700', 'information' => 'bg-gray-100 text-gray-600']; @endphp
                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $typeColors[$adhesion->premiere_adhesion] ?? 'bg-gray-100 text-gray-600' }}">
                        {{ $adhesion->label_premiere_adhesion }}
                    </span>
                </td>
                <td class="px-4 py-4">
                    @php $statutColors = ['nouvelle' => 'bg-orange-100 text-orange-700', 'traitee' => 'bg-blue-100 text-blue-700', 'acceptee' => 'bg-green-100 text-green-700', 'refusee' => 'bg-red-100 text-red-700']; @endphp
                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $statutColors[$adhesion->statut] ?? 'bg-gray-100 text-gray-600' }}">
                        {{ $adhesion->label_statut }}
                    </span>
                </td>
                <td class="px-4 py-4 text-gray-400 text-xs">{{ $adhesion->created_at->locale('fr')->isoFormat('D MMM Y, H[h]mm') }}</td>
                <td class="px-4 py-4">
                    <div class="flex items-center justify-center gap-2">
                        <a href="{{ route('admin.adhesions.show', $adhesion) }}" class="w-8 h-8 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center"><i class="fas fa-eye text-xs"></i></a>
                        <form method="POST" action="{{ route('admin.adhesions.destroy', $adhesion) }}" onsubmit="return confirm('Supprimer cette demande ?')">
                            @csrf @method('DELETE')
                            <button class="w-8 h-8 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg flex items-center justify-center"><i class="fas fa-trash text-xs"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-6 py-12 text-center text-gray-400">Aucune demande d'adhésion reçue.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($adhesions->hasPages())<div class="px-6 py-4 border-t border-gray-50">{{ $adhesions->links() }}</div>@endif
</div>

@endsection
