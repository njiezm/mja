<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

/**
 * Génère automatiquement un slug unique à partir du titre.
 *
 * En cas de collision (deux contenus au même titre), un suffixe numérique
 * est ajouté : mon-titre, mon-titre-2, mon-titre-3, …
 * Un slug fourni manuellement est également rendu unique.
 */
trait HasUniqueSlug
{
    protected static function bootHasUniqueSlug(): void
    {
        static::saving(function ($model): void {
            $source = $model->slug ?: ($model->{static::slugSource()} ?? '');
            $base   = Str::slug($source);

            if ($base === '') {
                return;
            }

            // Ne recalcule que si le slug est vide ou si le titre source a changé.
            if ($model->slug && ! $model->isDirty(static::slugSource())) {
                return;
            }

            $model->slug = static::makeSlugUnique($base, $model->getKey());
        });
    }

    protected static function makeSlugUnique(string $base, $ignoreId = null): string
    {
        $slug  = $base;
        $count = 2;

        while (
            static::query()
                ->where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->whereKeyNot($ignoreId))
                ->exists()
        ) {
            $slug = "{$base}-{$count}";
            $count++;
        }

        return $slug;
    }

    /** Colonne servant de base au slug (surchargeable dans le modèle). */
    protected static function slugSource(): string
    {
        return 'titre';
    }
}
