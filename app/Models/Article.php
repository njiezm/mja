<?php

namespace App\Models;

use App\Models\Concerns\HasUniqueSlug;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasUniqueSlug;

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
}
