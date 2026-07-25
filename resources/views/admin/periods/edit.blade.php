@extends('layouts.admin')
@section('title', 'Modifier la période')
@section('page-title', 'Modifier la période')
@section('content')
<div class="max-w-lg mt-4">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="flex h-1"><div class="flex-1 bg-mja-blue"></div><div class="flex-1 bg-mja-yellow"></div><div class="flex-1 bg-mja-red"></div></div>
        <form method="POST" action="{{ route('admin.periods.update', $period) }}" class="p-6 space-y-5">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nom <span class="text-red-500">*</span></label>
                <input type="text" name="label" value="{{ old('label', $period->label) }}" required
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-mja-blue">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Début <span class="text-red-500">*</span></label>
                    <input type="date" name="date_debut" value="{{ old('date_debut', $period->date_debut->format('Y-m-d')) }}" required
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-mja-blue">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Fin <span class="text-red-500">*</span></label>
                    <input type="date" name="date_fin" value="{{ old('date_fin', $period->date_fin->format('Y-m-d')) }}" required
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-mja-blue">
                </div>
            </div>
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="actif" value="1" {{ old('actif', $period->actif) ? 'checked' : '' }} class="w-5 h-5 rounded text-mja-blue">
                <span class="text-sm font-semibold text-gray-700">Période active</span>
            </label>
            <p class="text-xs text-gray-400">À l'enregistrement, les adhésions sans période dont la date tombe dans cet intervalle seront automatiquement rattachées.</p>
            <div class="flex gap-3 pt-1">
                <button type="submit" class="flex-1 bg-mja-blue hover:bg-mja-bluedark text-white font-display font-bold py-3 rounded-xl transition-colors">
                    <i class="fas fa-save mr-2"></i> Enregistrer
                </button>
                <a href="{{ route('admin.periods.index') }}" class="px-5 py-3 border border-gray-200 rounded-xl text-gray-600 hover:bg-gray-50 font-display font-semibold text-sm transition-colors">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
