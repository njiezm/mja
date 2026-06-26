<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $projects = [
            [
                'titre' => "Madin' Ti Dèj",
                'slug' => 'madin-ti-dej',
                'description_courte' => 'Distribution de petits-déjeuners sains et équilibrés aux candidats au baccalauréat le jour des épreuves.',
                'description' => "L'opération « Ti Dèj » consiste à distribuer des petits-déjeuners sains et équilibrés aux candidats au baccalauréat le jour des épreuves, notamment lors de l'examen de philosophie pour le baccalauréat général et technologique.\n\nCette action s'inscrit dans le projet Santé Nutrition Sport (SNS), conçu pour encourager les jeunes à adopter de nouvelles habitudes alimentaires en favorisant des produits sains et locaux.\n\nDepuis 2016, ce sont plus de 3 000 petits-déjeuners qui ont été distribués. L'initiative se décline également dans d'autres territoires : Karu' Ti Dèj en Guadeloupe et Guia' Ti Dèj en Guyane.",
                'statut' => 'en_cours',
                'date_debut' => '2016-01-01',
                'actif' => true,
                'ordre' => 1,
            ],
            [
                'titre' => 'Trophées Lumina',
                'slug' => 'trophees-lumina',
                'description_courte' => 'Les grands trophées de la jeunesse martiniquaise pour valoriser et récompenser les jeunes actifs dans différents domaines.',
                'description' => "Les Trophées Lumina sont une initiative co-organisée par Madin'Jeunes Ambition pour valoriser et récompenser les jeunes qui s'investissent dans différents domaines d'activité en Martinique.\n\nCe projet a pour objectif de mettre en lumière les talents et les initiatives des jeunes martiniquais, qu'il s'agisse de projets culturels, entrepreneuriaux, sportifs, sociaux ou éducatifs.\n\nLa première édition s'est tenue en 2012, suivie d'une seconde en 2017. Depuis 2025, les trophées ont été relancés avec une cérémonie annuelle de prestige rassemblant plus de 200 personnes.",
                'statut' => 'en_cours',
                'date_debut' => '2012-01-01',
                'actif' => true,
                'ordre' => 2,
            ],
            [
                'titre' => "Madin'Santé Challenge",
                'slug' => 'madinsante-challenge',
                'description_courte' => 'Un défi sportif et santé collectif de 30 jours pour les jeunes de toute la Martinique.',
                'description' => "Le Madin'Santé Challenge est un défi collectif organisé par MJA pour sensibiliser les jeunes martiniquais aux enjeux de la santé et du bien-être.\n\nPendant 30 jours, les participants s'engagent à adopter de bonnes habitudes : marche quotidienne, alimentation équilibrée, hydratation suffisante et repos.\n\nLes résultats sont partagés sur les réseaux sociaux pour créer une dynamique collective et une émulation positive. Des équipes de 5 à 10 personnes sont formées, avec des récompenses pour les équipes les plus engagées.",
                'statut' => 'en_cours',
                'date_debut' => '2017-06-01',
                'actif' => true,
                'ordre' => 3,
            ],
            [
                'titre' => 'Épanouissement urbain des jeunes',
                'slug' => 'epanouissement-urbain',
                'description_courte' => "Actions d'appui à l'épanouissement des jeunes en milieu urbain à Fort-de-France.",
                'description' => "Dans le cadre de son engagement pour la jeunesse martiniquaise, MJA développe des actions d'appui à l'épanouissement urbain des jeunes.\n\nCe programme inclut des activités citoyennes, des actions de médiation sociale, des ateliers de développement personnel et des initiatives culturelles dans les quartiers de Fort-de-France.\n\nL'association accueille également des volontaires en Service Civique pour renforcer ces actions de proximité.",
                'statut' => 'en_cours',
                'date_debut' => '2018-01-01',
                'actif' => true,
                'ordre' => 4,
            ],
            [
                'titre' => "Madin'Académie",
                'slug' => 'madin-academie',
                'description_courte' => 'Programme de soutien scolaire et d\'accompagnement éducatif pour les jeunes martiniquais en difficulté.',
                'description' => "Madin'Académie est un programme de soutien scolaire et d'accompagnement éducatif développé par MJA en partenariat avec des enseignants volontaires et des étudiants tuteurs.\n\nLe programme propose :\n- Des sessions de soutien scolaire hebdomadaires (mathématiques, français, sciences)\n- Des ateliers méthodologie et gestion du temps\n- Un accompagnement individuel pour les élèves en grande difficulté\n- Des séances d'orientation et de découverte des métiers\n\nPublic cible : collégiens et lycéens de Fort-de-France et environs.",
                'statut' => 'en_cours',
                'date_debut' => '2020-10-01',
                'actif' => true,
                'ordre' => 5,
            ],
            [
                'titre' => 'Green MJA — Engagement environnemental',
                'slug' => 'green-mja',
                'description_courte' => "Sensibilisation des jeunes à l'environnement et aux enjeux écologiques de la Martinique.",
                'description' => "Le programme Green MJA engage les jeunes membres de l'association dans des actions concrètes pour l'environnement martiniquais.\n\nActivités :\n- Opérations de nettoyage des plages et mangroves\n- Ateliers compostage et jardinage urbain\n- Sensibilisation à la biodiversité locale (faune et flore endémiques)\n- Partenariat avec les collectivités pour des actions de recyclage\n- Création de jardins pédagogiques dans les écoles\n\nGreen MJA s'inscrit dans une démarche globale de développement durable pour la Martinique.",
                'statut' => 'en_cours',
                'date_debut' => '2022-04-22',
                'actif' => true,
                'ordre' => 6,
            ],
            [
                'titre' => 'MJA Sport Day',
                'slug' => 'mja-sport-day',
                'description_courte' => 'Grande journée sportive annuelle ouverte à tous les jeunes de la Martinique.',
                'description' => "Le MJA Sport Day est l'événement sportif incontournable organisé chaque été par Madin'Jeunes Ambition.\n\nCette journée rassemble des centaines de jeunes autour du sport et du dépassement de soi :\n- Tournois de football, basketball et volleyball\n- Initiations aux sports de combat\n- Course à pied et activités d'endurance\n- Ateliers bien-être et récupération sportive\n- Village santé avec conseils nutritionnels\n\nLe MJA Sport Day est gratuit et ouvert à tous les jeunes de 12 à 30 ans.",
                'statut' => 'en_cours',
                'date_debut' => '2021-07-01',
                'actif' => true,
                'ordre' => 7,
            ],
            [
                'titre' => 'Koze Mwen — Ateliers Expression',
                'slug' => 'koze-mwen',
                'description_courte' => "Ateliers d'expression orale et de prise de parole en public pour les jeunes martiniquais.",
                'description' => "Koze Mwen (« Parle-moi » en créole martiniquais) est un programme de développement des compétences d'expression et de communication.\n\nFace au constat que de nombreux jeunes peinent à s'exprimer en public, MJA a développé ce programme innovant :\n- Ateliers de prise de parole en public\n- Théâtre forum et improvisation\n- Débats mouvants sur des sujets de société\n- Podcast et médias numériques\n- Slam et expression artistique\n\nLancement prévu pour la rentrée 2026 dans plusieurs établissements de Fort-de-France.",
                'statut' => 'a_venir',
                'date_debut' => '2026-09-01',
                'actif' => true,
                'ordre' => 8,
            ],
        ];

        foreach ($projects as $project) {
            Project::firstOrCreate(['slug' => $project['slug']], $project);
        }
    }
}
