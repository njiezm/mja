@props([
    'name',
    'value'   => null,
    'rows'    => 10,
    'id'      => null,
    'aide'    => null,
])

@php
    $identifiant = $id ?: 'ed-' . $name;
    $contenu     = old($name, $value);
    // Un contenu antérieur à l'éditeur est du texte brut : on le convertit en
    // HTML pour que l'éditeur l'affiche avec ses retours à la ligne.
    $htmlInitial = $contenu !== null && $contenu !== strip_tags((string) $contenu)
        ? $contenu
        : nl2br(e((string) $contenu));
@endphp

<div class="editeur-riche" data-cible="{{ $identifiant }}">
    <div class="flex flex-wrap items-center gap-1 border border-gray-200 border-b-0 rounded-t-xl bg-gray-50 px-2 py-1.5">
        @foreach([
            [['bold', 'fa-bold', 'Gras'], ['italic', 'fa-italic', 'Italique'], ['underline', 'fa-underline', 'Souligné']],
            [['justifyLeft', 'fa-align-left', 'Aligner à gauche'], ['justifyCenter', 'fa-align-center', 'Centrer'], ['justifyRight', 'fa-align-right', 'Aligner à droite'], ['justifyFull', 'fa-align-justify', 'Justifier']],
            [['insertUnorderedList', 'fa-list-ul', 'Liste à puces'], ['insertOrderedList', 'fa-list-ol', 'Liste numérotée']],
            [['formatBlock:h3', 'fa-heading', 'Sous-titre'], ['createLink', 'fa-link', 'Insérer un lien'], ['unlink', 'fa-link-slash', 'Retirer le lien']],
            [['removeFormat', 'fa-eraser', 'Effacer la mise en forme']],
        ] as $indexGroupe => $groupe)
            @if($indexGroupe > 0)<span class="w-px h-5 bg-gray-200 mx-1"></span>@endif
            @foreach($groupe as [$commande, $icone, $titre])
            <button type="button" data-commande="{{ $commande }}" title="{{ $titre }}" aria-label="{{ $titre }}"
                    class="er-btn w-8 h-8 rounded-lg text-gray-500 hover:bg-white hover:text-mja-blue hover:shadow-sm transition-colors flex items-center justify-center">
                <i class="fas {{ $icone }} text-xs" aria-hidden="true"></i>
            </button>
            @endforeach
        @endforeach
    </div>

    <div id="{{ $identifiant }}" contenteditable="true" role="textbox" aria-multiline="true"
         style="min-height: {{ max(6, (int) $rows) * 1.6 }}rem"
         class="er-zone w-full border border-gray-200 rounded-b-xl px-4 py-3 text-sm leading-relaxed bg-white focus:outline-none focus:ring-2 focus:ring-mja-blue overflow-y-auto">{!! $htmlInitial !!}</div>

    <textarea name="{{ $name }}" class="er-source sr-only" tabindex="-1" aria-hidden="true">{{ $contenu }}</textarea>

    <p class="text-[11px] text-gray-400 mt-1.5">
        {{ $aide ?? "Gras, italique, listes, liens et alignement (y compris justifié). Seule cette mise en forme est conservée à l'enregistrement." }}
    </p>
</div>

@once
@push('scripts')
<script>
(function () {
    document.querySelectorAll('.editeur-riche').forEach(function (bloc) {
        var zone   = bloc.querySelector('.er-zone');
        var source = bloc.querySelector('.er-source');
        if (!zone || !source) return;

        // Le champ réellement envoyé est le textarea : on le tient à jour à
        // chaque frappe plutôt qu'au seul submit, pour que la restauration
        // après erreur de validation retrouve le contenu.
        function synchroniser() { source.value = zone.innerHTML.trim(); }

        zone.addEventListener('input', synchroniser);
        zone.addEventListener('blur', synchroniser);
        var formulaire = bloc.closest('form');
        if (formulaire) formulaire.addEventListener('submit', synchroniser);

        // Un collage depuis Word ou une page web apporte son propre balisage :
        // on ne garde que le texte, la mise en forme se refait avec la barre.
        zone.addEventListener('paste', function (e) {
            e.preventDefault();
            var texte = (e.clipboardData || window.clipboardData).getData('text/plain');
            document.execCommand('insertText', false, texte);
        });

        bloc.querySelectorAll('.er-btn').forEach(function (bouton) {
            // mousedown plutôt que click : le focus doit rester dans la zone,
            // sinon la sélection est perdue avant l'exécution de la commande.
            bouton.addEventListener('mousedown', function (e) {
                e.preventDefault();
                var commande = bouton.dataset.commande;

                zone.focus();

                if (commande === 'createLink') {
                    var url = window.prompt('Adresse du lien (https://…)');
                    if (url) document.execCommand('createLink', false, url);
                } else if (commande.indexOf('formatBlock:') === 0) {
                    document.execCommand('formatBlock', false, commande.split(':')[1]);
                } else {
                    document.execCommand(commande, false, null);
                }

                synchroniser();
            });
        });

        synchroniser();
    });
})();
</script>
@endpush
@endonce
