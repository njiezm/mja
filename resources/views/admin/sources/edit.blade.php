@extends('layouts.admin')
@section('title', 'Modifier la source')
@section('page-title', 'Modifier la source')
@section('content')
<div class="max-w-xl mt-4">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="flex h-1"><div class="flex-1 bg-mja-blue"></div><div class="flex-1 bg-mja-yellow"></div><div class="flex-1 bg-mja-red"></div></div>
        <form method="POST" action="{{ route('admin.sources.update', $source) }}" class="p-6 space-y-5">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nom <span class="text-red-500">*</span></label>
                <input type="text" name="label" value="{{ old('label', $source->label) }}" required
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-mja-blue">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Lien <span class="text-red-500">*</span></label>
                <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden focus-within:ring-2 focus-within:ring-mja-blue">
                    <span class="text-xs text-gray-400 pl-3 pr-1 select-none">{{ url('/') }}/</span>
                    <input type="text" name="slug" value="{{ old('slug', $source->slug) }}" required
                        class="flex-1 px-1 py-3 text-sm outline-none min-w-0">
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Redirection</label>
                <input type="text" name="target" value="{{ old('target', $source->target) }}"
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-mja-blue">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Description</label>
                <input type="text" name="description" value="{{ old('description', $source->description) }}"
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-mja-blue">
            </div>
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $source->is_active) ? 'checked' : '' }}
                    class="w-5 h-5 rounded text-mja-blue">
                <span class="text-sm font-semibold text-gray-700">Source active (le lien fonctionne)</span>
            </label>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="flex-1 bg-mja-blue hover:bg-mja-bluedark text-white font-display font-bold py-3 rounded-xl transition-colors">
                    <i class="fas fa-save mr-2"></i> Enregistrer
                </button>
                <a href="{{ route('admin.sources.index') }}" class="px-5 py-3 border border-gray-200 rounded-xl text-gray-600 hover:bg-gray-50 font-display font-semibold text-sm transition-colors">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
