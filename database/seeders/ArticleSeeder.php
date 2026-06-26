<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $articles = [
            [
                'titre' => "Fwi Ti Dèj 2025 : 3 territoires mobilisés le même jour pour le bac !",
                'slug' => 'fwi-ti-dej-bac-2025-trois-territoires',
                'extrait' => "Le 16 juin 2025, Madin'Jeunes Ambition et son réseau Fwi Ti Dèj ont distribué simultanément des petits-déjeuners en Martinique, Guadeloupe et Guyane.",
                'contenu' => "Le 16 juin 2025, le réseau Fwi Ti Dèj a réalisé un beau tour de force : mobiliser trois territoires ultramarins simultanément le matin de l'épreuve de philosophie du baccalauréat.\n\nMadin' Ti Dèj (Martinique) — 8e édition : tous les centres d'examen de Martinique ont été couverts. Des bénévoles MJA se sont levés à l'aube pour préparer et distribuer des petits-déjeuners équilibrés à des centaines de candidats.\n\nKaru' Ti Dèj (Guadeloupe, Saint-Martin, Marie-Galante) — avec les précieux partenaires Chemin d'Audace, K2 Esports et Unik Prod qui ont rendu l'opération possible sur l'archipel.\n\nGuia' Ti Dèj (Guyane) — avec le soutien du CAVL, de Graine D'Elie et de la Maison des Lycéens LP Melkior Garré pour couvrir les centres guyanais.\n\nAu total : plus de 600 petits-déjeuners distribués gratuitement, un message de prévention nutritionnelle porté dans toute la francophonie ultramarine.\n\nMerci aux bénévoles et partenaires qui rendent chaque année cette opération possible !",
                'categorie' => 'actualite',
                'auteur' => 'MJA',
                'publie' => true,
                'publie_le' => '2025-06-16 12:00:00',
            ],
            [
                'titre' => "Madin'Jeunes Ambition lance son programme Madin'Santé Challenge",
                'slug' => 'mja-madinsante-challenge',
                'extrait' => "MJA lance le Madin'Santé Challenge, un défi collectif de 30 jours pour adopter un mode de vie plus sain.",
                'contenu' => "Madin'Jeunes Ambition lance son Madin'Santé Challenge, une initiative innovante qui invite les jeunes martiniquais à relever un défi santé collectif sur 30 jours.\n\nLe principe est simple : en équipe de 5 à 10 personnes, les participants s'engagent à adopter de bonnes habitudes quotidiennes — marche, alimentation équilibrée, hydratation, sommeil.\n\nChaque semaine, les équipes partagent leurs progrès sur les réseaux sociaux avec le hashtag #MadinSanteChallenge, créant une émulation positive à l'échelle de toute l'île.\n\nDes récompenses sont prévues pour les équipes les plus engagées. Inscriptions ouvertes sur la page Facebook de MJA.",
                'categorie' => 'sante',
                'auteur' => 'MJA',
                'publie' => true,
                'publie_le' => '2024-03-15 10:00:00',
            ],
            [
                'titre' => "Rejoignez MJA en Service Civique !",
                'slug' => 'rejoignez-mja-service-civique',
                'extrait' => "Madin'Jeunes Ambition recrute des volontaires en Service Civique pour développer ses actions en faveur des jeunes de Fort-de-France.",
                'contenu' => "Vous avez entre 16 et 25 ans et vous souhaitez vous engager pour votre territoire ?\n\nMadin'Jeunes Ambition recrute des volontaires en Service Civique pour renforcer ses équipes.\n\nEn rejoignant MJA, vous participerez à :\n- L'organisation d'événements (Ti Dèj, ateliers santé, événements culturels)\n- La communication de l'association (réseaux sociaux, presse)\n- L'animation d'ateliers et d'activités pour les jeunes\n- Le développement de nouveaux projets\n\nIndemnité mensuelle versée par l'État. Formation civique et citoyenne incluse. Pour postuler, contactez-nous via le formulaire de contact.",
                'categorie' => 'actualite',
                'auteur' => 'MJA',
                'publie' => true,
                'publie_le' => '2025-01-10 09:00:00',
            ],
            [
                'titre' => "Bilan 2024 : Une année riche pour Madin'Jeunes Ambition",
                'slug' => 'bilan-2024-mja',
                'extrait' => "Retour sur les actions, événements et projets qui ont marqué l'année 2024 pour l'association Madin'Jeunes Ambition.",
                'contenu' => "L'année 2024 a été particulièrement riche pour Madin'Jeunes Ambition. Retour sur les temps forts.\n\nAu total, l'association a organisé ou participé à plus de 12 événements, touchant plusieurs centaines de jeunes martiniquais.\n\nParmi les temps forts 2024 :\n- Opération Ti Dèj au bac : 400 petits-déjeuners distribués\n- Participation au Forum des Associations de Fort-de-France\n- Lancement du programme Green MJA\n- Accueil de 5 nouveaux volontaires en Service Civique\n- Renforcement du partenariat avec la Collectivité Territoriale de Martinique\n\nMerci à tous nos membres, bénévoles, partenaires et sympathisants. Ensemble, nous continuons à faire rayonner les valeurs de MJA !",
                'categorie' => 'actualite',
                'auteur' => 'MJA',
                'publie' => true,
                'publie_le' => '2024-12-28 10:00:00',
            ],
            [
                'titre' => "Trophées Lumina 2025 : les lauréats sont connus !",
                'slug' => 'trophees-lumina-2025-laureats',
                'extrait' => "La cérémonie des Trophées Lumina 2025 a récompensé 8 jeunes talents martiniquais lors d'une soirée de prestige au Palais des Congrès.",
                'contenu' => "La soirée des Trophées Lumina 2025 a réuni plus de 200 personnes au Palais des Congrès de Madiana pour célébrer les jeunes talents martiniquais.\n\nCe prix, co-organisé par Madin'Jeunes Ambition, vise à mettre en lumière les jeunes qui s'illustrent par leur engagement dans différents domaines.\n\nLes lauréats 2025 :\n- Trophée Entrepreneuriat : Marie-Laure J., fondatrice d'une startup agro-alimentaire\n- Trophée Culture : Le collectif artistic' Martinique\n- Trophée Sport : Jérémy D., champion de judo\n- Trophée Engagement associatif : Sarah M., présidente d'une association de jeunes\n- Trophée Éducation : Marcus L., créateur d'une plateforme de cours en créole\n- Trophée Innovation : Tech4Matie, startup technologique locale\n- Trophée Solidarité : L'équipe bénévole des Lumières du Nord\n- Coup de cœur du jury : Chloé B., 17 ans, militante environnementale\n\nFélicitations à tous les lauréats !",
                'categorie' => 'actualite',
                'auteur' => 'MJA',
                'publie' => true,
                'publie_le' => '2025-12-07 09:00:00',
            ],
            [
                'titre' => "Green MJA : lancement du programme environnemental",
                'slug' => 'lancement-green-mja',
                'extrait' => "MJA engage ses membres dans des actions concrètes pour l'environnement martiniquais avec le lancement de Green MJA.",
                'contenu' => "Madin'Jeunes Ambition franchit une nouvelle étape avec le lancement de Green MJA, son programme dédié à l'engagement environnemental des jeunes.\n\nFace aux enjeux climatiques et à la fragilité des écosystèmes martiniquais, MJA souhaite mobiliser sa jeunesse autour d'actions concrètes et visibles.\n\nPremières actions :\n- Nettoyage de la plage de la Pointe du Bout avec 40 bénévoles\n- Plantation d'arbres fruitiers locaux dans un jardin partagé de Fort-de-France\n- Atelier de sensibilisation au tri sélectif dans un collège\n- Création d'un potager pédagogique avec une école primaire\n\n\"Nous voulons montrer aux jeunes qu'agir pour l'environnement, c'est aussi agir pour leur propre avenir\", explique la coordinatrice de Green MJA.\n\nVous souhaitez participer ? Contactez-nous !",
                'categorie' => 'actualite',
                'auteur' => 'MJA',
                'publie' => true,
                'publie_le' => '2025-04-22 10:00:00',
            ],
            [
                'titre' => "MJA et la CTM signent un partenariat pour la jeunesse",
                'slug' => 'partenariat-mja-ctm-2025',
                'extrait' => "Madin'Jeunes Ambition a signé une convention de partenariat avec la Collectivité Territoriale de Martinique pour renforcer ses actions en faveur des jeunes.",
                'contenu' => "Une étape importante pour Madin'Jeunes Ambition ! L'association a signé une convention de partenariat avec la Collectivité Territoriale de Martinique (CTM).\n\nCe partenariat officialise le soutien de la CTM aux projets de MJA et ouvre de nouvelles perspectives pour le développement des actions de l'association sur l'ensemble du territoire martiniquais.\n\nAu menu de la convention :\n- Soutien financier aux projets éducatifs et de santé\n- Mise à disposition de locaux pour les ateliers et réunions\n- Accompagnement des volontaires en Service Civique\n- Relais communication via les canaux institutionnels\n\nCe partenariat renforce la légitimité de MJA et confirme la pertinence de ses actions aux yeux des institutions locales.",
                'categorie' => 'actualite',
                'auteur' => 'MJA',
                'publie' => true,
                'publie_le' => '2025-02-14 09:00:00',
            ],
            [
                'titre' => "Ti Dèj : bilan de 8 ans d'engagement pour la nutrition des jeunes",
                'slug' => 'bilan-8-ans-ti-dej',
                'extrait' => "Retour sur 8 ans d'opération Ti Dèj : les chiffres, les témoignages et les perspectives d'avenir de cette initiative emblématique.",
                'contenu' => "8 ans déjà que l'opération Ti Dèj accompagne les candidats au baccalauréat martiniquais !\n\nDepuis 2016, Madin'Jeunes Ambition se mobilise chaque matin du premier jour d'épreuve pour distribuer des petits-déjeuners sains aux lycéens.\n\nLes chiffres clés :\n- 3 200+ petits-déjeuners distribués au total\n- 8 éditions réussies\n- Plus de 200 bénévoles mobilisés au fil des années\n- 4 lycées partenaires à Fort-de-France\n- Initiative reprise en Guadeloupe et Guyane\n\nLes témoignages ne manquent pas : des anciens candidats devenus eux-mêmes bénévoles de MJA, des proviseurs qui soulignent l'impact positif sur l'ambiance du matin des examens...\n\nL'opération Ti Dèj continuera tant que des jeunes passeront le baccalauréat en Martinique. Et au-delà, l'idée d'étendre l'initiative à d'autres examens (BTS, concours) est à l'étude.",
                'categorie' => 'sante',
                'auteur' => 'MJA',
                'publie' => true,
                'publie_le' => '2024-07-01 10:00:00',
            ],
            [
                'titre' => "Bilan 2025 : une année de records pour MJA",
                'slug' => 'bilan-2025-mja',
                'extrait' => "L'année 2025 restera dans les mémoires comme l'une des plus actives de l'histoire de Madin'Jeunes Ambition.",
                'contenu' => "L'année 2025 a été exceptionnelle pour Madin'Jeunes Ambition. Avec 70 membres actifs et une dizaine de projets en cours, l'association n'a jamais été aussi dynamique.\n\nChiffres clés 2025 :\n- 15 événements organisés ou co-organisés\n- Plus de 2 000 jeunes touchés par nos actions\n- 500+ petits-déjeuners distribués lors du Ti Dèj\n- Gala Trophées Lumina : 200 participants, 8 lauréats\n- Signature d'une convention de partenariat avec la CTM\n- Lancement du programme Green MJA\n- 8 volontaires en Service Civique accueillis\n\nEn 2026, MJA compte franchir de nouveaux caps avec le lancement de Koze Mwen, l'extension de Madin'Académie et une nouvelle édition du MJA Sport Day.\n\nMerci à toute la communauté MJA pour cette belle année !",
                'categorie' => 'actualite',
                'auteur' => 'MJA',
                'publie' => true,
                'publie_le' => '2025-12-30 10:00:00',
            ],
        ];

        foreach ($articles as $article) {
            Article::firstOrCreate(['slug' => $article['slug']], $article);
        }
    }
}
