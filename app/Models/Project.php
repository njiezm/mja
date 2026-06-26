<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Project extends Model
{
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

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($project) {
            if (empty($project->slug)) {
                $project->slug = Str::slug($project->titre);
            }
        });
    }

    public function scopeActif($query)
    {
        return $query->where('actif', true)->orderByDesc('created_at');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
