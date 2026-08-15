<?php

namespace Database\Seeders;

use App\Models\AdhesionPeriod;
use App\Models\Event;
use Illuminate\Database\Seeder;

/**
 * Saison d'adhésion 2026-2027 et calage de la Foyal Color Red.
 *
 * La saison couvre une année pleine : la campagne du 17 août au 31 octobre
 * n'est que la fenêtre d'inscription. Si la période s'arrêtait au 31 octobre,
 * plus aucune période ne serait active ensuite et les relances de
 * renouvellement ne partiraient jamais.
 */
class SaisonAdhesion2026Seeder extends Seeder
{
    public function run(): void
    {
        $periode = AdhesionPeriod::updateOrCreate(
            ['label' => 'Saison 2026-2027'],
            [
                'date_debut' => '2026-08-17',
                'date_fin'   => '2027-08-16',
                'actif'      => true,
            ],
        );

        $this->command?->info("Période « {$periode->label} » : du 17/08/2026 au 16/08/2027.");

        // La Foyal Color Red 2026 se tient le 22 août au Parc La Savane.
        // La participation de MJA reste à confirmer : l'événement demeure en
        // brouillon, mais sa date et son lieu ne sont plus des inconnues.
        $foyal = Event::updateOrCreate(
            ['slug' => 'foyal-color-red-2026'],
            [
                'titre'              => 'Foyal Color Red',
                'description_courte' => "Participation de MJA à la Foyal Color Red, 5 km de couleurs au Parc La Savane.",
                'description'        => "Madin'Jeunes Ambition participe à la Foyal Color Red : cinq kilomètres de couleurs à travers Fort-de-France, au départ du Parc La Savane.\n\n[À compléter avant publication : confirmation de la participation, heure de rendez-vous de l'équipe MJA, nombre de participants.]",
                'date_debut'         => '2026-08-22 08:00:00',
                'lieu'               => 'Parc La Savane',
                'adresse'            => 'Parc La Savane, Fort-de-France',
                'gratuit'            => true,
                'publie'             => false,
            ],
        );

        $this->command?->info("Événement « {$foyal->titre} » calé au 22/08/2026 (brouillon).");
        $this->command?->warn("Publier l'événement une fois la participation de MJA confirmée.");
    }
}
