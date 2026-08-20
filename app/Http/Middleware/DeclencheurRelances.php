<?php

namespace App\Http\Middleware;

use App\Services\RelanceService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * Déclencheur de relances sans tâche planifiée.
 *
 * L'hébergement ne propose pas de cron : les relances sont donc évaluées à
 * l'occasion des visites du site. Le travail a lieu dans `terminate()`, après
 * l'envoi de la réponse — le visiteur n'attend jamais.
 *
 * Deux garde-fous : un verrou atomique (deux visites simultanées ne déclenchent
 * qu'un seul passage) et un marqueur journalier (un seul passage par jour, à
 * partir de l'heure configurée). Le journal `adhesion_relances` reste de toute
 * façon la protection finale contre les doublons.
 *
 * Si un cron devient disponible, il suffit de planifier `mja:relances` et de
 * retirer ce middleware : le reste ne change pas.
 */
class DeclencheurRelances
{
    private const VERROU  = 'mja:relances:verrou';
    private const MARQUEUR = 'mja:relances:dernier-jour';

    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }

    public function terminate(Request $request, Response $response): void
    {
        // Une requête de bot ou un simple asset n'a pas à porter ce travail :
        // on ne se déclenche que sur une page réellement rendue.
        if (! $request->isMethod('GET') || $request->ajax()) {
            return;
        }

        if (! $this->cEstLeMoment()) {
            return;
        }

        // Verrou court : la tâche est brève, mais deux visites simultanées ne
        // doivent pas la lancer deux fois.
        $verrou = Cache::lock(self::VERROU, 300);

        if (! $verrou->get()) {
            return;
        }

        try {
            // Marqué avant l'envoi : en cas d'erreur, on ne réessaie pas en
            // boucle à chaque visite — la prochaine fenêtre suffira.
            Cache::put(self::MARQUEUR, now()->toDateString(), now()->addDays(2));

            app(RelanceService::class)->executer();
        } catch (\Throwable $e) {
            Log::error('[Relances] déclenchement web échoué : ' . $e->getMessage());
        } finally {
            $verrou->release();
        }
    }

    /** Après l'heure configurée, et pas déjà passé aujourd'hui. */
    private function cEstLeMoment(): bool
    {
        // Suspendu en back-office : ni verrou ni marqueur à consommer.
        if (RelanceService::suspendues()) {
            return false;
        }

        if (! RelanceService::actif('relance_paiement_active')
            && ! RelanceService::actif('relance_renouvellement_active')) {
            return false;
        }

        if (now()->hour < RelanceService::reglage('relances_heure')) {
            return false;
        }

        return Cache::get(self::MARQUEUR) !== now()->toDateString();
    }
}
