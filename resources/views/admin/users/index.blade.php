@extends('layouts.admin')
@section('title', 'Comptes administrateurs')
@section('page-title', 'Comptes administrateurs')
@section('content')
<div class="flex items-center justify-between mb-6 mt-4">
    <p class="text-gray-500 text-sm">{{ $users->count() }} compte(s)</p>
    <a href="{{ route('admin.users.create') }}" class="bg-mja-blue hover:bg-mja-bluedark text-white font-semibold text-sm px-5 py-2.5 rounded-xl transition-colors flex items-center gap-2">
        <i class="fas fa-plus"></i> Ajouter un compte
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-gray-100 bg-gray-50">
                <th class="text-left px-6 py-3 font-display font-bold text-gray-500 text-xs uppercase tracking-wider">Nom</th>
                <th class="text-left px-6 py-3 font-display font-bold text-gray-500 text-xs uppercase tracking-wider">Email</th>
                <th class="text-left px-6 py-3 font-display font-bold text-gray-500 text-xs uppercase tracking-wider">Rôle</th>
                <th class="text-left px-6 py-3 font-display font-bold text-gray-500 text-xs uppercase tracking-wider">Créé le</th>
                <th class="px-6 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @foreach($users as $u)
            <tr class="hover:bg-gray-50 transition-colors {{ $u->id === auth()->id() ? 'bg-blue-50/30' : '' }}">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center font-display font-black text-sm shrink-0
                            {{ $u->isSuperAdmin() ? 'bg-mja-yellow/20 text-yellow-700' : 'bg-mja-blue/10 text-mja-blue' }}">
                            {{ strtoupper(substr($u->name, 0, 1)) }}
                        </div>
                        <div>
                            <div class="font-display font-bold text-gray-900">{{ $u->name }}</div>
                            @if($u->id === auth()->id())
                            <div class="text-xs text-mja-blue font-semibold">Vous</div>
                            @endif
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 text-gray-600">{{ $u->email }}</td>
                <td class="px-6 py-4">
                    @if($u->isSuperAdmin())
                    <span class="inline-flex items-center gap-1.5 bg-mja-yellow/15 text-yellow-700 font-display font-bold text-xs px-3 py-1 rounded-full">
                        <i class="fas fa-crown text-[10px]"></i> Super Admin
                    </span>
                    @else
                    <span class="inline-flex items-center gap-1.5 bg-mja-blue/10 text-mja-blue font-display font-bold text-xs px-3 py-1 rounded-full">
                        <i class="fas fa-user-shield text-[10px]"></i> Admin
                    </span>
                    @endif
                </td>
                <td class="px-6 py-4 text-gray-400 text-xs">{{ $u->created_at->locale('fr')->isoFormat('D MMM Y') }}</td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-2 justify-end">
                        <a href="{{ route('admin.users.edit', $u) }}"
                           class="w-8 h-8 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center transition-colors"
                           title="Modifier">
                            <i class="fas fa-edit text-xs"></i>
                        </a>
                        @if($u->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.users.destroy', $u) }}" data-confirm="Supprimer le compte de {{ $u->name }} ?">
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
@endsection
