@extends('layouts.admin')
@section('title', 'Périodes d\'adhésion')
@section('page-title', 'Périodes d\'adhésion')
@section('content')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-4">
    {{-- Formulaire création --}}
    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sticky top-24">
            <h2 class="font-display font-bold text-gray-800 mb-1"><i class="fas fa-plus-circle text-mja-blue mr-1"></i> Nouvelle période</h2>
            <p class="text-xs text-gray-400 mb-4">Une saison d'adhésion (ex. « Saison 2025-2026 »). Chaque adhésion sera automatiquement rattachée à la période correspondant à sa date.</p>
            <form method="POST" action="{{ route('admin.periods.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-display font-bold text-gray-600 mb-1">Nom <span class="text-mja-red">*</span></label>
                    <input type="text" name="label" value="{{ old('label') }}" required placeholder="Saison 2025-2026"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-mja-blue @error('label') border-red-400 @enderror">
                    @error('label')<p class="text-mja-red text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-display font-bold text-gray-600 mb-1">Début <span class="text-mja-red">*</span></label>
                    <input type="date" name="date_debut" value="{{ old('date_debut') }}" required
                        class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-mja-blue @error('date_debut') border-red-400 @enderror">
                    @error('date_debut')<p class="text-mja-red text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-display font-bold text-gray-600 mb-1">Fin <span class="text-mja-red">*</span></label>
                    <input type="date" name="date_fin" value="{{ old('date_fin') }}" required
                        class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-mja-blue @error('date_fin') border-red-400 @enderror">
                    @error('date_fin')<p class="text-mja-red text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <label class="flex items-center gap-2 text-sm text-gray-600">
                    <input type="checkbox" name="actif" value="1" checked class="rounded text-mja-blue"> Active
                </label>
                <button class="w-full bg-mja-blue hover:bg-mja-bluedark text-white font-display font-bold py-2.5 rounded-xl text-sm transition-colors">Créer la période</button>
            </form>
        </div>
    </div>

    {{-- Liste --}}
    <div class="lg:col-span-2">
        @if($current)
        <div class="bg-mja-blue/5 border border-mja-blue/20 rounded-2xl p-4 mb-4 flex items-center gap-3 text-sm">
            <i class="fas fa-circle-check text-mja-blue"></i>
            <span>Période courante : <strong>{{ $current->label }}</strong> — les nouvelles adhésions y seront rattachées.</span>
        </div>
        @else
        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 mb-4 text-sm text-amber-800">
            <i class="fas fa-triangle-exclamation mr-1"></i> Aucune période ne couvre la date du jour : les nouvelles adhésions ne seront rattachées à aucune saison. Créez/ajustez une période active.
        </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-x-auto">
            <table class="w-full text-sm min-w-[520px]">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50 text-left">
                        <th class="px-4 py-3 font-display font-bold text-gray-500 text-xs uppercase">Période</th>
                        <th class="px-4 py-3 font-display font-bold text-gray-500 text-xs uppercase">Dates</th>
                        <th class="px-4 py-3 font-display font-bold text-gray-500 text-xs uppercase text-center">Adhésions</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($periods as $p)
                    <tr class="hover:bg-gray-50 transition-colors {{ $p->actif ? '' : 'opacity-60' }}">
                        <td class="px-4 py-3">
                            <div class="font-display font-bold text-gray-900 flex items-center gap-2">
                                {{ $p->label }}
                                @if($current && $p->id === $current->id)<span class="text-[10px] bg-green-100 text-green-700 px-1.5 py-0.5 rounded-full">en cours</span>@endif
                                @unless($p->actif)<span class="text-[10px] bg-gray-100 text-gray-500 px-1.5 py-0.5 rounded">inactive</span>@endunless
                            </div>
                        </td>
                        <td class="px-4 py-3 text-gray-600 text-xs">
                            {{ $p->date_debut->format('d/m/Y') }} → {{ $p->date_fin->format('d/m/Y') }}
                        </td>
                        <td class="px-4 py-3 text-center font-bold text-gray-800">{{ $p->adhesions_count }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2 justify-end">
                                <a href="{{ route('admin.periods.edit', $p) }}" class="w-8 h-8 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center transition-colors" title="Modifier"><i class="fas fa-edit text-xs"></i></a>
                                <form method="POST" action="{{ route('admin.periods.destroy', $p) }}" data-confirm="Supprimer la période « {{ $p->label }} » ? Les adhésions rattachées ne seront plus classées mais ne seront pas supprimées.">
                                    @csrf @method('DELETE')
                                    <button class="w-8 h-8 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg flex items-center justify-center transition-colors" title="Supprimer"><i class="fas fa-trash text-xs"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-4 py-10 text-center text-gray-400 text-sm">Aucune période. Créez la première saison d'adhésion.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
