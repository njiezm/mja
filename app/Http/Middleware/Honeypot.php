<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Honeypot
{
    /** Nom du champ piège (invisible pour les humains, rempli par les bots). */
    public const FIELD = 'site_web';

    /**
     * Bloque les soumissions dont le champ piège est rempli (bots).
     * Réponse neutre (retour au formulaire) pour ne pas informer le robot.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (filled($request->input(self::FIELD))) {
            return back();
        }

        return $next($request);
    }
}
