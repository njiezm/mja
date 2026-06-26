<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-5">
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Titre <span class="text-red-500">*</span></label>
        <input type="text" name="titre" value="{{ old('titre', $article->titre ?? '') }}" required
            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-mja-blue"
            placeholder="Titre de l'article">
    </div>
    <div class="grid grid-cols-2 gap-5">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Catégorie <span class="text-red-500">*</span></label>
            <select name="categorie" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-mja-blue">
                @foreach(['actualite'=>'Actualité','projet'=>'Projet','sante'=>'Santé','education'=>'Éducation','evenement'=>'Événement','autre'=>'Autre'] as $key => $label)
                <option value="{{ $key }}" {{ old('categorie', $article->categorie ?? 'actualite') === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Auteur</label>
            <input type="text" name="auteur" value="{{ old('auteur', $article->auteur ?? '') }}"
                class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-mja-blue"
                placeholder="Nom de l'auteur">
        </div>
    </div>
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Extrait / Résumé</label>
        <textarea name="extrait" rows="2"
            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-mja-blue resize-none"
            placeholder="Courte description affichée dans les listes...">{{ old('extrait', $article->extrait ?? '') }}</textarea>
    </div>
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Contenu <span class="text-red-500">*</span></label>
        <textarea name="contenu" rows="12"
            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-mja-blue resize-y"
            placeholder="Contenu complet de l'article...">{{ old('contenu', $article->contenu ?? '') }}</textarea>
    </div>
    <div class="grid grid-cols-2 gap-5 items-end">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Image de couverture</label>
            <input type="file" name="image" accept="image/*" class="w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-mja-blue/10 file:text-mja-blue file:font-semibold hover:file:bg-mja-blue/20">
            @isset($article)
            @if($article->image)
            <div class="mt-2"><img src="{{ asset('storage/'.$article->image) }}" class="w-24 h-16 rounded-lg object-cover border"></div>
            @endif
            @endisset
        </div>
        <div class="space-y-3">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Date de publication</label>
                <input type="datetime-local" name="publie_le"
                    value="{{ old('publie_le', isset($article) && $article->publie_le ? $article->publie_le->format('Y-m-d\TH:i') : '') }}"
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-mja-blue">
                <p class="text-xs text-gray-400 mt-1">Laisser vide = date du jour à la publication.</p>
            </div>
            <div class="flex items-center gap-3">
                <input type="hidden" name="publie" value="0">
                <input type="checkbox" name="publie" id="publie" value="1" {{ old('publie', ($article->publie ?? false) ? '1' : '0') == '1' ? 'checked' : '' }}
                    class="w-5 h-5 rounded text-mja-blue cursor-pointer">
                <label for="publie" class="text-sm font-semibold text-gray-700 cursor-pointer">Publier cet article</label>
            </div>
        </div>
    </div>
</div>
