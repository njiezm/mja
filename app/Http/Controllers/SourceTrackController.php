<?php

namespace App\Http\Controllers;

use App\Models\Source;
use App\Models\SourceVisit;
use Illuminate\Http\Request;

class SourceTrackController extends Controller
{
    /**
     * Enregistre une visite issue d'un lien de tracking /{source} puis redirige
     * vers la destination configurée. Mémorise la source en session pour
     * l'attribution des conversions (adhésion / contact).
     */
    public function handle(Request $request, string $source)
    {
        $model = Source::where('slug', $source)->where('is_active', true)->first();

        // Slug inconnu ou désactivé → 404 normal (comportement inchangé).
        abort_unless($model, 404);

        SourceVisit::create([
            'source_id'    => $model->id,
            'visitor_hash' => hash('sha256', $request->ip() . '|' . (string) $request->userAgent()),
            'ip'           => $request->ip(),
            'user_agent'   => $request->userAgent(),
            'referer'      => $request->headers->get('referer'),
            'utm_medium'   => $request->query('utm_medium'),
            'utm_campaign' => $request->query('utm_campaign'),
            'device'       => SourceVisit::detectDevice($request->userAgent()),
        ]);

        $request->session()->put('mja_source_id', $model->id);

        $target = $model->target ?: '/';

        return redirect(str_starts_with($target, 'http') ? $target : '/' . ltrim($target, '/'));
    }
}
