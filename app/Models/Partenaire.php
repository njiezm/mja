<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partenaire extends Model
{
    protected $fillable = ['nom', 'logo', 'url', 'description', 'ordre', 'actif'];

    protected $casts = ['actif' => 'boolean'];

    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }
}
