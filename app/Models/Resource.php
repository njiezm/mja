<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    protected $fillable = [
        'titre', 'description', 'fichier', 'lien_externe',
        'type', 'categorie', 'actif', 'ordre',
    ];

    protected $casts = [
        'actif' => 'boolean',
        'ordre' => 'integer',
    ];

    /** Types disponibles : clé => [libellé, icône FontAwesome]. */
    public const TYPES = [
        'document'    => ['Document', 'fa-file-lines'],
        'pdf'         => ['PDF', 'fa-file-pdf'],
        'guide'       => ['Guide', 'fa-book-open'],
        'lien'        => ['Lien externe', 'fa-link'],
        'video'       => ['Vidéo', 'fa-video'],
        'audio'       => ['Audio', 'fa-music'],
        'podcast'     => ['Podcast', 'fa-microphone'],
        'infographie' => ['Infographie', 'fa-chart-column'],
    ];

    public function typeLabel(): string
    {
        return self::TYPES[$this->type][0] ?? ucfirst((string) $this->type);
    }

    public function typeIcon(): string
    {
        return self::TYPES[$this->type][1] ?? 'fa-file';
    }

    public function scopeActif($query)
    {
        return $query->where('actif', true)->orderBy('ordre');
    }
}
