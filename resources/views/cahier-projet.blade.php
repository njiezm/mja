@php
    /**
     * Cahier du projet — documentation technique et fonctionnelle du site MJA.
     *
     * Le contenu est décrit une seule fois, sous forme de blocs, puis rendu
     * deux fois : en HTML pour la lecture à l'écran, et en PDF vectoriel pour
     * la diffusion. Une seule source, deux sorties — sans quoi les deux
     * versions divergent au premier changement.
     *
     * Types de blocs : p, sous, liste, etapes, table, note, code.
     */
    /**
     * Schéma de la base, dessiné plutôt qu'écrit.
     *
     * La géométrie est décrite une fois dans un repère de 1000 x 900, puis
     * mise à l'échelle par chaque rendu : SVG pour l'écran, tracés vectoriels
     * pour le PDF. Les deux dessins sont donc rigoureusement identiques.
     */
    $schema = [
        'w' => 1000, 'h' => 1005,

        'zones' => [
            ['x' => 8, 'y' => 8, 'w' => 984, 'h' => 500,
             'titre' => 'LE COEUR — COMPTE, ADHÉSION, SAISON',
             'fond' => '#F2F6FD', 'bord' => '#B9CCE9', 'encre' => '#1A3D8A'],
            ['x' => 8, 'y' => 530, 'w' => 984, 'h' => 230,
             'titre' => "D'OÙ VIENNENT LES ADHÉRENTS",
             'fond' => '#EFF8FE', 'bord' => '#AEDBF3', 'encre' => '#1477B0'],
            ['x' => 8, 'y' => 792, 'w' => 984, 'h' => 200,
             'titre' => 'LE CONTENU DU SITE',
             'fond' => '#FFF9EF', 'bord' => '#F3DDB4', 'encre' => '#B57708'],
        ],

        'liens' => [
            ['points' => [[500, 224], [500, 270]], 'de' => ['1', 512, 244], 'vers' => ['N', 512, 266]],
            ['points' => [[290, 350], [380, 350]], 'de' => ['1', 298, 342], 'vers' => ['N', 366, 342]],
            ['points' => [[620, 350], [710, 350]], 'de' => ['1', 628, 342], 'vers' => ['N', 696, 342]],
            ['points' => [[440, 446], [440, 470], [560, 470], [560, 446]],
             'note' => ['renouvelle la saison précédente', 500, 486]],
            ['points' => [[600, 446], [600, 572]], 'note' => ['source_id', 614, 550]],
            ['points' => [[620, 647], [710, 647]], 'de' => ['1', 628, 639], 'vers' => ['N', 696, 639]],
            ['points' => [[270, 907], [340, 907]], 'de' => ['1', 278, 899], 'vers' => ['N', 326, 899]],
        ],

        'tables' => [
            ['x' => 380, 'y' => 48, 'w' => 240, 'nom' => 'adhesion_periods', 'sous' => 'les saisons',
             'fond' => '#F5A623', 'encre' => '#0B1E45',
             'champs' => ['label', 'date_debut', 'date_fin', 'actif']],

            ['x' => 40, 'y' => 270, 'w' => 250, 'nom' => 'users', 'sous' => 'un compte par personne',
             'fond' => '#2048A4', 'encre' => '#FFFFFF',
             'champs' => ['email', 'role', 'adhesion_id', 'is_active']],

            ['x' => 380, 'y' => 270, 'w' => 240, 'nom' => 'adhesions', 'sous' => 'une par saison',
             'fond' => '#1A3D8A', 'encre' => '#FFFFFF',
             'champs' => ['statut', 'moyen_paiement', 'period_id', 'user_id']],

            ['x' => 710, 'y' => 270, 'w' => 250, 'nom' => 'adhesion_relances', 'sous' => 'journal des envois',
             'fond' => '#6C7A91', 'encre' => '#FFFFFF',
             'champs' => ['type', 'numero', 'envoyee_le']],

            ['x' => 380, 'y' => 572, 'w' => 240, 'nom' => 'sources', 'sous' => 'liens tracés',
             'fond' => '#1E93D6', 'encre' => '#FFFFFF',
             'champs' => ['slug', 'target', 'is_active']],

            ['x' => 710, 'y' => 572, 'w' => 250, 'nom' => 'source_visits', 'sous' => 'une ligne par visite',
             'fond' => '#3DAEF5', 'encre' => '#0B1E45',
             'champs' => ['visitor_hash', 'referer', 'device']],

            ['x' => 40, 'y' => 832, 'w' => 230, 'nom' => 'projects', 'sous' => 'actions récurrentes',
             'fond' => '#2048A4', 'encre' => '#FFFFFF',
             'champs' => ['titre', 'statut', 'ordre']],

            ['x' => 340, 'y' => 832, 'w' => 240, 'nom' => 'events', 'sous' => 'éditions datées',
             'fond' => '#3DAEF5', 'encre' => '#0B1E45',
             'champs' => ['titre', 'date_debut', 'project_id']],
        ],

        'pastilles' => [
            ['x' => 640, 'y' => 832, 'nom' => 'articles'],
            ['x' => 810, 'y' => 832, 'nom' => 'resources'],
            ['x' => 640, 'y' => 870, 'nom' => 'team_members'],
            ['x' => 810, 'y' => 870, 'nom' => 'partenaires'],
            ['x' => 640, 'y' => 908, 'nom' => 'contacts'],
            ['x' => 810, 'y' => 908, 'nom' => 'donations'],
            ['x' => 640, 'y' => 946, 'nom' => 'settings'],
            ['x' => 810, 'y' => 946, 'nom' => 'members (hérité)'],
        ],
    ];


    /**
     * Cycle de vie d'une adhésion : les statuts et ce qui fait passer de
     * l'un à l'autre. Même repère de 1000 unités que le schéma de la base,
     * donc même rendu à l'écran et dans le PDF.
     */
    $cycle = [
        'w' => 1000, 'h' => 620,

        'zones' => [
            ['x' => 8, 'y' => 8, 'w' => 984, 'h' => 604,
             'titre' => 'DU FORMULAIRE À LA CARTE DE MEMBRE',
             'fond' => '#F2F6FD', 'bord' => '#B9CCE9', 'encre' => '#1A3D8A'],
        ],

        'liens' => [
            ['points' => [[250, 150], [370, 150]], 'note' => ['paiement hors ligne', 258, 138]],
            ['points' => [[250, 300], [370, 300]], 'note' => ['carte réglée', 258, 288]],
            ['points' => [[620, 300], [700, 300]], 'de' => ['1', 628, 292], 'vers' => ['1', 688, 292]],
            ['points' => [[490, 210], [490, 262]], 'note' => ['encaissement constaté', 502, 240]],
            ['points' => [[490, 358], [490, 430], [200, 430], [200, 396]],
             'note' => ['fin de saison', 340, 452]],
            ['points' => [[250, 470], [370, 470]], 'note' => ['sans suite', 258, 458]],
        ],

        'tables' => [
            ['x' => 40, 'y' => 100, 'w' => 210, 'nom' => 'nouvelle', 'sous' => 'demande reçue',
             'fond' => '#F5A623', 'encre' => '#0B1E45',
             'champs' => ['notification équipe', 'accusé de réception']],

            ['x' => 370, 'y' => 100, 'w' => 250, 'nom' => 'en_attente_paiement', 'sous' => 'chèque, espèces, virement',
             'fond' => '#6C7A91', 'encre' => '#FFFFFF',
             'champs' => ['relances automatiques', 'jusqu\'à 3 envois']],

            ['x' => 370, 'y' => 262, 'w' => 250, 'nom' => 'payee', 'sous' => 'adhérent à jour',
             'fond' => '#1A3D8A', 'encre' => '#FFFFFF',
             'champs' => ['jeton de compte', 'email de bienvenue', 'entrée au trombinoscope']],

            ['x' => 700, 'y' => 262, 'w' => 250, 'nom' => 'carte de membre', 'sous' => 'attestation PDF',
             'fond' => '#2048A4', 'encre' => '#FFFFFF',
             'champs' => ['espace adhérent', 'back-office']],

            ['x' => 40, 'y' => 262, 'w' => 210, 'nom' => 'formulaire', 'sous' => 'trois cas',
             'fond' => '#3DAEF5', 'encre' => '#0B1E45',
             'champs' => ['première adhésion', 'réadhésion', 'prise d\'informations']],

            ['x' => 40, 'y' => 420, 'w' => 210, 'nom' => 'à renouveler', 'sous' => 'saison suivante',
             'fond' => '#1E93D6', 'encre' => '#FFFFFF',
             'champs' => ['relance + lien magique']],

            ['x' => 370, 'y' => 420, 'w' => 250, 'nom' => 'refusee / desistement', 'sous' => 'fin de parcours',
             'fond' => '#D0021B', 'encre' => '#FFFFFF',
             'champs' => ['email d\'information']],
        ],

        'pastilles' => [
            ['x' => 700, 'y' => 430, 'nom' => 'prise_infos'],
            ['x' => 700, 'y' => 468, 'nom' => 'traitee'],
        ],
    ];

    $doc = [

    ['titre' => 'Présentation du projet', 'blocs' => [
        ['p', "Le site de Madin'Jeunes Ambition est une application Laravel qui remplit trois rôles : présenter l'association au public, gérer les adhésions de bout en bout, et offrir aux adhérents un espace personnel. Un back-office complet permet à l'équipe de piloter le contenu et la vie associative sans intervention technique."],
        ['p', "Il ne s'agit pas d'un site vitrine avec un formulaire greffé. L'adhésion en est le cœur : elle crée un compte, déclenche des emails, encaisse une cotisation, alimente un trombinoscope, produit une carte de membre et se renouvelle chaque saison. Tout le reste gravite autour."],
        ['sous', 'Les quatre publics'],
        ['liste', [
            "Le visiteur — découvre les projets, les événements, les actualités, adhère ou fait un don.",
            "L'adhérent — consulte son espace, met à jour ses informations, télécharge sa carte, renouvelle son adhésion.",
            "Le gestionnaire de contenu — rédige articles, projets, événements, ressources.",
            "L'administrateur — suit les adhésions, les paiements, les relances, les comptes.",
        ]],
        ['note', "Ce document décrit l'état du code au 15 août 2026. Il s'adresse autant à un développeur qui reprend le projet qu'à un membre du bureau qui veut comprendre ce que fait la machine."],
    ]],

    ['titre' => 'Pile technique', 'blocs' => [
        ['table', ['entetes' => ['Élément', 'Choix', 'Précision'], 'lignes' => [
            ['Framework', 'Laravel 13', 'PHP 8.3 minimum'],
            ['Base de données', 'PostgreSQL', 'SQLite en test (mémoire)'],
            ['Gabarits', 'Blade', '89 vues'],
            ['Styles', 'Tailwind CSS précompilé', 'feuille statique, pas de build au déploiement'],
            ['Icônes', 'Font Awesome', 'servi en local, repli CDN'],
            ['Polices', 'Gill Sans, AllRound Gothic', 'servies depuis le domaine, préchargées'],
            ['Paiement', 'Stripe', 'API HTTP directe, sans SDK'],
            ['Emails', 'Mailables Laravel', 'gabarits Blade en tableaux HTML'],
        ]]],
        ['note', "La feuille Tailwind est précompilée et livrée telle quelle. Conséquence pratique : une classe utilitaire absente de cette feuille — notamment toute valeur arbitraire entre crochets — n'aura aucun effet. Les mises en page inhabituelles s'écrivent donc en CSS, dans un bloc de la vue concernée."],
    ]],

    ['titre' => 'Arborescence du code', 'blocs' => [
        ['table', ['entetes' => ['Dossier', 'Fichiers', 'Contenu'], 'lignes' => [
            ['app/Models', '15', "Entités : Adhesion, User, Event, Project, Article, Resource, Setting…"],
            ['app/Http/Controllers', '11', 'Pages publiques, adhésion, dons, recherche, sitemap'],
            ['app/Http/Controllers/Admin', '16', 'Back-office : contenu, adhésions, relances, comptes, paramètres'],
            ['app/Http/Controllers/Member', '4', 'Espace adhérent : compte, connexion, mot de passe, tableau de bord'],
            ['app/Http/Middleware', '6', 'Rôles, honeypot, suivi des sources, déclencheur de relances'],
            ['app/Services', '2', 'StripeService, RelanceService'],
            ['app/Support', '5', 'Cotisation, Telephone, HtmlRiche, ReseauSocial, Token'],
            ['app/Mail', '11', 'Confirmations, notifications, relances, identifiants'],
            ['app/Console/Commands', '6', 'Purge, sauvegarde, relances, diagnostics'],
            ['database/migrations', '33', 'Schéma, dans l\'ordre chronologique'],
            ['database/seeders', '11', 'Jeux de données : contenu, équipe, adhérents, saison'],
            ['resources/views', '89', 'Gabarits Blade'],
        ]]],
        ['sous', 'Les 140 routes'],
        ['liste', [
            "39 publiques — accueil, projets, événements, actualités, ressources, adhésion, dons, contact, recherche, mentions légales, sitemap, notifications Stripe.",
            "17 dans l'espace adhérent, sous le préfixe /espace.",
            "5 d'authentification du back-office — connexion, déconnexion, mot de passe oublié.",
            "77 dans le back-office, sous le préfixe /admin.",
        ]],
        ['p', "Le détail de chacune figure au chapitre « Les routes, une par une »."],
        ['p', "Une route de suivi des sources d'acquisition est déclarée en dernier : elle capture tout segment unique non déjà routé et renvoie une erreur 404 si le slug ne correspond à aucune source enregistrée. Sa position en fin de fichier est délibérée — la déplacer casserait toutes les pages."],
    ]],

    ['titre' => 'Modèle de données', 'blocs' => [
        ['p', "Seize tables métier et neuf tables techniques. Le pivot du modèle est le couple compte / adhésion : une personne possède un compte unique, et autant d'adhésions que de saisons auxquelles elle a participé. Tout le reste — contenu éditorial, suivi des sources, réglages — gravite autour sans y toucher."],

        ['sous', "Schéma d'ensemble"],
        ['schema', $schema],
        ['p', "Il se lit ainsi : un trait « 1 — N » signifie qu'une ligne de la table de gauche peut avoir plusieurs lignes en face. Une saison a plusieurs adhésions ; un compte a plusieurs adhésions ; une adhésion a plusieurs relances ; un projet a de zéro à N événements."],

        ['sous', 'users — le compte unique'],
        ['p', "Une seule table de comptes pour tout le site, depuis la fusion des comptes adhérents et administrateurs. Un compte peut être adhérent, administrateur, ou les deux. Le rôle décide de l'accès au back-office ; la clé adhesion_id décide de l'accès à l'espace adhérent."],
        ['table', ['entetes' => ['Colonne', 'Type', 'Rôle'], 'lignes' => [
            ['id', 'entier', 'Identifiant'],
            ['name', 'texte', "Nom affiché — « Prénom Nom » pour un adhérent"],
            ['email', 'texte', 'Identifiant de connexion, unique, stocké en minuscules'],
            ['email_verified_at', 'date', "Vérification de l'adresse — non utilisé actuellement"],
            ['password', 'haché', 'Mot de passe, hachage bcrypt'],
            ['password_encrypted', 'chiffré', 'Copie réversible du mot de passe, lisible du seul super admin'],
            ['remember_token', 'texte', 'Connexion persistante « se souvenir de moi »'],
            ['role', 'texte', 'membre, gestionnaire_contenu, admin, super_admin'],
            ['is_active', 'booléen', "Révocation d'accès sans suppression du compte"],
            ['adhesion_id', 'clé', 'Adhésion en cours — null pour un compte purement administrateur'],
            ['show_in_directory', 'booléen', 'Visibilité au trombinoscope des adhérents'],
            ['restore_token', 'texte', 'Jeton de restauration après suppression, 30 jours'],
            ['deleted_at', 'date', 'Suppression douce — le compte disparaît sans être effacé'],
            ['created_at, updated_at', 'date', 'Horodatage'],
        ]]],

        ['sous', 'adhesions — une ligne par saison'],
        ['p', "Trente-deux colonnes : l'identité, les coordonnées, les informations pratiques, le consentement, le paiement, le statut et les jetons. Une adhésion n'est jamais modifiée d'une saison à l'autre — une réadhésion crée une nouvelle ligne qui pointe vers la précédente."],
        ['table', ['entetes' => ['Colonne', 'Type', 'Rôle'], 'lignes' => [
            ['id', 'entier', 'Identifiant'],
            ['user_id', 'clé', 'Compte propriétaire — plusieurs adhésions pour un même compte'],
            ['period_id', 'clé', 'Saison concernée — null tant que le rattachement n\'est pas fait'],
            ['renouvelle_adhesion_id', 'clé', "Adhésion de la saison précédente que celle-ci renouvelle"],
            ['source_id', 'clé', "Source d'acquisition ayant amené la personne"],
            ['premiere_adhesion', 'texte', 'premiere, readhesion ou information'],
            ['civilite, nom, prenom', 'texte', 'Identité'],
            ['date_naissance', 'texte', 'Format jj/mm/aaaa — vide pour une prise d\'informations'],
            ['profession', 'texte', 'Situation professionnelle ou études'],
            ['telephone', 'texte', 'Formaté avec espaces à l\'enregistrement'],
            ['email', 'texte', "Sert de clé de rapprochement avec le compte"],
            ['adresse_postale', 'texte', 'Obligatoire pour une adhésion, non demandée pour une prise d\'informations'],
            ['taille_tshirt', 'texte', 'XS à XXL'],
            ['permis', 'texte', 'Oui, Non, En cours — utile pour organiser les déplacements'],
            ['problemes_sante', 'texte', 'Allergies et contre-indications, facultatif'],
            ['urgence_contact', 'texte', "Personne à prévenir"],
            ['droit_image', 'booléen', "Autorisation de diffusion de l'image"],
            ['rgpd_consentement', 'booléen', 'Consentement au traitement des données'],
            ['reseaux_sociaux', 'JSON', 'Comptes facultatifs, affichés au trombinoscope'],
            ['photo', 'texte', "Chemin de la photo, facultative, déposable plus tard"],
            ['message', 'texte', "Question posée lors d'une simple prise d'informations"],
            ['moyen_paiement', 'texte', 'cheque, espece, virement, en_ligne'],
            ['statut', 'texte', 'nouvelle, prise_infos, en_attente_paiement, payee, refusee, desistement'],
            ['lu', 'booléen', "Marqueur de lecture en back-office"],
            ['account_token', 'texte', 'Jeton de création de compte, valable 30 jours'],
            ['account_token_expires_at', 'date', "Échéance du précédent"],
            ['renouvellement_token', 'texte', 'Lien magique de réadhésion, valable 90 jours'],
            ['renouvellement_token_expires_at', 'date', "Échéance du précédent"],
            ['created_at, updated_at', 'date', 'Horodatage'],
        ]]],
        ['note', "Le statut « payee » est le pivot : il déclenche l'email de bienvenue, le jeton de création de compte, l'entrée au trombinoscope et l'accès à la carte de membre."],

        ['sous', 'adhesion_periods — les saisons'],
        ['table', ['entetes' => ['Colonne', 'Type', 'Rôle'], 'lignes' => [
            ['label', 'texte', 'Intitulé affiché, par exemple « Saison 2026-2027 »'],
            ['date_debut, date_fin', 'date', 'Bornes de la saison'],
            ['actif', 'booléen', 'Une saison inactive ne devient jamais la saison courante'],
        ]]],
        ['p', "La saison courante est celle qui est active et qui contient la date du jour. C'est elle qui est attribuée aux nouvelles adhésions et qui détermine si une adhésion est à renouveler."],

        ['sous', 'adhesion_relances — le journal des envois'],
        ['table', ['entetes' => ['Colonne', 'Type', 'Rôle'], 'lignes' => [
            ['adhesion_id', 'clé', 'Adhésion relancée'],
            ['type', 'texte', 'renouvellement ou paiement'],
            ['numero', 'entier', 'Rang de la relance : première, deuxième, troisième'],
            ['email', 'texte', "Adresse réellement destinataire au moment de l'envoi"],
            ['automatique', 'booléen', 'Envoi déclenché par le planificateur ou à la main'],
            ['envoyee_le', 'date', "Date d'envoi — sert d'anti-doublon"],
        ]]],

        ['sous', 'projects et events — les actions'],
        ['table', ['entetes' => ['Table', 'Colonnes', 'Précision'], 'lignes' => [
            ['projects', 'titre, slug, description_courte, description, image, statut, date_debut, date_fin, actif, ordre', "statut vaut en_cours, a_venir ou termine ; ordre commande l'affichage"],
            ['events', 'titre, slug, description_courte, description, image, date_debut, date_fin, lieu, adresse, gratuit, prix, lien_inscription, publie, project_id', "project_id rattache l'événement à un projet — null s'il est isolé"],
        ]]],
        ['p', "Un projet est une action récurrente (Fwi Ti Dèj, La Caravane de l'unité) ; un événement en est une édition datée. La page d'un projet affiche ses éditions à venir puis ses éditions passées."],

        ['sous', 'Le contenu éditorial'],
        ['table', ['entetes' => ['Table', 'Colonnes', 'Précision'], 'lignes' => [
            ['articles', 'titre, slug, extrait, contenu, image, categorie, auteur, publie, publie_le', 'Actualités ; publie_le commande le tri'],
            ['resources', 'titre, description, fichier, lien_externe, type, categorie, actif, ordre', 'Documents à télécharger ou liens externes'],
            ['team_members', 'prenom, nom, role, bio, photo, email, actif, ordre', "Bureau et équipe affichés sur la page À propos"],
            ['partenaires', 'nom, logo, url, description, ordre, actif', 'Logos et liens des partenaires'],
        ]]],

        ['sous', 'Les autres tables métier'],
        ['table', ['entetes' => ['Table', 'Colonnes', 'Précision'], 'lignes' => [
            ['contacts', 'nom, email, telephone, sujet, message, lu, source_id', 'Messages reçus par le formulaire de contact'],
            ['donations', 'prenom, nom, email, montant, message, statut, stripe_session_id, lu', 'Dons ponctuels, encaissés par Stripe Checkout'],
            ['settings', 'key, value', "Réglages clé-valeur ; les valeurs sensibles sont chiffrées au repos"],
            ['sources', 'slug, label, description, target, is_active', "Liens tracés : /flyer-septembre redirige vers la cible en notant la visite"],
            ['source_visits', 'source_id, visitor_hash, ip, user_agent, referer, utm_medium, utm_campaign, device', 'Une ligne par visite, visiteur anonymisé par empreinte'],
            ['members', 'adhesion_id, email, password, show_in_directory, restore_token', 'Ancienne table des comptes adhérents, conservée après la fusion'],
        ]]],
        ['note', "La table members n'est plus lue ni écrite par le code. Elle reste en place le temps de valider la bascule en production ; une migration ultérieure pourra la retirer."],

        ['sous', 'Les tables techniques'],
        ['p', "Neuf tables appartiennent au fonctionnement de Laravel et ne contiennent aucune donnée métier : migrations, sessions, cache, cache_locks, jobs, job_batches, failed_jobs, password_reset_tokens et member_password_reset_tokens. Elles peuvent être vidées sans perte, à l'exception de migrations qui mémorise le schéma appliqué."],

        ['sous', 'Les relations, en résumé'],
        ['etapes', [
            ['users -> adhesions', "Un compte a plusieurs adhésions (historique). users.adhesion_id désigne la courante."],
            ['adhesions -> adhesion_periods', "Chaque adhésion appartient à une saison ; le rattachement est modifiable en back-office."],
            ['adhesions -> adhesions', "renouvelle_adhesion_id chaîne les saisons successives d'une même personne."],
            ['adhesions -> adhesion_relances', "Journal des relances envoyées, garde-fou contre les doublons."],
            ['adhesions -> sources', "L'adhésion garde la trace du support qui a amené la personne."],
            ['projects -> events', "Un projet a de zéro à N événements ; un événement appartient à un seul projet, ou à aucun."],
            ['sources -> source_visits', "Suivi des campagnes d'acquisition, visiteur anonymisé par empreinte."],
        ]],
    ]],

    ['titre' => 'Les routes, une par une', 'blocs' => [
        ['p', "Toutes les routes sont déclarées dans routes/web.php, à l'exception de l'authentification du back-office qui vit dans routes/auth.php. L'ordre du fichier compte : la route de suivi des sources, déclarée en dernier, capture tout segment unique non déjà routé."],

        ['sous', 'Pages publiques'],
        ['table', ['entetes' => ['Méthode et URL', 'Nom', 'Ce que fait la route'], 'lignes' => [
            ['GET /', 'home', "Accueil : carrousel, projets phares, prochains événements, dernières actualités"],
            ['GET /a-propos', 'about', "Histoire, valeurs, bureau et équipe, partenaires"],
            ['GET /contact', 'contact', 'Formulaire de contact'],
            ['POST /contact', 'contact.store', 'Enregistre le message, notifie et accuse réception — pot de miel, 5 envois par minute'],
            ['GET /projets', 'projects.index', 'Liste des projets actifs'],
            ['GET /projets/{slug}', 'projects.show', "Fiche projet, avec ses éditions à venir et passées"],
            ['GET /evenements', 'events.index', 'Agenda : à venir puis passés'],
            ['GET /evenements/{slug}', 'events.show', "Fiche événement, lieu, tarif, lien d'inscription"],
            ['GET /evenements/{slug}/ics', 'events.ics', "Fichier calendrier à ajouter à son agenda"],
            ['GET /actualites', 'articles.index', 'Liste des actualités publiées'],
            ['GET /actualites/{slug}', 'articles.show', 'Article complet'],
            ['GET /ressources', 'resources.index', 'Documents à télécharger et liens utiles'],
            ['GET /sante-nutrition-sport', 'sns', "Page du programme Fwi Ti Dèj et Santé Nutrition Sport"],
            ['GET /adhesion', 'adhesion', "Formulaire d'adhésion — redirige un adhérent connecté vers son renouvellement"],
            ['POST /adhesion', 'adhesion.store', 'Enregistre la demande, vérifie le paiement, envoie les emails'],
            ['POST /adhesion/paiement-intent', 'adhesion.payment-intent', 'Crée le paiement Stripe appelé depuis la page, en AJAX'],
            ['GET /adhesion/paiement/succes', 'adhesion.paiement.succes', 'Retour de paiement abouti'],
            ['GET /adhesion/paiement/annule', 'adhesion.paiement.annule', 'Retour de paiement abandonné'],
            ['GET /adhesion/renouveler/{token}', 'adhesion.renouveler', 'Lien magique de réadhésion, sans connexion, 90 jours'],
            ['GET /don', 'don', 'Formulaire de don'],
            ['POST /don', 'don.store', 'Ouvre la page de paiement Stripe'],
            ['GET /don/merci', 'don.merci', 'Remerciement après don'],
            ['GET /recherche', 'search', 'Recherche parmi actualités, projets, événements et ressources'],
            ['GET /mentions-legales', 'mentions-legales', 'Mentions légales'],
            ['GET /politique-de-confidentialite', 'confidentialite', 'Politique de confidentialité et RGPD'],
            ['GET /sitemap.xml', 'sitemap', 'Plan du site pour les moteurs de recherche'],
            ['POST /stripe/webhook', 'stripe.webhook', "Notifications de paiement envoyées par Stripe. Ni session ni jeton CSRF : l'appel est authentifié par sa signature"],
            ['GET /{source}', 'source.track', "Lien tracé : note la visite puis redirige. Doit rester la dernière route déclarée"],
        ]]],

        ['sous', 'Espace adhérent — préfixe /espace'],
        ['table', ['entetes' => ['Méthode et URL', 'Nom', 'Ce que fait la route'], 'lignes' => [
            ['GET /espace/creer/{token}', 'member.account.create', 'Formulaire de création de compte, ouvert par le lien reçu par email'],
            ['POST /espace/creer/{token}', 'member.account.store', 'Crée le compte et connecte la personne'],
            ['GET /espace/restaurer/{token}', 'member.account.restore', 'Restaure un compte supprimé, dans les 30 jours'],
            ['GET /espace/connexion', 'member.login', 'Page de connexion adhérent'],
            ['POST /espace/connexion', 'member.login.post', 'Connexion — 6 tentatives par minute'],
            ['POST /espace/deconnexion', 'member.logout', 'Déconnexion'],
            ['GET /espace/mot-de-passe-oublie', 'member.password.request', 'Demande de lien de réinitialisation'],
            ['POST /espace/mot-de-passe-oublie', 'member.password.email', 'Envoie le lien — 4 demandes par minute'],
            ['GET /espace/reinitialiser/{token}', 'member.password.reset', 'Formulaire de nouveau mot de passe'],
            ['POST /espace/reinitialiser', 'member.password.update', 'Enregistre le nouveau mot de passe'],
            ['GET /espace', 'member.dashboard', "Tableau de bord : adhésion en cours, rappel de renouvellement, accès rapides"],
            ['GET /espace/trombinoscope', 'member.trombinoscope', 'Annuaire des adhérents qui ont accepté d\'y figurer'],
            ['GET /espace/carte', 'member.card', 'Carte de membre et attestation, téléchargeables en PDF'],
            ['GET /espace/profil', 'member.profile.edit', 'Modification des informations personnelles'],
            ['PUT /espace/profil', 'member.profile.update', 'Enregistre les modifications'],
            ['DELETE /espace/compte', 'member.account.destroy', 'Suppression du compte, réversible 30 jours'],
            ['GET /espace/renouveler', 'adhesion.renouveler.espace', 'Réadhésion pré-remplie depuis l\'espace'],
        ]]],

        ['sous', 'Authentification du back-office'],
        ['table', ['entetes' => ['Méthode et URL', 'Nom', 'Ce que fait la route'], 'lignes' => [
            ['GET /connexion', 'login', 'Page de connexion du back-office'],
            ['POST /connexion', 'login.post', 'Connexion'],
            ['POST /deconnexion', 'logout', 'Déconnexion'],
            ['GET /mot-de-passe-oublie', 'password.request', 'Demande de lien de réinitialisation'],
            ['POST /mot-de-passe-oublie', 'password.email', 'Envoie le lien'],
            ['GET /reinitialiser-mot-de-passe/{token}', 'password.reset', 'Formulaire de nouveau mot de passe'],
            ['POST /reinitialiser-mot-de-passe', 'password.update', 'Enregistre le nouveau mot de passe'],
        ]]],

        ['sous', 'Back-office — contenu (gestionnaire, admin, super admin)'],
        ['table', ['entetes' => ['URL', 'Nom', 'Ce que fait la route'], 'lignes' => [
            ['GET /admin', 'admin.dashboard', 'Tableau de bord : chiffres clés, dernières demandes, alertes'],
            ['/admin/articles', 'admin.articles.*', 'Actualités : liste, création, édition, suppression'],
            ['/admin/projects', 'admin.projects.*', "Projets, avec rattachement des événements"],
            ['/admin/events', 'admin.events.*', 'Événements, avec rattachement à un projet'],
            ['/admin/resources', 'admin.resources.*', 'Ressources : fichiers et liens'],
            ['/admin/team', 'admin.team.*', "Bureau et équipe"],
            ['/admin/partenaires', 'admin.partenaires.*', 'Partenaires'],
            ['GET /admin/contacts', 'admin.contacts.index', 'Messages reçus'],
            ['GET /admin/contacts/{id}', 'admin.contacts.show', 'Message détaillé, marqué comme lu'],
            ['DELETE /admin/contacts/{id}', 'admin.contacts.destroy', 'Supprime le message'],
        ]]],
        ['p', "Les groupes notés avec une étoile suivent la convention Laravel : index, create, store, edit, update, destroy. Six routes chacun, sauf les partenaires et l'équipe qui n'ont pas de page publique dédiée."],

        ['sous', 'Back-office — gestion (admin et super admin)'],
        ['table', ['entetes' => ['Méthode et URL', 'Nom', 'Ce que fait la route'], 'lignes' => [
            ['GET /admin/adhesions', 'admin.adhesions.index', 'Liste des demandes, filtre par saison, alerte sur les adhésions sans saison'],
            ['GET /admin/adhesions/export', 'admin.adhesions.export', 'Export CSV, filtré par saison'],
            ['GET /admin/adhesions/{id}', 'admin.adhesions.show', 'Fiche complète de la demande'],
            ['PATCH /admin/adhesions/{id}/statut', 'admin.adhesions.statut', "Change le statut et envoie l'email correspondant"],
            ['PATCH /admin/adhesions/{id}/periode', 'admin.adhesions.periode', 'Rattache la demande à une saison, ou l\'en détache'],
            ['POST /admin/adhesions/rattacher-periode', 'admin.adhesions.rattacher-periode', 'Rattache d\'un coup toutes les adhésions sans saison'],
            ['GET /admin/adhesions/{id}/carte', 'admin.adhesions.carte', "Attestation d'adhésion et carte de membre, à rééditer pour un adhérent qui n'a pas de compte"],
            ['DELETE /admin/adhesions/{id}', 'admin.adhesions.destroy', 'Supprime la demande et sa photo'],
            ['GET /admin/relances', 'admin.relances.index', 'Réglages, relances dues, historique des envois'],
            ['PUT /admin/relances', 'admin.relances.update', 'Enregistre les réglages de relance'],
            ['POST /admin/relances/executer', 'admin.relances.executer', 'Déclenche à la main la campagne du jour'],
            ['POST /admin/relances/adhesion/{id}', 'admin.relances.une', 'Relance une seule personne'],
            ['GET /admin/periodes', 'admin.periods.index', 'Saisons : liste et création'],
            ['/admin/periodes/{id}', 'admin.periods.*', 'Édition et suppression'],
            ['GET /admin/dons', 'admin.donations.index', 'Dons reçus'],
            ['DELETE /admin/dons/{id}', 'admin.donations.destroy', 'Supprime un don'],
            ['GET /admin/sources', 'admin.sources.index', "Liens tracés et statistiques d'acquisition"],
            ['GET /admin/sources/export', 'admin.sources.export', 'Export CSV des visites'],
            ['/admin/sources/{id}', 'admin.sources.*', 'Édition et suppression'],
            ['GET /admin/comptes-adherents', 'admin.members.index', 'Comptes des adhérents'],
            ['POST /admin/comptes-adherents', 'admin.members.store', 'Crée le compte d\'un adhérent et lui envoie ses accès'],
            ['GET /admin/comptes-adherents/export', 'admin.members.export', 'Export CSV des comptes'],
            ['PATCH /admin/comptes-adherents/{id}/mot-de-passe', 'admin.members.reset-password', 'Réinitialise un mot de passe'],
            ['PATCH /admin/comptes-adherents/{id}/role', 'admin.members.role', 'Nomme un adhérent gestionnaire ou administrateur'],
            ['PATCH /admin/comptes-adherents/{id}/trombinoscope', 'admin.members.toggle-directory', 'Affiche ou masque la personne au trombinoscope'],
            ['GET /admin/parametres', 'admin.settings.edit', 'Cotisation, frais bancaires, coordonnées, Stripe, relances'],
            ['PUT /admin/parametres', 'admin.settings.update', 'Enregistre les réglages'],
        ]]],

        ['sous', 'Back-office — comptes du personnel (super admin)'],
        ['table', ['entetes' => ['Méthode et URL', 'Nom', 'Ce que fait la route'], 'lignes' => [
            ['/admin/users', 'admin.users.*', 'Comptes administrateurs et gestionnaires : liste, création, édition, suppression'],
            ['PATCH /admin/users/{id}/reset-password', 'admin.users.reset-password', 'Réinitialise le mot de passe et le communique'],
            ['PATCH /admin/users/{id}/toggle-active', 'admin.users.toggle-active', "Suspend ou réactive l'accès"],
        ]]],
    ]],

    ['titre' => 'Les contrôleurs', 'blocs' => [
        ['p', "Trente-trois contrôleurs, répartis en quatre familles : les pages publiques à la racine, l'authentification du back-office dans Auth, l'espace adhérent dans Member, le back-office dans Admin. Un contrôleur ne contient que l'enchaînement des opérations ; les règles de calcul vivent dans app/Support et app/Services."],

        ['sous', 'Pages publiques'],
        ['table', ['entetes' => ['Contrôleur', 'Méthodes', 'Rôle'], 'lignes' => [
            ['HomeController', 'index, about, contact, contactStore, sns, mentionsLegales, confidentialite', "Accueil et pages institutionnelles. index compose le carrousel, les projets phares et l'agenda."],
            ['ProjectController', 'index, show', "Liste des projets ; la fiche charge les événements liés, séparés en à venir et passés."],
            ['EventController', 'index, show, ics', "Agenda public ; ics produit un fichier calendrier à la volée."],
            ['ArticleController', 'index, show', 'Actualités publiées, triées par date de publication.'],
            ['ResourceController', 'index', 'Documents et liens, groupés par catégorie.'],
            ['AdhesionController', 'create, store, paymentIntent, paiementSucces, paiementAnnule, renouvelerParLien, renouvelerDepuisEspace', "Le contrôleur le plus dense du projet : formulaire, paiement, enregistrement, emails, réadhésion."],
            ['DonationController', 'create, store, merci', 'Dons ponctuels par Stripe Checkout.'],
            ['SearchController', 'index', 'Recherche transversale sur quatre types de contenu.'],
            ['SitemapController', 'index', 'Plan du site au format XML, alimenté par le contenu publié.'],
            ['SourceTrackController', 'handle', 'Enregistre la visite issue d\'un lien tracé puis redirige.'],
        ]]],

        ['sous', 'Espace adhérent'],
        ['table', ['entetes' => ['Contrôleur', 'Méthodes', 'Rôle'], 'lignes' => [
            ['Member\\SpaceController', 'dashboard, trombinoscope, card, editProfile, updateProfile, destroy', "Tableau de bord, annuaire, carte de membre, profil, suppression de compte."],
            ['Member\\AccountController', 'showCreate, store, restore', 'Création du compte par jeton, restauration après suppression.'],
            ['Member\\AuthController', 'showLogin, login, logout', 'Connexion adhérent — même identité que le back-office.'],
            ['Member\\PasswordResetController', 'showLinkRequest, sendResetLink, showReset, reset', 'Mot de passe oublié.'],
        ]]],

        ['sous', 'Back-office — contenu'],
        ['table', ['entetes' => ['Contrôleur', 'Rôle'], 'lignes' => [
            ['Admin\\DashboardController', 'Chiffres clés, dernières demandes, alertes.'],
            ['Admin\\ArticleController', 'Actualités : image, extrait, contenu enrichi, publication.'],
            ['Admin\\ProjectController', "Projets ; synchronise aussi les événements rattachés."],
            ['Admin\\EventController', 'Événements ; rattachement à un projet, tarif, inscription.'],
            ['Admin\\ResourceController', 'Ressources : fichier téléversé ou lien externe.'],
            ['Admin\\TeamController', "Bureau et équipe, avec ordre d'affichage."],
            ['Admin\\PartenaireController', 'Partenaires : logo, lien, ordre.'],
            ['Admin\\ContactController', 'Messages reçus : consultation et suppression.'],
        ]]],

        ['sous', 'Back-office — gestion'],
        ['table', ['entetes' => ['Contrôleur', 'Rôle'], 'lignes' => [
            ['Admin\\AdhesionController', "Demandes d'adhésion : liste, fiche, statut, rattachement à une saison, export CSV, suppression."],
            ['Admin\\RelanceController', 'Relances : réglages, aperçu des envois dus, déclenchement manuel, historique.'],
            ['Admin\\PeriodController', 'Saisons : création, édition, activation.'],
            ['Admin\\DonationController', 'Dons reçus.'],
            ['Admin\\SourceController', "Liens tracés et statistiques d'acquisition."],
            ['Admin\\MemberAccountController', "Comptes adhérents : création, mot de passe, rôle, visibilité au trombinoscope, export."],
            ['Admin\\SettingController', 'Réglages du site ; les secrets sont chiffrés avant enregistrement.'],
            ['Admin\\UserController', 'Comptes du personnel — réservé au super administrateur.'],
        ]]],

        ['sous', 'Autour des contrôleurs'],
        ['table', ['entetes' => ['Classe', 'Rôle'], 'lignes' => [
            ['Support\\Cotisation', "Montant à régler pour que l'association encaisse la cotisation entière."],
            ['Support\\Telephone', 'Mise en forme des numéros : trois chiffres puis des paires.'],
            ['Support\\HtmlRiche', "Assainissement du texte enrichi par liste blanche."],
            ['Support\\ReseauSocial', "Transforme un pseudo en adresse de profil."],
            ['Services\\StripeService', 'Appels HTTP directs à Stripe : paiement, vérification, session de don.'],
            ['Services\\RelanceService', 'Sélection des personnes à relancer, envoi, journalisation.'],
            ['Auth\LoginController', 'Connexion et déconnexion du back-office.'],
            ['Auth\ForgotPasswordController', 'Demande de réinitialisation du mot de passe.'],
            ['Auth\ResetPasswordController', 'Enregistrement du nouveau mot de passe.'],
            ['Middleware', 'content, admin, super_admin, honeypot, TrackVisit, DeclencheurRelances.'],
        ]]],
    ]],

    ['titre' => 'Les vues', 'blocs' => [
        ['p', "Quatre-vingt-neuf gabarits Blade. Deux mises en page servent de socle : layouts/app pour le site public et l'espace adhérent, layouts/admin pour le back-office. Les vues préfixées d'un tiret bas sont des formulaires partagés entre la création et l'édition."],

        ['sous', 'Socle et éléments réutilisables'],
        ['table', ['entetes' => ['Vue', 'Rôle'], 'lignes' => [
            ['layouts/app', "Mise en page publique : en-tête, menu, pied de page, métadonnées de partage."],
            ['layouts/admin', 'Mise en page du back-office : barre latérale, messages, confirmation avant suppression.'],
            ['partials/seasonal-banner', "Bandeau saisonnier affiché selon la période de l'année."],
            ['components/editeur-riche', "Éditeur de texte enrichi : gras, listes, liens, alignement."],
            ['components/phone-field', 'Champ téléphone : indicatif et mise en forme automatique.'],
            ['welcome', 'Page par défaut de Laravel, non utilisée.'],
        ]]],

        ['sous', 'Site public'],
        ['table', ['entetes' => ['Vue', 'Rôle'], 'lignes' => [
            ['home', 'Accueil : carrousel, présentation, projets, agenda, actualités, partenaires.'],
            ['about', "À propos : histoire, valeurs, bureau et équipe."],
            ['contact', 'Formulaire de contact.'],
            ['adhesion', "Formulaire d'adhésion, de réadhésion et de prise d'informations, avec paiement intégré."],
            ['projects/index, projects/show', "Liste des projets ; fiche avec éditions à venir et passées."],
            ['events/index, events/show', 'Agenda et fiche événement.'],
            ['articles/index, articles/show', 'Actualités.'],
            ['resources/index', 'Ressources à télécharger.'],
            ['sns', "Programme Fwi Ti Dèj et Santé Nutrition Sport."],
            ['search', 'Résultats de recherche, groupés par type.'],
            ['don/create, don/merci', 'Don et remerciement.'],
            ['legal/mentions-legales, legal/confidentialite', 'Pages légales.'],
            ['sitemap', 'Plan du site au format XML.'],
        ]]],

        ['sous', 'Espace adhérent'],
        ['table', ['entetes' => ['Vue', 'Rôle'], 'lignes' => [
            ['member/dashboard', "Tableau de bord : adhésion en cours, rappel de renouvellement, accès rapides."],
            ['member/card', "Attestation d'adhésion et carte de membre, avec téléchargement en PDF."],
            ['member/trombinoscope', 'Annuaire des adhérents qui ont accepté de figurer.'],
            ['member/profile', 'Modification des informations et suppression du compte.'],
            ['member/create', 'Création du compte depuis le lien reçu par email.'],
            ['member/login, member/forgot-password, member/reset-password', 'Connexion et mot de passe oublié.'],
        ]]],

        ['sous', 'Le don'],
        ['p', "Deux voies possibles, et une règle de priorité réglable en back-office. Le formulaire par carte est la voie normale : le don s'achève sans quitter le site et l'association garde la main sur le reçu. Un lien vers une plateforme externe, renseigné dans les paramètres, prend le relais — automatiquement si le paiement en ligne est indisponible, ou volontairement si l'association le met en avant, par exemple pour confier les reçus fiscaux à un tiers. Quand les deux existent, le second est proposé sous le premier : personne ne reste bloqué."],

        ['sous', 'Back-office'],
        ['table', ['entetes' => ['Vue', 'Rôle'], 'lignes' => [
            ['admin/dashboard', 'Vue d\'ensemble : chiffres, dernières demandes, alertes.'],
            ['admin/adhesions/index, show', "Liste des demandes et fiche détaillée."],
            ['admin/relances/index', 'Réglages, relances dues, historique.'],
            ['admin/members/index', 'Comptes adhérents et attribution des rôles.'],
            ['admin/users/index, create, edit', 'Comptes du personnel.'],
            ['admin/periods/index, edit', "Saisons d'adhésion."],
            ['admin/settings/edit', 'Réglages du site, cotisation, Stripe, coordonnées bancaires.'],
            ['admin/articles/*', 'Actualités : liste, création, édition, formulaire partagé.'],
            ['admin/projects/*', "Projets, avec la liste des événements à rattacher."],
            ['admin/events/*', 'Événements, avec le projet de rattachement.'],
            ['admin/resources/*', 'Ressources.'],
            ['admin/team/*', "Bureau et équipe."],
            ['admin/partenaires/*', 'Partenaires.'],
            ['admin/sources/index, edit', 'Liens tracés et statistiques.'],
            ['admin/contacts/index, show', 'Messages reçus.'],
            ['admin/donations/index', 'Dons.'],
        ]]],

        ['sous', 'Emails'],
        ['p', "Douze gabarits, écrits en tableaux HTML pour rester lisibles dans tous les clients de messagerie."],
        ['table', ['entetes' => ['Vue', 'Envoyé quand'], 'lignes' => [
            ['emails/adhesion-confirmation', "Une demande d'adhésion vient d'être enregistrée — accusé de réception."],
            ['emails/adhesion-notification', "Une demande vient d'arriver — alerte vers l'association."],
            ['emails/adhesion-statut', 'Le statut de la demande change : acceptée, en attente de paiement, refusée.'],
            ['emails/relance-renouvellement', "La saison a changé et l'adhésion n'a pas été renouvelée."],
            ['emails/relance-paiement', "Un règlement par chèque, espèces ou virement n'est pas parvenu."],
            ['emails/member-password-reset', 'Un adhérent a demandé un nouveau mot de passe.'],
            ['emails/account-deleted', "Un compte vient d'être supprimé — contient le lien de restauration."],
            ['emails/contact-confirmation', 'Accusé de réception du formulaire de contact.'],
            ['emails/contact-notification', "Un message de contact vient d'arriver."],
            ['emails/admin-created', "Un compte de back-office vient d'être créé."],
            ['emails/admin-password-reset', 'Mot de passe de back-office réinitialisé.'],
            ['emails/reset-password', 'Lien de réinitialisation du back-office.'],
        ]]],
    ]],

    ['titre' => 'Rôles et permissions', 'blocs' => [
        ['table', ['entetes' => ['Rôle', 'Espace adhérent', 'Contenu', 'Gestion', 'Comptes', 'Paramètres'], 'lignes' => [
            ['Adhérent', 'Oui', '—', '—', '—', '—'],
            ['Gestionnaire de contenu', 'Si adhérent', 'Oui', '—', '—', '—'],
            ['Administrateur', 'Si adhérent', 'Oui', 'Oui', 'Tous sauf super admins', 'Sauf secrets Stripe'],
            ['Super administrateur', 'Si adhérent', 'Oui', 'Oui', 'Tous', 'Tout'],
        ]]],
        ['p', "Trois middlewares gardent ces frontières : content, admin et super_admin. Chacun vérifie aussi que le compte est actif, et déconnecte immédiatement un accès révoqué."],
        ['sous', 'Nommer un adhérent'],
        ['p', "Un adhérent devient gestionnaire ou administrateur depuis la page Comptes adhérents : son compte existe déjà, on ne fait qu'élargir ses droits. Un administrateur confère les rôles jusqu'à administrateur inclus, et gère ses pairs ; le rang de super administrateur, lui, reste hors de portée — c'est celui qui peut tout reprendre si un compte d'administration part à la dérive. Personne ne peut modifier son propre rôle : c'est le meilleur moyen de se verrouiller dehors."],
    ]],

    ['titre' => 'Parcours utilisateurs', 'blocs' => [
        ['sous', "Première adhésion"],
        ['etapes', [
            ['1. Le formulaire', "Identité, coordonnées, réseaux sociaux facultatifs, taille de T-shirt, permis, santé, contact d'urgence, droit à l'image, consentement RGPD. La photo est facultative."],
            ['2. Le paiement', "Carte bancaire dans la page, ou chèque, espèces, virement. En carte, le bouton d'envoi reste désactivé tant que le règlement n'a pas abouti."],
            ['3. L\'enregistrement', "Le serveur revérifie le paiement auprès de Stripe avant d'enregistrer. Une notification part vers l'association, une confirmation vers la personne."],
            ['4. Le compte', "Une fois la cotisation encaissée, un lien de création de compte est envoyé, valable 30 jours. Si un compte existe déjà à cette adresse, il est rattaché à la nouvelle adhésion et la personne reçoit un lien d'accès — jamais un second compte, jamais un mot de passe en clair."],
            ['5. L\'espace', "Tableau de bord, trombinoscope, carte de membre téléchargeable en PDF, modification du profil."],
        ]],

        ['sous', 'Réadhésion'],
        ['p', "Deux chemins, pour ne laisser personne de côté."],
        ['etapes', [
            ["Depuis l'espace", "Un encart « Votre adhésion est à renouveler » apparaît dès que la saison change. Le formulaire s'ouvre pré-rempli, la photo est conservée : il n'y a qu'à vérifier et payer."],
            ['Par lien magique', "Les emails de relance contiennent un lien personnel valable 90 jours, qui ouvre le même écran pré-rempli sans connexion — pour les adhérents qui n'ont jamais créé de compte."],
        ]],

        ['sous', "Prise d'informations"],
        ['p', "Un troisième choix, volontairement dépouillé : civilité, nom, prénom, téléphone, email et la question posée. Ni date de naissance, ni taille de T-shirt, ni contact d'urgence, ni droit à l'image, ni cotisation. C'est ce qu'impose le principe de minimisation des données."],

        ['sous', 'Back-office'],
        ['liste', [
            "Contenu — actualités, projets, événements, ressources, équipe, partenaires, avec éditeur de texte enrichi.",
            "Adhésions — liste, fiche détaillée, changement de statut, export CSV.",
            "Relances — réglages, aperçu des envois dus, déclenchement manuel, historique.",
            "Comptes — administrateurs d'un côté, adhérents de l'autre, avec attribution des rôles.",
            "Sources — liens tracés pour mesurer d'où viennent les adhésions.",
            "Périodes — les saisons d'adhésion.",
            "Paramètres — cotisation, frais bancaires, coordonnées, clés Stripe.",
        ]],
    ]],

    ['titre' => 'Choix techniques à connaître', 'blocs' => [

        ['sous', 'La cotisation et les frais bancaires'],
        ['p', "Le montant réglé par carte est calculé pour que l'association encaisse la cotisation entière. On résout l'équation qui donne le montant brut laissant le net voulu après commission, puis on arrondit au pas supérieur — un arrondi vers le bas ferait encaisser moins que prévu. Avec 20 € et la tarification européenne, le payeur règle 20,60 €."],

        ['sous', 'Les relances sans tâche planifiée'],
        ['p', "L'hébergement ne propose pas de cron. Un middleware évalue donc les relances à l'occasion des visites, une fois par jour, après une heure configurable — et travaille après l'envoi de la réponse, le visiteur n'attend rien. Deux garde-fous : un verrou atomique contre les visites simultanées, et un marqueur journalier. Le journal des relances reste la protection finale contre les doublons. La commande et sa planification existent déjà : le jour où un cron est disponible, il suffit de le brancher."],

        ['sous', "L'éditeur de texte enrichi"],
        ['p', "Le contenu rédigé en back-office peut porter du gras, des listes, des liens et surtout de l'alignement, justification comprise. Comme il s'affiche tel quel sur le site public, il est assaini à l'écriture par une liste blanche stricte : balises autorisées, attributs autorisés, seules quatre valeurs d'alignement acceptées, et seuls les liens http, mailto, tel ou internes conservés. L'assainissement se fait dans un mutateur du modèle, donc quel que soit le chemin emprunté."],
        ['note', "Les textes enregistrés avant l'éditeur ne contiennent aucune balise : ils continuent d'être échappés et affichés comme avant. La bascule est automatique."],

        ['sous', 'Les exports PDF'],
        ['p', "L'attestation d'adhésion et la carte de membre sont produites en PDF vectoriel : pages A4, polices standard, texte sélectionnable, encodage WinAnsi pour les accents, dégradés et images. Le document est écrit directement dans le format, sans librairie — l'adhérent télécharge un vrai PDF, pas une impression de page web."],

        ['sous', "Le site derrière un proxy"],
        ['p', "L'hébergement termine le HTTPS sur un proxy, puis transmet la requête en clair au site avec l'en-tête X-Forwarded-Proto. Sans faire confiance à cet en-tête, le framework croit la requête non sécurisée et fabrique des adresses en http:// : le navigateur voit alors des formulaires pointant vers une autre origine que la page, et la règle « form-action 'self' » de la politique de sécurité du contenu bloque l'envoi. Les en-têtes du proxy sont donc déclarés de confiance dans bootstrap/app.php. C'est aussi ce qui rend correctes les adresses des emails, des aperçus de partage et des redirections de paiement."],
        ['note', "La politique de sécurité du contenu est écrite dans public/.htaccess. Toute nouvelle ressource externe — police, script, image d'un autre domaine — doit y être déclarée, sinon le navigateur la bloque en silence."],

        ['sous', 'Le rapprochement des comptes'],
        ['p', "Une personne dont le compte a été créé par l'association peut très bien remplir le formulaire public sans être connectée — elle n'a parfois jamais reçu ses accès. À l'enregistrement, l'adhésion cherche donc un compte à la même adresse email, en ignorant la casse et les espaces, et s'y rattache. Un compte supprimé est restauré : la personne revient d'elle-même, et l'unicité de l'email interdirait de toute façon d'en créer un second. Le mot de passe existant n'est jamais touché ; l'accès se transmet par un lien de réinitialisation."],

        ['sous', 'Les notifications de paiement'],
        ['p', "Le retour du navigateur après paiement n'est pas fiable : un onglet fermé pendant l'authentification bancaire, une connexion perdue, et l'encaissement ne serait jamais enregistré. Stripe prévient donc le serveur directement. Chaque appel est vérifié par sa signature — horodatage toléré à cinq minutes, comparaison à temps constant — et le traitement est idempotent, car un même événement peut arriver plusieurs fois. Quand un paiement aboutit sans demande enregistrée, l'association reçoit une alerte avec la référence Stripe et l'email du payeur : personne ne paie dans le vide."],
        ['note', "À déclarer une fois dans le tableau de bord Stripe : l'adresse https://mja-martinique.com/stripe/webhook, les événements payment_intent.succeeded et checkout.session.completed, puis coller le secret whsec_… dans Paramètres → Stripe."],

        ['sous', "Les aperçus de partage"],
        ['p', "Un lien collé sur WhatsApp, Facebook ou LinkedIn affiche une vignette, un titre et un résumé. Chaque page publique fournit les siens ; les vignettes de rubrique, au format 1200 x 630, sont composées une fois pour toutes par la commande mja:images-partage et livrées avec le site. Deux pièges évités : l'adresse de l'image doit être absolue, sans quoi aucun aperçu n'apparaît, et les dimensions annoncées doivent être les vraies. Une image déclarée mais absente du disque retombe sur la vignette de l'association plutôt que de renvoyer une erreur au robot."],

        ['sous', "Les pages d'erreur"],
        ['p', "Treize pages, de 400 à 504, aux couleurs de l'association : chacune explique ce qui s'est passé, ce que la personne peut faire, et propose une sortie. Elles sont volontairement autonomes — aucun gabarit partagé, aucune requête en base, aucune feuille de style externe. Une page d'erreur doit s'afficher quand le reste ne fonctionne plus : celle qui interroge la base rejouerait l'incident qu'elle annonce."],

        ['sous', 'Le suivi des sources'],
        ['p', "Chaque source d'acquisition dispose d'un lien court. La visite est enregistrée avec une empreinte anonyme, et l'identifiant est conservé en session : si la personne adhère, l'adhésion porte la source. C'est ce qui permet de savoir quel support a réellement fonctionné."],
    ]],

    ['titre' => 'Exploitation', 'blocs' => [
        ['sous', 'Installation'],
        ['code', "composer install --no-dev --optimize-autoloader\nphp artisan key:generate\nphp artisan migrate --force\nphp artisan storage:link\nphp artisan config:cache && php artisan route:cache && php artisan view:cache"],

        ['sous', 'Remplir le site sans toucher aux comptes'],
        ['code', "php artisan db:seed --class=ProjectSeeder --force\nphp artisan db:seed --class=EventSeeder --force\nphp artisan db:seed --class=ArticleSeeder --force\nphp artisan db:seed --class=ResourceSeeder --force\nphp artisan db:seed --class=EvenementsRentree2026Seeder --force\nphp artisan db:seed --class=SaisonAdhesion2026Seeder --force"],
        ['note', "Ne jamais lancer db:seed seul : le seeder global appelle AdminSeeder. MembresActuelsSeeder régénère les mots de passe des adhérents — à réserver à une première installation."],

        ['sous', 'Commandes utiles'],
        ['table', ['entetes' => ['Commande', 'Effet'], 'lignes' => [
            ['mja:relances', 'Envoie les relances dues ; --simulation pour lister sans envoyer'],
            ['mja:purge-members', 'Supprime définitivement les comptes effacés depuis plus de 30 jours'],
            ['mja:backup', 'Sauvegarde base et fichiers déposés'],
            ['mja:mdp-membres', 'Régénère ou importe les mots de passe adhérents'],
            ['mja:diag-photos', 'Diagnostique les photos manquantes du trombinoscope'],
        ]]],

        ['sous', 'Après une mise à jour du code'],
        ['p', "Vider puis reconstruire les caches de vues et de configuration. Les vues Blade sont compilées : une modification de gabarit n'est visible qu'après vidage du cache si celui-ci a été construit."],
    ]],

    ['titre' => 'Que faire quand…', 'blocs' => [
        ['p', "Les pannes qui reviennent, et ce qui les provoque. Dans la quasi-totalité des cas la cause est un cache non régénéré ou un réglage absent, pas un défaut du code."],

        ['sous', 'Page blanche ou erreur 500 après un déploiement'],
        ['etapes', [
            ['Regarder le journal', "storage/logs/laravel.log, dernières lignes : le message y est en clair."],
            ['Cache de configuration', "Un .env modifié n'est pris en compte qu'après php artisan config:cache."],
            ['Migrations', "Une colonne ajoutée par le code mais absente en base donne « column does not exist » : php artisan migrate --force."],
            ['Droits des dossiers', "storage/ et bootstrap/cache/ doivent être inscriptibles par le serveur web."],
        ]],

        ['sous', 'Route [nom] not defined'],
        ['p', "Le cache de routes date d'avant l'ajout. Les vues se recompilent seules quand le fichier change, les routes non : php artisan route:clear puis route:cache. C'est le seul fichier dont une modification reste invisible tant que le cache n'est pas régénéré."],

        ['sous', "Un formulaire est bloqué par le navigateur"],
        ['p', "Message « violates Content Security Policy ». Deux causes : une ressource externe non déclarée dans public/.htaccess, ou des adresses fabriquées en http:// alors que la page est en https:// — le formulaire vise alors une autre origine. Le second cas se règle par la confiance accordée aux en-têtes du proxy, déjà en place."],

        ['sous', "Un email n'est pas parti"],
        ['etapes', [
            ['Le site ne bloque pas', "Tous les envois sont enveloppés : un échec est journalisé, jamais affiché au visiteur. L'action se poursuit."],
            ['Vérifier le journal', "Chercher « Mail » dans storage/logs/laravel.log : le motif du refus du serveur y figure."],
            ['Réglages', "Les identifiants du serveur d'envoi sont dans .env, pas en base. Un config:cache est nécessaire après changement."],
            ['Destinataires', "La liste des adresses notifiées est en back-office, dans les paramètres."],
        ]],

        ['sous', "Un paiement n'apparaît pas"],
        ['etapes', [
            ['Le paiement a-t-il abouti', "Le tableau de bord Stripe fait foi, pas le site."],
            ['La demande existe-t-elle', "Si la personne a réglé puis fermé son navigateur avant d'envoyer le formulaire, aucune adhésion n'a été créée. Le webhook envoie alors une alerte à l'association avec la référence et l'email du payeur."],
            ['Le webhook est-il déclaré', "Sans l'adresse et le secret renseignés côté Stripe, aucun rattrapage n'a lieu."],
            ['Rattraper à la main', "Créer l'adhésion en back-office et la passer en « Payée » : les emails et l'accès suivent."],
        ]],

        ['sous', "Une image ne s'affiche pas"],
        ['p', "Les fichiers déposés vivent sur le disque « public », exposé par un lien symbolique. Si toutes les images téléversées manquent d'un coup, le lien a disparu : php artisan storage:link. Si une seule manque, le fichier a été supprimé du disque alors que la fiche le référence encore."],

        ['sous', "Un adhérent ne peut pas se connecter"],
        ['p', "Depuis la fusion des comptes, l'espace adhérent et le back-office partagent la même identité. Une personne qui avait deux mots de passe n'en a plus qu'un : celui du back-office. Le lien « mot de passe oublié » règle tous les cas ; un administrateur peut aussi régénérer le mot de passe depuis Comptes adhérents."],

        ['sous', 'Les relances ne partent pas'],
        ['p', "Faute de tâche planifiée sur l'hébergement, elles sont évaluées à l'occasion des visites, une fois par jour, après une heure configurable. Sans visite après cette heure, rien ne part. La page Relances du back-office affiche les envois dus et permet de déclencher la campagne à la main."],
    ]],

    ['titre' => "Le cycle de vie d'une adhésion", 'blocs' => [
        ['p', "Une adhésion traverse quelques états, et chaque passage déclenche quelque chose : un email, un jeton, une entrée au trombinoscope. Le statut n'est donc pas une simple étiquette — c'est lui qui commande le reste."],
        ['schema', $cycle],

        ['sous', 'Ce que déclenche chaque statut'],
        ['table', ['entetes' => ['Statut', 'Quand', 'Ce qui se déclenche'], 'lignes' => [
            ['nouvelle', "Formulaire envoyé, règlement hors ligne", "Notification à l'équipe, accusé de réception à la personne"],
            ['en_attente_paiement', "L'équipe attend le chèque, l'espèce ou le virement", 'Relances automatiques, jusqu\'à trois, espacées de quinze jours'],
            ['payee', "Cotisation encaissée, par carte ou constatée par l'équipe", "Jeton de création de compte, email de bienvenue, accès à la carte, entrée au trombinoscope"],
            ['prise_infos', "Simple demande de renseignements", 'Aucun paiement, aucune relance, données réduites au minimum'],
            ['refusee', 'Demande écartée', "Email d'information"],
            ['desistement', 'La personne renonce', 'Aucun envoi'],
            ['traitee', 'État libre, à disposition de l\'équipe', 'Aucun automatisme'],
        ]]],
        ['note', "Le passage à « payee » est le seul qui ouvre des droits. Il est donc réversible avec précaution : repasser une adhésion à un autre statut retire l'accès à la carte, mais ne supprime ni le compte ni le mot de passe."],

        ['sous', 'La réadhésion'],
        ['p', "Une réadhésion ne modifie jamais l'adhésion précédente : elle crée une ligne nouvelle qui pointe vers elle. L'historique reste intact, saison après saison, et le compte suit — sa clé d'adhésion courante bascule sur la dernière."],
    ]],

    ['titre' => 'Les emails, un par un', 'blocs' => [
        ['p', "Douze messages, tous en HTML de tableau pour rester lisibles dans les clients de messagerie anciens. Aucun envoi ne bloque le site : un échec est journalisé et l'action se poursuit — une confirmation perdue vaut mieux qu'une adhésion perdue."],

        ['sous', "Autour de l'adhésion"],
        ['table', ['entetes' => ['Message', 'Déclencheur', 'Destinataire'], 'lignes' => [
            ['Notification adhésion', "Le formulaire vient d'être envoyé", "L'association — liste réglable en back-office"],
            ['Confirmation adhésion', 'Le formulaire vient d\'être envoyé', 'La personne, en accusé de réception'],
            ['Changement de statut', "Passage à payée, en attente de paiement ou refusée", "La personne — porte le lien de création de compte quand elle devient adhérente"],
            ['Relance de paiement', "Règlement hors ligne non parvenu, après le délai réglé", 'La personne, trois fois au maximum'],
            ['Relance de renouvellement', "La saison s'achève et l'adhésion n'a pas été renouvelée", 'La personne — contient un lien magique valable 90 jours'],
        ]]],

        ['sous', 'Autour du compte'],
        ['table', ['entetes' => ['Message', 'Déclencheur', 'Destinataire'], 'lignes' => [
            ['Identifiants espace adhérent', "Un administrateur crée le compte ou régénère le mot de passe", 'L\'adhérent'],
            ['Réinitialisation adhérent', "Demande depuis « mot de passe oublié », ou adhésion réglée par quelqu'un qui a déjà un compte", "L'adhérent — un lien, jamais un mot de passe en clair"],
            ['Compte supprimé', "L'adhérent supprime son compte", 'L\'adhérent — contient le lien de restauration, valable 30 jours'],
            ['Compte back-office créé', 'Le super admin crée un accès', 'Le nouvel arrivant'],
            ['Réinitialisation back-office', 'Mot de passe régénéré ou oublié', 'Le titulaire du compte'],
        ]]],

        ['sous', 'Autour du contact'],
        ['table', ['entetes' => ['Message', 'Déclencheur', 'Destinataire'], 'lignes' => [
            ['Notification contact', 'Un message arrive par le formulaire', "L'association"],
            ['Confirmation contact', 'Un message arrive par le formulaire', "L'expéditeur, en accusé de réception"],
        ]]],

        ['sous', 'Les réglages qui commandent les envois'],
        ['table', ['entetes' => ['Réglage', 'Défaut', 'Effet'], 'lignes' => [
            ['Emails de notification', "Adresse de contact de l'association", 'Qui reçoit les alertes de demande et de message'],
            ['Relance de paiement', 'Active, 7 jours, puis tous les 14, 3 au plus', "Quand part la première relance et à quel rythme"],
            ['Relance de renouvellement', 'Active, 30 jours avant la fin de saison, puis tous les 21, 3 au plus', 'Idem pour les renouvellements'],
            ['Heure de déclenchement', 'Réglable', "Avant cette heure, aucune campagne n'est évaluée"],
        ]]],
    ]],

    ['titre' => 'La sécurité en pratique', 'blocs' => [
        ['p', "Rien d'exotique, mais quelques choix qu'il vaut mieux connaître avant d'y toucher."],

        ['sous', 'Les mots de passe'],
        ['p', "Hachés en bcrypt, comme partout. Une copie chiffrée réversible est conservée en plus, lisible du seul super administrateur : c'est ce qui permet de redonner ses accès à un adhérent sans réinitialiser. Ce choix est un compromis assumé — il facilite la vie d'une petite association, au prix d'un secret de plus à protéger. La clé de chiffrement est celle de l'application : la perdre rend ces copies illisibles, sans empêcher personne de se connecter."],
        ['note', "Aucun email ne contient jamais de mot de passe en clair : ce qui circule, ce sont des liens à usage unique."],

        ['sous', 'Les jetons'],
        ['table', ['entetes' => ['Jeton', 'Durée', 'Ce qu\'il ouvre'], 'lignes' => [
            ['Création de compte', '30 jours', "L'écran de création, pour une adhésion réglée"],
            ['Renouvellement', '90 jours', 'Le formulaire pré-rempli, sans connexion'],
            ['Restauration de compte', '30 jours', 'La reprise d\'un compte supprimé'],
            ['Réinitialisation', 'Selon la configuration', 'Le choix d\'un nouveau mot de passe'],
        ]]],
        ['p', "Tous sont tirés au hasard et vérifiés uniques. Un jeton de renouvellement est effacé dès qu'il a servi : le lien d'un email ancien ne rouvre pas un formulaire."],

        ['sous', 'La suppression douce'],
        ['p', "Un compte supprimé n'est pas effacé : il est marqué, disparaît de tous les écrans, et un lien de restauration part par email. Une commande purge définitivement au-delà de trente jours. C'est ce qui permet de revenir sur une suppression faite trop vite — et ce qui explique qu'une adresse reste indisponible pendant ce délai."],

        ['sous', 'Les limites de débit'],
        ['table', ['entetes' => ['Formulaire', 'Limite'], 'lignes' => [
            ['Adhésion', '5 envois par minute'],
            ['Contact', '5 envois par minute'],
            ['Don', '10 par minute'],
            ['Connexion', '6 tentatives par minute'],
            ['Mot de passe oublié', '4 demandes par minute'],
        ]]],
        ['p', "Ces limites portent sur l'adresse du visiteur. Une connexion partagée — un lycée, un local associatif — peut donc voir un blocage sans que personne n'ait mal agi. C'est la page 429 qui l'explique."],

        ['sous', 'Le pot de miel'],
        ['p', "Les formulaires publics portent un champ invisible et un horodatage. Un robot remplit le premier ou répond trop vite ; la demande est alors écartée sans message d'erreur, pour ne rien apprendre à l'automate. Un visiteur normal ne voit jamais rien."],

        ['sous', "L'assainissement du texte enrichi"],
        ['p', "Le contenu rédigé en back-office s'affiche tel quel sur le site public : il est donc filtré à l'écriture par une liste blanche stricte — balises autorisées, attributs autorisés, quatre valeurs d'alignement, et seuls les liens http, mailto, tel ou internes conservés. Le filtre est posé dans un mutateur du modèle, donc appliqué quel que soit le chemin d'écriture."],

        ['sous', 'Les paiements'],
        ['p', "Le navigateur n'est jamais cru sur parole : un règlement annoncé par la page est revérifié auprès de Stripe avant enregistrement, montant et devise compris. Les appels entrants de Stripe sont authentifiés par signature, avec une tolérance de cinq minutes sur l'horodatage et une comparaison à temps constant. Les clés secrètes sont chiffrées en base."],
    ]],

    ['titre' => 'Les données personnelles', 'blocs' => [
        ['p', "Ce que le site conserve, pourquoi, combien de temps, et comment répondre si quelqu'un demande des comptes. Le responsable de traitement est l'association."],

        ['sous', 'Ce qui est collecté'],
        ['table', ['entetes' => ['Donnée', 'Pourquoi', 'Où'], 'lignes' => [
            ['Identité, date de naissance', "Tenir le registre des membres, vérifier l'âge", 'adhesions'],
            ['Coordonnées, adresse postale', 'Contacter, convoquer, envoyer les documents', 'adhesions'],
            ['Profession', 'Connaître le profil des membres', 'adhesions'],
            ['Taille de T-shirt, permis', "Organiser les actions et les déplacements", 'adhesions'],
            ['Problèmes de santé', "Sécurité pendant les activités — donnée sensible", 'adhesions'],
            ['Contact d\'urgence', 'Prévenir un proche en cas de besoin', 'adhesions'],
            ['Photo', 'Trombinoscope et carte de membre', 'disque public'],
            ['Réseaux sociaux', 'Facultatif, affiché au trombinoscope', 'adhesions'],
            ['Email et mot de passe', 'Accès à l\'espace adhérent', 'users'],
            ['Empreinte de visite', "Mesurer l'efficacité des supports — sans identifier personne", 'source_visits'],
        ]]],
        ['note', "Les problèmes de santé relèvent des données sensibles : accès réservé au back-office, jamais affichés publiquement, et à ne pas exporter sans raison."],

        ['sous', 'Les consentements'],
        ['p', "Deux cases distinctes, et elles ne se valent pas. Le consentement au traitement des données est obligatoire — sans lui, pas d'adhésion. Le droit à l'image est également demandé pour une adhésion, et n'est jamais coché lors d'une simple prise d'informations : quelqu'un qui pose une question n'a pas donné d'accord de diffusion."],

        ['sous', 'Combien de temps'],
        ['table', ['entetes' => ['Donnée', 'Conservation'], 'lignes' => [
            ['Adhésions', "Conservées comme registre de l'association, saison après saison"],
            ['Compte supprimé', 'Effacé définitivement 30 jours après la demande'],
            ['Jetons', 'De 30 à 90 jours, puis sans effet'],
            ['Messages de contact', "Jusqu'à suppression manuelle en back-office"],
            ['Visites tracées', 'Empreinte anonyme, sans lien avec une identité'],
        ]]],
        ['p', "La durée de conservation des adhésions n'est pas fixée par le code : c'est une décision de l'association, à écrire dans sa politique de confidentialité."],

        ['sous', 'Répondre à une demande'],
        ['etapes', [
            ['Accès', "Retrouver la personne dans Adhésions, exporter sa fiche, y joindre son compte et ses éventuelles photos."],
            ['Rectification', "Modifier la fiche en back-office, ou laisser la personne le faire depuis son espace."],
            ['Effacement', "Supprimer le compte depuis Comptes adhérents et la fiche depuis Adhésions. Le compte part définitivement au bout de 30 jours ; la fiche, elle, est retirée immédiatement, photo comprise."],
            ['Opposition à l\'image', "Décocher le droit à l'image sur la fiche, retirer la personne du trombinoscope, supprimer sa photo."],
            ['Portabilité', "L'export CSV des adhésions fournit un format lisible et réutilisable."],
        ]],
        ['note', "Une demande d'effacement ne dispense pas l'association de tenir son registre des membres : les obligations légales priment, il faut alors expliquer ce qui est conservé et pourquoi."],

        ['sous', 'Les tiers'],
        ['p', "Stripe reçoit l'email et le montant pour encaisser, et conserve ses propres traces. Le serveur d'envoi des emails voit passer les messages. Aucune autre donnée ne quitte le site : ni régie publicitaire, ni mesure d'audience externe, ni bouton de réseau social traçant. Une plateforme de dons, si elle est branchée, applique sa propre politique."],
    ]],

    ['titre' => 'Points de vigilance', 'blocs' => [
        ['liste', [
            "Feuille Tailwind précompilée — toute classe utilitaire absente de la feuille livrée reste sans effet. Écrire le CSS spécifique dans la vue.",
            "Fusion des comptes — pour les personnes qui avaient deux mots de passe, celui du back-office subsiste. Les prévenir avant la bascule.",
            "Événements de démonstration — le jeu d'exemples publie des événements fictifs, dont une assemblée générale. À dépublier ou à remplacer par les vraies dates.",
            "Webhook Stripe — la route existe et vérifie les signatures, mais elle reste sans effet tant que l'adresse et le secret ne sont pas déclarés dans le tableau de bord Stripe.",
            "Table members — conservée après la fusion, à supprimer une fois la bascule validée en production. Le seeder qui l'alimentait est neutralisé : son contenu reste en commentaire, à ne réactiver que sur une base vierge.",
            "Dépendances de développement — PHPUnit n'est pas installé sur l'hébergement (installation sans --dev). Les tests ne s'exécutent que sur un poste de développement.",
            "Politique de sécurité du contenu — définie dans public/.htaccess. Elle bloque tout domaine non déclaré, sans message visible pour le visiteur : à relire avant d'ajouter un service tiers.",
            "Cache de routes — une modification de routes/web.php reste invisible en production tant que php artisan route:cache n'a pas été relancé. Les vues, elles, se recompilent seules.",
        ]],
    ]],

    ];

    // Compteurs affichés en page de garde.
    $nbSections = count($doc);
    $nbBlocs = array_sum(array_map(fn ($s) => count($s['blocs']), $doc));
@endphp
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex, nofollow">
<title>Cahier du projet — Site Madin'Jeunes Ambition</title>
<link rel="icon" type="image/jpeg" href="{{ asset('images/logo.jpg') }}">
<link rel="stylesheet" href="{{ asset('css/gill-sans.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/fontawesome/css/all.min.css') }}">
<style>
:root{--navy:#1A3D8A;--dark:#2048A4;--blue:#3DAEF5;--bluedark:#1E93D6;
      --yellow:#F5A623;--red:#D0021B;--ink:#0B1E45;--gris:#6C7A91;--bord:#E4EAF4;--fond:#F5F8FD}
*{box-sizing:border-box}
body{margin:0;background:var(--fond);color:#3A4A63;font-family:'Gill Sans','Open Sans',sans-serif;
     font-size:15px;line-height:1.65}
.wrap{max-width:1080px;margin:0 auto;padding:0 22px}

header{background:linear-gradient(135deg,#1A3D8A 0%,#2048A4 48%,#3262CC 100%);color:#fff;
       padding:34px 0 30px;position:relative;overflow:hidden}
header .bar{position:absolute;left:0;right:0;top:0;height:6px;display:flex}
header .bar i{flex:1}
.b1{background:var(--blue)}.b2{background:var(--yellow)}.b3{background:var(--red)}
.idt{display:flex;align-items:center;gap:15px;margin-bottom:18px}
.idt img{height:56px;width:56px;object-fit:contain;background:#fff;border-radius:13px;padding:5px}
.idt .n{font-weight:800;font-size:18px;letter-spacing:.7px}
.idt .s{font-size:12.5px;color:#BDD4F5;font-style:italic}
h1{margin:0 0 6px;font-size:29px;font-weight:800}
header .st{color:#C9DBFA;font-size:15px}
.compteurs{display:flex;flex-wrap:wrap;gap:10px;margin-top:20px}
.compteur{background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.22);border-radius:12px;
          padding:9px 15px;font-size:13px;font-weight:600}
.compteur b{font-size:18px;display:block;line-height:1.2}

.outils{background:#fff;border:1px solid var(--bord);border-radius:16px;padding:13px 18px;
        margin:-24px 0 28px;display:flex;flex-wrap:wrap;gap:10px;align-items:center;
        box-shadow:0 6px 22px rgba(20,40,90,.09)}
.outils .sep{flex:1}
.btn{border:0;border-radius:11px;padding:9px 16px;font:inherit;font-size:13.5px;font-weight:700;
     cursor:pointer;display:inline-flex;align-items:center;gap:7px;transition:.15s}
.btn.p{background:var(--bluedark);color:#fff}.btn.p:hover{background:var(--dark)}
.btn.g{background:#EEF3FB;color:var(--dark)}.btn.g:hover{background:#E2EBF8}

.sommaire{background:#fff;border:1px solid var(--bord);border-radius:16px;padding:20px 24px;margin-bottom:26px}
.sommaire h2{margin:0 0 12px;font-size:14px;font-weight:800;color:var(--navy);
             text-transform:uppercase;letter-spacing:.8px}
.sommaire ol{margin:0;padding-left:20px;columns:2;column-gap:34px}
.sommaire li{margin-bottom:6px}
.sommaire a{color:var(--dark);text-decoration:none;font-weight:600}
.sommaire a:hover{text-decoration:underline}

section.bloc{background:#fff;border:1px solid var(--bord);border-radius:16px;padding:26px 30px;margin-bottom:20px}
section.bloc > h2{margin:0 0 18px;font-size:21px;font-weight:800;color:var(--navy);
                  display:flex;align-items:center;gap:12px}
section.bloc > h2 .num{background:var(--navy);color:#fff;width:32px;height:32px;border-radius:9px;
                       display:flex;align-items:center;justify-content:center;font-size:15px;flex:none}
h3{margin:24px 0 10px;font-size:16px;font-weight:800;color:var(--ink)}
h3:first-of-type{margin-top:6px}
p{margin:0 0 12px}
ul.simple{margin:0 0 14px;padding-left:20px}
ul.simple li{margin-bottom:6px}

table{width:100%;border-collapse:collapse;margin:0 0 16px;font-size:13.5px}
th{text-align:left;background:#F2F7FF;color:var(--navy);font-weight:800;font-size:11.5px;
   text-transform:uppercase;letter-spacing:.5px;padding:9px 12px;border-bottom:2px solid var(--bord)}
td{padding:9px 12px;border-bottom:1px solid #EEF2F8;vertical-align:top}
tr:last-child td{border-bottom:0}
td:first-child{font-weight:700;color:var(--ink);white-space:nowrap}

.etape{display:flex;gap:14px;margin-bottom:12px}
.etape .cle{width:190px;flex:none;font-weight:800;color:var(--dark);font-size:14px}
.etape .txt{flex:1}

.note{background:#FFFBEB;border:1px solid #FDE9B8;border-radius:11px;padding:13px 16px;
      margin:0 0 16px;font-size:13.5px;color:#78591C}
pre{background:var(--ink);color:#D8E4F8;border-radius:11px;padding:15px 17px;margin:0 0 16px;
    font-family:'Courier New',monospace;font-size:12.5px;line-height:1.7;overflow-x:auto}

/* Schéma de la base : le SVG occupe la largeur disponible, sur un fond
   quadrillé qui rappelle un tableau blanc. */
.schema{margin:0 0 18px;border:1px solid var(--bord);border-radius:14px;padding:14px;
        background-color:#FBFCFE;
        background-image:linear-gradient(#EAF0F9 1px,transparent 1px),
                         linear-gradient(90deg,#EAF0F9 1px,transparent 1px);
        background-size:26px 26px}
.schema svg{width:100%;height:auto;display:block;
            font-family:'Gill Sans','Open Sans',sans-serif}

footer{padding:24px 0 44px;color:var(--gris);font-size:13px;display:flex;flex-wrap:wrap;
       gap:10px;justify-content:space-between}
@media(max-width:720px){.sommaire ol{columns:1}.etape{flex-direction:column;gap:2px}.etape .cle{width:auto}}
</style>
</head>
<body>

<header>
  <div class="bar"><i class="b1"></i><i class="b2"></i><i class="b3"></i></div>
  <div class="wrap">
    <div class="idt">
      <img src="{{ asset('images/logo.jpg') }}" alt="Madin'Jeunes Ambition">
      <div>
        <div class="n">MADIN' JEUNES AMBITION</div>
        <div class="s">Relève tous les défis !</div>
      </div>
    </div>
    <h1>Cahier du projet</h1>
    <div class="st">Documentation technique et fonctionnelle du site</div>
    <div class="compteurs">
      <div class="compteur"><b>{{ $nbSections }}</b> sections</div>
      <div class="compteur"><b>140</b> routes</div>
      <div class="compteur"><b>16</b> tables métier</div>
      <div class="compteur"><b>89</b> gabarits</div>
      <div class="compteur"><b>33</b> migrations</div>
    </div>
  </div>
</header>

<div class="wrap">
  <div class="outils">
    <span style="font-size:13px;color:var(--gris)">
      Document de référence pour toute personne qui reprend ou découvre le projet.
    </span>
    <span class="sep"></span>
    <button class="btn g" id="btn-imprimer"><i class="fas fa-print"></i> Imprimer</button>
    <button class="btn p" id="btn-pdf" data-logo="{{ asset('images/logo.jpg') }}">
      <i class="fas fa-file-pdf"></i> Télécharger le PDF
    </button>
  </div>

  <div class="sommaire">
    <h2>Sommaire</h2>
    <ol>
      @foreach($doc as $i => $section)
      <li><a href="#s{{ $i + 1 }}">{{ $section['titre'] }}</a></li>
      @endforeach
    </ol>
  </div>

  @foreach($doc as $i => $section)
  <section class="bloc" id="s{{ $i + 1 }}">
    <h2><span class="num">{{ $i + 1 }}</span> {{ $section['titre'] }}</h2>

    @foreach($section['blocs'] as $bloc)
      @php [$type, $contenu] = $bloc; @endphp

      @if($type === 'p')
        <p>{{ $contenu }}</p>

      @elseif($type === 'sous')
        <h3>{{ $contenu }}</h3>

      @elseif($type === 'liste')
        <ul class="simple">
          @foreach($contenu as $item)<li>{{ $item }}</li>@endforeach
        </ul>

      @elseif($type === 'etapes')
        @foreach($contenu as [$cle, $txt])
        <div class="etape"><div class="cle">{{ $cle }}</div><div class="txt">{{ $txt }}</div></div>
        @endforeach

      @elseif($type === 'table')
        <table>
          <thead><tr>@foreach($contenu['entetes'] as $e)<th>{{ $e }}</th>@endforeach</tr></thead>
          <tbody>
            @foreach($contenu['lignes'] as $ligne)
            <tr>@foreach($ligne as $cellule)<td>{{ $cellule }}</td>@endforeach</tr>
            @endforeach
          </tbody>
        </table>

      @elseif($type === 'note')
        <div class="note"><i class="fas fa-circle-info"></i> {{ $contenu }}</div>

      @elseif($type === 'code')
        <pre>{{ $contenu }}</pre>

      @elseif($type === 'schema')
        {{-- Diagramme dessiné : mêmes coordonnées que la version PDF. --}}
        <div class="schema">
        <svg viewBox="0 0 {{ $contenu['w'] }} {{ $contenu['h'] }}" role="img"
             aria-label="Schéma des tables de la base de données">
          @foreach($contenu['zones'] as $z)
          <rect x="{{ $z['x'] }}" y="{{ $z['y'] }}" width="{{ $z['w'] }}" height="{{ $z['h'] }}"
                rx="18" fill="{{ $z['fond'] }}" stroke="{{ $z['bord'] }}" stroke-width="2"
                stroke-dasharray="9 7"/>
          <text x="{{ $z['x'] + 22 }}" y="{{ $z['y'] + 26 }}" fill="{{ $z['encre'] }}"
                font-size="15" font-weight="700" letter-spacing="1.4">{{ $z['titre'] }}</text>
          @endforeach

          @foreach($contenu['liens'] as $l)
          <polyline points="@foreach($l['points'] as $p){{ $p[0] }},{{ $p[1] }} @endforeach"
                    fill="none" stroke="#8FA3C0" stroke-width="2.6"
                    stroke-linecap="round" stroke-linejoin="round"/>
          <circle cx="{{ $l['points'][0][0] }}" cy="{{ $l['points'][0][1] }}" r="4.5" fill="#8FA3C0"/>
          <circle cx="{{ end($l['points'])[0] }}" cy="{{ end($l['points'])[1] }}" r="4.5" fill="#8FA3C0"/>
          @foreach(['de', 'vers'] as $bout)
            @if(isset($l[$bout]))
            <text x="{{ $l[$bout][1] }}" y="{{ $l[$bout][2] }}" fill="#1A3D8A"
                  font-size="16" font-weight="800">{{ $l[$bout][0] }}</text>
            @endif
          @endforeach
          @if(isset($l['note']))
          <text x="{{ $l['note'][1] }}" y="{{ $l['note'][2] }}" fill="#6C7A91" font-size="14"
                text-anchor="{{ count($l['points']) > 2 ? 'middle' : 'start' }}"
                font-style="italic">{{ $l['note'][0] }}</text>
          @endif
          @endforeach

          @foreach($contenu['tables'] as $t)
          @php $h = 58 + count($t['champs']) * 26 + 14; @endphp
          <rect x="{{ $t['x'] }}" y="{{ $t['y'] }}" width="{{ $t['w'] }}" height="{{ $h }}"
                rx="12" fill="#FFFFFF" stroke="{{ $t['fond'] }}" stroke-width="2.4"/>
          <path d="M{{ $t['x'] }},{{ $t['y'] + 38 }} L{{ $t['x'] }},{{ $t['y'] + 12 }}
                   a12,12 0 0 1 12,-12 L{{ $t['x'] + $t['w'] - 12 }},{{ $t['y'] }}
                   a12,12 0 0 1 12,12 L{{ $t['x'] + $t['w'] }},{{ $t['y'] + 38 }} Z"
                fill="{{ $t['fond'] }}"/>
          <text x="{{ $t['x'] + 14 }}" y="{{ $t['y'] + 26 }}" fill="{{ $t['encre'] }}"
                font-size="19" font-weight="800">{{ $t['nom'] }}</text>
          <text x="{{ $t['x'] + 14 }}" y="{{ $t['y'] + 53 }}" fill="#8494AB"
                font-size="12.5" font-style="italic">{{ $t['sous'] }}</text>
          @foreach($t['champs'] as $i => $champ)
          <text x="{{ $t['x'] + 14 }}" y="{{ $t['y'] + 80 + $i * 26 }}"
                fill="{{ str_ends_with($champ, '_id') ? $t['fond'] : '#5A6A80' }}"
                font-size="14.5" font-weight="{{ str_ends_with($champ, '_id') ? 700 : 400 }}">{{ $champ }}</text>
          @endforeach
          @endforeach

          @foreach($contenu['pastilles'] as $p)
          <rect x="{{ $p['x'] }}" y="{{ $p['y'] }}" width="150" height="30" rx="9"
                fill="#FFFFFF" stroke="#D8E3F3" stroke-width="2"/>
          <text x="{{ $p['x'] + 75 }}" y="{{ $p['y'] + 20 }}" fill="#3A5480" font-size="14"
                font-weight="600" text-anchor="middle">{{ $p['nom'] }}</text>
          @endforeach
        </svg>
        </div>
      @endif
    @endforeach
  </section>
  @endforeach

  <footer>
    <span>Madin' Jeunes Ambition — Relève tous les défis !</span>
    <span>Cahier du projet — {{ now()->locale('fr')->isoFormat('D MMMM Y') }}</span>
  </footer>
</div>

<script>var CAHIER = @json($doc);</script>
<script src="{{ asset('js/mja-pdf.js') }}?v={{ filemtime(public_path('js/mja-pdf.js')) }}"></script>
<script src="{{ asset('js/cahier-pdf.js') }}?v={{ filemtime(public_path('js/cahier-pdf.js')) }}"></script>
</body>
</html>
