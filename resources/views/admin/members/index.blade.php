@extends('layouts.admin')
@section('title', 'Comptes adhérents')
@section('page-title', 'Comptes adhérents')
@section('content')

<div class="flex flex-wrap items-center justify-between gap-3 mb-6 mt-4">
    <p class="text-gray-500 text-sm">
        {{ $comptes->total() }} compte(s) espace adhérent
        @if($sansCompte->total() > 0)
        · <span class="text-amber-600 font-semibold">{{ $sansCompte->total() }} adhérent(s) payé(s) sans compte</span>
        @endif
    </p>
    @if($peutVoirMotsDePasse)
    <a href="{{ route('admin.members.export') }}" class="bg-mja-blue hover:bg-mja-bluedark text-white font-semibold text-sm px-5 py-2.5 rounded-xl transition-colors flex items-center gap-2">
        <i class="fas fa-file-csv"></i> Exporter
    </a>
    @endif
</div>

@if($illisibles > 0)
<div class="bg-amber-50 border border-amber-100 rounded-2xl px-5 py-4 mb-6 text-sm text-amber-900 flex gap-3">
    <i class="fas fa-triangle-exclamation mt-0.5 shrink-0"></i>
    <div>
        <strong>{{ $illisibles }} mot(s) de passe non lisible(s).</strong>
        Ces comptes ont été créés avant cette page : seule l'empreinte du mot de passe est stockée, il est donc
        impossible de le relire. Deux options, <em>sans</em> couper l'accès des adhérents :
        <ul class="list-disc list-inside mt-2 space-y-1">
            <li>si le CSV du seeder est encore sur ce serveur, lancez
                <code class="font-mono text-xs bg-white/70 rounded px-1.5 py-0.5">php artisan mja:mdp-membres --importer-csv</code>
                — il rend lisibles les mots de passe <strong>existants</strong> sans les modifier ;</li>
            <li>sinon, régénérez au cas par cas avec la clé <i class="fas fa-key text-[10px]"></i> ci-dessous
                (l'ancien mot de passe cesse alors de fonctionner).</li>
        </ul>
    </div>
</div>
@endif

<form method="GET" class="mb-5">
    <div class="relative max-w-md">
        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 text-sm"></i>
        <input type="search" name="q" value="{{ $recherche }}" placeholder="Nom, prénom ou email…"
               class="w-full pl-11 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-mja-blue/30 focus:border-mja-blue">
    </div>
</form>

@if($comptes->isEmpty())
<div class="bg-white rounded-2xl border border-gray-100 p-12 text-center text-gray-400">
    <i class="fas fa-id-badge text-3xl mb-3"></i>
    <p>Aucun compte adhérent{{ $recherche ? ' pour cette recherche' : '' }}.</p>
</div>
@else
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-x-auto">
    <table class="w-full text-sm min-w-[880px]">
        <thead>
            <tr class="border-b border-gray-100 bg-gray-50">
                <th class="text-left px-6 py-3 font-display font-bold text-gray-500 text-xs uppercase tracking-wider">Adhérent</th>
                <th class="text-left px-6 py-3 font-display font-bold text-gray-500 text-xs uppercase tracking-wider">Email</th>
                <th class="text-left px-6 py-3 font-display font-bold text-gray-500 text-xs uppercase tracking-wider">Mot de passe</th>
                <th class="text-left px-6 py-3 font-display font-bold text-gray-500 text-xs uppercase tracking-wider">Rôle</th>
                <th class="text-left px-6 py-3 font-display font-bold text-gray-500 text-xs uppercase tracking-wider">Trombinoscope</th>
                <th class="px-6 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @foreach($comptes as $m)
            @php
                $a   = $m->adhesion;
                $pwd = $m->getDecryptedPassword();
                $nom = $a ? $a->prenom . ' ' . $a->nom : $m->email;
            @endphp
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        @if($a?->photo)
                        <img src="{{ Storage::url($a->photo) }}" alt="" class="w-9 h-9 rounded-xl object-cover shrink-0">
                        @else
                        <div class="w-9 h-9 rounded-xl bg-mja-blue/10 text-mja-blue flex items-center justify-center font-display font-black text-sm shrink-0">
                            {{ mb_strtoupper(mb_substr($a?->prenom ?? '?', 0, 1)) }}
                        </div>
                        @endif
                        <div>
                                    <div class="font-display font-bold text-gray-900">{{ $a?->prenom }} {{ $a?->nom }}
                                @if($m->canAccessBackOffice())
                                <span class="ml-1 align-middle inline-flex items-center gap-1 bg-mja-yellow/15 text-yellow-700 text-[10px] font-display font-bold px-2 py-0.5 rounded-full" title="Ce compte a aussi accès à l'administration">
                                    <i class="fas fa-sliders text-[9px]"></i> {{ $m->roleLabel() }}
                                </span>
                                @endif
                            </div>
                            @if($a?->period)<div class="text-xs text-gray-400">{{ $a->period->label }}</div>@endif
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 text-gray-600">{{ $m->email }}</td>
                <td class="px-6 py-4">
                    @if(! $peutVoirMotsDePasse)
                    <span class="text-xs text-gray-400 italic" title="Réservé au super administrateur">masqué</span>
                    @elseif($pwd)
                    <div class="flex items-center gap-2">
                        <code class="pwd-mask font-mono text-xs bg-gray-100 rounded px-2 py-1 text-gray-700" data-pwd="{{ $pwd }}">••••••••</code>
                        <button type="button" onclick="togglePwd(this)" class="text-gray-400 hover:text-mja-blue transition-colors" title="Afficher / masquer">
                            <i class="fas fa-eye text-xs"></i>
                        </button>
                        <button type="button" onclick="copyPwd(this)" data-pwd="{{ $pwd }}" class="text-gray-400 hover:text-mja-blue transition-colors" title="Copier">
                            <i class="fas fa-copy text-xs"></i>
                        </button>
                    </div>
                    @else
                    <span class="text-xs text-gray-400 italic">non lisible — régénérer</span>
                    @endif
                </td>
                <td class="px-6 py-4">
                    {{-- Nommer un adhérent gestionnaire ou administrateur : son compte
                         existe déjà, on ne fait qu'élargir ses droits. --}}
                    @php $rolesPossibles = auth()->user()->assignableRolesForMember(); @endphp
                    @if($rolesPossibles && ! auth()->user()->is($m) && auth()->user()->canManage($m))
                    <form method="POST" action="{{ route('admin.members.role', $m) }}" class="flex items-center gap-1.5">
                        @csrf @method('PATCH')
                        <select name="role" class="border border-gray-200 rounded-lg px-2 py-1 text-xs focus:outline-none focus:ring-2 focus:ring-mja-blue">
                            @foreach($rolesPossibles as $cle)
                            <option value="{{ $cle }}" @selected($m->role === $cle)>{{ \App\Models\User::ROLES[$cle] }}</option>
                            @endforeach
                        </select>
                        <button class="w-7 h-7 bg-gray-100 hover:bg-mja-blue hover:text-white text-gray-600 rounded-lg flex items-center justify-center transition-colors" title="Enregistrer le rôle">
                            <i class="fas fa-check text-[10px]"></i>
                        </button>
                    </form>
                    @else
                    <span class="text-xs text-gray-500">{{ $m->roleLabel() }}</span>
                    @endif
                </td>
                <td class="px-6 py-4">
                    <form method="POST" action="{{ route('admin.members.toggle-directory', $m) }}">
                        @csrf @method('PATCH')
                        <button class="inline-flex items-center gap-1.5 font-display font-bold text-xs px-3 py-1 rounded-full transition-colors {{ $m->show_in_directory ? 'bg-green-50 text-green-700 hover:bg-green-100' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}"
                                title="{{ $m->show_in_directory ? 'Retirer du trombinoscope' : 'Afficher dans le trombinoscope' }}">
                            <i class="fas {{ $m->show_in_directory ? 'fa-eye' : 'fa-eye-slash' }} text-[10px]"></i>
                            {{ $m->show_in_directory ? 'Visible' : 'Masqué' }}
                        </button>
                    </form>
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-2 justify-end">
                        @if($a)
                        <a href="{{ route('admin.adhesions.show', $a) }}"
                           class="w-8 h-8 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg flex items-center justify-center transition-colors" title="Voir la fiche d'adhésion">
                            <i class="fas fa-eye text-xs"></i>
                        </a>
                        @if($a->isAdherent())
                        <a href="{{ route('admin.adhesions.carte', $a) }}" target="_blank" rel="noopener"
                           class="w-8 h-8 bg-mja-blue/10 hover:bg-mja-blue/20 text-mja-blue rounded-lg flex items-center justify-center transition-colors"
                           title="Attestation et carte de membre">
                            <i class="fas fa-id-card text-xs"></i>
                        </a>
                        @endif
                        @endif
                        <form method="POST" action="{{ route('admin.members.reset-password', $m) }}"
                              data-confirm="Générer un nouveau mot de passe pour {{ $nom }} ? L'ancien cessera de fonctionner.">
                            @csrf @method('PATCH')
                            <button class="w-8 h-8 bg-amber-50 hover:bg-amber-100 text-amber-600 rounded-lg flex items-center justify-center transition-colors" title="Régénérer le mot de passe">
                                <i class="fas fa-key text-xs"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @if($comptes->hasPages())<div class="px-6 py-4 border-t border-gray-50">{{ $comptes->links() }}</div>@endif
</div>
@endif

@if($sansCompte->isNotEmpty())
<h2 class="font-display font-black text-lg text-gray-900 mt-10 mb-1">Adhérents sans compte</h2>
<p class="text-gray-500 text-sm mb-4">Cotisation payée mais aucun accès à l'espace adhérent. Générer un compte crée aussi son mot de passe.</p>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-x-auto">
    <table class="w-full text-sm min-w-[620px]">
        <thead>
            <tr class="border-b border-gray-100 bg-gray-50">
                <th class="text-left px-6 py-3 font-display font-bold text-gray-500 text-xs uppercase tracking-wider">Adhérent</th>
                <th class="text-left px-6 py-3 font-display font-bold text-gray-500 text-xs uppercase tracking-wider">Email</th>
                <th class="px-6 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @foreach($sansCompte as $a)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 font-display font-bold text-gray-900">{{ $a->prenom }} {{ $a->nom }}</td>
                <td class="px-6 py-4 text-gray-600">{{ $a->email }}</td>
                <td class="px-6 py-4 text-right">
                    <form method="POST" action="{{ route('admin.members.store') }}"
                          data-confirm="Créer un compte espace adhérent pour {{ $a->prenom }} {{ $a->nom }} ?">
                        @csrf
                        <input type="hidden" name="adhesion_id" value="{{ $a->id }}">
                        <button class="bg-mja-blue hover:bg-mja-bluedark text-white font-semibold text-xs px-4 py-2 rounded-lg transition-colors">
                            <i class="fas fa-user-plus mr-1"></i> Générer le compte
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @if($sansCompte->hasPages())<div class="px-6 py-4 border-t border-gray-50">{{ $sansCompte->links() }}</div>@endif
</div>
@endif

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

function copyPwd(btn) {
    navigator.clipboard.writeText(btn.dataset.pwd).then(function () {
        var icon = btn.querySelector('i');
        icon.classList.replace('fa-copy', 'fa-check');
        setTimeout(function () { icon.classList.replace('fa-check', 'fa-copy'); }, 1200);
    });
}
</script>
@endpush
@endsection
