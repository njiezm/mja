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
        <x-editeur-riche name="description" :value="$project->description ?? null" :rows="10" />
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

    {{-- Rattachement des événements. Un projet en accepte de zéro à N ;
         un événement, lui, n'appartient qu'à un seul projet. --}}
    @isset($evenements)
    <div class="border-t border-gray-100 pt-5">
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Événements liés à ce projet</label>
        <p class="text-[11px] text-gray-400 mb-3">
            Ils s'afficheront sur la page publique du projet, les prochains d'abord.
            Un événement déjà rattaché à un autre projet est signalé : le cocher ici le déplacera.
        </p>

        @php
            $dejaLies = old('evenements', isset($project) ? $project->events->pluck('id')->all() : []);
            $aVenir   = $evenements->filter(fn ($e) => $e->date_debut && $e->date_debut->isFuture());
            $passes   = $evenements->reject(fn ($e) => $e->date_debut && $e->date_debut->isFuture());
        @endphp

        @if($evenements->isEmpty())
        <p class="text-sm text-gray-400 italic">Aucun événement enregistré pour l'instant.</p>
        @else
        <div class="max-h-72 overflow-y-auto border border-gray-100 rounded-xl divide-y divide-gray-50">
            @foreach([['À venir', $aVenir], ['Passés', $passes]] as [$titreGroupe, $groupe])
                @if($groupe->count())
                <div class="px-4 py-2 bg-gray-50 text-[11px] font-bold uppercase tracking-wider text-gray-500">
                    {{ $titreGroupe }} <span class="font-normal">({{ $groupe->count() }})</span>
                </div>
                    @foreach($groupe as $ev)
                    @php $prisAilleurs = $ev->project_id && ! in_array($ev->id, (array) $dejaLies, true); @endphp
                    <label class="flex items-center gap-3 px-4 py-2.5 hover:bg-gray-50 cursor-pointer">
                        <input type="checkbox" name="evenements[]" value="{{ $ev->id }}"
                               {{ in_array($ev->id, (array) $dejaLies) ? 'checked' : '' }}
                               class="w-4 h-4 rounded text-mja-blue cursor-pointer shrink-0">
                        <span class="text-xs font-mono text-gray-400 w-20 shrink-0">
                            {{ $ev->date_debut?->format('d/m/Y') ?? '—' }}
                        </span>
                        <span class="text-sm text-gray-800 flex-1 truncate">{{ $ev->titre }}</span>
                        @unless($ev->publie)
                        <span class="text-[10px] font-bold uppercase bg-amber-50 text-amber-700 rounded-full px-2 py-0.5 shrink-0">Brouillon</span>
                        @endunless
                        @if($prisAilleurs)
                        <span class="text-[10px] font-bold uppercase bg-gray-100 text-gray-500 rounded-full px-2 py-0.5 shrink-0">Autre projet</span>
                        @endif
                    </label>
                    @endforeach
                @endif
            @endforeach
        </div>
        @endif
    </div>
    @endisset
</div>
