<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Génération de jetons uniformisée pour toute l'application
 * (création de compte adhérent, liens sécurisés, etc.).
 */
class Token
{
    /** Jeton aléatoire, sûr pour une URL. */
    public static function generate(int $length = 48): string
    {
        return Str::random($length);
    }

    /**
     * Jeton garanti unique pour une colonne d'un modèle donné.
     *
     * @param  class-string<Model>  $modelClass
     */
    public static function uniqueFor(string $modelClass, string $column = 'token', int $length = 48): string
    {
        do {
            $token = self::generate($length);
        } while ($modelClass::query()->where($column, $token)->exists());

        return $token;
    }
}
