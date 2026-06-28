@extends('layouts.admin')
@section('title', 'Ajouter un compte admin')
@section('page-title', 'Ajouter un compte administrateur')
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
        <form method="POST" action="{{ route('admin.users.store') }}" class="p-6 space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nom complet <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-mja-blue @error('name') border-red-400 @enderror"
                    placeholder="Ex : Marie Dupont">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Adresse email <span class="text-red-500">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-mja-blue @error('email') border-red-400 @enderror"
                    placeholder="admin@exemple.fr">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Rôle <span class="text-red-500">*</span></label>
                <select name="role" required class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-mja-blue">
                    <option value="admin"       {{ old('role','admin') === 'admin'       ? 'selected' : '' }}>Administrateur</option>
                    <option value="super_admin" {{ old('role') === 'super_admin' ? 'selected' : '' }}>Super Administrateur</option>
                </select>
            </div>
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-sm font-semibold text-gray-700">Mot de passe <span class="text-red-500">*</span></label>
                    <button type="button" onclick="generatePassword()" class="text-xs text-mja-blue hover:text-mja-bluedark font-display font-bold flex items-center gap-1 transition-colors">
                        <i class="fas fa-wand-magic-sparkles text-[10px]"></i> Suggérer un mot de passe
                    </button>
                </div>
                <div class="relative">
                    <input type="password" name="password" id="pw" required minlength="8"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-mja-blue pr-20 @error('password') border-red-400 @enderror"
                        placeholder="Minimum 8 caractères">
                    <div class="absolute right-1 top-1/2 -translate-y-1/2 flex items-center gap-0.5">
                        <button type="button" id="copyBtn" onclick="copyPassword()" title="Copier" class="hidden w-7 h-7 text-gray-400 hover:text-mja-blue flex items-center justify-center transition-colors">
                            <i id="copyIcon" class="fas fa-copy text-xs"></i>
                        </button>
                        <button type="button" onclick="togglePw('pw','eyePw')" class="w-7 h-7 text-gray-400 hover:text-gray-600 flex items-center justify-center">
                            <i id="eyePw" class="fas fa-eye text-sm"></i>
                        </button>
                    </div>
                </div>
                <div id="pw-strength" class="hidden mt-2 h-1.5 rounded-full bg-gray-100 overflow-hidden">
                    <div id="pw-strength-bar" class="h-full rounded-full transition-all duration-300"></div>
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Confirmer le mot de passe <span class="text-red-500">*</span></label>
                <div class="relative">
                    <input type="password" name="password_confirmation" id="pw2" required minlength="8"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-mja-blue pr-12">
                    <button type="button" onclick="togglePw('pw2','eyePw2')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 p-1">
                        <i id="eyePw2" class="fas fa-eye text-sm"></i>
                    </button>
                </div>
            </div>

            <div class="bg-mja-blue/5 rounded-xl p-4 flex items-start gap-3 border border-mja-blue/20">
                <input type="checkbox" name="send_mail" id="send_mail" value="1" {{ old('send_mail') ? 'checked' : '' }}
                    class="mt-0.5 w-5 h-5 rounded text-mja-blue cursor-pointer shrink-0">
                <label for="send_mail" class="cursor-pointer">
                    <div class="text-sm font-display font-bold text-mja-dark">Envoyer un email de notification</div>
                    <div class="text-xs text-gray-500 mt-0.5">Un email avec les identifiants de connexion sera envoyé à l'adresse indiquée.</div>
                </label>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="flex-1 bg-mja-blue hover:bg-mja-bluedark text-white font-display font-bold py-3 rounded-xl transition-colors">
                    <i class="fas fa-user-plus mr-2"></i> Créer le compte
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
    var f = document.getElementById(id), e = document.getElementById(eyeId);
    if (f.type === 'password') { f.type = 'text'; e.className = 'fas fa-eye-slash text-sm'; }
    else { f.type = 'password'; e.className = 'fas fa-eye text-sm'; }
}

function generatePassword() {
    var chars = 'abcdefghijkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789!@#$%-_';
    var pw = '';
    var arr = new Uint8Array(14);
    window.crypto.getRandomValues(arr);
    arr.forEach(function(b) { pw += chars[b % chars.length]; });

    var f1 = document.getElementById('pw'), f2 = document.getElementById('pw2');
    f1.value = pw; f2.value = pw;
    f1.type = 'text'; f2.type = 'text';
    document.getElementById('eyePw').className  = 'fas fa-eye-slash text-sm';
    document.getElementById('eyePw2').className = 'fas fa-eye-slash text-sm';
    document.getElementById('copyBtn').classList.remove('hidden');
    updateStrength(pw);
    f1.focus();
}

function copyPassword() {
    var val = document.getElementById('pw').value;
    navigator.clipboard.writeText(val).then(function() {
        var icon = document.getElementById('copyIcon');
        icon.className = 'fas fa-check text-xs text-green-500';
        setTimeout(function() { icon.className = 'fas fa-copy text-xs'; }, 2000);
    });
}

function updateStrength(pw) {
    var score = 0;
    if (pw.length >= 8)  score++;
    if (pw.length >= 12) score++;
    if (/[A-Z]/.test(pw)) score++;
    if (/[0-9]/.test(pw)) score++;
    if (/[^a-zA-Z0-9]/.test(pw)) score++;

    var bar = document.getElementById('pw-strength-bar');
    var wrap = document.getElementById('pw-strength');
    wrap.classList.remove('hidden');
    var colors = ['#ef4444','#f97316','#eab308','#22c55e','#16a34a'];
    bar.style.width = (score * 20) + '%';
    bar.style.background = colors[score - 1] || '#ef4444';
}

document.getElementById('pw').addEventListener('input', function() {
    if (this.value) updateStrength(this.value);
    else document.getElementById('pw-strength').classList.add('hidden');
});
</script>
@endsection
