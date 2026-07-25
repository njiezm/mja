<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdhesionPeriod extends Model
{
    protected $fillable = ['label', 'date_debut', 'date_fin', 'actif'];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin'   => 'date',
        'actif'      => 'boolean',
    ];

    public function adhesions(): HasMany
    {
        return $this->hasMany(Adhesion::class, 'period_id');
    }

    /** Période active correspondant à une date donnée (par défaut : aujourd'hui). */
    public static function forDate($date = null): ?self
    {
        $date = $date ? \Illuminate\Support\Carbon::parse($date) : now();

        return static::where('actif', true)
            ->whereDate('date_debut', '<=', $date)
            ->whereDate('date_fin', '>=', $date)
            ->orderByDesc('date_debut')
            ->first();
    }

    /** Période courante (aujourd'hui). */
    public static function current(): ?self
    {
        return static::forDate(now());
    }
}
