<?php

namespace App\Support;

/**
 * Transforme ce qu'un adhérent a saisi (un pseudo, un lien complet, parfois un
 * simple nom) en URL cliquable — ou renvoie null quand ce n'est pas possible,
 * auquel cas l'information reste affichée sans lien.
 */
class ReseauSocial
{
    /** Gabarits d'URL par réseau, appliqués à un pseudo. */
    private const GABARITS = [
        'instagram' => 'https://www.instagram.com/%s',
        'tiktok'    => 'https://www.tiktok.com/@%s',
        'snapchat'  => 'https://www.snapchat.com/add/%s',
        'x'         => 'https://x.com/%s',
        'facebook'  => null,   // saisi le plus souvent comme « Prénom Nom »
        'linkedin'  => null,   // saisi le plus souvent comme lien complet
    ];

    public static function url(string $reseau, ?string $valeur): ?string
    {
        $valeur = trim((string) $valeur);

        if ($valeur === '') {
            return null;
        }

        // Un lien complet est utilisé tel quel, quel que soit le réseau.
        if (preg_match('#^https?://#i', $valeur)) {
            return $valeur;
        }

        $gabarit = self::GABARITS[$reseau] ?? null;

        if (! $gabarit) {
            return null;
        }

        $pseudo = ltrim($valeur, '@');

        // Un pseudo contenant des espaces n'en est pas un : on n'invente pas d'URL.
        if ($pseudo === '' || preg_match('/\s/', $pseudo)) {
            return null;
        }

        return sprintf($gabarit, rawurlencode($pseudo));
    }
}
