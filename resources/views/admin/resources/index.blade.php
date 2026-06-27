@extends('layouts.admin')
@section('title', 'Ressources')
@section('page-title', 'Ressources')
@section('content')
<div class="flex items-center justify-between mb-6 mt-4">
    <p class="text-gray-500 text-sm">{{ $resources->total() }} ressource(s)</p>
    <a href="{{ route('admin.resources.create') }}" class="bg-mja-blue hover:bg-mja-bluedark text-white font-semibold text-sm px-5 py-2.5 rounded-xl transition-colors flex items-center gap-2">
        <i class="fas fa-plus"></i> Nouvelle ressource
    </a>
</div>
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
            <tr>
                <th class="px-6 py-3 text-left font-semibold">Titre</th>
                <th class="px-4 py-3 text-left font-semibold">Type</th>
                <th class="px-4 py-3 text-left font-semibold">Catégorie</th>
                <th class="px-4 py-3 text-left font-semibold">Visible</th>
                <th class="px-4 py-3 text-center font-semibold">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($resources as $resource)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">
                    <div class="font-semibold text-gray-900">{{ $resource->titre }}</div>
                    <div class="text-gray-400 text-xs mt-0.5">{{ Str::limit($resource->description, 60) }}</div>
                </td>
                <td class="px-4 py-4">
                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">{{ $resource->type }}</span>
                </td>
                <td class="px-4 py-4 text-gray-500 text-xs">{{ $resource->categorie ?? '—' }}</td>
                <td class="px-4 py-4">
                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $resource->actif ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                        {{ $resource->actif ? 'Oui' : 'Non' }}
                    </span>
                </td>
                <td class="px-4 py-4">
                    <div class="flex items-center justify-center gap-2">
                        <a href="{{ route('admin.resources.edit', $resource) }}" class="w-8 h-8 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center"><i class="fas fa-edit text-xs"></i></a>
                        <form method="POST" action="{{ route('admin.resources.destroy', $resource) }}" data-confirm="Supprimer ?">
                            @csrf @method('DELETE')
                            <button class="w-8 h-8 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg flex items-center justify-center"><i class="fas fa-trash text-xs"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-6 py-12 text-center text-gray-400">Aucune ressource. <a href="{{ route('admin.resources.create') }}" class="text-mja-blue underline">Créer la première</a></td></tr>
            @endforelse
        </tbody>
    </table>
    @if($resources->hasPages())<div class="px-6 py-4 border-t border-gray-50">{{ $resources->links() }}</div>@endif
</div>
@endsection
