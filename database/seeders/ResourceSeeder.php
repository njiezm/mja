<?php

namespace Database\Seeders;

use App\Models\Resource;
use Illuminate\Database\Seeder;

class ResourceSeeder extends Seeder
{
    public function run(): void
    {
        $resources = [
            // ─── Association ─────────────────────────────────────────────────────
            [
                'titre' => 'Guide du bénévole MJA',
                'description' => "Tout ce qu'il faut savoir pour rejoindre et s'impliquer dans l'association Madin'Jeunes Ambition. Valeurs, fonctionnement, projets et engagements.",
                'type' => 'guide',
                'categorie' => 'Association',
                'actif' => true,
                'ordre' => 1,
            ],
            [
                'titre' => "Statuts de l'association",
                'description' => "Les statuts officiels de l'association Madin'Jeunes Ambition, fondée le 5 novembre 2011 à Fort-de-France.",
                'type' => 'document',
                'categorie' => 'Association',
                'actif' => true,
                'ordre' => 2,
            ],
            [
                'titre' => 'Rapport d\'activité 2024',
                'description' => "Bilan complet des actions, événements et projets menés par MJA au cours de l'année 2024.",
                'type' => 'document',
                'categorie' => 'Association',
                'actif' => true,
                'ordre' => 3,
            ],
            [
                'titre' => 'Présentation MJA — Diaporama',
                'description' => "Présentation officielle de l'association MJA : histoire, missions, projets et impact. Utile pour nos partenaires et nouveaux membres.",
                'type' => 'document',
                'categorie' => 'Association',
                'actif' => true,
                'ordre' => 4,
            ],

            // ─── Santé ───────────────────────────────────────────────────────────
            [
                'titre' => 'Guide nutrition saine pour les jeunes martiniquais',
                'description' => "Conseils et recommandations pour adopter une alimentation équilibrée au quotidien, intégrant les produits locaux martiniquais.",
                'type' => 'guide',
                'categorie' => 'Santé',
                'actif' => true,
                'ordre' => 5,
            ],
            [
                'titre' => 'Recettes créoles saines — Collection SNS',
                'description' => "12 recettes créoles traditionnelles revisitées pour être plus légères et nutritives. Utilise des produits locaux martiniquais.",
                'type' => 'guide',
                'categorie' => 'Santé',
                'actif' => true,
                'ordre' => 6,
            ],
            [
                'titre' => 'Prévention santé — Les gestes du quotidien',
                'description' => "Fiche pratique sur les gestes essentiels pour prendre soin de sa santé : sommeil, hydratation, activité physique, alimentation.",
                'type' => 'document',
                'categorie' => 'Santé',
                'actif' => true,
                'ordre' => 7,
            ],
            [
                'titre' => 'Programme SNS — Présentation complète',
                'description' => "Présentation détaillée du programme Santé Nutrition Sport de MJA : objectifs, actions, partenaires et résultats.",
                'type' => 'document',
                'categorie' => 'Santé',
                'actif' => true,
                'ordre' => 8,
            ],

            // ─── Jeunesse ────────────────────────────────────────────────────────
            [
                'titre' => 'Service Civique — Comment postuler ?',
                'description' => "Toutes les informations pour postuler à une mission de Service Civique auprès de MJA ou d'autres associations de Martinique.",
                'lien_externe' => 'https://www.engagement-jeunes.com',
                'type' => 'lien',
                'categorie' => 'Jeunesse',
                'actif' => true,
                'ordre' => 9,
            ],
            [
                'titre' => "Guide d'orientation professionnelle en Martinique",
                'description' => "Ressource complète pour les jeunes martiniquais : secteurs qui recrutent, formations disponibles, aides à l'emploi et conseils pratiques.",
                'type' => 'guide',
                'categorie' => 'Jeunesse',
                'actif' => true,
                'ordre' => 10,
            ],
            [
                'titre' => "Aides régionales pour les jeunes — Guide CTM",
                'description' => "Toutes les aides de la Collectivité Territoriale de Martinique accessibles aux jeunes : bourse, permis de conduire, formation, création d'entreprise.",
                'lien_externe' => 'https://www.collectivitedemartinique.mq',
                'type' => 'lien',
                'categorie' => 'Jeunesse',
                'actif' => true,
                'ordre' => 11,
            ],
            [
                'titre' => 'Annuaire des associations de Martinique',
                'description' => "Répertoire des associations actives en Martinique pour trouver des partenaires, des ressources et des opportunités d'engagement.",
                'lien_externe' => 'https://www.martinique.gouv.fr',
                'type' => 'lien',
                'categorie' => 'Jeunesse',
                'actif' => true,
                'ordre' => 12,
            ],

            // ─── Éducation ───────────────────────────────────────────────────────
            [
                'titre' => "Kit création d'association en Martinique",
                'description' => "Guide pratique pour créer votre association en Martinique : démarches administratives, statuts, budget et communication.",
                'type' => 'guide',
                'categorie' => 'Éducation',
                'actif' => true,
                'ordre' => 13,
            ],
            [
                'titre' => "Modèle de CV pour les jeunes",
                'description' => "Template de CV adapté aux jeunes martiniquais pour la recherche d'emploi, de stage ou de formation.",
                'type' => 'document',
                'categorie' => 'Éducation',
                'actif' => true,
                'ordre' => 14,
            ],
        ];

        foreach ($resources as $resource) {
            Resource::firstOrCreate(['titre' => $resource['titre']], $resource);
        }
    }
}
