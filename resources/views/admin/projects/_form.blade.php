<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-5">
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Titre <span class="text-red-500">*</span></label>
        <input type="text" name="titre" value="{{ old('titre', $project->titre ?? '') }}" required
            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-mja-blue"
            placeholder="Nom du projet">
    </div>
    <div class="grid grid-cols-2 gap-5">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Statut <span class="text-red-500">*</span></label>
            <select name="statut" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-mja-blue">
                <option value="en_cours" {{ old('statut', $project->statut ?? 'en_cours') === 'en_cours' ? 'selected' : '' }}>En cours</option>
                <option value="a_venir" {{ old('statut', $project->statut ?? '') === 'a_venir' ? 'selected' : '' }}>À venir</option>
                <option value="termine" {{ old('statut', $project->statut ?? '') === 'termine' ? 'selected' : '' }}>Terminé</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Ordre d'affichage</label>
            <input type="number" name="ordre" value="{{ old('ordre', $project->ordre ?? 0) }}" min="0"
                class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-mja-blue">
        </div>
    </div>
    <div class="grid grid-cols-2 gap-5">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Date de début</label>
            <input type="date" name="date_debut" value="{{ old('date_debut', isset($project->date_debut) ? $project->date_debut->format('Y-m-d') : '') }}"
                class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-mja-blue">
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Date de fin</label>
            <input type="date" name="date_fin" value="{{ old('date_fin', isset($project->date_fin) ? $project->date_fin->format('Y-m-d') : '') }}"
                class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-mja-blue">
        </div>
    </div>
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Description courte</label>
        <textarea name="description_courte" rows="2"
            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-mja-blue resize-none"
            placeholder="Résumé en quelques phrases...">{{ old('description_courte', $project->description_courte ?? '') }}</textarea>
    </div>
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Description complète</label>
        <textarea name="description" rows="10"
            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-mja-blue resize-y">{{ old('description', $project->description ?? '') }}</textarea>
    </div>
    <div class="grid grid-cols-2 gap-5 items-end">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Image</label>
            <input type="file" name="image" accept="image/*" class="w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-mja-blue/10 file:text-mja-blue file:font-semibold hover:file:bg-mja-blue/20">
            @isset($project) @if($project->image)<div class="mt-2"><img src="{{ asset('storage/'.$project->image) }}" class="w-24 h-16 rounded-lg object-cover border"></div>@endif @endisset
        </div>
        <div class="flex items-center gap-3 pb-1">
            <input type="hidden" name="actif" value="0">
            <input type="checkbox" name="actif" id="actif" value="1" {{ old('actif', ($project->actif ?? true) ? '1' : '0') == '1' ? 'checked' : '' }} class="w-5 h-5 rounded text-mja-blue cursor-pointer">
            <label for="actif" class="text-sm font-semibold text-gray-700 cursor-pointer">Afficher ce projet</label>
        </div>
    </div>
</div>
