<?php

namespace App\Models;

use App\Models\Concerns\HasUniqueSlug;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasUniqueSlug;

    protected $fillable = [
        'titre', 'slug', 'description_courte', 'description',
        'image', 'date_debut', 'date_fin', 'lieu', 'adresse',
        'gratuit', 'prix', 'lien_inscription', 'publie',
    ];

    protected $casts = [
        'publie' => 'boolean',
        'gratuit' => 'boolean',
        'prix' => 'decimal:2',
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
    ];

    public function scopePublie($query)
    {
        return $query->where('publie', true)->orderBy('date_debut');
    }

    public function scopeAvenir($query)
    {
        return $query->where('publie', true)->where('date_debut', '>=', now())->orderBy('date_debut');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
