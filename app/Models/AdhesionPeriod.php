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

    /**
     * Saison à laquelle rattacher une adhésion enregistrée maintenant.
     *
     * Deux saisons se suivent rarement au jour près : entre la fin de l'une
     * et le début de l'autre, aucune ne contient la date du jour. Sans
     * repli, les adhésions de cet intervalle partaient sans saison —
     * invisibles dans les filtres, les exports et les relances.
     *
     * Ordre retenu : la saison en cours, sinon la prochaine à s'ouvrir,
     * sinon la dernière connue.
     */
    public static function pourAdhesion(): ?self
    {
        if ($courante = static::current()) {
            return $courante;
        }

        $prochaine = static::where('actif', true)
            ->whereDate('date_debut', '>', now())
            ->orderBy('date_debut')
            ->first();

        return $prochaine ?: static::where('actif', true)->orderByDesc('date_debut')->first();
    }
}
