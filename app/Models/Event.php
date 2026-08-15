<?php

namespace App\Models;

use App\Models\Concerns\HasImage;
use App\Models\Concerns\HasUniqueSlug;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasImage, HasUniqueSlug;

    protected $fillable = [
        'titre', 'slug', 'description_courte', 'description',
        'image', 'date_debut', 'date_fin', 'lieu', 'adresse',
        'gratuit', 'prix', 'lien_inscription', 'publie', 'project_id',
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

    public function project(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Le contenu vient de l'éditeur enrichi du back-office : il est assaini
     * ici, à l'écriture, quel que soit le chemin emprunté (formulaire, seeder,
     * import). Rien de non autorisé ne peut donc atteindre la base.
     */
    protected function description(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            set: fn (?string $valeur) => \App\Support\HtmlRiche::nettoyer($valeur),
        );
    }

    /** Génère le contenu d'un fichier .ics (agenda) pour cet événement. */
    public function toIcs(): string
    {
        $fmt = fn ($d) => $d ? $d->clone()->utc()->format('Ymd\THis\Z') : null;
        $uid = 'event-' . $this->id . '@' . request()->getHost();
        $end = $this->date_fin ?: $this->date_debut->clone()->addHours(2);

        $esc = fn ($t) => addcslashes(preg_replace('/\s+/', ' ', strip_tags((string) $t)), ",;\\");

        $lines = [
            'BEGIN:VCALENDAR',
            'VERSION:2.0',
            'PRODID:-//Madin Jeunes Ambition//FR',
            'CALSCALE:GREGORIAN',
            'BEGIN:VEVENT',
            'UID:' . $uid,
            'DTSTAMP:' . $fmt(now()),
            'DTSTART:' . $fmt($this->date_debut),
            'DTEND:' . $fmt($end),
            'SUMMARY:' . $esc($this->titre),
            'DESCRIPTION:' . $esc($this->description_courte ?: $this->description),
            'LOCATION:' . $esc(trim(($this->lieu ?? '') . ' ' . ($this->adresse ?? ''))),
            'URL:' . route('events.show', $this->slug),
            'END:VEVENT',
            'END:VCALENDAR',
        ];

        return implode("\r\n", $lines);
    }
}
