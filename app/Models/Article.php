<?php

namespace App\Models;

use App\Models\Concerns\HasImage;
use App\Models\Concerns\HasUniqueSlug;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasImage, HasUniqueSlug;

    protected $fillable = [
        'titre', 'slug', 'extrait', 'contenu', 'image',
        'categorie', 'auteur', 'publie', 'publie_le',
    ];

    protected $casts = [
        'publie' => 'boolean',
        'publie_le' => 'datetime',
    ];

    public function scopePublie($query)
    {
        return $query->where('publie', true)->orderByDesc('publie_le');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
    /**
     * Le contenu vient de l'éditeur enrichi du back-office : il est assaini
     * ici, à l'écriture, quel que soit le chemin emprunté (formulaire, seeder,
     * import). Rien de non autorisé ne peut donc atteindre la base.
     */
    protected function contenu(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            set: fn (?string $valeur) => \App\Support\HtmlRiche::nettoyer($valeur),
        );
    }
}
