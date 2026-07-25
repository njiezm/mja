<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SourceVisit extends Model
{
    protected $fillable = ['source_id', 'visitor_hash', 'ip', 'user_agent', 'referer', 'utm_medium', 'utm_campaign', 'device'];

    public function source(): BelongsTo
    {
        return $this->belongsTo(Source::class);
    }

    /** Déduit le type d'appareil à partir du user-agent. */
    public static function detectDevice(?string $ua): string
    {
        $ua = (string) $ua;
        if (preg_match('/iPad|Tablet|PlayBook|Silk|(Android(?!.*Mobile))/i', $ua)) {
            return 'tablet';
        }
        if (preg_match('/Mobile|iP(hone|od)|Android.*Mobile|BlackBerry|IEMobile|Opera Mini/i', $ua)) {
            return 'mobile';
        }
        return 'desktop';
    }

    /** Domaine lisible d'une provenance (referer) : « Facebook », « Google », « Direct »… */
    public static function refererLabel(?string $referer): string
    {
        if (! $referer) {
            return 'Accès direct';
        }
        $host = parse_url($referer, PHP_URL_HOST) ?: $referer;
        $host = preg_replace('/^www\./', '', strtolower($host));

        return match (true) {
            str_contains($host, 'facebook') || str_contains($host, 'fb.')  => 'Facebook',
            str_contains($host, 'instagram')                                => 'Instagram',
            str_contains($host, 'tiktok')                                   => 'TikTok',
            str_contains($host, 'youtube') || str_contains($host, 'youtu.be') => 'YouTube',
            str_contains($host, 'google')                                   => 'Google',
            str_contains($host, 'bing')                                     => 'Bing',
            str_contains($host, 't.co') || str_contains($host, 'twitter') || str_contains($host, 'x.com') => 'X / Twitter',
            str_contains($host, 'whatsapp')                                 => 'WhatsApp',
            str_contains($host, request()->getHost())                       => 'Site interne',
            default                                                          => $host,
        };
    }
}
