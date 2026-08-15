<?php

namespace App\Console\Commands;

use App\Services\RelanceService;
use Illuminate\Console\Command;

class EnvoyerRelances extends Command
{
    protected $signature = 'mja:relances {--simulation : Liste les envois sans rien expédier}';

    protected $description = 'Envoie les relances dues (cotisation en attente, renouvellement de saison)';

    public function handle(RelanceService $relances): int
    {
        $simulation = (bool) $this->option('simulation');

        $bilan = $relances->executer(simulation: $simulation);

        if ($simulation) {
            $this->info('Simulation — aucun email envoyé.');
            foreach ($bilan['details'] as $ligne) {
                $this->line('  · ' . $ligne);
            }
        }

        $this->info(sprintf(
            '%d relance(s) de paiement, %d de renouvellement%s.',
            $bilan['paiement'],
            $bilan['renouvellement'],
            $bilan['echecs'] ? ", {$bilan['echecs']} échec(s)" : '',
        ));

        return self::SUCCESS;
    }
}
