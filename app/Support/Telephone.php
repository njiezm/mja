<?php

namespace App\Support;

/**
 * Mise en forme des numéros de téléphone : trois chiffres, puis des paires
 * (« 696 43 88 21 »). Le même découpage est appliqué côté navigateur pendant
 * la saisie et ici avant enregistrement, pour que la base ne contienne qu'un
 * seul format quelle que soit la façon dont le numéro est arrivé.
 */
class Telephone
{
    /** Formate la partie « numéro » (sans indicatif). */
    public static function formater(?string $numero): string
    {
        $chiffres = preg_replace('/\D+/', '', (string) $numero) ?? '';

        if ($chiffres === '') {
            return '';
        }

        $chiffres = substr($chiffres, 0, 10);

        if (strlen($chiffres) <= 3) {
            return $chiffres;
        }

        $morceaux = [substr($chiffres, 0, 3)];

        foreach (str_split(substr($chiffres, 3), 2) as $paire) {
            $morceaux[] = $paire;
        }

        return implode(' ', $morceaux);
    }

    /** Assemble « +596 696 43 88 21 » à partir de l'indicatif et du numéro. */
    public static function complet(?string $indicatif, ?string $numero): string
    {
        $numero    = self::formater($numero);
        $indicatif = trim((string) $indicatif);

        return trim(($indicatif !== '' ? $indicatif . ' ' : '') . $numero);
    }

    /**
     * Sépare un numéro stocké en [indicatif, numéro] pour ré-alimenter le
     * formulaire (renouvellement, édition du profil).
     *
     * @return array{0: string, 1: string}
     */
    public static function separer(?string $complet, string $defaut = '+596'): array
    {
        $complet = trim((string) $complet);

        if ($complet === '') {
            return [$defaut, ''];
        }

        // Les indicatifs les plus longs d'abord : +590 doit l'emporter sur +59.
        $indicatifs = ['+596', '+590', '+594', '+597', '+509', '+33', '+32', '+41', '+44', '+55', '+1'];

        foreach ($indicatifs as $indicatif) {
            if (str_starts_with($complet, $indicatif)) {
                return [$indicatif, self::formater(substr($complet, strlen($indicatif)))];
            }
        }

        return [$defaut, self::formater($complet)];
    }
}
