<?php

namespace App\Models;

use App\Models\Concerns\HasUniqueSlug;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasUniqueSlug;

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

    public function scopeActif($query)
    {
        return $query->where('actif', true)->orderByDesc('created_at');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
