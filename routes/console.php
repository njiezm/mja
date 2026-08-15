<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Purge quotidienne des comptes membres supprimés depuis plus de 30 jours.
Schedule::command('mja:purge-members')->dailyAt('03:00');

// Sauvegarde hebdomadaire (base de données + fichiers déposés).
Schedule::command('mja:backup')->weeklyOn(0, '02:00');

// Relances (cotisation en attente, renouvellement de saison).
// L'hébergement actuel n'a pas de cron : c'est le middleware
// DeclencheurRelances qui prend le relais. La planification reste déclarée ici
// pour qu'il suffise de brancher `schedule:run` le jour où c'est possible.
Schedule::command('mja:relances')->dailyAt('09:00');
