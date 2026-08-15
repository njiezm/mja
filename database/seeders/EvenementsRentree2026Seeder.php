<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Project;
use Illuminate\Database\Seeder;

/**
 * Événements de la rentrée 2026, extraits du plan de communication.
 *
 * Le plan liste des *dates de publication*, pas des dates d'événement : la
 * plupart des lignes sont des visuels, des stories ou des rappels de campagne,
 * qui n'ont rien à faire sur le site. Seules les lignes correspondant à un
 * rendez-vous réel sont reprises ici.
 *
 * Tout ce dont la date ou le lieu n'est pas explicitement donné dans le plan
 * est créé **non publié** : mieux vaut un brouillon à compléter en back-office
 * qu'une date inventée affichée au public.
 */
class EvenementsRentree2026Seeder extends Seeder
{
    public function run(): void
    {
        $caravane = Project::where('slug', 'like', '%caravane%')->first();

        $evenements = [
            [
                // Seule ligne du plan qui donne date ET lieu explicitement.
                'titre'              => 'Journée Portes Ouvertes des Associations',
                'slug'               => 'journee-portes-ouvertes-associations-2026',
                'description_courte' => "MJA vous accueille sur son stand à la Savane pour la journée des associations.",
                'description'        => "Madin'Jeunes Ambition tient un stand à la Journée Portes Ouvertes des Associations, à la Savane.\n\nVenez rencontrer l'équipe, découvrir nos projets et poser vos questions sur l'adhésion. C'est l'occasion idéale de nous connaître avant de nous rejoindre.",
                'date_debut'         => '2026-09-05 09:00:00',
                'lieu'               => 'La Savane',
                'adresse'            => 'La Savane, Fort-de-France',
                'gratuit'            => true,
                'publie'             => true,
            ],
            [
                'titre'              => 'MJA Boat Party',
                'slug'               => 'mja-boat-party-2026',
                'description_courte' => "Sortie en mer conviviale ouverte aux adhérents et sympathisants.",
                'description'        => "Sortie bateau organisée par Madin'Jeunes Ambition : un moment de détente et de cohésion entre adhérents, sympathisants et curieux.\n\n[À compléter avant publication : date et heure définitives, lieu d'embarquement, tarif, modalités d'inscription.]",
                'date_debut'         => '2026-08-30 10:00:00',
                'lieu'               => 'À confirmer',
                'gratuit'            => true,
                'publie'             => false,
            ],
            [
                'titre'              => 'MJA Fitness',
                'slug'               => 'mja-fitness-septembre-2026',
                'description_courte' => "Séance de sport collective ouverte à tous les niveaux.",
                'description'        => "Séance MJA Fitness : bouger ensemble, quel que soit son niveau, dans le prolongement du programme Santé · Nutrition · Sport.\n\n[À compléter avant publication : date exacte — le plan indique « deuxième semaine de septembre » —, lieu, encadrement et matériel à prévoir.]",
                'date_debut'         => '2026-09-12 08:00:00',
                'lieu'               => 'À confirmer',
                'gratuit'            => true,
                'publie'             => false,
            ],
            [
                'titre'              => 'Journée Portes Ouvertes MJA',
                'slug'               => 'journee-portes-ouvertes-mja-2026',
                'description_courte' => "Une journée pour découvrir l'association, son local et son équipe.",
                'description'        => "Madin'Jeunes Ambition ouvre ses portes : présentation des projets, rencontre avec l'équipe et réponses à toutes vos questions sur l'engagement associatif.\n\n[À compléter avant publication : date exacte — le plan indique « première semaine d'octobre » —, horaires et équipe présente.]",
                'date_debut'         => '2026-10-03 09:00:00',
                'lieu'               => 'Local de MJA',
                'gratuit'            => true,
                'publie'             => false,
            ],
            [
                // Retours d'activité : le plan les date en publication, pas en
                // déroulé. Les vraies dates restent à renseigner.
                'titre'              => 'Foyal Color Red',
                'slug'               => 'foyal-color-red-2026',
                'description_courte' => "Participation de MJA à Foyal Color Red.",
                'description'        => "Madin'Jeunes Ambition a participé à Foyal Color Red.\n\n[À compléter avant publication : date réelle de l'événement, lieu, nature de la participation, et vérification des droits à l'image sur les photos retenues.]",
                'date_debut'         => '2026-08-18 09:00:00',
                'lieu'               => 'À confirmer',
                'gratuit'            => true,
                'publie'             => false,
            ],
            [
                'titre'              => "MJ'Afterwork cinéma",
                'slug'               => 'mj-afterwork-cinema-2026',
                'description_courte' => "Soirée cinéma entre adhérents au local de l'association.",
                'description'        => "Afterwork cinéma au local : une soirée détente entre adhérents, dans la continuité de la vie associative de MJA.\n\n[À compléter avant publication : date confirmée et film projeté.]",
                'date_debut'         => '2026-08-10 18:30:00',
                'lieu'               => 'Local de MJA',
                'gratuit'            => true,
                'publie'             => false,
            ],
        ];

        foreach ($evenements as $donnees) {
            // Rattachement au projet « Caravane de l'unité » quand il existe :
            // un projet accepte de zéro à N événements.
            if ($caravane && str_contains($donnees['slug'], 'caravane')) {
                $donnees['project_id'] = $caravane->id;
            }

            Event::updateOrCreate(['slug' => $donnees['slug']], $donnees);
        }

        $this->command?->info(count($evenements) . ' événement(s) de rentrée créés ou mis à jour.');
        $this->command?->warn('5 sur 6 sont en brouillon : complétez date, lieu et photo en back-office avant publication.');
    }
}
