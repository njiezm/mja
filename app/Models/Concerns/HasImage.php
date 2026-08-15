<?php

namespace App\Models\Concerns;

/**
 * Résolution souple du chemin d'image d'un contenu.
 *
 * Historiquement, `image` contenait toujours un chemin relatif au disque
 * `public` (« events/ma-photo.jpg ») et les vues préfixaient par « storage/ ».
 * Cela interdisait de réutiliser une image déjà présente dans public/images/
 * ou d'en héberger une ailleurs. Ce trait accepte les trois formes :
 *
 *  - « https://… »        → utilisée telle quelle ;
 *  - « images/kit/x.jpg » → servie depuis public/ ;
 *  - « events/x.jpg »     → servie depuis le disque public (storage/).
 */
trait HasImage
{
    /** URL affichable de l'image, ou null s'il n'y en a pas. */
    public function imageUrl(): ?string
    {
        $chemin = trim((string) $this->image);

        if ($chemin === '') {
            return null;
        }

        if (preg_match('#^(https?:)?//#i', $chemin)) {
            return $chemin;
        }

        $chemin = ltrim($chemin, '/');

        // Les noms de fichiers contiennent parfois des espaces : chaque segment
        // est encodé, les séparateurs restent des « / ».
        $encode = fn (string $p) => implode('/', array_map('rawurlencode', explode('/', $p)));

        // Les fichiers déjà livrés avec le site vivent sous public/.
        if (str_starts_with($chemin, 'images/')) {
            return asset($encode($chemin));
        }

        return asset('storage/' . $encode($chemin));
    }
}
