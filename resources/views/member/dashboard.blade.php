@extends('layouts.app')
@section('title', "Mon espace membre — Madin'Jeunes Ambition")

@section('content')
<section class="hero-gradient text-white py-14">
    <div class="max-w-4xl mx-auto px-4 flex items-center justify-between gap-4">
        <div>
            <h1 class="font-display font-black text-3xl sm:text-4xl mb-1">Mon espace</h1>
            <p class="text-gray-300">Bonjour {{ $adhesion->prenom }} 👋</p>
        </div>
        <form method="POST" action="{{ route('member.logout') }}">
            @csrf
            <button class="bg-white/10 hover:bg-white/20 text-white font-display font-semibold text-sm px-4 py-2 rounded-full transition-colors">
                <i class="fas fa-sign-out-alt mr-1"></i> Déconnexion
            </button>
        </form>
    </div>
</section>
<div class="flex h-1"><div class="flex-1 bg-mja-blue"></div><div class="flex-1 bg-mja-yellow"></div><div class="flex-1 bg-mja-red"></div></div>

<section class="py-14">
    <div class="max-w-4xl mx-auto px-4">
        @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 rounded-xl p-4 mb-6 text-sm font-display font-semibold flex items-center gap-2">
            <i class="fas fa-check-circle text-green-500"></i> {{ session('success') }}
        </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Carte profil --}}
            <div class="md:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 flex items-center gap-5 border-b border-gray-50">
                    @if($adhesion->photo)
                    <img src="{{ Storage::url($adhesion->photo) }}" alt="Ma photo" class="w-20 h-20 rounded-2xl object-cover shadow">
                    @else
                    <div class="w-20 h-20 rounded-2xl bg-mja-blue/10 flex items-center justify-center text-mja-blue text-2xl font-display font-black">{{ strtoupper(substr($adhesion->prenom,0,1)) }}</div>
                    @endif
                    <div>
                        <h2 class="font-display font-black text-xl text-mja-gray">{{ $adhesion->prenom }} {{ $adhesion->nom }}</h2>
                        @if($adhesion->isAdherent())
                        <span class="inline-flex items-center gap-1.5 bg-green-50 text-green-700 text-xs font-display font-bold px-3 py-1 rounded-full mt-1"><i class="fas fa-circle-check"></i> Adhérent(e) à jour</span>
                        @else
                        <span class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-700 text-xs font-display font-bold px-3 py-1 rounded-full mt-1">{{ $adhesion->label_statut }}</span>
                        @endif
                    </div>
                </div>
                <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-5 text-sm">
                    @foreach([
                        ['Email', $adhesion->email], ['Téléphone', $adhesion->telephone],
                        ['Profession', $adhesion->profession], ['Taille T-shirt', $adhesion->taille_tshirt],
                    ] as [$l, $v])
                    <div>
                        <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-0.5">{{ $l }}</div>
                        <div class="font-semibold text-gray-800">{{ $v }}</div>
                    </div>
                    @endforeach
                    <div class="sm:col-span-2">
                        <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Adresse</div>
                        <div class="font-semibold text-gray-800 whitespace-pre-line">{{ $adhesion->adresse_postale }}</div>
                    </div>
                </div>
                <div class="px-6 pb-6">
                    <a href="{{ route('member.profile.edit') }}" class="inline-flex items-center gap-2 btn-blue font-display font-bold text-sm px-5 py-2.5 rounded-xl transition-colors">
                        <i class="fas fa-pen"></i> Modifier mes informations
                    </a>
                </div>
            </div>

            {{-- Colonne actions --}}
            <div class="space-y-6">
                <a href="{{ route('member.trombinoscope') }}" class="block bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                    <div class="w-10 h-10 bg-mja-yellow/15 text-mja-yellow rounded-xl flex items-center justify-center mb-3"><i class="fas fa-users"></i></div>
                    <h3 class="font-display font-bold text-mja-gray mb-1">Trombinoscope</h3>
                    <p class="text-sm text-gray-500 mb-3">Découvrez les autres adhérents de l'association.</p>
                    <span class="inline-flex items-center gap-1.5 text-mja-blue font-display font-bold text-sm">Voir le trombinoscope <i class="fas fa-arrow-right text-xs"></i></span>
                </a>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-display font-bold text-mja-gray mb-1">Mon compte</h3>
                    <p class="text-sm text-gray-500 mb-4">Vous pouvez supprimer votre compte. Vous aurez <strong>30 jours</strong> pour le restaurer avant sa suppression définitive.</p>
                    <button type="button" onclick="openDeleteModal()" class="text-mja-red hover:text-red-700 text-sm font-display font-bold flex items-center gap-2">
                        <i class="fas fa-trash"></i> Supprimer mon compte
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Modale de confirmation de suppression --}}
<div id="delete-modal" class="fixed inset-0 z-[60] hidden items-center justify-center px-4" style="background:rgba(10,20,40,.6)">
    <div id="delete-modal-card" class="bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden transform transition-all duration-200 scale-95 opacity-0">
        <div class="flex h-1"><div class="flex-1 bg-mja-blue"></div><div class="flex-1 bg-mja-yellow"></div><div class="flex-1 bg-mja-red"></div></div>
        <div class="p-7 text-center">
            <div class="w-16 h-16 bg-red-50 rounded-2xl flex items-center justify-center mx-auto mb-5">
                <i class="fas fa-trash-alt text-mja-red text-2xl"></i>
            </div>
            <h3 class="font-display font-black text-xl text-mja-gray mb-2">Supprimer votre compte ?</h3>
            <p class="text-sm text-gray-500 leading-relaxed mb-2">Votre accès à l'espace membre sera immédiatement désactivé.</p>
            <div class="bg-amber-50 border border-amber-100 rounded-xl px-4 py-3 text-sm text-amber-800 mb-6 text-left flex gap-2">
                <i class="fas fa-clock mt-0.5 shrink-0"></i>
                <span>Vous recevrez un email et disposerez de <strong>30 jours</strong> pour restaurer votre compte. Passé ce délai, il sera <strong>définitivement supprimé</strong>.</span>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closeDeleteModal()" class="flex-1 py-3 border-2 border-gray-200 hover:border-gray-300 rounded-xl font-display font-bold text-gray-600 hover:bg-gray-50 transition-colors text-sm">
                    Annuler
                </button>
                <form method="POST" action="{{ route('member.account.destroy') }}" class="flex-1">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full py-3 bg-mja-red hover:bg-red-700 text-white rounded-xl font-display font-bold transition-colors text-sm">
                        <i class="fas fa-trash mr-1"></i> Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openDeleteModal() {
    var m = document.getElementById('delete-modal');
    var c = document.getElementById('delete-modal-card');
    m.classList.remove('hidden'); m.classList.add('flex');
    requestAnimationFrame(function () { c.classList.remove('scale-95', 'opacity-0'); });
}
function closeDeleteModal() {
    var m = document.getElementById('delete-modal');
    var c = document.getElementById('delete-modal-card');
    c.classList.add('scale-95', 'opacity-0');
    setTimeout(function () { m.classList.add('hidden'); m.classList.remove('flex'); }, 200);
}
document.getElementById('delete-modal').addEventListener('click', function (e) {
    if (e.target === this) closeDeleteModal();
});
</script>
@endpush
@endsection
