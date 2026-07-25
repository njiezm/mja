<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Carte de membre — {{ $adhesion->prenom }} {{ $adhesion->nom }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logomjat.png') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/gill-sans.css') }}">
    <link rel="stylesheet" href="{{ asset('fonts/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome/css/all.min.css') }}">
    <style>
        body { font-family: 'Gill Sans','Open Sans',sans-serif; background:#f4f6f9; margin:0; padding:24px; }
        .font-round { font-family: 'AllRound Gothic','Gill Sans',sans-serif; }
        .card-mja { background: linear-gradient(135deg,#1A3D8A 0%,#2048A4 45%,#3262CC 100%); }
        @media print {
            .no-print { display:none !important; }
            body { background:#fff; padding:0; }
        }
    </style>
</head>
<body>
    <div class="max-w-2xl mx-auto">
        <div class="no-print flex items-center justify-between mb-5">
            <a href="{{ route('member.dashboard') }}" class="text-mja-blue hover:underline font-display font-semibold text-sm"><i class="fas fa-arrow-left mr-1"></i> Mon espace</a>
            <button onclick="window.print()" class="btn-blue font-display font-bold text-sm px-5 py-2.5 rounded-xl"><i class="fas fa-print mr-1"></i> Imprimer / PDF</button>
        </div>

        {{-- Carte de membre --}}
        <div class="card-mja text-white rounded-3xl shadow-2xl overflow-hidden mb-8">
            <div class="flex h-1.5"><div class="flex-1 bg-mja-blue"></div><div class="flex-1 bg-mja-yellow"></div><div class="flex-1 bg-mja-red"></div></div>
            <div class="p-7 flex items-center gap-6">
                @if($adhesion->photo)
                <img src="{{ \Illuminate\Support\Facades\Storage::url($adhesion->photo) }}" alt="" class="w-24 h-24 rounded-2xl object-cover border-2 border-white/40 shrink-0">
                @else
                <div class="w-24 h-24 rounded-2xl bg-white/10 flex items-center justify-center text-3xl font-round font-black shrink-0">{{ strtoupper(substr($adhesion->prenom,0,1)) }}</div>
                @endif
                <div class="flex-1 min-w-0">
                    <div class="font-round font-black text-2xl">
                        <span class="text-mja-blue">M</span><span class="text-mja-yellow">J</span><span class="text-mja-red">A</span>
                        <span class="text-white/70 text-xs font-display font-semibold uppercase tracking-widest ml-2">Carte de membre</span>
                    </div>
                    <div class="font-display font-black text-2xl mt-2 truncate">{{ $adhesion->prenom }} {{ $adhesion->nom }}</div>
                    <div class="text-gray-200 text-sm mt-1">
                        <span class="inline-flex items-center gap-1.5 bg-green-500/20 text-green-100 px-2.5 py-0.5 rounded-full text-xs font-display font-bold"><i class="fas fa-circle-check"></i> Adhérent(e) à jour</span>
                        @if($adhesion->period)<span class="ml-2">{{ $adhesion->period->label }}</span>@endif
                    </div>
                    <div class="text-white/50 text-xs mt-2 font-display">N° {{ str_pad($adhesion->id, 5, '0', STR_PAD_LEFT) }}</div>
                </div>
            </div>
        </div>

        {{-- Attestation --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 text-gray-700 text-sm leading-relaxed">
            <h1 class="font-display font-black text-xl text-mja-gray mb-5 text-center">Attestation d'adhésion</h1>
            <p class="mb-4">L'association <strong>Madin'Jeunes Ambition</strong>, sise 22 passage du Cœur sur la Main, 97200 Fort-de-France (Martinique), atteste que&nbsp;:</p>
            <p class="mb-4 text-center font-display font-bold text-lg text-mja-gray">{{ $adhesion->civilite }} {{ $adhesion->prenom }} {{ $adhesion->nom }}</p>
            <p class="mb-4">
                est <strong>adhérent(e)</strong> de l'association @if($adhesion->period)pour la <strong>{{ $adhesion->period->label }}</strong>@endif et à jour de sa cotisation.
            </p>
            <p class="text-gray-500">Fait à Fort-de-France, le {{ now()->locale('fr')->isoFormat('D MMMM Y') }}.</p>
            <div class="mt-8 flex items-center gap-3 justify-end text-gray-400">
                <img src="{{ asset('images/logomjat.png') }}" alt="MJA" class="h-12 w-auto object-contain opacity-80">
                <span class="text-xs font-display">Madin'Jeunes Ambition</span>
            </div>
        </div>
    </div>
</body>
</html>
