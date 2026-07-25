<?php

namespace App\Http\Middleware;

use App\Models\Source;
use App\Models\SourceVisit;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class TrackVisit
{
    /**
     * Attribue une visite lorsqu'un lien porte un paramètre ?utm_source=…
     * (Facebook Ads, newsletter, etc.). La source est créée si besoin.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethod('GET')
            && $request->filled('utm_source')
            && ! $request->is('admin', 'admin/*')) {

            $slug = Str::slug(substr((string) $request->query('utm_source'), 0, 60));

            if ($slug !== '') {
                $source = Source::firstOrCreate(
                    ['slug' => $slug],
                    ['label' => Str::title(str_replace('-', ' ', $slug)), 'target' => '/', 'is_active' => true]
                );

                SourceVisit::create([
                    'source_id'    => $source->id,
                    'visitor_hash' => hash('sha256', $request->ip() . '|' . (string) $request->userAgent()),
                    'ip'           => $request->ip(),
                    'user_agent'   => $request->userAgent(),
                    'referer'      => $request->headers->get('referer'),
                    'utm_medium'   => $request->query('utm_medium'),
                    'utm_campaign' => $request->query('utm_campaign'),
                    'device'       => SourceVisit::detectDevice($request->userAgent()),
                ]);

                $request->session()->put('mja_source_id', $source->id);
            }
        }

        return $next($request);
    }
}
