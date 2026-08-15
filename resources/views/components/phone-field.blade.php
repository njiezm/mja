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
    <input type="tel" name="{{ $name }}" value="{{ \App\Support\Telephone::formater(old($name, $value)) }}" @if($required) required @endif
        aria-label="Numéro de téléphone" autocomplete="tel-national" inputmode="numeric" maxlength="14"
        class="pf-number flex-1 bg-transparent border-0 px-3 py-3 text-sm outline-none min-w-0"
        placeholder="{{ $placeholder }}">
</div>

@once
@push('scripts')
<script>
(function () {
    /**
     * Met le numéro au format « 696 43 88 21 » : trois chiffres, puis des paires.
     * Le regroupement est appliqué pendant la saisie — sans quoi le champ se lit
     * comme un bloc de chiffres collés alors que le placeholder montre l'inverse.
     */
    function grouper(chiffres) {
        chiffres = chiffres.slice(0, 10);
        if (chiffres.length <= 3) { return chiffres; }

        var morceaux = [chiffres.slice(0, 3)];
        for (var i = 3; i < chiffres.length; i += 2) {
            morceaux.push(chiffres.slice(i, i + 2));
        }
        return morceaux.join(' ');
    }

    function chiffresAvant(texte, position) {
        return (texte.slice(0, position).match(/\d/g) || []).length;
    }

    /** Position du caret juste après le n-ième chiffre du texte formaté. */
    function positionApres(texte, nbChiffres) {
        if (nbChiffres <= 0) { return 0; }

        var vus = 0;
        for (var i = 0; i < texte.length; i++) {
            if (/\d/.test(texte[i])) {
                vus++;
                if (vus === nbChiffres) { return i + 1; }
            }
        }
        return texte.length;
    }

    function formater(champ) {
        var avant = chiffresAvant(champ.value, champ.selectionStart === null ? champ.value.length : champ.selectionStart);
        var formate = grouper((champ.value.match(/\d/g) || []).join(''));

        if (formate === champ.value) { return; }

        champ.value = formate;

        // Le caret suit le chiffre qu'il précédait, pas la position brute :
        // sinon toute correction en milieu de numéro renvoie le curseur à la fin.
        try { champ.setSelectionRange(positionApres(formate, avant), positionApres(formate, avant)); } catch (e) {}
    }

    document.querySelectorAll('.phone-field').forEach(function (root) {
        var select = root.querySelector('.pf-code');
        var flag   = root.querySelector('.pf-flag');
        var numero = root.querySelector('.pf-number');

        function sync() {
            var opt = select.options[select.selectedIndex];
            flag.innerHTML = opt ? '<img src="' + opt.dataset.flag + '" alt="" style="width:20px;height:14px;object-fit:cover;border-radius:2px;box-shadow:0 0 0 1px rgba(0,0,0,.06)">' : '';
        }
        select.addEventListener('change', sync);
        sync();

        if (numero) {
            numero.addEventListener('input', function () { formater(this); });
            numero.addEventListener('blur',  function () { formater(this); });
            formater(numero);
        }
    });
})();
</script>
@endpush
@endonce
