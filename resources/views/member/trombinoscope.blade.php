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
            @php
                $a = $m->adhesion;
                $nomAffiche = $a->prenom . ' ' . mb_strtoupper(mb_substr($a->nom, 0, 1)) . '.';
                $initiale = mb_strtoupper(mb_substr($a->prenom, 0, 1));
            @endphp
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 text-center card-hover {{ $me && $m->id === $me->id ? 'ring-2 ring-mja-blue' : '' }}">
            <button type="button"
                    class="w-full text-center cursor-pointer focus:outline-none focus:ring-2 focus:ring-mja-blue rounded-xl"
                    data-photo="{{ $a->photo ? Storage::url($a->photo) : '' }}"
                    data-nom="{{ $nomAffiche }}"
                    data-initiale="{{ $initiale }}"
                    data-moi="{{ $me && $m->id === $me->id ? '1' : '' }}"
                    aria-label="Agrandir la photo de {{ $nomAffiche }}">
                @if($a->photo)
                <img src="{{ Storage::url($a->photo) }}" alt="{{ $a->prenom }}" loading="lazy" class="w-20 h-20 rounded-2xl object-cover mx-auto mb-3">
                @else
                <div class="w-20 h-20 rounded-2xl bg-mja-blue/10 text-mja-blue flex items-center justify-center text-2xl font-display font-black mx-auto mb-3">
                    {{ $initiale }}
                </div>
                @endif
                <div class="font-display font-bold text-mja-gray text-sm leading-tight">
                    {{ $nomAffiche }}
                </div>
                @if($me && $m->id === $me->id)<div class="text-[11px] text-mja-blue font-semibold mt-0.5">Vous</div>@endif
            </button>

                {{-- Réseaux partagés par l'adhérent : hors du bouton, un lien ne
                     pouvant pas être imbriqué dans un élément interactif. --}}
                @if($a->reseaux_sociaux)
                <div class="flex justify-center flex-wrap gap-2 mt-2.5">
                    @foreach($a->reseaux_sociaux as $cle => $valeur)
                    @php
                        $meta = \App\Models\Adhesion::RESEAUX[$cle] ?? null;
                        $lien = $meta ? \App\Support\ReseauSocial::url($cle, $valeur) : null;
                    @endphp
                    @if($meta)
                        @if($lien)
                        <a href="{{ $lien }}" target="_blank" rel="noopener noreferrer"
                           title="{{ $meta[0] }} : {{ $meta[2] }}{{ $valeur }}"
                           class="w-7 h-7 rounded-lg bg-gray-50 hover:bg-mja-blue hover:text-white text-gray-500 flex items-center justify-center transition-colors text-xs">
                            <i class="{{ $meta[1] }}" aria-hidden="true"></i>
                        </a>
                        @else
                        <span title="{{ $meta[0] }} : {{ $meta[2] }}{{ $valeur }}"
                              class="w-7 h-7 rounded-lg bg-gray-50 text-gray-400 flex items-center justify-center text-xs">
                            <i class="{{ $meta[1] }}" aria-hidden="true"></i>
                        </span>
                        @endif
                    @endif
                    @endforeach
                </div>
                @endif
            </div>
            @endforeach
        </div>
        <p class="text-xs text-gray-400 text-center mt-8">
            <i class="fas fa-lock mr-1"></i> Cet annuaire n'est visible que par les adhérents connectés. Vous pouvez retirer votre profil depuis « Modifier mes informations ».
        </p>
        @endif
    </div>
</section>

{{-- Modale : photo agrandie --}}
<div id="photo-modal" class="fixed inset-0 z-[60] hidden items-center justify-center px-4" style="background:rgba(10,20,40,.7)"
     role="dialog" aria-modal="true" aria-labelledby="photo-modal-nom">
    <div id="photo-modal-card" class="bg-white rounded-3xl shadow-2xl max-w-sm w-full overflow-hidden transform transition-all duration-200 scale-95 opacity-0">
        <div class="flex h-1"><div class="flex-1 bg-mja-blue"></div><div class="flex-1 bg-mja-yellow"></div><div class="flex-1 bg-mja-red"></div></div>
        <div class="p-7 text-center">
            <img id="photo-modal-img" src="" alt="" class="hidden w-56 h-56 sm:w-64 sm:h-64 rounded-3xl object-cover mx-auto mb-5 bg-gray-100">
            <div id="photo-modal-initiale" class="hidden w-56 h-56 sm:w-64 sm:h-64 rounded-3xl bg-mja-blue/10 text-mja-blue items-center justify-center text-6xl font-display font-black mx-auto mb-5"></div>
            <h3 id="photo-modal-nom" class="font-display font-black text-xl text-mja-gray"></h3>
            <div id="photo-modal-moi" class="hidden text-xs text-mja-blue font-semibold mt-1">Vous</div>
            <button type="button" onclick="closePhotoModal()"
                    class="mt-6 w-full py-3 border-2 border-gray-200 hover:border-gray-300 rounded-xl font-display font-bold text-gray-600 hover:bg-gray-50 transition-colors text-sm">
                Fermer
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    var modal = document.getElementById('photo-modal');
    if (!modal) return;

    var card     = document.getElementById('photo-modal-card');
    var img      = document.getElementById('photo-modal-img');
    var initiale = document.getElementById('photo-modal-initiale');
    var nom      = document.getElementById('photo-modal-nom');
    var moi      = document.getElementById('photo-modal-moi');
    var declencheur = null;

    function openPhotoModal(btn) {
        declencheur = btn;
        var photo = btn.dataset.photo;

        if (photo) {
            img.src = photo;
            img.alt = btn.dataset.nom;
            img.classList.remove('hidden');
            initiale.classList.add('hidden');
            initiale.classList.remove('flex');
        } else {
            img.classList.add('hidden');
            img.removeAttribute('src');
            initiale.textContent = btn.dataset.initiale;
            initiale.classList.remove('hidden');
            initiale.classList.add('flex');
        }

        nom.textContent = btn.dataset.nom;
        moi.classList.toggle('hidden', !btn.dataset.moi);

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        requestAnimationFrame(function () { card.classList.remove('scale-95', 'opacity-0'); });
    }

    window.closePhotoModal = function () {
        card.classList.add('scale-95', 'opacity-0');
        setTimeout(function () {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            if (declencheur) { declencheur.focus(); declencheur = null; }
        }, 200);
    };

    document.querySelectorAll('[data-photo]').forEach(function (btn) {
        btn.addEventListener('click', function () { openPhotoModal(btn); });
    });

    modal.addEventListener('click', function (e) {
        if (e.target === this) window.closePhotoModal();
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) window.closePhotoModal();
    });
})();
</script>
@endpush
