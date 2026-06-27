@extends('layouts.admin')
@section('title', 'Équipe')
@section('page-title', 'Membres de l\'équipe')
@section('content')
<div class="flex items-center justify-between mb-6 mt-4">
    <p class="text-gray-500 text-sm">{{ $members->total() }} membre(s)</p>
    <a href="{{ route('admin.team.create') }}" class="bg-mja-blue hover:bg-mja-bluedark text-white font-semibold text-sm px-5 py-2.5 rounded-xl transition-colors flex items-center gap-2">
        <i class="fas fa-plus"></i> Ajouter un membre
    </a>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
    @forelse($members as $member)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-start gap-4">
        <div class="w-14 h-14 rounded-xl overflow-hidden flex-shrink-0 bg-mja-blue/10">
            @if($member->photo)
            <img src="{{ asset('storage/'.$member->photo) }}" class="w-full h-full object-cover">
            @else
            <div class="w-full h-full flex items-center justify-center text-mja-blue font-bold text-lg">
                {{ strtoupper(substr($member->prenom, 0, 1).substr($member->nom, 0, 1)) }}
            </div>
            @endif
        </div>
        <div class="flex-1 min-w-0">
            <div class="font-display font-bold text-gray-900">{{ $member->nom_complet }}</div>
            <div class="text-mja-blue text-xs font-semibold mt-0.5">{{ $member->role }}</div>
            <div class="flex items-center gap-1 mt-1">
                <span class="w-1.5 h-1.5 rounded-full {{ $member->actif ? 'bg-green-500' : 'bg-gray-300' }}"></span>
                <span class="text-xs text-gray-400">{{ $member->actif ? 'Actif' : 'Inactif' }}</span>
            </div>
        </div>
        <div class="flex flex-col gap-1 flex-shrink-0">
            <a href="{{ route('admin.team.edit', $member) }}" class="w-8 h-8 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center"><i class="fas fa-edit text-xs"></i></a>
            <form method="POST" action="{{ route('admin.team.destroy', $member) }}" data-confirm="Supprimer ?">
                @csrf @method('DELETE')
                <button class="w-8 h-8 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg flex items-center justify-center"><i class="fas fa-trash text-xs"></i></button>
            </form>
        </div>
    </div>
    @empty
    <div class="col-span-3 text-center py-16 text-gray-400">
        <i class="fas fa-users text-5xl mb-4 block"></i>
        Aucun membre. <a href="{{ route('admin.team.create') }}" class="text-mja-blue underline">Ajouter le premier</a>
    </div>
    @endforelse
</div>
@if($members->hasPages())<div class="mt-6">{{ $members->links() }}</div>@endif
@endsection
