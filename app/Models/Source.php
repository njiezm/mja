<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Source extends Model
{
    protected $fillable = ['slug', 'label', 'description', 'target', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function visits(): HasMany
    {
        return $this->hasMany(SourceVisit::class);
    }

    public function adhesions(): HasMany
    {
        return $this->hasMany(Adhesion::class);
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class);
    }

    /** URL publique complète de tracking. */
    public function trackingUrl(): string
    {
        return url('/' . $this->slug);
    }
}
