@extends('layouts.app')
@section('title', "Trombinoscope — Espace membre MJA")

@section('content')
<section class="hero-gradient text-white py-12">
    <div class="max-w-5xl mx-auto px-4 flex items-center justify-between gap-4">
        <div>
            <h1 class="font-display font-black text-2xl sm:text-3xl">Trombinoscope</h1>
            <p class="text-gray-300 text-sm mt-1">Les adhérents de Madin'Jeunes Ambition ({{ $membres->count() }})</p>
        </div>
        <a href="{{ route('member.dashboard') }}" class="text-gray-300 hover:text-white text-sm font-display font-semibold shrink-0"><i class="fas fa-arrow-left mr-1"></i> Mon espace</a>
    </div>
</section>
<div class="flex h-1"><div class="flex-1 bg-mja-blue"></div><div class="flex-1 bg-mja-yellow"></div><div class="flex-1 bg-mja-red"></div></div>

<section class="py-12">
    <div class="max-w-5xl mx-auto px-4">
        @if($membres->isEmpty())
        <div class="bg-white rounded-2xl border border-gray-100 p-12 text-center text-gray-400">
            <i class="fas fa-users text-3xl mb-3"></i>
            <p>Aucun adhérent ne figure encore dans le trombinoscope.</p>
        </div>
        @else
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-5">
            @foreach($membres as $m)
            @php $a = $m->adhesion; @endphp
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 text-center card-hover {{ $me && $m->id === $me->id ? 'ring-2 ring-mja-blue' : '' }}">
                @if($a->photo)
                <img src="{{ Storage::url($a->photo) }}" alt="{{ $a->prenom }}" class="w-20 h-20 rounded-2xl object-cover mx-auto mb-3">
                @else
                <div class="w-20 h-20 rounded-2xl bg-mja-blue/10 text-mja-blue flex items-center justify-center text-2xl font-display font-black mx-auto mb-3">
                    {{ strtoupper(substr($a->prenom, 0, 1)) }}
                </div>
                @endif
                <div class="font-display font-bold text-mja-gray text-sm leading-tight">
                    {{ $a->prenom }} {{ strtoupper(substr($a->nom, 0, 1)) }}.
                </div>
                @if($me && $m->id === $me->id)<div class="text-[11px] text-mja-blue font-semibold mt-0.5">Vous</div>@endif
            </div>
            @endforeach
        </div>
        <p class="text-xs text-gray-400 text-center mt-8">
            <i class="fas fa-lock mr-1"></i> Cet annuaire n'est visible que par les adhérents connectés. Vous pouvez retirer votre profil depuis « Modifier mes informations ».
        </p>
        @endif
    </div>
</section>
@endsection
