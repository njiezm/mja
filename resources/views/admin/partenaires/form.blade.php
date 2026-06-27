@extends('layouts.admin')
@section('title', isset($partenaire->id) ? 'Modifier ' . $partenaire->nom : 'Nouveau partenaire')
@section('page-title', isset($partenaire->id) ? 'Modifier le partenaire' : 'Nouveau partenaire')

@section('content')
<div class="max-w-2xl mt-4">

    @if($errors->any())
    <div class="mb-5 bg-red-50 border border-red-200 text-red-800 text-sm px-5 py-3.5 rounded-xl">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form method="POST"
          action="{{ isset($partenaire->id) ? route('admin.partenaires.update', $partenaire) : route('admin.partenaires.store') }}"
          enctype="multipart/form-data">
        @csrf
        @if(isset($partenaire->id)) @method('PUT') @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-5">

            {{-- Nom --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Nom du partenaire <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nom" value="{{ old('nom', $partenaire->nom ?? '') }}" required
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-mja-blue"
                    placeholder="Région Martinique, Ville de Fort-de-France…">
            </div>

            {{-- URL --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Site web</label>
                <input type="url" name="url" value="{{ old('url', $partenaire->url ?? '') }}"
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-mja-blue"
                    placeholder="https://www.exemple.fr">
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Description courte
                    <span class="text-gray-400 font-normal">(max. 200 caractères)</span>
                </label>
                <input type="text" name="description" value="{{ old('description', $partenaire->description ?? '') }}"
                    maxlength="200"
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-mja-blue"
                    placeholder="Collectivité territoriale, soutien institutionnel…">
            </div>

            {{-- Logo + Ordre --}}
            <div class="grid grid-cols-2 gap-5 items-start">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Logo
                        <span class="text-gray-400 font-normal">(PNG/JPG — max 2 Mo)</span>
                    </label>
                    <input type="file" name="logo" accept="image/*"
                        class="w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-mja-blue/10 file:text-mja-blue file:font-semibold">
                    @if(isset($partenaire->id) && $partenaire->logo)
                    <div class="mt-3 flex items-center gap-3">
                        <img src="{{ asset('storage/'.$partenaire->logo) }}" alt="{{ $partenaire->nom }}"
                             class="h-12 object-contain rounded-lg border border-gray-100 bg-gray-50 p-1">
                        <span class="text-xs text-gray-400">Logo actuel</span>
                    </div>
                    @endif
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Ordre d'affichage</label>
                    <input type="number" name="ordre" value="{{ old('ordre', $partenaire->ordre ?? 0) }}"
                        min="0" max="999"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-mja-blue">
                    <p class="text-xs text-gray-400 mt-1">0 = en premier</p>
                </div>
            </div>

            {{-- Actif --}}
            <div class="flex items-center gap-3 pt-1">
                <input type="hidden" name="actif" value="0">
                <input type="checkbox" name="actif" id="actif" value="1"
                    {{ old('actif', ($partenaire->actif ?? true) ? '1' : '0') == '1' ? 'checked' : '' }}
                    class="w-5 h-5 rounded text-mja-blue cursor-pointer">
                <label for="actif" class="text-sm font-semibold text-gray-700 cursor-pointer">
                    Afficher ce partenaire sur le site
                </label>
            </div>
        </div>

        {{-- Boutons --}}
        <div class="flex items-center gap-3 mt-6">
            <button type="submit"
                class="bg-mja-blue hover:bg-mja-bluedark text-white font-bold text-sm px-6 py-3 rounded-xl transition-colors flex items-center gap-2">
                <i class="fas fa-save"></i>
                {{ isset($partenaire->id) ? 'Enregistrer les modifications' : 'Ajouter le partenaire' }}
            </button>
            <a href="{{ route('admin.partenaires.index') }}"
               class="text-sm text-gray-500 hover:text-gray-700 font-semibold px-4 py-3 rounded-xl hover:bg-gray-100 transition-colors">
                Annuler
            </a>
        </div>
    </form>
</div>
@endsection
