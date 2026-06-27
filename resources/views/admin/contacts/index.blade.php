@extends('layouts.admin')
@section('title', 'Messages')
@section('page-title', 'Messages reçus')
@section('content')
<div class="mb-6 mt-4">
    <p class="text-gray-500 text-sm">{{ $contacts->total() }} message(s) au total — {{ \App\Models\Contact::where('lu', false)->count() }} non lu(s)</p>
</div>
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
            <tr>
                <th class="px-6 py-3 text-left font-semibold w-4"></th>
                <th class="px-4 py-3 text-left font-semibold">De</th>
                <th class="px-4 py-3 text-left font-semibold">Sujet</th>
                <th class="px-4 py-3 text-left font-semibold">Date</th>
                <th class="px-4 py-3 text-center font-semibold">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($contacts as $contact)
            <tr class="hover:bg-gray-50 {{ !$contact->lu ? 'bg-blue-50/40' : '' }}">
                <td class="px-6 py-4">
                    @if(!$contact->lu)<span class="w-2 h-2 bg-blue-500 rounded-full inline-block"></span>@endif
                </td>
                <td class="px-4 py-4">
                    <div class="font-semibold {{ !$contact->lu ? 'text-gray-900' : 'text-gray-600' }}">{{ $contact->nom }}</div>
                    <div class="text-xs text-gray-400">{{ $contact->email }}</div>
                </td>
                <td class="px-4 py-4 {{ !$contact->lu ? 'font-semibold text-gray-800' : 'text-gray-600' }}">{{ $contact->sujet }}</td>
                <td class="px-4 py-4 text-gray-400 text-xs">{{ $contact->created_at->locale('fr')->isoFormat('D MMM Y, H[h]mm') }}</td>
                <td class="px-4 py-4">
                    <div class="flex items-center justify-center gap-2">
                        <a href="{{ route('admin.contacts.show', $contact) }}" class="w-8 h-8 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center"><i class="fas fa-eye text-xs"></i></a>
                        <form method="POST" action="{{ route('admin.contacts.destroy', $contact) }}" data-confirm="Supprimer ce message ?">
                            @csrf @method('DELETE')
                            <button class="w-8 h-8 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg flex items-center justify-center"><i class="fas fa-trash text-xs"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-6 py-12 text-center text-gray-400">Aucun message reçu.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($contacts->hasPages())<div class="px-6 py-4 border-t border-gray-50">{{ $contacts->links() }}</div>@endif
</div>
@endsection
