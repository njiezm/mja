<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{
    protected $fillable = [
        'titre', 'slug', 'extrait', 'contenu', 'image',
        'categorie', 'auteur', 'publie', 'publie_le',
    ];

    protected $casts = [
        'publie' => 'boolean',
        'publie_le' => 'datetime',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($article) {
            if (empty($article->slug)) {
                $article->slug = Str::slug($article->titre);
            }
        });
    }

    public function scopePublie($query)
    {
        return $query->where('publie', true)->orderByDesc('publie_le');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
