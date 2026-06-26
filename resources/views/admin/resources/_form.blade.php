<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-5">
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Titre <span class="text-red-500">*</span></label>
        <input type="text" name="titre" value="{{ old('titre', $resource->titre ?? '') }}" required
            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-mja-blue">
    </div>
    <div class="grid grid-cols-2 gap-5">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Type <span class="text-red-500">*</span></label>
            <select name="type" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-mja-blue">
                @foreach(['document'=>'📄 Document','pdf'=>'📕 PDF','guide'=>'📖 Guide','lien'=>'🔗 Lien externe','video'=>'🎬 Vidéo','audio'=>'🎵 Audio','podcast'=>'🎙️ Podcast','infographie'=>'📊 Infographie'] as $key => $label)
                <option value="{{ $key }}" {{ old('type', $resource->type ?? 'document') === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Catégorie</label>
            <input type="text" name="categorie" value="{{ old('categorie', $resource->categorie ?? '') }}"
                class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-mja-blue" placeholder="Ex: Santé, Éducation...">
        </div>
    </div>
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Description</label>
        <textarea name="description" rows="3" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-mja-blue resize-none">{{ old('description', $resource->description ?? '') }}</textarea>
    </div>
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Fichier à télécharger</label>
        <input type="file" name="fichier" class="w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-mja-blue/10 file:text-mja-blue file:font-semibold">
        @isset($resource) @if($resource->fichier)<p class="text-xs text-gray-400 mt-1">Fichier actuel : {{ basename($resource->fichier) }}</p>@endif @endisset
    </div>
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">OU Lien externe (URL)</label>
        <input type="url" name="lien_externe" value="{{ old('lien_externe', $resource->lien_externe ?? '') }}"
            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-mja-blue" placeholder="https://...">
    </div>
    <div class="grid grid-cols-2 gap-5 items-center">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Ordre d'affichage</label>
            <input type="number" name="ordre" value="{{ old('ordre', $resource->ordre ?? 0) }}" min="0"
                class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-mja-blue">
        </div>
        <div class="flex items-center gap-3 pt-4">
            <input type="hidden" name="actif" value="0">
            <input type="checkbox" name="actif" id="actif" value="1" {{ old('actif', ($resource->actif ?? true) ? '1' : '0') == '1' ? 'checked' : '' }} class="w-5 h-5 rounded text-mja-blue cursor-pointer">
            <label for="actif" class="text-sm font-semibold text-gray-700 cursor-pointer">Afficher cette ressource</label>
        </div>
    </div>
</div>
