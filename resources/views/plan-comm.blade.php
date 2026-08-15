@php
    /**
     * Plan de communication de la campagne d'adhésion 2026-2027.
     *
     * Le tableau d'origine mélangeait dates de publication, statuts et notes
     * de réunion sur trente lignes. On le restructure ici en trois choses
     * distinctes : les jalons de la campagne (ce qui est daté et engageant),
     * les contenus à produire (ordonnés dans le temps), et les points à
     * valider avant diffusion.
     *
     * Chaque champ est modifiable dans la page ; les changements sont
     * conservés dans le navigateur et exportables.
     */

    // Jalons : dates structurantes, indépendantes des publications.
    $jalons = [
        ['17/08/2026', "Lancement de la campagne", "Ouverture des adhésions et des réadhésions.", 'ouverture'],
        ['05/09/2026', "Journée Portes Ouvertes des Associations", "Stand MJA à la Savane.", 'evenement'],
        ['31/10/2026', "Clôture de la campagne", "Fin des adhésions pour la saison.", 'cloture'],
    ];

    /**
     * Contenus du plan.
     * type   : short | infographie | photo
     * statut : publie | validation | production | proposition
     * lie    : rattachement à un élément déjà établi sur le site
     */
    $contenus = [
        ['10/08/2026', "Story MJ'Afterwork cinéma", 'short', 'publie',
         "Invitation et rappel de l'afterwork cinéma au local.",
         "Date issue de la note de réunion ; à confirmer selon la disponibilité du visuel et du sondage.",
         "Événement « MJ'Afterwork cinéma » (brouillon sur le site)"],

        ['17/08/2026', "Bande-annonce MJ'Adhésion 2026", 'short', 'production',
         "Vidéo dynamique de découverte de MJA pour les nouveaux adhérents.",
         "Recensement des vidéos, sélection des séquences, montage, validation interne, vérification des droits à l'image.",
         "Kit d'adhésion — gabarit « Bande-annonce » (2,5 s)"],

        ['17/08/2026', "Flyer principal MJ'Adhésion", 'infographie', 'validation',
         "Présentation de la campagne d'adhésion et de réadhésion, avec appel à l'action.",
         "Valider le texte, le lien du formulaire et les informations de contact.",
         "Kit d'adhésion — gabarits « Moderne » et « Trio »"],

        ['17/08/2026', "Rappel lancement adhésion / réadhésion", 'infographie', 'validation',
         "Message de lancement.",
         "À publier avec le flyer principal et le lien d'adhésion validé.", null],

        ['18/08/2026', "Story Foyal Color Red", 'short', 'proposition',
         "Annonce de la participation de MJA à la Foyal Color Red.",
         "Annonce de la participation de MJA à la Foyal Color Red du 22 août, Parc La Savane.",
         "Événement « Foyal Color Red » — 22/08/2026, Parc La Savane",
         ["Confirmation de la participation de MJA", "Point de rendez-vous de l'équipe"]],

        ['24/08/2026', "Publication Foyal Color Red", 'photo', 'proposition',
         "Retour visuel sur Foyal Color Red et mise en avant de l'engagement MJA.",
         "Retour visuel après l'événement du 22 août au Parc La Savane.",
         "Événement « Foyal Color Red » — 22/08/2026, Parc La Savane",
         ["Photos de l'équipe MJA sur place", "Nombre de participants MJA", "Autorisations de droit à l'image"]],

        ['19/08/2026', "Publication sortie LaserWest / bowling", 'short', 'proposition',
         "Retour d'activité mettant en avant la cohésion et la vie communautaire.",
         "Valider les images, les personnes visibles et le texte avant diffusion.", null,
         ["Date réelle de la sortie", "Lieu (LaserWest ou bowling ?)", "Photos validées"]],

        ['20/08/2026', "Flyer déclinaison réadhésion", 'infographie', 'validation',
         "Version ciblée pour les membres à renouveler.",
         "Dépend du flyer principal ; faire valider le message, les modalités et le formulaire.",
         "Kit — variante « Appel à la réadhésion »"],

        ['21/08/2026', "Flyer imprimable MJ'Adhésion", 'infographie', 'validation',
         "Version adaptée à l'impression et à l'affichage.",
         "Dépend du flyer principal ; vérifier lisibilité, QR/lien et validation avant impression.",
         "Kit — export PDF A5 / A4 / A3"],

        ['25/08/2026', "MJA et la sécurité routière", 'infographie', 'proposition',
         "Présentation d'un axe d'engagement de l'association.",
         "Premier contenu thématique ; date à ajuster selon la cadence de production.", null],

        ['27/08/2026', "Rappel adhésion / réadhésion", 'short', 'proposition',
         "Rappel public avec appel à l'action simple.",
         "Rythme de deux publications par semaine ; vérifier la cohérence avec les autres contenus.", null],

        ['28/08/2026', "MJA et l'engagement", 'infographie', 'proposition',
         "Présentation de la place de l'engagement dans MJA.",
         "Valider l'angle, le message et l'illustration avant production.", null],

        ['30/08/2026', "Story MJA Boat Party", 'short', 'proposition',
         "Annonce ou rappel de l'événement Boat Party.",
         "Confirmer l'événement avant diffusion.",
         "Événement « MJA Boat Party » (brouillon)",
         ["Date et heure", "Lieu d'embarquement", "Tarif", "Modalités d'inscription", "Nombre de places"]],

        ['01/09/2026', "Publication MJA Boat Party", 'photo', 'proposition',
         "Publication de présentation de la Boat Party.",
         "Dépend de la validation des informations pratiques.",
         "Événement « MJA Boat Party » (brouillon)",
         ["Mêmes informations que la story du 30/08"]],

        ['02/09/2026', "Flyer Journées Portes Ouvertes", 'infographie', 'proposition',
         "Flyer d'information pour les journées portes ouvertes.",
         "Valider les informations pratiques avant publication.",
         "Événement « Journée Portes Ouvertes des Associations » (publié)",
         ["Horaires du stand", "Emplacement sur la Savane", "Équipe présente", "Supports à imprimer"]],

        ['04/09/2026', "MJA et le sport", 'infographie', 'proposition',
         "Présentation des activités ou projets sportifs de MJA.",
         "Prévoir une image ou une illustration validée.", null],

        ['05/09/2026', "Journée Portes Ouvertes des Associations", 'infographie', 'proposition',
         "Invitation et informations pratiques pour le 5 septembre à la Savane.",
         "À publier le jour J ou en rappel.",
         "Événement publié sur le site",
         ["Horaires du stand", "Équipe présente"]],

        ['07/09/2026', "MJA en action — Caravane de l'unité", 'short', 'proposition',
         "Présentation de la caravane de l'unité, avec témoignage.",
         "Série d'un post tous les dix jours.",
         "Projet « La Caravane de l'unité »",
         ["Accord écrit de la personne interviewée", "Images de la caravane", "Date de tournage"]],

        ['11/09/2026', "MJA et la nutrition", 'infographie', 'proposition',
         "Présentation d'une action ou d'un projet lié à la nutrition.",
         "Date proposée dans le rythme de deux publications par semaine.",
         "Projet « Fwi Ti Dèj »"],

        ['12/09/2026', "MJA Fitness", 'short', 'proposition',
         "Contenu autour de MJA Fitness.",
         "La note indique seulement « deuxième semaine de septembre ».",
         "Événement « MJA Fitness » (brouillon)",
         ["Date exacte", "Heure", "Lieu", "Encadrement", "Matériel à prévoir"]],

        ['17/09/2026', "MJA en action — retour d'un nouveau membre", 'short', 'proposition',
         "Témoignage d'un nouvel adhérent sur son arrivée et son expérience.",
         "À valider avec la personne concernée.", null,
         ["Accord écrit de la personne", "Date de tournage"]],

        ['18/09/2026', "MJA et le lien intergénérationnel", 'infographie', 'proposition',
         "Présentation d'un projet ou d'une valeur liée au lien intergénérationnel.",
         "Vérifier la cohérence avec les événements et contenus déjà publiés.", null],

        ['24/09/2026', "Rappel adhésion / réadhésion", 'short', 'proposition',
         "Point d'étape public sur la campagne et appel à l'action.",
         "Prévoir un message distinct pour les nouveaux adhérents et pour les membres à renouveler.", null],

        ['27/09/2026', "MJA en action — en direct d'une action", 'short', 'proposition',
         "Retour d'action sur le terrain.",
         "À caler selon l'action retenue.", null,
         ["Action à filmer", "Date", "Présences", "Autorisations de droit à l'image"]],

        ['02/10/2026', "MJA et le développement personnel", 'infographie', 'proposition',
         "Présentation d'un projet ou d'un bénéfice concret de l'engagement.",
         "Date proposée ; à ajuster selon la cadence de production.", null],

        ['03/10/2026', "Journée Portes Ouvertes MJA au local", 'infographie', 'proposition',
         "Annonce de la journée portes ouvertes organisée par MJA.",
         "Date indicative : première semaine d'octobre.",
         "Événement « Journée Portes Ouvertes MJA » (brouillon)",
         ["Date exacte", "Horaires", "Format de la journée", "Équipe présente", "Matériel"]],

        ['07/10/2026', "MJA en action — vie communautaire", 'short', 'proposition',
         "Contenu « Passer du bon temps avec MJA ».",
         "À caler selon la sortie retenue.",
         "Action « Vie communautaire »",
         ["Sortie à filmer", "Date", "Accord des personnes filmées"]],

        ['15/10/2026', "Rappel adhésion / réadhésion", 'short', 'proposition',
         "Relance publique avant la dernière phase de campagne.",
         "Prévoir un rappel des objectifs et des modalités de contact.",
         "Relances automatiques du back-office"],

        ['26/10/2026', "Bilan et dernière relance", 'infographie', 'proposition',
         "Rappel final avant la clôture du 31 octobre.",
         "Repose sur les chiffres de la campagne.", null,
         ["Nombre d'adhésions à date", "Nombre de réadhésions", "Objectif à afficher ou non"]],

        ['31/10/2026', "Clôture adhésion / réadhésion", 'photo', 'proposition',
         "Message de clôture et remerciement des membres et nouveaux adhérents.",
         "Date structurante : publier après vérification de la clôture.", null,
         ["Chiffres définitifs", "Photo de groupe de la saison"]],
    ];

    // Regroupement par mois, dans l'ordre chronologique.
    $mois = [];
    foreach ($contenus as $i => $c) {
        [$j, $m, $a] = explode('/', $c[0]);
        $cle = $a . '-' . $m;
        $mois[$cle][] = $i;
    }
    // Tri par date à l'intérieur de chaque mois : l'ordre du tableau ne fait
    // plus foi dès qu'une date est corrigée.
    foreach ($mois as $cle => $liste) {
        usort($liste, function ($a, $b) use ($contenus) {
            [$ja, $ma, $aa] = explode('/', $contenus[$a][0]);
            [$jb, $mb, $ab] = explode('/', $contenus[$b][0]);
            return [$aa, $ma, $ja] <=> [$ab, $mb, $jb];
        });
        $mois[$cle] = $liste;
    }
    ksort($mois);

    $nomsMois = ['08' => 'Août', '09' => 'Septembre', '10' => 'Octobre', '11' => 'Novembre'];

    $compte = ['publie' => 0, 'validation' => 0, 'production' => 0, 'proposition' => 0];
    foreach ($contenus as $c) { $compte[$c[3]]++; }

    $typesLabel  = ['short' => 'Vidéo courte', 'infographie' => 'Infographie', 'photo' => 'Photo'];
    $statutLabel = ['publie' => 'Publié', 'validation' => 'En validation',
                    'production' => 'En production', 'proposition' => 'En proposition'];
@endphp
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex, nofollow">
<title>Plan de communication — Campagne d'adhésion {{ $planSaison ?? '2026-2027' }}</title>
<link rel="icon" type="image/jpeg" href="{{ asset('images/logo.jpg') }}">
<link rel="stylesheet" href="{{ asset('css/gill-sans.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/fontawesome/css/all.min.css') }}">
<style>
:root{
  --navy:#1A3D8A; --dark:#2048A4; --blue:#3DAEF5; --bluedark:#1E93D6;
  --yellow:#F5A623; --red:#D0021B; --ink:#0B1E45; --gris:#6C7A91;
  --bord:#E4EAF4; --fond:#F5F8FD;
}
*{box-sizing:border-box}
body{margin:0;background:var(--fond);color:#333;font-family:'Gill Sans','Open Sans',sans-serif;font-size:15px;line-height:1.55}
.wrap{max-width:1180px;margin:0 auto;padding:0 22px}

/* ── En-tête ─────────────────────────────────────────────────── */
header{background:linear-gradient(135deg,#1A3D8A 0%,#2048A4 48%,#3262CC 100%);color:#fff;padding:34px 0 30px;position:relative;overflow:hidden}
header .bar{position:absolute;left:0;right:0;top:0;height:6px;display:flex}
header .bar i{flex:1}
.b1{background:var(--blue)}.b2{background:var(--yellow)}.b3{background:var(--red)}
.idt{display:flex;align-items:center;gap:16px;margin-bottom:20px}
.idt img{height:62px;width:62px;object-fit:contain;background:#fff;border-radius:14px;padding:5px}
.idt .n{font-weight:800;font-size:19px;letter-spacing:.8px}
.idt .s{font-size:13px;color:#BDD4F5;font-style:italic}
h1{margin:0 0 6px;font-size:31px;font-weight:800;letter-spacing:-.3px}
.periode{color:#C9DBFA;font-size:15px}
.compteurs{display:flex;flex-wrap:wrap;gap:10px;margin-top:20px}
.compteur{background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.22);border-radius:12px;padding:9px 15px;font-size:13px;font-weight:600}
.compteur b{font-size:19px;display:block;line-height:1.2}

/* ── Jalons ──────────────────────────────────────────────────── */
.jalons{display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:14px;margin:-26px 0 30px}
.jalon{background:#fff;border-radius:16px;padding:17px 19px;box-shadow:0 6px 22px rgba(20,40,90,.09);border-top:4px solid var(--blue)}
.jalon.ouverture{border-top-color:var(--blue)}
.jalon.evenement{border-top-color:var(--yellow)}
.jalon.cloture{border-top-color:var(--red)}
.jalon .d{font-weight:800;color:var(--navy);font-size:14px;letter-spacing:.4px}
.jalon .t{font-weight:800;color:var(--ink);margin:3px 0 2px;font-size:16px}
.jalon .x{color:var(--gris);font-size:13.5px}

/* ── Barre d'outils ──────────────────────────────────────────── */
.outils{background:#fff;border:1px solid var(--bord);border-radius:16px;padding:14px 18px;margin-bottom:26px;
        display:flex;flex-wrap:wrap;gap:10px;align-items:center;position:sticky;top:0;z-index:20}
.outils .sep{flex:1}
.chip{border:1.5px solid var(--bord);background:#fff;border-radius:999px;padding:6px 14px;font:inherit;font-size:13px;
      font-weight:600;color:var(--gris);cursor:pointer;transition:.15s}
.chip:hover{border-color:var(--blue);color:var(--dark)}
.chip.on{background:var(--navy);border-color:var(--navy);color:#fff}
.btn{border:0;border-radius:11px;padding:9px 16px;font:inherit;font-size:13.5px;font-weight:700;cursor:pointer;transition:.15s;
     display:inline-flex;align-items:center;gap:7px}
.btn.p{background:var(--bluedark);color:#fff}.btn.p:hover{background:var(--dark)}
.btn.g{background:#EEF3FB;color:var(--dark)}.btn.g:hover{background:#E2EBF8}
.etat{font-size:12.5px;color:var(--gris)}

/* ── Frise ───────────────────────────────────────────────────── */
.mois{margin-bottom:34px}
.mois > h2{display:flex;align-items:center;gap:12px;margin:0 0 16px;font-size:21px;font-weight:800;color:var(--navy)}
.mois > h2 span{flex:1;height:2px;background:var(--bord)}
.mois > h2 em{font-style:normal;font-size:13px;font-weight:600;color:var(--gris)}

.carte{background:#fff;border:1px solid var(--bord);border-radius:16px;padding:0;margin-bottom:12px;display:flex;overflow:hidden;
       transition:box-shadow .15s,border-color .15s}
.carte:hover{box-shadow:0 6px 20px rgba(20,40,90,.08);border-color:#D2DEF0}
.carte .date{width:96px;flex:none;background:var(--navy);color:#fff;display:flex;flex-direction:column;
             align-items:center;justify-content:center;padding:16px 6px;text-align:center}
.carte .date b{font-size:26px;font-weight:800;line-height:1}
.carte .date i{font-style:normal;font-size:12px;color:#BDD4F5;text-transform:uppercase;letter-spacing:1px;margin-top:3px}
.carte .corps{flex:1;padding:15px 18px;min-width:0}
.carte .haut{display:flex;flex-wrap:wrap;gap:8px;align-items:center;margin-bottom:7px}
.tag{font-size:11px;font-weight:800;letter-spacing:.7px;text-transform:uppercase;border-radius:999px;padding:3px 10px}
.t-short{background:#E7F3FF;color:var(--dark)}
.t-infographie{background:#FFF3DC;color:#92400E}
.t-photo{background:#FDE7EA;color:#9B1C1E}
.s-publie{background:#E7F7EC;color:#166534}
.s-validation{background:#E7F0FF;color:#1E3A8A}
.s-production{background:#FFF3DC;color:#92400E}
.s-proposition{background:#EEF2F8;color:var(--gris)}
.carte h3{margin:0 0 4px;font-size:17px;font-weight:800;color:var(--ink)}
.carte p{margin:0 0 6px;color:#4A5A73;font-size:14.5px}
.carte .note{color:var(--gris);font-size:13px;display:flex;gap:7px;align-items:flex-start}
.carte .note i{color:var(--yellow);margin-top:3px}
.carte .lie{margin-top:8px;display:inline-flex;align-items:center;gap:7px;background:#F2F7FF;border:1px solid #DCE8FA;
            border-radius:9px;padding:5px 11px;font-size:12.5px;color:var(--dark);font-weight:600}
.carte .manque{margin-top:9px;background:#FFF6E5;border:1px solid #FBE3B4;border-radius:10px;padding:8px 11px;
                font-size:12.5px;color:#78591C;display:flex;flex-wrap:wrap;gap:6px;align-items:center}
.carte .manque b{font-weight:800;color:#92400E;margin-right:2px}
.carte .manque span{background:#fff;border:1px solid #F3DDAF;border-radius:7px;padding:2px 8px}
.carte .sup{border:0;background:transparent;color:#C2CEDE;cursor:pointer;font-size:14px;padding:6px;align-self:flex-start}
.carte .sup:hover{color:var(--red)}

[contenteditable]{outline:0;border-radius:6px;padding:1px 4px;margin:0 -4px;transition:background .12s}
[contenteditable]:hover{background:#F5F8FD}
[contenteditable]:focus{background:#FFF8E8;box-shadow:0 0 0 2px var(--yellow)}

/* ── Vigilance ───────────────────────────────────────────────── */
.vigilance{background:#FFFBEB;border:1px solid #FDE9B8;border-radius:16px;padding:20px 22px;margin:8px 0 40px}
.vigilance h2{margin:0 0 10px;font-size:17px;font-weight:800;color:#92400E;display:flex;align-items:center;gap:9px}
.vigilance ul{margin:0;padding-left:20px;color:#78591C;font-size:14px}
.vigilance li{margin-bottom:5px}

footer{border-top:1px solid var(--bord);padding:22px 0 40px;color:var(--gris);font-size:13px;
       display:flex;flex-wrap:wrap;gap:10px;justify-content:space-between}

@media print{
  body{background:#fff}
  .outils,.carte .sup{display:none!important}
  .carte{break-inside:avoid;box-shadow:none}
  header{background:var(--navy)!important;-webkit-print-color-adjust:exact;print-color-adjust:exact}
  .tag,.carte .date,.jalon{-webkit-print-color-adjust:exact;print-color-adjust:exact}
}
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
    <h1>Plan de communication</h1>
    <div class="periode">Campagne d'adhésion 2026-2027 — du 17 août au 31 octobre 2026</div>
    <div class="compteurs">
      <div class="compteur"><b>{{ count($contenus) }}</b> contenus planifiés</div>
      <div class="compteur"><b>{{ $compte['publie'] }}</b> publiés</div>
      <div class="compteur"><b>{{ $compte['validation'] }}</b> en validation</div>
      <div class="compteur"><b>{{ $compte['production'] }}</b> en production</div>
      <div class="compteur"><b>{{ $compte['proposition'] }}</b> en proposition</div>
      <div class="compteur"><b>11</b> semaines de campagne</div>
    </div>
  </div>
</header>

<div class="wrap">

  <div class="jalons">
    @foreach($jalons as [$d, $t, $x, $classe])
    <div class="jalon {{ $classe }}">
      <div class="d">{{ $d }}</div>
      <div class="t" contenteditable="true">{{ $t }}</div>
      <div class="x" contenteditable="true">{{ $x }}</div>
    </div>
    @endforeach
  </div>

  <div class="outils">
    <button class="chip on" data-filtre="tous">Tout</button>
    <button class="chip" data-filtre="short"><i class="fas fa-video"></i> Vidéos</button>
    <button class="chip" data-filtre="infographie"><i class="fas fa-image"></i> Infographies</button>
    <button class="chip" data-filtre="photo"><i class="fas fa-camera"></i> Photos</button>
    <button class="chip" data-filtre="validation">En validation</button>
    <button class="chip" data-filtre="proposition">À caler</button>
    <span class="sep"></span>
    <span class="etat" id="etat">Modifiable — les changements restent sur cet ordinateur</span>
    <button class="btn g" id="btn-reset"><i class="fas fa-rotate-left"></i> Réinitialiser</button>
    <button class="btn g" id="btn-csv"><i class="fas fa-file-csv"></i> Exporter</button>
    <button class="btn g" id="btn-print"><i class="fas fa-print"></i> Imprimer</button>
    <button class="btn p" id="btn-pdf" data-logo="{{ asset('images/logo.jpg') }}"><i class="fas fa-file-pdf"></i> Document PDF</button>
  </div>

  @foreach($mois as $cle => $indices)
  @php [$annee, $numMois] = explode('-', $cle); @endphp
  <section class="mois" data-mois="{{ $cle }}">
    <h2>
      {{ $nomsMois[$numMois] ?? $numMois }} {{ $annee }}
      <span></span>
      <em>{{ count($indices) }} contenu{{ count($indices) > 1 ? 's' : '' }}</em>
    </h2>

    @foreach($indices as $i)
    @php
        [$date, $titre, $type, $statut, $desc, $note, $lie] = array_slice($contenus[$i], 0, 7);
        $manque = $contenus[$i][7] ?? null;
        [$jj, $mm] = explode('/', $date);
    @endphp
    <article class="carte" data-type="{{ $type }}" data-statut="{{ $statut }}" data-id="c{{ $i }}">
      <div class="date">
        <b>{{ $jj }}</b>
        <i>{{ mb_substr($nomsMois[$mm] ?? $mm, 0, 4) }}</i>
      </div>
      <div class="corps">
        <div class="haut">
          <span class="tag t-{{ $type }}">{{ $typesLabel[$type] }}</span>
          <span class="tag s-{{ $statut }}">{{ $statutLabel[$statut] }}</span>
        </div>
        <h3 contenteditable="true" data-champ="titre">{{ $titre }}</h3>
        <p contenteditable="true" data-champ="desc">{{ $desc }}</p>
        <div class="note">
          <i class="fas fa-circle-exclamation"></i>
          <span contenteditable="true" data-champ="note">{{ $note }}</span>
        </div>
        @if($lie)
        <div class="lie"><i class="fas fa-link"></i> {{ $lie }}</div>
        @endif
        @if($manque)
        {{-- Ce qu'il reste à obtenir : la question la plus utile quand on
             ouvre le document pour préparer une réunion. --}}
        <div class="manque">
            <b><i class="fas fa-circle-question"></i> Il manque</b>
            @foreach($manque as $m)<span>{{ $m }}</span>@endforeach
        </div>
        @endif
      </div>
      <button class="sup" title="Retirer cette ligne"><i class="fas fa-xmark"></i></button>
    </article>
    @endforeach
  </section>
  @endforeach

  <div class="vigilance">
    <h2><i class="fas fa-triangle-exclamation"></i> À valider avant diffusion</h2>
    <ul>
      <li><b>Droit à l'image</b> — les retours d'activité (Foyal Color Red, LaserWest, actions filmées) montrent des personnes identifiables : vérifier que chacune a signé l'autorisation, y compris les personnes extérieures à l'association.</li>
      <li><b>Accord des personnes citées</b> — les contenus « MJA en action » reposent sur des témoignages nominatifs. Obtenir un accord écrit avant publication, et ne pas diffuser le nom sans validation.</li>
      <li><b>Dates de retour d'activité</b> — la sortie LaserWest / bowling est datée dans le plan à sa date de <em>publication</em>, pas à celle de la sortie. Renseigner la date réelle avant de l'afficher sur le site. La Foyal Color Red, elle, est calée au 22 août au Parc La Savane.</li>
      <li><b>Dates indicatives</b> — Boat Party, MJA Fitness et la journée portes ouvertes au local n'ont qu'une semaine approximative. Les événements correspondants restent en brouillon sur le site tant que la date n'est pas confirmée.</li>
      <li><b>Cotisation</b> — les visuels grand public n'affichent pas le montant : il se découvre sur le formulaire. Ne l'ajouter que sur les supports internes.</li>
    </ul>
  </div>

  <footer>
    <span>Madin' Jeunes Ambition — Relève tous les défis !</span>
    <span>Document de travail interne — {{ now()->locale('fr')->isoFormat('D MMMM Y') }}</span>
  </footer>
</div>

<script>
(function () {
  var CLE = 'mja-plan-comm-v1';

  /* ── Filtres ─────────────────────────────────────────────────── */
  var puces = document.querySelectorAll('.chip');
  puces.forEach(function (p) {
    p.addEventListener('click', function () {
      puces.forEach(function (q) { q.classList.remove('on'); });
      p.classList.add('on');
      var f = p.dataset.filtre;

      document.querySelectorAll('.carte').forEach(function (c) {
        var visible = f === 'tous' || c.dataset.type === f || c.dataset.statut === f;
        c.style.display = visible ? '' : 'none';
      });

      // Un mois dont plus aucune carte n'est visible n'a pas à rester affiché.
      document.querySelectorAll('.mois').forEach(function (m) {
        var reste = m.querySelectorAll('.carte:not([style*="none"])').length;
        m.style.display = reste ? '' : 'none';
      });
    });
  });

  /* ── Sauvegarde locale des modifications ─────────────────────── */
  var etat = document.getElementById('etat');
  var minuteur = null;

  function enregistrer() {
    var data = {};
    document.querySelectorAll('.carte').forEach(function (c) {
      var id = c.dataset.id, o = {};
      c.querySelectorAll('[data-champ]').forEach(function (e) { o[e.dataset.champ] = e.innerHTML; });
      o.masquee = c.dataset.masquee === '1';
      data[id] = o;
    });
    data._jalons = Array.prototype.map.call(
      document.querySelectorAll('.jalon [contenteditable]'), function (e) { return e.innerHTML; });

    try {
      localStorage.setItem(CLE, JSON.stringify(data));
      etat.textContent = 'Modifications enregistrées sur cet ordinateur';
    } catch (e) {
      etat.textContent = "Impossible d'enregistrer (stockage du navigateur indisponible)";
    }
  }

  function enregistrerBientot() {
    clearTimeout(minuteur);
    etat.textContent = 'Enregistrement…';
    minuteur = setTimeout(enregistrer, 600);
  }

  function restaurer() {
    var brut;
    try { brut = localStorage.getItem(CLE); } catch (e) { return; }
    if (!brut) return;

    var data;
    try { data = JSON.parse(brut); } catch (e) { return; }

    document.querySelectorAll('.carte').forEach(function (c) {
      var o = data[c.dataset.id];
      if (!o) return;
      c.querySelectorAll('[data-champ]').forEach(function (e) {
        if (o[e.dataset.champ] !== undefined) e.innerHTML = o[e.dataset.champ];
      });
      if (o.masquee) { c.dataset.masquee = '1'; c.style.display = 'none'; }
    });

    if (data._jalons) {
      document.querySelectorAll('.jalon [contenteditable]').forEach(function (e, i) {
        if (data._jalons[i] !== undefined) e.innerHTML = data._jalons[i];
      });
    }
    etat.textContent = 'Version modifiée restaurée';
  }

  document.addEventListener('input', function (e) {
    if (e.target.isContentEditable) enregistrerBientot();
  });

  document.querySelectorAll('.carte .sup').forEach(function (b) {
    b.addEventListener('click', function () {
      var c = b.closest('.carte');
      c.dataset.masquee = '1';
      c.style.display = 'none';
      enregistrer();
    });
  });

  document.getElementById('btn-reset').addEventListener('click', function () {
    if (!confirm('Revenir au plan d\'origine ? Vos modifications seront perdues.')) return;
    try { localStorage.removeItem(CLE); } catch (e) {}
    location.reload();
  });

  /* ── Export CSV (ouvrable dans Excel ou Google Sheets) ───────── */
  document.getElementById('btn-csv').addEventListener('click', function () {
    var lignes = [['Date', 'Titre', 'Type', 'Statut', 'Description', 'Point de vigilance', 'Rattachement']];

    document.querySelectorAll('.carte').forEach(function (c) {
      if (c.dataset.masquee === '1') return;
      var mois = c.closest('.mois').dataset.mois.split('-');
      var lu = function (n) {
        var e = c.querySelector('[data-champ="' + n + '"]');
        return e ? e.textContent.trim() : '';
      };
      var lie = c.querySelector('.lie');
      lignes.push([
        c.querySelector('.date b').textContent + '/' + mois[1] + '/' + mois[0],
        lu('titre'),
        c.querySelector('.t-' + c.dataset.type).textContent,
        c.querySelector('.s-' + c.dataset.statut).textContent,
        lu('desc'), lu('note'),
        lie ? lie.textContent.trim() : ''
      ]);
    });

    var csv = '﻿' + lignes.map(function (l) {
      return l.map(function (v) { return '"' + String(v).replace(/"/g, '""') + '"'; }).join(';');
    }).join('\r\n');

    var a = document.createElement('a');
    a.href = 'data:text/csv;charset=utf-8,' + encodeURIComponent(csv);
    a.download = 'plan-communication-mja-2026-2027.csv';
    document.body.appendChild(a); a.click(); a.remove();
  });

  document.getElementById('btn-print').addEventListener('click', function () { window.print(); });

  restaurer();
})();
</script>
<script>
/* =====================================================================
   Génération d'un PDF vectoriel — document officiel, pas une capture

   Le PDF est écrit à la main : pages A4, polices standard (Helvetica, dont
   les métriques sont celles d'Arial, ce qui permet de mesurer le texte avec
   un canvas), texte sélectionnable et fichier léger. Aucune librairie.

   Le contenu est lu dans la page au moment de l'export : les modifications
   faites à l'écran se retrouvent donc dans le document.
   ===================================================================== */
(function () {
  var A4 = { w: 595.28, h: 841.89 };
  var MARGE = 46;
  var COUL = {
    navy:  [0.102, 0.239, 0.541],
    dark:  [0.125, 0.282, 0.643],
    blue:  [0.239, 0.682, 0.961],
    jaune: [0.961, 0.651, 0.137],
    rouge: [0.816, 0.008, 0.106],
    encre: [0.043, 0.118, 0.271],
    gris:  [0.424, 0.478, 0.569],
    texte: [0.290, 0.353, 0.451],
    trait: [0.894, 0.918, 0.957],
    creme: [1.000, 0.973, 0.898],
    bleuPale: [0.949, 0.969, 1.000]
  };

  /* ── Mesure : Arial est métriquement compatible avec Helvetica ─────── */
  var mc = document.createElement('canvas').getContext('2d');
  function larg(txt, taille, gras, ital) {
    mc.font = (ital ? 'italic ' : '') + (gras ? 'bold ' : '') + taille + 'px Helvetica, Arial, sans-serif';
    return mc.measureText(txt).width;
  }

  /* ── Encodage WinAnsi : les accents français sortent corrects ──────── */
  var HORS_LATIN1 = { '€': 128, '‚': 130, 'ƒ': 131, '„': 132, '…': 133,
    '†': 134, '‡': 135, 'ˆ': 136, '‰': 137, 'Š': 138, '‹': 139,
    'Œ': 140, 'Ž': 142, '‘': 145, '’': 146, '“': 147, '”': 148,
    '•': 149, '–': 150, '—': 151, '˜': 152, '™': 153, 'š': 154,
    '›': 155, 'œ': 156, 'ž': 158, 'Ÿ': 159 };

  function versWinAnsi(str) {
    var out = '';
    for (var i = 0; i < str.length; i++) {
      var c = str[i], code = str.charCodeAt(i);
      if (HORS_LATIN1[c] !== undefined) { out += String.fromCharCode(HORS_LATIN1[c]); continue; }
      out += code <= 255 ? c : '?';
    }
    return out;
  }

  function echapper(str) {
    return versWinAnsi(str).replace(/\\/g, '\\\\').replace(/\(/g, '\\(').replace(/\)/g, '\\)');
  }

  /* ── Document ──────────────────────────────────────────────────────── */
  function Doc() {
    this.pages = [];
    this.flux = null;
    this.images = [];
    this.y = 0;
  }

  Doc.prototype.nouvellePage = function () {
    this.flux = [];
    this.pages.push(this.flux);
    this.y = MARGE;
    return this.pages.length;
  };

  /** Conversion repère écran (origine en haut) → repère PDF (origine en bas). */
  function pdfY(y) { return A4.h - y; }

  Doc.prototype.couleur = function (c, trait) {
    this.flux.push(c[0].toFixed(3) + ' ' + c[1].toFixed(3) + ' ' + c[2].toFixed(3) + (trait ? ' RG' : ' rg'));
  };

  Doc.prototype.rect = function (x, y, w, h, c) {
    this.couleur(c);
    this.flux.push(x.toFixed(2) + ' ' + pdfY(y + h).toFixed(2) + ' ' + w.toFixed(2) + ' ' + h.toFixed(2) + ' re f');
  };

  /** Rectangle à coins arrondis (quatre courbes de Bézier). */
  Doc.prototype.rectArrondi = function (x, y, w, h, r, c) {
    r = Math.min(r, w / 2, h / 2);
    var k = r * 0.5523, b = pdfY(y + h), t = pdfY(y);
    this.couleur(c);
    this.flux.push(
      (x + r).toFixed(2) + ' ' + b.toFixed(2) + ' m',
      (x + w - r).toFixed(2) + ' ' + b.toFixed(2) + ' l',
      (x + w - r + k).toFixed(2) + ' ' + b.toFixed(2) + ' ' + (x + w).toFixed(2) + ' ' + (b + r - k).toFixed(2) + ' ' + (x + w).toFixed(2) + ' ' + (b + r).toFixed(2) + ' c',
      (x + w).toFixed(2) + ' ' + (t - r).toFixed(2) + ' l',
      (x + w).toFixed(2) + ' ' + (t - r + k).toFixed(2) + ' ' + (x + w - r + k).toFixed(2) + ' ' + t.toFixed(2) + ' ' + (x + w - r).toFixed(2) + ' ' + t.toFixed(2) + ' c',
      (x + r).toFixed(2) + ' ' + t.toFixed(2) + ' l',
      (x + r - k).toFixed(2) + ' ' + t.toFixed(2) + ' ' + x.toFixed(2) + ' ' + (t - r + k).toFixed(2) + ' ' + x.toFixed(2) + ' ' + (t - r).toFixed(2) + ' c',
      x.toFixed(2) + ' ' + (b + r).toFixed(2) + ' l',
      x.toFixed(2) + ' ' + (b + r - k).toFixed(2) + ' ' + (x + r - k).toFixed(2) + ' ' + b.toFixed(2) + ' ' + (x + r).toFixed(2) + ' ' + b.toFixed(2) + ' c',
      'f'
    );
  };

  Doc.prototype.texte = function (x, y, str, o) {
    o = o || {};
    var taille = o.size || 10;
    var police = o.gras ? '/F2' : (o.ital ? '/F3' : '/F1');
    var l = larg(str, taille, o.gras, o.ital);
    if (o.align === 'right') x -= l;
    else if (o.align === 'center') x -= l / 2;
    this.couleur(o.c || COUL.encre);
    this.flux.push('BT ' + police + ' ' + taille + ' Tf '
      + x.toFixed(2) + ' ' + pdfY(y).toFixed(2) + ' Td (' + echapper(str) + ') Tj ET');
    return l;
  };

  /** Découpe un texte pour qu'il tienne dans `maxW`. */
  Doc.prototype.decouper = function (str, taille, maxW, gras) {
    var mots = String(str).split(/\s+/), lignes = [], cur = '';
    for (var i = 0; i < mots.length; i++) {
      var essai = cur ? cur + ' ' + mots[i] : mots[i];
      if (larg(essai, taille, gras) > maxW && cur) { lignes.push(cur); cur = mots[i]; }
      else { cur = essai; }
    }
    if (cur) lignes.push(cur);
    return lignes;
  };

  Doc.prototype.paragraphe = function (x, y, str, taille, maxW, o) {
    o = o || {};
    var lignes = this.decouper(str, taille, maxW, o.gras), lh = o.lh || taille * 1.35;
    for (var i = 0; i < lignes.length; i++) this.texte(x, y + i * lh, lignes[i], o);
    return lignes.length * lh;
  };

  Doc.prototype.image = function (nom, x, y, w, h) {
    this.flux.push('q ' + w.toFixed(2) + ' 0 0 ' + h.toFixed(2) + ' '
      + x.toFixed(2) + ' ' + pdfY(y + h).toFixed(2) + ' cm /' + nom + ' Do Q');
  };

  /** Assemblage final du fichier PDF. */
  Doc.prototype.produire = function () {
    var objs = [], self = this;
    var nPages = this.pages.length;

    var idPolices = { F1: 0, F2: 0, F3: 0 };
    var idContenus = [], idImages = {};

    /* 1 catalogue, 2 arbre de pages, puis pages, contenus, polices, images. */
    var num = 3;
    var idPage = [];
    for (var i = 0; i < nPages; i++) idPage.push(num++);
    for (i = 0; i < nPages; i++) idContenus.push(num++);
    idPolices.F1 = num++; idPolices.F2 = num++; idPolices.F3 = num++;
    this.images.forEach(function (im) { idImages[im.nom] = num++; });

    objs[0] = '<< /Type /Catalog /Pages 2 0 R >>';
    objs[1] = '<< /Type /Pages /Kids [' + idPage.map(function (n) { return n + ' 0 R'; }).join(' ')
            + '] /Count ' + nPages + ' >>';

    var res = '<< /Font << /F1 ' + idPolices.F1 + ' 0 R /F2 ' + idPolices.F2 + ' 0 R /F3 ' + idPolices.F3 + ' 0 R >>';
    if (this.images.length) {
      res += ' /XObject << ' + this.images.map(function (im) { return '/' + im.nom + ' ' + idImages[im.nom] + ' 0 R'; }).join(' ') + ' >>';
    }
    res += ' >>';

    for (i = 0; i < nPages; i++) {
      objs[idPage[i] - 1] = '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 ' + A4.w + ' ' + A4.h + ']'
        + ' /Resources ' + res + ' /Contents ' + idContenus[i] + ' 0 R >>';
      var flux = this.pages[i].join('\n');
      objs[idContenus[i] - 1] = '<< /Length ' + flux.length + ' >>\nstream\n' + flux + '\nendstream';
    }

    objs[idPolices.F1 - 1] = '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica /Encoding /WinAnsiEncoding >>';
    objs[idPolices.F2 - 1] = '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold /Encoding /WinAnsiEncoding >>';
    objs[idPolices.F3 - 1] = '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Oblique /Encoding /WinAnsiEncoding >>';

    this.images.forEach(function (im) {
      objs[idImages[im.nom] - 1] = '<< /Type /XObject /Subtype /Image /Width ' + im.w + ' /Height ' + im.h
        + ' /ColorSpace /DeviceRGB /BitsPerComponent 8 /Filter /DCTDecode /Length ' + im.bin.length
        + ' >>\nstream\n' + im.bin + '\nendstream';
    });

    var out = '%PDF-1.4\n%\xE2\xE3\xCF\xD3\n', offsets = [];
    for (i = 0; i < objs.length; i++) {
      offsets.push(out.length);
      out += (i + 1) + ' 0 obj\n' + objs[i] + '\nendobj\n';
    }
    var xref = out.length;
    out += 'xref\n0 ' + (objs.length + 1) + '\n0000000000 65535 f \n';
    for (i = 0; i < offsets.length; i++) out += ('0000000000' + offsets[i]).slice(-10) + ' 00000 n \n';
    out += 'trailer\n<< /Size ' + (objs.length + 1) + ' /Root 1 0 R /Info << /Title ('
        + echapper("Plan de communication - Madin' Jeunes Ambition") + ') /Author ('
        + echapper("Madin' Jeunes Ambition") + ') >> >>\nstartxref\n' + xref + '\n%%EOF';

    var bytes = new Uint8Array(out.length);
    for (i = 0; i < out.length; i++) bytes[i] = out.charCodeAt(i) & 0xFF;
    return new Blob([bytes], { type: 'application/pdf' });
  };

  /* ── Chargement du logo pour l'incruster ───────────────────────────── */
  function chargerLogo(url) {
    return fetch(url).then(function (r) { return r.arrayBuffer(); }).then(function (buf) {
      var oct = new Uint8Array(buf), bin = '';
      for (var i = 0; i < oct.length; i++) bin += String.fromCharCode(oct[i]);

      /* Dimensions lues dans les marqueurs SOF du JPEG. */
      var w = 0, h = 0, p = 2;
      while (p < oct.length) {
        if (oct[p] !== 0xFF) { p++; continue; }
        var m = oct[p + 1];
        if (m >= 0xC0 && m <= 0xCF && m !== 0xC4 && m !== 0xC8 && m !== 0xCC) {
          h = (oct[p + 5] << 8) | oct[p + 6];
          w = (oct[p + 7] << 8) | oct[p + 8];
          break;
        }
        p += 2 + ((oct[p + 2] << 8) | oct[p + 3]);
      }
      return w && h ? { nom: 'Logo', bin: bin, w: w, h: h } : null;
    }).catch(function () { return null; });
  }

  /* ── Lecture du plan dans la page ──────────────────────────────────── */
  function lirePlan() {
    var mois = [];
    document.querySelectorAll('.mois').forEach(function (m) {
      if (m.style.display === 'none') return;
      var titre = m.querySelector('h2').childNodes[0].textContent.trim();
      var items = [];
      m.querySelectorAll('.carte').forEach(function (c) {
        if (c.dataset.masquee === '1') return;
        var lu = function (n) {
          var e = c.querySelector('[data-champ="' + n + '"]');
          return e ? e.textContent.trim() : '';
        };
        var lien = c.querySelector('.lie');
        items.push({
          jour: c.querySelector('.date b').textContent.trim(),
          moisCourt: c.querySelector('.date i').textContent.trim(),
          type: c.querySelector('.t-' + c.dataset.type).textContent.trim(),
          statut: c.querySelector('.s-' + c.dataset.statut).textContent.trim(),
          cleType: c.dataset.type,
          cleStatut: c.dataset.statut,
          titre: lu('titre'), desc: lu('desc'), note: lu('note'),
          lien: lien ? lien.textContent.trim() : ''
        });
      });
      if (items.length) mois.push({ titre: titre, items: items });
    });

    var jalons = [];
    document.querySelectorAll('.jalon').forEach(function (j) {
      jalons.push({
        date: j.querySelector('.d').textContent.trim(),
        titre: j.querySelector('.t').textContent.trim(),
        desc: j.querySelector('.x').textContent.trim()
      });
    });

    var vigilance = [];
    document.querySelectorAll('.vigilance li').forEach(function (li) {
      vigilance.push(li.textContent.replace(/\s+/g, ' ').trim());
    });

    return { mois: mois, jalons: jalons, vigilance: vigilance };
  }

  /* ── Composition du document ───────────────────────────────────────── */
  function composer(plan, logo) {
    var d = new Doc();
    if (logo) d.images.push(logo);
    var L = A4.w - 2 * MARGE;
    var total = 0;
    plan.mois.forEach(function (m) { total += m.items.length; });

    /* ---- Page de garde ---- */
    d.nouvellePage();
    d.rect(0, 0, A4.w, 250, COUL.navy);
    d.rect(0, 0, A4.w / 3, 7, COUL.blue);
    d.rect(A4.w / 3, 0, A4.w / 3, 7, COUL.jaune);
    d.rect(2 * A4.w / 3, 0, A4.w / 3, 7, COUL.rouge);

    if (logo) {
      d.rectArrondi(MARGE, 42, 64, 64, 10, [1, 1, 1]);
      d.image('Logo', MARGE + 5, 47, 54, 54);
    }
    d.texte(MARGE + (logo ? 80 : 0), 68, "MADIN' JEUNES AMBITION", { size: 15, gras: true, c: [1, 1, 1] });
    d.texte(MARGE + (logo ? 80 : 0), 86, 'Relève tous les défis !', { size: 11, ital: true, c: [0.741, 0.831, 0.961] });

    d.texte(MARGE, 158, 'PLAN DE COMMUNICATION', { size: 26, gras: true, c: [1, 1, 1] });
    d.texte(MARGE, 186, "Campagne d'adhésion 2026-2027", { size: 14, c: [0.788, 0.859, 0.980] });
    d.texte(MARGE, 208, 'Du 17 août au 31 octobre 2026', { size: 11, c: [0.741, 0.831, 0.961] });

    /* Compteurs */
    var y = 282, cw = (L - 3 * 10) / 4;
    var stats = [[String(total), 'contenus'], [String(plan.jalons.length), 'jalons'],
                 ['11', 'semaines'], [String(plan.mois.length), 'mois']];
    stats.forEach(function (s, i) {
      var x = MARGE + i * (cw + 10);
      d.rectArrondi(x, y, cw, 52, 8, COUL.bleuPale);
      d.texte(x + cw / 2, y + 26, s[0], { size: 19, gras: true, c: COUL.navy, align: 'center' });
      d.texte(x + cw / 2, y + 42, s[1], { size: 9, c: COUL.gris, align: 'center' });
    });

    /* Jalons */
    y = 372;
    d.texte(MARGE, y, 'JALONS DE LA CAMPAGNE', { size: 10, gras: true, c: COUL.dark });
    y += 8;
    d.rect(MARGE, y, 34, 2.4, COUL.jaune);
    y += 22;

    plan.jalons.forEach(function (j, i) {
      var h = 54;
      d.rectArrondi(MARGE, y, L, h, 8, [1, 1, 1]);
      d.rect(MARGE, y, 4, h, [COUL.blue, COUL.jaune, COUL.rouge][i % 3]);
      d.texte(MARGE + 16, y + 20, j.date, { size: 10, gras: true, c: COUL.navy });
      d.texte(MARGE + 92, y + 20, j.titre, { size: 11.5, gras: true, c: COUL.encre });
      d.paragraphe(MARGE + 92, y + 36, j.desc, 9.5, L - 108, { c: COUL.texte });
      y += h + 9;
    });

    /* ---- Pages de contenu ---- */
    var page = 1;

    function entete() {
      page = d.nouvellePage();
      d.rect(0, 0, A4.w, 3, COUL.navy);
      if (logo) d.image('Logo', MARGE, 20, 22, 22);
      d.texte(MARGE + (logo ? 30 : 0), 36, 'Plan de communication — MJA', { size: 9, gras: true, c: COUL.gris });
      d.texte(A4.w - MARGE, 36, "Campagne d'adhésion 2026-2027", { size: 9, c: COUL.gris, align: 'right' });
      d.rect(MARGE, 46, L, 0.8, COUL.trait);
      d.y = 74;
    }

    function place(besoin) {
      if (d.y + besoin > A4.h - 56) entete();
    }

    entete();

    plan.mois.forEach(function (m) {
      place(90);
      d.texte(MARGE, d.y, m.titre.toUpperCase(), { size: 15, gras: true, c: COUL.navy });
      d.texte(A4.w - MARGE, d.y, m.items.length + (m.items.length > 1 ? ' contenus' : ' contenu'),
              { size: 9, c: COUL.gris, align: 'right' });
      d.y += 7;
      d.rect(MARGE, d.y, L, 2, COUL.jaune);
      d.y += 20;

      m.items.forEach(function (it) {
        /* Hauteur de la fiche, calculée avant de savoir si elle tient. */
        var lTexte = L - 78;
        var nDesc = d.decouper(it.desc, 9.5, lTexte).length;
        var nNote = it.note ? d.decouper(it.note, 8.5, lTexte - 12).length : 0;
        var h = 26 + nDesc * 13 + (nNote ? nNote * 11.5 + 8 : 0) + (it.lien ? 16 : 0) + 14;
        h = Math.max(h, 62);
        place(h + 10);

        var y0 = d.y;
        d.rectArrondi(MARGE, y0, L, h, 8, [1, 1, 1]);
        d.rectArrondi(MARGE, y0, 58, h, 8, COUL.navy);
        d.rect(MARGE + 46, y0, 12, h, COUL.navy);
        d.texte(MARGE + 29, y0 + h / 2 - 2, it.jour, { size: 17, gras: true, c: [1, 1, 1], align: 'center' });
        d.texte(MARGE + 29, y0 + h / 2 + 12, it.moisCourt.toUpperCase(), { size: 7.5, c: [0.741, 0.831, 0.961], align: 'center' });

        var x = MARGE + 70, yy = y0 + 17;

        /* Étiquettes type et statut */
        var teinteType = { short: COUL.blue, infographie: COUL.jaune, photo: COUL.rouge }[it.cleType] || COUL.blue;
        var lt = larg(it.type, 7.5, true) + 12;
        d.rectArrondi(x, yy - 9, lt, 13, 6.5, teinteType);
        d.texte(x + 6, yy, it.type.toUpperCase(), { size: 7.5, gras: true, c: [1, 1, 1] });

        var teinteStatut = { publie: [0.086, 0.396, 0.204], production: [0.573, 0.251, 0.055], proposition: COUL.gris }[it.cleStatut] || COUL.gris;
        var ls = larg(it.statut, 7.5, true) + 12;
        d.rectArrondi(x + lt + 6, yy - 9, ls, 13, 6.5, COUL.trait);
        d.texte(x + lt + 12, yy, it.statut.toUpperCase(), { size: 7.5, gras: true, c: teinteStatut });

        yy += 18;
        d.texte(x, yy, it.titre, { size: 11.5, gras: true, c: COUL.encre });
        yy += 15;
        yy += d.paragraphe(x, yy, it.desc, 9.5, lTexte, { c: COUL.texte, lh: 13 });

        if (it.note) {
          yy += 4;
          d.rect(x, yy - 7, 2.5, nNote * 11.5, COUL.jaune);
          yy += d.paragraphe(x + 10, yy, it.note, 8.5, lTexte - 12, { c: COUL.gris, lh: 11.5 });
        }
        if (it.lien) {
          yy += 4;
          d.texte(x, yy + 4, 'Rattachement : ' + it.lien, { size: 8.5, ital: true, c: COUL.dark });
        }

        d.y = y0 + h + 9;
      });

      d.y += 8;
    });

    /* ---- Points de vigilance ---- */
    if (plan.vigilance.length) {
      place(140);
      d.texte(MARGE, d.y, 'À VALIDER AVANT DIFFUSION', { size: 13, gras: true, c: [0.573, 0.251, 0.055] });
      d.y += 7;
      d.rect(MARGE, d.y, L, 2, COUL.jaune);
      d.y += 18;

      plan.vigilance.forEach(function (v) {
        var n = d.decouper(v, 9.5, L - 34).length;
        var h = n * 13 + 16;
        place(h + 8);
        d.rectArrondi(MARGE, d.y, L, h, 7, COUL.creme);
        d.rectArrondi(MARGE + 12, d.y + h / 2 - 3, 6, 6, 3, COUL.jaune);
        d.paragraphe(MARGE + 26, d.y + 16, v, 9.5, L - 40, { c: [0.471, 0.349, 0.110], lh: 13 });
        d.y += h + 7;
      });
    }

    /* ---- Pied de page sur toutes les pages sauf la garde ---- */
    for (var p = 1; p < d.pages.length; p++) {
      d.flux = d.pages[p];
      d.rect(MARGE, A4.h - 44, L, 0.8, COUL.trait);
      d.texte(MARGE, A4.h - 30, "Madin' Jeunes Ambition — Relève tous les défis !", { size: 8, c: COUL.gris });
      d.texte(A4.w - MARGE, A4.h - 30, 'Page ' + p + ' / ' + (d.pages.length - 1),
              { size: 8, c: COUL.gris, align: 'right' });
    }

    return d;
  }

  /* ── Bouton ────────────────────────────────────────────────────────── */
  var bouton = document.getElementById('btn-pdf');
  if (!bouton) return;

  bouton.addEventListener('click', function () {
    var initial = bouton.innerHTML;
    bouton.disabled = true;
    bouton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Génération…';

    chargerLogo(bouton.dataset.logo).then(function (logo) {
      var doc = composer(lirePlan(), logo);
      var blob = doc.produire();
      var a = document.createElement('a');
      a.href = URL.createObjectURL(blob);
      a.download = 'plan-communication-mja-2026-2027.pdf';
      document.body.appendChild(a); a.click(); a.remove();
      setTimeout(function () { URL.revokeObjectURL(a.href); }, 4000);
    }).catch(function (e) {
      alert("La génération du PDF a échoué : " + e.message);
    }).then(function () {
      bouton.disabled = false;
      bouton.innerHTML = initial;
    });
  });
})();
</script>
</body>
</html>
