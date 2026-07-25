@extends('layouts.admin')
@section('title', 'Comptes')
@section('page-title', 'Comptes')
@section('content')
<div class="flex items-center justify-between mb-6 mt-4">
    <p class="text-gray-500 text-sm">{{ $users->count() }} compte(s)</p>
    <a href="{{ route('admin.users.create') }}" class="bg-mja-blue hover:bg-mja-bluedark text-white font-semibold text-sm px-5 py-2.5 rounded-xl transition-colors flex items-center gap-2">
        <i class="fas fa-plus"></i> Ajouter un compte
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-x-auto">
    <table class="w-full text-sm min-w-[820px]">
        <thead>
            <tr class="border-b border-gray-100 bg-gray-50">
                <th class="text-left px-6 py-3 font-display font-bold text-gray-500 text-xs uppercase tracking-wider">Nom</th>
                <th class="text-left px-6 py-3 font-display font-bold text-gray-500 text-xs uppercase tracking-wider">Email</th>
                <th class="text-left px-6 py-3 font-display font-bold text-gray-500 text-xs uppercase tracking-wider">Rôle</th>
                <th class="text-left px-6 py-3 font-display font-bold text-gray-500 text-xs uppercase tracking-wider">Mot de passe</th>
                <th class="text-left px-6 py-3 font-display font-bold text-gray-500 text-xs uppercase tracking-wider">Statut</th>
                <th class="px-6 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @foreach($users as $u)
            @php
                $badge = match($u->role) {
                    \App\Models\User::ROLE_SUPER_ADMIN => ['bg-mja-yellow/15 text-yellow-700','fa-crown','Super Admin'],
                    \App\Models\User::ROLE_ADMIN       => ['bg-mja-blue/10 text-mja-blue','fa-user-shield','Admin'],
                    default                            => ['bg-gray-100 text-gray-600','fa-pen-nib','Gestionnaire'],
                };
                $pwd = $u->getDecryptedPassword();
            @endphp
            <tr class="hover:bg-gray-50 transition-colors {{ $u->id === auth()->id() ? 'bg-blue-50/30' : '' }} {{ $u->is_active ? '' : 'opacity-60' }}">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center font-display font-black text-sm shrink-0 {{ $badge[0] }}">
                            {{ strtoupper(substr($u->name, 0, 1)) }}
                        </div>
                        <div>
                            <div class="font-display font-bold text-gray-900">{{ $u->name }}</div>
                            @if($u->id === auth()->id())<div class="text-xs text-mja-blue font-semibold">Vous</div>@endif
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 text-gray-600">{{ $u->email }}</td>
                <td class="px-6 py-4">
                    <span class="inline-flex items-center gap-1.5 font-display font-bold text-xs px-3 py-1 rounded-full {{ $badge[0] }}">
                        <i class="fas {{ $badge[1] }} text-[10px]"></i> {{ $badge[2] }}
                    </span>
                </td>
                <td class="px-6 py-4">
                    @if($pwd)
                    <div class="flex items-center gap-2">
                        <code class="pwd-mask font-mono text-xs bg-gray-100 rounded px-2 py-1 text-gray-700" data-pwd="{{ $pwd }}">••••••••</code>
                        <button type="button" onclick="togglePwd(this)" class="text-gray-400 hover:text-mja-blue transition-colors" title="Afficher / masquer">
                            <i class="fas fa-eye text-xs"></i>
                        </button>
                    </div>
                    @else
                    <span class="text-xs text-gray-400 italic">indisponible — réinitialiser</span>
                    @endif
                </td>
                <td class="px-6 py-4">
                    @if($u->is_active)
                    <span class="inline-flex items-center gap-1.5 bg-green-50 text-green-700 font-display font-bold text-xs px-3 py-1 rounded-full">
                        <i class="fas fa-circle text-[7px]"></i> Actif
                    </span>
                    @else
                    <span class="inline-flex items-center gap-1.5 bg-red-50 text-mja-red font-display font-bold text-xs px-3 py-1 rounded-full">
                        <i class="fas fa-ban text-[10px]"></i> Révoqué
                    </span>
                    @endif
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-2 justify-end">
                        <a href="{{ route('admin.users.edit', $u) }}"
                           class="w-8 h-8 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center transition-colors" title="Modifier">
                            <i class="fas fa-edit text-xs"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.users.reset-password', $u) }}" data-confirm="Générer un nouveau mot de passe pour {{ $u->name }} ?">
                            @csrf @method('PATCH')
                            <button class="w-8 h-8 bg-amber-50 hover:bg-amber-100 text-amber-600 rounded-lg flex items-center justify-center transition-colors" title="Réinitialiser le mot de passe">
                                <i class="fas fa-key text-xs"></i>
                            </button>
                        </form>
                        @if($u->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.users.toggle-active', $u) }}"
                              data-confirm="{{ $u->is_active ? 'Révoquer' : 'Réactiver' }} l'accès de {{ $u->name }} ?">
                            @csrf @method('PATCH')
                            <button class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors {{ $u->is_active ? 'bg-gray-100 hover:bg-gray-200 text-gray-600' : 'bg-green-50 hover:bg-green-100 text-green-600' }}"
                                    title="{{ $u->is_active ? 'Révoquer l\'accès' : 'Réactiver' }}">
                                <i class="fas {{ $u->is_active ? 'fa-user-slash' : 'fa-user-check' }} text-xs"></i>
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.users.destroy', $u) }}" data-confirm="Supprimer définitivement le compte de {{ $u->name }} ?">
                            @csrf @method('DELETE')
                            <button class="w-8 h-8 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg flex items-center justify-center transition-colors" title="Supprimer">
                                <i class="fas fa-trash text-xs"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@push('scripts')
<script>
function togglePwd(btn) {
    var code = btn.parentNode.querySelector('.pwd-mask');
    var icon = btn.querySelector('i');
    if (code.textContent === '••••••••') {
        code.textContent = code.dataset.pwd;
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        code.textContent = '••••••••';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}
</script>
@endpush
@endsection
