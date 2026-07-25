@props([
    'name'          => 'telephone',
    'indicatifName' => 'indicatif',
    'value'         => null,
    'indicatif'     => '+596',
    'required'      => false,
    'placeholder'   => '696 00 00 00',
])

@php
    // Drapeaux réels (SVG téléchargés dans public/images/flags/). Antilles-Guyane d'abord.
    $pays = [
        ['+596', 'Martinique', 'mq.svg'],
        ['+590', 'Guadeloupe', 'gp.svg'],
        ['+594', 'Guyane', 'gf.svg'],
        ['+33',  'France', 'fr.svg'],
        ['+509', 'Haïti', 'ht.svg'],
        ['+1',   'Rép. dominicaine', 'do.svg'],
        ['+1',   'Canada', 'ca.svg'],
        ['+1',   'États-Unis', 'us.svg'],
        ['+597', 'Suriname', 'sr.svg'],
        ['+55',  'Brésil', 'br.svg'],
        ['+32',  'Belgique', 'be.svg'],
        ['+41',  'Suisse', 'ch.svg'],
        ['+44',  'Royaume-Uni', 'gb.svg'],
    ];
    $selCode = old($indicatifName, $indicatif);
    $picked = false;
@endphp

<div class="phone-field flex items-stretch border-2 border-gray-100 focus-within:border-mja-blue rounded-xl overflow-hidden transition-colors">
    <div class="pf-flag w-7 flex items-center justify-center pl-3 shrink-0" aria-hidden="true"></div>
    <select name="{{ $indicatifName }}" aria-label="Indicatif téléphonique du pays" class="pf-code bg-transparent border-0 text-xs font-bold text-gray-500 font-display pl-1.5 pr-1 py-3 outline-none cursor-pointer">
        @foreach($pays as [$code, $label, $file])
        @php $isSel = (! $picked && $code === $selCode); if ($isSel) $picked = true; @endphp
        <option value="{{ $code }}" data-flag="{{ asset('images/flags/'.$file) }}" @selected($isSel)>
            {{ $label }} ({{ $code }})
        </option>
        @endforeach
    </select>
    <span class="w-px bg-gray-200 my-2"></span>
    <input type="tel" name="{{ $name }}" value="{{ old($name, $value) }}" @if($required) required @endif
        aria-label="Numéro de téléphone" autocomplete="tel-national"
        class="pf-number flex-1 bg-transparent border-0 px-3 py-3 text-sm outline-none min-w-0"
        placeholder="{{ $placeholder }}">
</div>

@once
@push('scripts')
<script>
document.querySelectorAll('.phone-field').forEach(function (root) {
    var select = root.querySelector('.pf-code');
    var flag   = root.querySelector('.pf-flag');
    function sync() {
        var opt = select.options[select.selectedIndex];
        flag.innerHTML = opt ? '<img src="' + opt.dataset.flag + '" alt="" style="width:20px;height:14px;object-fit:cover;border-radius:2px;box-shadow:0 0 0 1px rgba(0,0,0,.06)">' : '';
    }
    select.addEventListener('change', sync);
    sync();
});
</script>
@endpush
@endonce
