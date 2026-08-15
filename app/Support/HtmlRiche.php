<?php

namespace App\Support;

use DOMDocument;
use DOMElement;
use DOMNode;
use Illuminate\Support\HtmlString;

/**
 * Assainissement et rendu des contenus rédigés dans l'éditeur du back-office.
 *
 * Le texte saisi peut contenir de la mise en forme (gras, listes, liens,
 * alignement, justification). Comme il finit affiché tel quel sur le site
 * public, il ne peut pas être stocké sans contrôle : tout ce qui n'est pas
 * explicitement autorisé ci-dessous est retiré à l'enregistrement.
 *
 * On assainit à l'écriture *et* on rend via `rendre()` à la lecture : les
 * contenus déjà en base avant l'éditeur restent du texte brut, ils sont donc
 * échappés comme avant.
 */
class HtmlRiche
{
    /** Balises conservées, avec leurs attributs autorisés. */
    private const AUTORISEES = [
        'p'          => ['style'],
        'br'         => [],
        'strong'     => [],
        'b'          => [],
        'em'         => [],
        'i'          => [],
        'u'          => [],
        's'          => [],
        'ul'         => ['style'],
        'ol'         => ['style'],
        'li'         => ['style'],
        'h2'         => ['style'],
        'h3'         => ['style'],
        'h4'         => ['style'],
        'blockquote' => ['style'],
        'a'          => ['href', 'target', 'rel'],
        'div'        => ['style'],
        'span'       => ['style'],
    ];

    /** Seules valeurs acceptées dans un attribut `style`. */
    private const ALIGNEMENTS = ['left', 'right', 'center', 'justify'];

    /**
     * Nettoie du HTML saisi dans l'éditeur. Renvoie une chaîne sûre à stocker
     * puis à afficher sans échappement.
     */
    public static function nettoyer(?string $html): ?string
    {
        $html = trim((string) $html);

        if ($html === '' || strip_tags($html) === '' && ! str_contains($html, '<br')) {
            return $html === '' ? null : null;
        }

        $document = new DOMDocument('1.0', 'UTF-8');

        // Sans le préambule XML, DOMDocument interprète l'entrée en ISO-8859-1
        // et casse les accents. LIBXML_HTML_* évite l'ajout d'un <html><body>.
        $prealable = libxml_use_internal_errors(true);
        $document->loadHTML(
            '<?xml encoding="UTF-8"?><div id="mja-racine">' . $html . '</div>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );
        libxml_clear_errors();
        libxml_use_internal_errors($prealable);

        $racine = $document->getElementById('mja-racine');

        if (! $racine) {
            return null;
        }

        self::nettoyerNoeud($racine);

        $sortie = '';
        foreach ($racine->childNodes as $enfant) {
            $sortie .= $document->saveHTML($enfant);
        }

        $sortie = trim($sortie);

        return $sortie === '' ? null : $sortie;
    }

    /** Parcourt l'arbre et supprime tout ce qui n'est pas sur la liste blanche. */
    private static function nettoyerNoeud(DOMNode $noeud): void
    {
        // Copie : la liste vivante se réorganise à chaque suppression.
        foreach (iterator_to_array($noeud->childNodes) as $enfant) {
            if ($enfant instanceof DOMElement) {
                $balise = strtolower($enfant->nodeName);

                if (! array_key_exists($balise, self::AUTORISEES)) {
                    // La balise disparaît, son contenu textuel est conservé.
                    self::nettoyerNoeud($enfant);
                    self::remplacerParSesEnfants($enfant);
                    continue;
                }

                self::nettoyerAttributs($enfant, $balise);
                self::nettoyerNoeud($enfant);
                continue;
            }

            // Commentaires, instructions de traitement, CDATA : rien à en faire.
            if (! ($enfant instanceof \DOMText)) {
                $noeud->removeChild($enfant);
            }
        }
    }

    private static function remplacerParSesEnfants(DOMElement $element): void
    {
        $parent = $element->parentNode;

        if (! $parent) {
            return;
        }

        while ($element->firstChild) {
            $parent->insertBefore($element->firstChild, $element);
        }

        $parent->removeChild($element);
    }

    private static function nettoyerAttributs(DOMElement $element, string $balise): void
    {
        $autorises = self::AUTORISEES[$balise];

        foreach (iterator_to_array($element->attributes) as $attribut) {
            $nom = strtolower($attribut->nodeName);

            if (! in_array($nom, $autorises, true)) {
                $element->removeAttribute($attribut->nodeName);
                continue;
            }

            if ($nom === 'style') {
                $alignement = self::alignement($attribut->nodeValue);
                $alignement
                    ? $element->setAttribute('style', "text-align: {$alignement}")
                    : $element->removeAttribute('style');
                continue;
            }

            if ($nom === 'href' && ! self::lienSur($attribut->nodeValue)) {
                $element->removeAttribute('href');
            }
        }

        // Un lien externe s'ouvre dans un nouvel onglet, sans fuite de referrer.
        if ($balise === 'a' && $element->hasAttribute('href')) {
            $element->setAttribute('target', '_blank');
            $element->setAttribute('rel', 'noopener noreferrer');
        }
    }

    /** Extrait `text-align` d'une déclaration de style, ou null. */
    private static function alignement(?string $style): ?string
    {
        if (! $style || ! preg_match('/text-align\s*:\s*([a-z-]+)/i', $style, $trouve)) {
            return null;
        }

        $valeur = strtolower($trouve[1]);

        return in_array($valeur, self::ALIGNEMENTS, true) ? $valeur : null;
    }

    /** N'accepte que http(s), mailto, tel et les liens internes. */
    private static function lienSur(?string $href): bool
    {
        $href = trim((string) $href);

        if ($href === '') {
            return false;
        }

        if (str_starts_with($href, '/') || str_starts_with($href, '#')) {
            return true;
        }

        return (bool) preg_match('#^(https?://|mailto:|tel:)#i', $href);
    }

    /**
     * Rend un contenu pour affichage.
     *
     * Un texte enregistré avant l'éditeur ne contient aucune balise : il est
     * échappé et ses retours à la ligne convertis, comme auparavant. Un contenu
     * issu de l'éditeur a déjà été assaini, il est rendu tel quel.
     */
    public static function rendre(?string $contenu): HtmlString
    {
        $contenu = (string) $contenu;

        if ($contenu === '') {
            return new HtmlString('');
        }

        $contientDuBalisage = $contenu !== strip_tags($contenu);

        return new HtmlString(
            $contientDuBalisage
                ? (self::nettoyer($contenu) ?? '')
                : nl2br(e($contenu))
        );
    }
}
