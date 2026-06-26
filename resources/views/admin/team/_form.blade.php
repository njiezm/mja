<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-5">
    <div class="grid grid-cols-2 gap-5">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Prénom <span class="text-red-500">*</span></label>
            <input type="text" name="prenom" value="{{ old('prenom', $team->prenom ?? '') }}" required
                class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-mja-blue">
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nom <span class="text-red-500">*</span></label>
            <input type="text" name="nom" value="{{ old('nom', $team->nom ?? '') }}" required
                class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-mja-blue">
        </div>
    </div>
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Rôle / Fonction <span class="text-red-500">*</span></label>
        <input type="text" name="role" value="{{ old('role', $team->role ?? '') }}" required
            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-mja-blue" placeholder="Président, Trésorier, Chargé de communication...">
    </div>
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Bio</label>
        <textarea name="bio" rows="4" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-mja-blue resize-none">{{ old('bio', $team->bio ?? '') }}</textarea>
    </div>
    <div class="grid grid-cols-2 gap-5">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email</label>
            <input type="email" name="email" value="{{ old('email', $team->email ?? '') }}"
                class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-mja-blue">
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Ordre d'affichage</label>
            <input type="number" name="ordre" value="{{ old('ordre', $team->ordre ?? 0) }}" min="0"
                class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-mja-blue">
        </div>
    </div>
    <div class="grid grid-cols-2 gap-5 items-end">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Photo</label>
            <input type="file" name="photo" accept="image/*" class="w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-mja-blue/10 file:text-mja-blue file:font-semibold">
            @isset($team) @if($team->photo)<div class="mt-2"><img src="{{ asset('storage/'.$team->photo) }}" class="w-16 h-16 rounded-full object-cover border-2 border-mja-blue/20"></div>@endif @endisset
        </div>
        <div class="flex items-center gap-3 pb-1">
            <input type="hidden" name="actif" value="0">
            <input type="checkbox" name="actif" id="actif" value="1" {{ old('actif', ($team->actif ?? true) ? '1' : '0') == '1' ? 'checked' : '' }} class="w-5 h-5 rounded text-mja-blue cursor-pointer">
            <label for="actif" class="text-sm font-semibold text-gray-700 cursor-pointer">Membre actif</label>
        </div>
    </div>
</div>
