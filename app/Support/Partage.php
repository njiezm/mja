<?php

namespace App\Support;

/**
 * Vignette et résumé d'une page, tels que les affichent WhatsApp, Facebook,
 * LinkedIn ou l'aperçu d'un SMS.
 *
 * Deux exigences des réseaux sociaux, faciles à manquer :
 *  — l'adresse de l'image doit être absolue (une adresse relative ne donne
 *    aucun aperçu, le robot ne connaît pas le domaine) ;
 *  — les dimensions annoncées doivent être les vraies, sinon l'image est
 *    étirée ou rognée n'importe comment.
 */
class Partage
{
    /** Vignette servie quand la page n'en propose aucune. */
    public const DEFAUT = 'images/partage/defaut.jpg';

    /** Dimensions déjà mesurées, pour ne pas relire le fichier deux fois. */
    private static array $tailles = [];

    /** Adresse absolue de la vignette d'une page, repli compris. */
    public static function image(?string $url = null): string
    {
        $url = trim((string) $url);

        if ($url === '') {
            return asset(self::DEFAUT);
        }

        $absolue = preg_match('#^(https?:)?//#i', $url) ? $url : url($url);

        // Une fiche peut désigner une image qui n'a jamais été déposée : le
        // robot du réseau social recevrait une 404 et n'afficherait aucun
        // aperçu. Mieux vaut la vignette de l'association qu'un lien nu.
        $chemin = self::cheminLocal($absolue);

        if ($chemin !== null && ! is_file($chemin)) {
            return asset(self::DEFAUT);
        }

        return $absolue;
    }

    /**
     * Dimensions réelles de la vignette, si le fichier est servi par ce site.
     *
     * @return array{0: int, 1: int}|null
     */
    public static function taille(string $url): ?array
    {
        if (array_key_exists($url, self::$tailles)) {
            return self::$tailles[$url];
        }

        $chemin = self::cheminLocal($url);
        $taille = null;

        if ($chemin && is_file($chemin)) {
            $info = @getimagesize($chemin);

            if ($info) {
                $taille = [(int) $info[0], (int) $info[1]];
            }
        }

        return self::$tailles[$url] = $taille;
    }

    /**
     * Fichier correspondant à une adresse du site, ou null si elle est externe.
     *
     * On compare les hôtes plutôt que les adresses complètes : APP_URL et
     * l'hôte réellement servi diffèrent souvent d'un « http/https » ou d'un
     * « www », et une comparaison littérale ferait passer nos propres images
     * pour des images distantes.
     */
    private static function cheminLocal(string $url): ?string
    {
        $chemin = parse_url($url, PHP_URL_PATH);

        if (! $chemin) {
            return null;
        }

        $hote = parse_url($url, PHP_URL_HOST);

        if ($hote) {
            $notres = array_filter([
                parse_url((string) config('app.url'), PHP_URL_HOST),
                request()?->getHost(),
            ]);

            if ($notres && ! in_array($hote, $notres, true)) {
                return null;   // image hébergée ailleurs
            }
        }

        // Le disque « public » est exposé via le lien symbolique public/storage.
        return public_path(rawurldecode(ltrim($chemin, '/')));
    }

    /**
     * Nettoie une valeur destinée à un attribut `content`.
     *
     * Une section déclarée en une ligne — `@section('title', "…")` — est déjà
     * échappée par Blade. La réafficher avec `{{ }}` produirait « &amp;#039; »
     * au lieu d'une apostrophe : on décode donc avant de laisser Blade
     * échapper une seule fois.
     */
    public static function texte(?string $valeur): string
    {
        return trim(html_entity_decode(strip_tags((string) $valeur), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    }

    /**
     * Résumé d'un texte pour l'aperçu : sans balise, sans saut de ligne, et
     * coupé sur un mot entier — les réseaux tronquent au-delà de ~200 signes.
     */
    public static function resume(?string $texte, int $max = 200): string
    {
        $propre = trim(preg_replace('/\s+/u', ' ', strip_tags((string) $texte)));

        if ($propre === '' || mb_strlen($propre) <= $max) {
            return $propre;
        }

        $coupe = mb_substr($propre, 0, $max);
        $espace = mb_strrpos($coupe, ' ');

        return rtrim($espace ? mb_substr($coupe, 0, $espace) : $coupe, " ,;:.") . '…';
    }
}
