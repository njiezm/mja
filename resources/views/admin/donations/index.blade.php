@extends('layouts.admin')
@section('title', 'Dons')
@section('page-title', 'Dons')
@section('content')

<div class="grid grid-cols-2 gap-4 mt-4 mb-6">
    <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 bg-mja-red/10 rounded-xl flex items-center justify-center shrink-0"><i class="fas fa-heart text-mja-red"></i></div>
        <div><div class="text-2xl font-display font-black text-mja-gray">{{ $stats['total'] }}</div><div class="text-xs text-gray-400 font-semibold uppercase tracking-wider">Dons reçus</div></div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center shrink-0"><i class="fas fa-euro-sign text-green-600"></i></div>
        <div><div class="text-2xl font-display font-black text-mja-gray">{{ number_format($stats['montant'], 2, ',', ' ') }} €</div><div class="text-xs text-gray-400 font-semibold uppercase tracking-wider">Total collecté</div></div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-x-auto">
    <table class="w-full text-sm min-w-[640px]">
        <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
            <tr>
                <th class="px-4 py-3 text-left font-semibold">Donateur</th>
                <th class="px-4 py-3 text-left font-semibold">Montant</th>
                <th class="px-4 py-3 text-left font-semibold">Statut</th>
                <th class="px-4 py-3 text-left font-semibold">Message</th>
                <th class="px-4 py-3 text-left font-semibold">Date</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($donations as $don)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3">
                    <div class="font-semibold text-gray-900">{{ $don->nom_complet }}</div>
                    <div class="text-xs text-gray-400">{{ $don->email }}</div>
                </td>
                <td class="px-4 py-3 font-display font-bold text-gray-900">{{ number_format((float) $don->montant, 2, ',', ' ') }} €</td>
                <td class="px-4 py-3">
                    @if($don->isPaid())
                    <span class="bg-green-100 text-green-700 text-xs font-bold px-2 py-0.5 rounded-full">Payé</span>
                    @else
                    <span class="bg-orange-100 text-orange-700 text-xs font-bold px-2 py-0.5 rounded-full">En attente</span>
                    @endif
                </td>
                <td class="px-4 py-3 text-gray-500 text-xs max-w-[220px] truncate">{{ $don->message }}</td>
                <td class="px-4 py-3 text-gray-400 text-xs">{{ $don->created_at->locale('fr')->isoFormat('D MMM Y') }}</td>
                <td class="px-4 py-3 text-right">
                    <form method="POST" action="{{ route('admin.donations.destroy', $don) }}" data-confirm="Supprimer ce don de la liste ?">
                        @csrf @method('DELETE')
                        <button class="w-8 h-8 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg inline-flex items-center justify-center"><i class="fas fa-trash text-xs"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-4 py-12 text-center text-gray-400">Aucun don pour le moment.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($donations->hasPages())<div class="px-6 py-4 border-t border-gray-50">{{ $donations->links() }}</div>@endif
</div>
@endsection
