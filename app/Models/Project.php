<?php

namespace App\Models;

use App\Models\Concerns\HasImage;
use App\Models\Concerns\HasUniqueSlug;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasImage, HasUniqueSlug;

    protected $fillable = [
        'titre', 'slug', 'description_courte', 'description',
        'image', 'statut', 'date_debut', 'date_fin', 'actif', 'ordre',
    ];

    protected $casts = [
        'actif' => 'boolean',
        'date_debut' => 'date',
        'date_fin' => 'date',
        'ordre' => 'integer',
    ];

    public function events(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function scopeActif($query)
    {
        return $query->where('actif', true)->orderByDesc('created_at');
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
    protected function description(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            set: fn (?string $valeur) => \App\Support\HtmlRiche::nettoyer($valeur),
        );
    }
}
