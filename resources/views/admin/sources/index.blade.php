@extends('layouts.admin')
@section('title', 'Sources d\'acquisition')
@section('page-title', 'Sources & tracking')
@section('content')

@php $maxSerie = max(1, max(array_column($series, 'count'))); @endphp

<!-- Totaux -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6 mt-4">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <div class="text-3xl font-display font-black text-mja-blue">{{ $totaux['sources'] }}</div>
        <div class="text-sm text-gray-500 mt-1">Sources</div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <div class="text-3xl font-display font-black text-mja-yellow">{{ number_format($totaux['visites'], 0, ',', ' ') }}</div>
        <div class="text-sm text-gray-500 mt-1">Visites trackées</div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <div class="text-3xl font-display font-black text-mja-red">{{ $totaux['conversions'] }}</div>
        <div class="text-sm text-gray-500 mt-1">Conversions</div>
    </div>
    <a href="{{ route('admin.sources.export') }}" class="bg-mja-dark rounded-2xl shadow-sm p-5 flex flex-col justify-center text-white hover:bg-mja-navy transition-colors">
        <i class="fas fa-file-csv text-2xl mb-1"></i>
        <div class="text-sm font-display font-bold">Exporter en CSV</div>
    </a>
</div>

<!-- Tunnel de conversion -->
@php
    $fv = max(1, $funnel['visites']);
    $steps = [
        ['Visites', $funnel['visites'], 'bg-mja-blue'],
        ['Adhésions soumises', $funnel['adhesions'], 'bg-mja-yellow'],
        ['Adhérents payés', $funnel['payees'], 'bg-green-500'],
    ];
@endphp
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-6">
    <h2 class="font-display font-bold text-gray-800 text-sm mb-4">Tunnel de conversion (sources trackées)</h2>
    <div class="space-y-3">
        @foreach($steps as $i => [$label, $val, $color])
        <div>
            <div class="flex justify-between text-xs mb-1">
                <span class="font-display font-bold text-gray-600">{{ $label }}</span>
                <span class="text-gray-500">{{ $val }} @if($i > 0)<span class="text-gray-400">({{ round($val / $fv * 100) }}%)</span>@endif</span>
            </div>
            <div class="h-3 bg-gray-100 rounded-full overflow-hidden">
                <div class="{{ $color }} h-full rounded-full transition-all" style="width: {{ max(2, round($val / $fv * 100)) }}%"></div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- Courbe 14 jours -->
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <h2 class="font-display font-bold text-gray-800 text-sm mb-4">Visites — 14 derniers jours</h2>
        <div class="flex items-end gap-1.5 h-28">
            @foreach($series as $pt)
            <div class="flex-1 flex flex-col items-center justify-end gap-1 group">
                <div class="w-full bg-mja-blue/80 hover:bg-mja-blue rounded-t transition-all relative" style="height: {{ max(2, round($pt['count'] / $maxSerie * 90)) }}px" title="{{ $pt['count'] }}">
                    <span class="absolute -top-5 left-1/2 -translate-x-1/2 text-[10px] font-bold text-gray-600 opacity-0 group-hover:opacity-100">{{ $pt['count'] }}</span>
                </div>
                <span class="text-[9px] text-gray-400 font-display">{{ $pt['label'] }}</span>
            </div>
            @endforeach
        </div>
    </div>
    <!-- Classement -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <h2 class="font-display font-bold text-gray-800 text-sm mb-4">Top sources</h2>
        @forelse($ranking as $i => $s)
        <div class="flex items-center gap-3 py-1.5 {{ !$loop->last ? 'border-b border-gray-50' : '' }}">
            <span class="w-5 text-center font-display font-black text-sm {{ $i === 0 ? 'text-mja-yellow' : 'text-gray-300' }}">{{ $i + 1 }}</span>
            <span class="flex-1 text-sm font-semibold text-gray-700 truncate">{{ $s->label }}</span>
            <span class="text-sm font-bold text-gray-900">{{ $stats[$s->id]['total'] }}</span>
        </div>
        @empty
        <p class="text-sm text-gray-400">Aucune donnée.</p>
        @endforelse
    </div>
</div>

<!-- Provenance + Appareils -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <h2 class="font-display font-bold text-gray-800 text-sm mb-4">Provenance des visites</h2>
        @php $totProv = max(1, $provenance->sum()); @endphp
        @forelse($provenance->take(8) as $label => $count)
        <div class="mb-2.5">
            <div class="flex justify-between text-xs mb-1"><span class="font-semibold text-gray-600">{{ $label }}</span><span class="text-gray-500">{{ $count }}</span></div>
            <div class="h-2 bg-gray-100 rounded-full overflow-hidden"><div class="bg-mja-blue h-full" style="width: {{ round($count / $totProv * 100) }}%"></div></div>
        </div>
        @empty
        <p class="text-sm text-gray-400">Aucune visite enregistrée.</p>
        @endforelse
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <h2 class="font-display font-bold text-gray-800 text-sm mb-4">Appareils</h2>
        @php
            $devLabels = ['mobile' => ['Mobile','fa-mobile-screen','text-mja-blue'], 'desktop' => ['Ordinateur','fa-desktop','text-mja-yellow'], 'tablet' => ['Tablette','fa-tablet-screen-button','text-mja-red']];
            $totDev = max(1, $devices->sum());
        @endphp
        @forelse($devLabels as $key => [$lbl, $icon, $col])
        @php $c = (int) ($devices[$key] ?? 0); @endphp
        <div class="flex items-center gap-3 mb-3">
            <i class="fas {{ $icon }} {{ $col }} w-5 text-center"></i>
            <span class="flex-1 text-sm font-semibold text-gray-600">{{ $lbl }}</span>
            <span class="text-sm text-gray-500">{{ $c }} <span class="text-gray-400">({{ round($c / $totDev * 100) }}%)</span></span>
        </div>
        @empty
        @endforelse
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Formulaire création -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h2 class="font-display font-bold text-gray-800 mb-4"><i class="fas fa-plus-circle text-mja-blue mr-1"></i> Nouvelle source</h2>
            <form method="POST" action="{{ route('admin.sources.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-display font-bold text-gray-600 mb-1">Nom <span class="text-mja-red">*</span></label>
                    <input type="text" name="label" value="{{ old('label') }}" required placeholder="Ex : Flyer BAC 2026"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-mja-blue @error('label') border-red-400 @enderror">
                    @error('label')<p class="text-mja-red text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-display font-bold text-gray-600 mb-1">Lien /… <span class="text-mja-red">*</span></label>
                    <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden focus-within:ring-2 focus-within:ring-mja-blue @error('slug') border-red-400 @enderror">
                        <span class="text-xs text-gray-400 pl-3 pr-1 select-none">{{ url('/') }}/</span>
                        <input type="text" name="slug" value="{{ old('slug') }}" required placeholder="flyer-bac" class="flex-1 px-1 py-2.5 text-sm outline-none min-w-0">
                    </div>
                    @error('slug')<p class="text-mja-red text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-display font-bold text-gray-600 mb-1">Redirection</label>
                    <input type="text" name="target" value="{{ old('target', '/') }}" placeholder="/adhesion" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-mja-blue">
                </div>
                <div>
                    <label class="block text-xs font-display font-bold text-gray-600 mb-1">Description</label>
                    <input type="text" name="description" value="{{ old('description') }}" placeholder="Note interne" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-mja-blue">
                </div>
                <button class="w-full bg-mja-blue hover:bg-mja-bluedark text-white font-display font-bold py-2.5 rounded-xl text-sm transition-colors">Créer la source</button>
            </form>
            <p class="text-[11px] text-gray-400 mt-4"><i class="fas fa-circle-info mr-1"></i> Astuce : ajoutez <code>?utm_source=facebook</code> à n'importe quel lien du site pour tracer une campagne sans créer de source à la main.</p>
        </div>
    </div>

    <!-- Table sources -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-x-auto">
            <table class="w-full text-sm min-w-[680px]">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50 text-left">
                        <th class="px-4 py-3 font-display font-bold text-gray-500 text-xs uppercase">Source</th>
                        <th class="px-4 py-3 font-display font-bold text-gray-500 text-xs uppercase">30 j</th>
                        <th class="px-4 py-3 font-display font-bold text-gray-500 text-xs uppercase text-center">Visites</th>
                        <th class="px-4 py-3 font-display font-bold text-gray-500 text-xs uppercase text-center">Conv.</th>
                        <th class="px-4 py-3 font-display font-bold text-gray-500 text-xs uppercase text-center">Taux</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($sources as $s)
                    @php $sp = $stats[$s->id]['spark']; $spMax = max(1, max($sp)); @endphp
                    <tr class="hover:bg-gray-50 transition-colors {{ $s->is_active ? '' : 'opacity-60' }}">
                        <td class="px-4 py-3">
                            <div class="font-display font-bold text-gray-900 flex items-center gap-2">
                                {{ $s->label }}
                                @unless($s->is_active)<span class="text-[10px] bg-gray-100 text-gray-500 px-1.5 py-0.5 rounded">inactif</span>@endunless
                            </div>
                            <button type="button" onclick="copyLink(this)" data-link="{{ $s->trackingUrl() }}" class="text-xs text-mja-blue hover:underline flex items-center gap-1 mt-0.5">
                                <i class="fas fa-link text-[10px]"></i> <span>/{{ $s->slug }}</span> <i class="fas fa-copy text-[10px] text-gray-300"></i>
                            </button>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-end gap-px h-8 w-24" title="30 derniers jours">
                                @foreach($sp as $v)<div class="flex-1 bg-mja-blue/70 rounded-sm" style="height: {{ max(1, round($v / $spMax * 32)) }}px"></div>@endforeach
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center font-bold text-gray-800">{{ $stats[$s->id]['total'] }}<div class="text-[10px] text-gray-400 font-normal">{{ $stats[$s->id]['uniques'] }} uniques</div></td>
                        <td class="px-4 py-3 text-center text-gray-600" title="{{ $stats[$s->id]['adhesions'] }} adhésion(s) dont {{ $stats[$s->id]['payees'] }} payée(s), {{ $stats[$s->id]['contacts'] }} message(s)">{{ $stats[$s->id]['conversions'] }}</td>
                        <td class="px-4 py-3 text-center"><span class="font-display font-bold {{ $stats[$s->id]['taux'] >= 5 ? 'text-green-600' : 'text-gray-500' }}">{{ $stats[$s->id]['taux'] }}%</span></td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2 justify-end">
                                <button type="button" onclick="showQr(@js($s->trackingUrl()), @js($s->label))" class="w-8 h-8 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg flex items-center justify-center transition-colors" title="QR code">
                                    <i class="fas fa-qrcode text-xs"></i>
                                </button>
                                <a href="{{ route('admin.sources.edit', $s) }}" class="w-8 h-8 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center transition-colors" title="Modifier"><i class="fas fa-edit text-xs"></i></a>
                                <form method="POST" action="{{ route('admin.sources.destroy', $s) }}" data-confirm="Supprimer la source « {{ $s->label }} » et ses statistiques ?">
                                    @csrf @method('DELETE')
                                    <button class="w-8 h-8 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg flex items-center justify-center transition-colors" title="Supprimer"><i class="fas fa-trash text-xs"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-4 py-10 text-center text-gray-400 text-sm">Aucune source. Créez-en une pour générer un lien de tracking.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modale QR -->
<div id="qr-modal" class="fixed inset-0 z-[60] hidden items-center justify-center px-4" style="background:rgba(10,20,40,.6)" onclick="if(event.target===this)closeQr()">
    <div class="bg-white rounded-3xl shadow-2xl max-w-xs w-full p-6 text-center">
        <h3 id="qr-title" class="font-display font-black text-gray-800 mb-1"></h3>
        <p id="qr-url" class="text-xs text-gray-400 mb-4 break-all"></p>
        <div id="qr-target" class="flex justify-center mb-5"></div>
        <div class="flex gap-3">
            <button type="button" onclick="closeQr()" class="flex-1 py-2.5 border-2 border-gray-200 hover:bg-gray-50 rounded-xl font-display font-bold text-gray-600 text-sm">Fermer</button>
            <button type="button" onclick="downloadQr()" class="flex-1 py-2.5 bg-mja-blue hover:bg-mja-bluedark text-white rounded-xl font-display font-bold text-sm"><i class="fas fa-download mr-1"></i> PNG</button>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('vendor/qrcode/qrcode.min.js') }}"></script>
<script>
function copyLink(btn) {
    navigator.clipboard.writeText(btn.dataset.link).then(function () {
        var icon = btn.querySelector('.fa-copy');
        if (icon) { icon.classList.replace('fa-copy', 'fa-check'); icon.classList.add('text-green-500');
            setTimeout(function () { icon.classList.replace('fa-check', 'fa-copy'); icon.classList.remove('text-green-500'); }, 1500); }
    });
}
var qrLabel = '';
function showQr(url, label) {
    qrLabel = label;
    document.getElementById('qr-title').textContent = label;
    document.getElementById('qr-url').textContent = url;
    var target = document.getElementById('qr-target');
    target.innerHTML = '';
    new QRCode(target, { text: url, width: 200, height: 200, correctLevel: QRCode.CorrectLevel.M });
    var m = document.getElementById('qr-modal');
    m.classList.remove('hidden'); m.classList.add('flex');
}
function closeQr() {
    var m = document.getElementById('qr-modal');
    m.classList.add('hidden'); m.classList.remove('flex');
}
function downloadQr() {
    var canvas = document.querySelector('#qr-target canvas');
    var img = document.querySelector('#qr-target img');
    var data = canvas ? canvas.toDataURL('image/png') : (img ? img.src : null);
    if (!data) return;
    var a = document.createElement('a');
    a.href = data;
    a.download = 'qr-' + qrLabel.toLowerCase().replace(/[^a-z0-9]+/g, '-') + '.png';
    a.click();
}
</script>
@endpush
@endsection
