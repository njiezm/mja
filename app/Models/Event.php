<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Event extends Model
{
    protected $fillable = [
        'titre', 'slug', 'description_courte', 'description',
        'image', 'date_debut', 'date_fin', 'lieu', 'adresse',
        'gratuit', 'lien_inscription', 'publie',
    ];

    protected $casts = [
        'publie' => 'boolean',
        'gratuit' => 'boolean',
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($event) {
            if (empty($event->slug)) {
                $event->slug = Str::slug($event->titre);
            }
        });
    }

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
