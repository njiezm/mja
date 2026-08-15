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
            ['Gabarits', 'Blade', '88 vues'],
            ['Styles', 'Tailwind CSS précompilé', 'feuille statique, pas de build au déploiement'],
            ['Icônes', 'Font Awesome', 'servi en local, repli CDN'],
            ['Polices', 'Gill Sans, AllRound Gothic', 'servies depuis le domaine, préchargées'],
            ['Paiement', 'Stripe', 'API HTTP directe, sans SDK'],
            ['Emails', 'Mailables Laravel', 'gabarits Blade en tableaux HTML'],
        ]]],
        ['note', "La feuille Tailwind est précompilée et livrée telle quelle. Conséquence pratique : une classe utilitaire absente de cette feuille — notamment toute valeur arbitraire entre crochets — n'aura aucun effet. Les mises en page inhabituelles s'écrivent donc en CSS, dans un bloc de la vue concernée."],
        ['sous', 'Aucune dépendance JavaScript'],
        ['p', "Le générateur de visuels, le monteur vidéo et les exports PDF sont écrits à la main, sans librairie. Le SVG est produit par concaténation de chaînes, la vidéo par MediaRecorder sur un canvas, et le PDF par écriture directe des objets du format. C'est plus de code, mais aucune dépendance à maintenir et aucun téléchargement pour le visiteur."],
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
            ['database/migrations', '32', 'Schéma, dans l\'ordre chronologique'],
            ['database/seeders', '11', 'Jeux de données : contenu, équipe, adhérents, saison'],
            ['resources/views', '88', 'Gabarits Blade'],
        ]]],
        ['sous', 'Les 135 routes'],
        ['liste', [
            "40 publiques — accueil, projets, événements, actualités, ressources, adhésion, dons, contact, mentions légales, sitemap.",
            "17 dans l'espace adhérent, sous le préfixe /espace.",
            "78 dans le back-office, sous le préfixe /admin.",
        ]],
        ['p', "Une route de suivi des sources d'acquisition est déclarée en dernier : elle capture tout segment unique non déjà routé et renvoie une erreur 404 si le slug ne correspond à aucune source enregistrée. Sa position en fin de fichier est délibérée — la déplacer casserait toutes les pages."],
        ['sous', 'Outils internes'],
        ['table', ['entetes' => ['Route', 'Accès', 'Rôle'], 'lignes' => [
            ['/kit-adhesion', 'Public', "Générateur de 139 visuels : posts, stories, affiches, flyers, kakémonos, bannières, vidéos"],
            ['/kit-video', 'Public', 'Monteur vidéo : intro, plans, outro, export MP4'],
            ['/plan-comm', 'Back-office', 'Plan de communication modifiable, export CSV et PDF'],
            ['/cahier-projet', 'Back-office', 'Le présent document'],
        ]]],
    ]],

    ['titre' => 'Modèle de données', 'blocs' => [
        ['p', "Dix-huit tables métier. Le pivot du modèle est le couple compte / adhésion : une personne possède un compte unique, et autant d'adhésions que de saisons auxquelles elle a participé."],

        ['sous', 'users — le compte unique'],
        ['p', "Une seule table de comptes pour tout le site. Un compte peut être adhérent, administrateur, ou les deux. Le rôle décide de l'accès au back-office ; la clé adhesion_id décide de l'accès à l'espace adhérent."],
        ['table', ['entetes' => ['Colonne', 'Type', 'Rôle'], 'lignes' => [
            ['id', 'entier', 'Identifiant'],
            ['name, email', 'texte', 'Identité — email unique, stocké en minuscules'],
            ['password', 'haché', 'Authentification'],
            ['password_encrypted', 'chiffré', 'Copie réversible, lisible par le super admin uniquement'],
            ['role', 'texte', 'membre, gestionnaire_contenu, admin, super_admin'],
            ['is_active', 'booléen', "Révocation d'accès sans suppression"],
            ['adhesion_id', 'clé', 'Adhésion en cours — null pour un compte purement administrateur'],
            ['show_in_directory', 'booléen', 'Visibilité au trombinoscope'],
            ['restore_token', 'texte', 'Restauration après suppression (30 jours)'],
            ['deleted_at', 'date', 'Suppression douce'],
        ]]],

        ['sous', 'adhesions — une ligne par saison'],
        ['p', "Trente-deux colonnes. Chaque adhésion porte l'identité, les coordonnées, les informations pratiques, le consentement RGPD, le moyen de paiement et le statut."],
        ['table', ['entetes' => ['Colonne', 'Rôle'], 'lignes' => [
            ['user_id', 'Compte propriétaire — plusieurs adhésions pour un même compte'],
            ['period_id', 'Saison concernée'],
            ['renouvelle_adhesion_id', "Adhésion de l'année précédente que celle-ci renouvelle"],
            ['statut', 'nouvelle, prise_infos, en_attente_paiement, payee, refusee, desistement'],
            ['moyen_paiement', 'cheque, espece, virement, en_ligne'],
            ['reseaux_sociaux', 'JSON — facultatif, affiché au trombinoscope'],
            ['photo', 'Facultative, déposable plus tard depuis l\'espace'],
            ['message', "Question posée lors d'une simple prise d'informations"],
            ['account_token', 'Jeton de création de compte, valable 30 jours'],
            ['renouvellement_token', 'Lien magique de réadhésion, valable 90 jours'],
            ['source_id', "Source d'acquisition ayant amené la personne"],
        ]]],

        ['sous', 'Les relations'],
        ['etapes', [
            ['users → adhesions', "Un compte a plusieurs adhésions (historique). users.adhesion_id désigne la courante."],
            ['adhesions → adhesion_periods', "Chaque adhésion appartient à une saison."],
            ['adhesions → adhesions', "renouvelle_adhesion_id chaîne les saisons successives d'une même personne."],
            ['adhesions → adhesion_relances', "Journal des relances envoyées, garde-fou contre les doublons."],
            ['projects → events', "Un projet a de zéro à N événements ; un événement appartient à un seul projet, ou à aucun."],
            ['sources → source_visits', "Suivi des campagnes d'acquisition, visiteur anonymisé par empreinte."],
        ]],

        ['sous', 'Les autres tables'],
        ['table', ['entetes' => ['Table', 'Contenu'], 'lignes' => [
            ['projects, events, articles, resources', 'Contenu éditorial du site'],
            ['team_members', "Bureau et équipe affichés sur la page À propos"],
            ['partenaires', 'Logos et liens des partenaires'],
            ['contacts', 'Messages reçus via le formulaire de contact'],
            ['donations', 'Dons, avec la session Stripe associée'],
            ['settings', 'Réglages clé-valeur ; les secrets sont chiffrés au repos'],
            ['adhesion_periods', 'Saisons — détermine la période courante et déclenche les renouvellements'],
            ['members', 'Ancienne table des comptes adhérents, conservée comme filet de sécurité après la fusion'],
        ]]],
        ['note', "La table members n'est plus utilisée par le code. Elle reste en place le temps de valider la bascule en production ; une migration ultérieure pourra la retirer."],
    ]],

    ['titre' => 'Rôles et permissions', 'blocs' => [
        ['table', ['entetes' => ['Rôle', 'Espace adhérent', 'Contenu', 'Gestion', 'Comptes', 'Paramètres'], 'lignes' => [
            ['Adhérent', 'Oui', '—', '—', '—', '—'],
            ['Gestionnaire de contenu', 'Si adhérent', 'Oui', '—', '—', '—'],
            ['Administrateur', 'Si adhérent', 'Oui', 'Oui', 'Gestionnaires et adhérents', 'Sauf secrets Stripe'],
            ['Super administrateur', 'Si adhérent', 'Oui', 'Oui', 'Tous', 'Tout'],
        ]]],
        ['p', "Trois middlewares gardent ces frontières : content, admin et super_admin. Chacun vérifie aussi que le compte est actif, et déconnecte immédiatement un accès révoqué."],
        ['sous', 'Nommer un adhérent'],
        ['p', "Un adhérent devient gestionnaire ou administrateur depuis la page Comptes adhérents : son compte existe déjà, on ne fait qu'élargir ses droits. Un administrateur ne peut conférer que le rôle de gestionnaire ; seul le super admin nomme des administrateurs. Personne ne peut modifier son propre rôle — c'est le meilleur moyen de se verrouiller dehors."],
    ]],

    ['titre' => 'Parcours utilisateurs', 'blocs' => [
        ['sous', "Première adhésion"],
        ['etapes', [
            ['1. Le formulaire', "Identité, coordonnées, réseaux sociaux facultatifs, taille de T-shirt, permis, santé, contact d'urgence, droit à l'image, consentement RGPD. La photo est facultative."],
            ['2. Le paiement', "Carte bancaire dans la page, ou chèque, espèces, virement. En carte, le bouton d'envoi reste désactivé tant que le règlement n'a pas abouti."],
            ['3. L\'enregistrement', "Le serveur revérifie le paiement auprès de Stripe avant d'enregistrer. Une notification part vers l'association, une confirmation vers la personne."],
            ['4. Le compte', "Une fois la cotisation encaissée, un lien de création de compte est envoyé, valable 30 jours."],
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

        ['sous', 'Le générateur de visuels'],
        ['p', "139 visuels composés en SVG à la volée, dans huit gabarits et quatorze pictogrammes vectoriels dessinés à la main. L'export rasterise le SVG dans un canvas, avec polices et images encodées en base64 — un SVG chargé dans une balise image n'a accès à aucune ressource externe. Les PDF sont écrits directement, une page, une image JPEG, sans librairie."],

        ['sous', 'Les exports PDF'],
        ['p', "Un moteur partagé écrit les objets PDF à la main : pages A4, polices standard, texte sélectionnable, encodage WinAnsi pour les accents. Les largeurs de texte sont mesurées avec un canvas en Arial, métriquement compatible avec Helvetica. Il sert au cahier du projet, au plan de communication et à la carte de membre."],

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

    ['titre' => 'Points de vigilance', 'blocs' => [
        ['liste', [
            "Feuille Tailwind précompilée — toute classe utilitaire absente de la feuille livrée reste sans effet. Écrire le CSS spécifique dans la vue.",
            "Fusion des comptes — pour les personnes qui avaient deux mots de passe, celui du back-office subsiste. Les prévenir avant la bascule.",
            "Événements de démonstration — le jeu d'exemples publie des événements fictifs, dont une assemblée générale. À dépublier ou à remplacer par les vraies dates.",
            "Adresses postales — la colonne existe encore et contient les anciennes saisies, bien qu'elle ne soit plus ni demandée ni affichée. Purge à décider.",
            "Webhook Stripe — le réglage existe mais aucun endpoint ne le consomme. Un paiement 3-D Secure abouti après fermeture du navigateur n'est pas rattrapé.",
            "Adhérents existants non connectés — une réadhésion faite sans être connecté crée une adhésion non rattachée au compte existant.",
            "Table members — conservée après la fusion, à supprimer une fois la bascule validée en production.",
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
      <div class="compteur"><b>135</b> routes</div>
      <div class="compteur"><b>18</b> tables métier</div>
      <div class="compteur"><b>88</b> gabarits</div>
      <div class="compteur"><b>32</b> migrations</div>
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
<script src="{{ asset('js/mja-pdf.js') }}"></script>
<script src="{{ asset('js/cahier-pdf.js') }}"></script>
</body>
</html>
