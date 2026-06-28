@extends('layouts.admin')
@section('title', 'Modifier — ' . $user->name)
@section('page-title', 'Modifier le compte')
@section('content')
<div class="max-w-xl mt-4">
    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-4 mb-5 text-sm font-semibold flex items-start gap-2">
        <i class="fas fa-exclamation-circle mt-0.5 shrink-0"></i>
        <div>{{ $errors->first() }}</div>
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="flex h-1"><div class="flex-1 bg-mja-blue"></div><div class="flex-1 bg-mja-yellow"></div><div class="flex-1 bg-mja-red"></div></div>
        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="p-6 space-y-5">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nom complet <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-mja-blue">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Adresse email <span class="text-red-500">*</span></label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-mja-blue">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Rôle <span class="text-red-500">*</span></label>
                <select name="role" required class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-mja-blue">
                    <option value="admin"       {{ old('role', $user->role) === 'admin'       ? 'selected' : '' }}>Administrateur</option>
                    <option value="super_admin" {{ old('role', $user->role) === 'super_admin' ? 'selected' : '' }}>Super Administrateur</option>
                </select>
            </div>

            <div class="border-t border-gray-100 pt-5">
                <p class="text-sm font-display font-bold text-gray-500 mb-4 flex items-center gap-2">
                    <i class="fas fa-lock text-xs"></i> Changer le mot de passe
                    <span class="font-normal text-gray-400">(laisser vide pour ne pas modifier)</span>
                </p>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nouveau mot de passe</label>
                        <div class="relative">
                            <input type="password" name="password" id="pw" minlength="8"
                                class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-mja-blue pr-12"
                                placeholder="Minimum 8 caractères">
                            <button type="button" onclick="togglePw('pw','eyePw')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 p-1">
                                <i id="eyePw" class="fas fa-eye text-sm"></i>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Confirmer</label>
                        <div class="relative">
                            <input type="password" name="password_confirmation" id="pw2" minlength="8"
                                class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-mja-blue pr-12">
                            <button type="button" onclick="togglePw('pw2','eyePw2')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 p-1">
                                <i id="eyePw2" class="fas fa-eye text-sm"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="flex-1 bg-mja-blue hover:bg-mja-bluedark text-white font-display font-bold py-3 rounded-xl transition-colors">
                    <i class="fas fa-save mr-2"></i> Enregistrer
                </button>
                <a href="{{ route('admin.users.index') }}" class="px-5 py-3 border border-gray-200 rounded-xl text-gray-600 hover:bg-gray-50 font-display font-semibold text-sm transition-colors">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
<script>
function togglePw(id, eyeId) {
    var f = document.getElementById(id);
    var e = document.getElementById(eyeId);
    if (f.type === 'password') { f.type = 'text'; e.className = 'fas fa-eye-slash text-sm'; }
    else { f.type = 'password'; e.className = 'fas fa-eye text-sm'; }
}
</script>
@endsection
