@extends('layouts.admin')
@section('title', 'Message de ' . $contact->nom)
@section('page-title', 'Détail du message')
@section('content')
<div class="max-w-2xl mt-4">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-mja-blue/5 border-b border-gray-100 p-6">
            <div class="flex items-start justify-between">
                <div>
                    <h2 class="font-display font-bold text-gray-900 text-xl">{{ $contact->sujet }}</h2>
                    <p class="text-sm text-gray-500 mt-1">{{ $contact->created_at->locale('fr')->isoFormat('dddd D MMMM Y à H[h]mm') }}</p>
                </div>
                <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $contact->lu ? 'bg-gray-100 text-gray-500' : 'bg-blue-100 text-blue-700' }}">
                    {{ $contact->lu ? 'Lu' : 'Non lu' }}
                </span>
            </div>
        </div>
        <div class="p-6 space-y-5">
            <div class="grid grid-cols-2 gap-5">
                <div>
                    <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Expéditeur</div>
                    <div class="font-semibold text-gray-900">{{ $contact->nom }}</div>
                </div>
                <div>
                    <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Email</div>
                    <a href="mailto:{{ $contact->email }}" class="text-mja-blue hover:underline">{{ $contact->email }}</a>
                </div>
                @if($contact->telephone)
                <div>
                    <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Téléphone</div>
                    <a href="tel:{{ $contact->telephone }}" class="inline-flex items-center gap-2 font-semibold text-mja-blue hover:underline">
                        <span class="text-base leading-none">🇲🇶</span>
                        <i class="fas fa-phone text-xs text-mja-blue"></i>
                        {{ $contact->telephone }}
                    </a>
                </div>
                @endif
            </div>
            <div class="border-t border-gray-100 pt-5">
                <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Message</div>
                <div class="bg-gray-50 rounded-xl p-5 text-gray-700 leading-relaxed text-sm whitespace-pre-wrap">{{ $contact->message }}</div>
            </div>
        </div>
        <div class="px-6 pb-6 flex items-center gap-3">
            <a href="mailto:{{ $contact->email }}?subject=Re: {{ $contact->sujet }}" class="bg-mja-blue hover:bg-mja-bluedark text-white font-semibold px-6 py-2.5 rounded-xl transition-colors text-sm flex items-center gap-2">
                <i class="fas fa-reply"></i> Répondre par email
            </a>
            <a href="{{ route('admin.contacts.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-5 py-2.5 rounded-xl transition-colors text-sm">
                <i class="fas fa-arrow-left mr-1"></i> Retour
            </a>
            <form method="POST" action="{{ route('admin.contacts.destroy', $contact) }}" data-confirm="Supprimer ce message ?" class="ml-auto">
                @csrf @method('DELETE')
                <button class="text-red-500 hover:text-red-700 text-sm font-semibold flex items-center gap-1">
                    <i class="fas fa-trash"></i> Supprimer
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
