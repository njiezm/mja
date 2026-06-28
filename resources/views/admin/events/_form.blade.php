<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-5">
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Titre <span class="text-red-500">*</span></label>
        <input type="text" name="titre" value="{{ old('titre', $event->titre ?? '') }}" required
            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-mja-blue">
    </div>
    <div class="grid grid-cols-2 gap-5">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Date & heure de début <span class="text-red-500">*</span></label>
            <input type="datetime-local" name="date_debut" value="{{ old('date_debut', isset($event->date_debut) ? $event->date_debut->format('Y-m-d\TH:i') : '') }}" required
                class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-mja-blue">
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Date & heure de fin</label>
            <input type="datetime-local" name="date_fin" value="{{ old('date_fin', isset($event->date_fin) ? $event->date_fin->format('Y-m-d\TH:i') : '') }}"
                class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-mja-blue">
        </div>
    </div>
    <div class="grid grid-cols-2 gap-5">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Lieu</label>
            <input type="text" name="lieu" value="{{ old('lieu', $event->lieu ?? '') }}"
                class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-mja-blue" placeholder="Salle, parc...">
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Adresse</label>
            <input type="text" name="adresse" value="{{ old('adresse', $event->adresse ?? '') }}"
                class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-mja-blue">
        </div>
    </div>
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Description courte</label>
        <textarea name="description_courte" rows="2" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-mja-blue resize-none">{{ old('description_courte', $event->description_courte ?? '') }}</textarea>
    </div>
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Description complète</label>
        <textarea name="description" rows="8" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-mja-blue resize-y">{{ old('description', $event->description ?? '') }}</textarea>
    </div>
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Lien d'inscription (URL)</label>
        <input type="url" name="lien_inscription" value="{{ old('lien_inscription', $event->lien_inscription ?? '') }}"
            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-mja-blue" placeholder="https://...">
    </div>
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Image</label>
        <input type="file" name="image" accept="image/*" class="w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-mja-blue/10 file:text-mja-blue file:font-semibold">
        @isset($event) @if($event->image)<div class="mt-2"><img src="{{ asset('storage/'.$event->image) }}" class="w-24 h-16 rounded-lg object-cover border"></div>@endif @endisset
    </div>
    <div class="flex flex-wrap items-start gap-6">
        <div>
            <div class="flex items-center gap-3 mb-3">
                <input type="hidden" name="gratuit" value="0">
                <input type="checkbox" name="gratuit" id="gratuit" value="1"
                    {{ old('gratuit', ($event->gratuit ?? true) ? '1' : '0') == '1' ? 'checked' : '' }}
                    class="w-5 h-5 rounded text-mja-blue cursor-pointer">
                <label for="gratuit" class="text-sm font-semibold text-gray-700 cursor-pointer">Entrée gratuite</label>
            </div>
            <div id="prix-field" class="{{ old('gratuit', ($event->gratuit ?? true) ? '1' : '0') == '1' ? 'hidden' : '' }}">
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Prix (€) <span class="text-red-500">*</span></label>
                <div class="relative">
                    <input type="number" name="prix" id="prix" step="0.01" min="0"
                        value="{{ old('prix', $event->prix ?? '') }}"
                        class="w-40 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-mja-blue pr-10"
                        placeholder="Ex : 5.00">
                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 font-bold text-sm">€</span>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <input type="hidden" name="publie" value="0">
            <input type="checkbox" name="publie" id="publie" value="1" {{ old('publie', ($event->publie ?? false) ? '1' : '0') == '1' ? 'checked' : '' }} class="w-5 h-5 rounded text-mja-blue cursor-pointer">
            <label for="publie" class="text-sm font-semibold text-gray-700 cursor-pointer">Publier</label>
        </div>
    </div>
    <script>
    document.getElementById('gratuit').addEventListener('change', function() {
        var pf = document.getElementById('prix-field');
        var px = document.getElementById('prix');
        if (this.checked) {
            pf.classList.add('hidden');
            px.value = '';
        } else {
            pf.classList.remove('hidden');
            px.focus();
        }
    });
    </script>
</div>
