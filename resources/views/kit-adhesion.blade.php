<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>MJ'Adhésion 2026-2027 — Kit de communication | Madin' Jeunes Ambition</title>
<meta name="robots" content="noindex, nofollow">
<link rel="icon" type="image/jpeg" href="{{ asset('images/logo.jpg') }}">

<!-- Polices de marque MJA (Gill Sans + AllRound Gothic) servies depuis /public/fonts -->
<link rel="preload" as="font" type="font/woff2" href="{{ asset('fonts/Gill_Sans.woff2') }}" crossorigin>
<link rel="preload" as="font" type="font/woff2" href="{{ asset('fonts/Gill_Sans_Bold.woff2') }}" crossorigin>
<link rel="preload" as="font" type="font/woff2" href="{{ asset('fonts/AllRoundGothic-Bold.woff2') }}" crossorigin>
<link rel="stylesheet" href="{{ asset('css/gill-sans.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/fontawesome/css/all.min.css') }}">

<style>
/* =====================================================================
   MJ'Adhésion — Kit de communication (page autonome, 100 % statique)
   Charte reprise du site : couleurs MJA, Gill Sans, AllRound Gothic.
   Aucune dépendance externe : tout est servi depuis /public.
   ===================================================================== */
:root{
  --blue:#3DAEF5; --bluedark:#1E93D6; --yellow:#F5A623; --red:#D0021B;
  --dark:#2048A4; --navy:#1A3D8A; --light:#EBF4FF; --gray:#333333;
  --line:#E9EEF6; --muted:#7A879B;
}
*{box-sizing:border-box}
html,body{margin:0;padding:0}
body{
  font-family:'Gill Sans','Open Sans',sans-serif; color:var(--gray);
  background:#fff; -webkit-font-smoothing:antialiased;
}
h1,h2,h3,h4{font-family:'Gill Sans','Montserrat',sans-serif;margin:0}
.font-round{font-family:'AllRound Gothic','Gill Sans',sans-serif}
a{color:inherit}
.wrap{max-width:1280px;margin:0 auto;padding:0 24px}
.tribar{display:flex;height:6px}
.tribar i{flex:1}
.tribar i:nth-child(1){background:var(--blue)}
.tribar i:nth-child(2){background:var(--yellow)}
.tribar i:nth-child(3){background:var(--red)}

/* ── En-tête ─────────────────────────────────────────────────────── */
header.hero{
  background:linear-gradient(135deg,#1A3D8A 0%,#2048A4 45%,#3262CC 100%);
  color:#fff;padding:44px 0 52px;position:relative;overflow:hidden
}
header.hero .ring{position:absolute;right:-70px;top:-70px;width:320px;height:320px;opacity:.13}
header.hero .brand{display:flex;align-items:center;gap:18px;margin-bottom:26px}
header.hero .brand img{height:64px;width:64px;object-fit:contain;background:#fff;border-radius:14px;padding:4px}
header.hero .brand b{font-size:15px;letter-spacing:.16em;text-transform:uppercase;display:block}
header.hero .brand span{font-size:13px;color:#B9CBEE}
header.hero h1{font-family:'AllRound Gothic','Gill Sans',sans-serif;font-weight:800;font-size:clamp(34px,5vw,58px);line-height:1.02;letter-spacing:-.01em}
header.hero h1 em{font-style:normal;color:var(--yellow)}
header.hero p.lead{max-width:680px;color:#CBD9F4;font-size:17px;line-height:1.6;margin:18px 0 0}
.badges{display:flex;flex-wrap:wrap;gap:8px;margin-top:22px}
.badges span{background:rgba(255,255,255,.13);border:1px solid rgba(255,255,255,.18);padding:7px 14px;border-radius:999px;font-size:13px;font-weight:600}

/* ── Barre d'outils ─────────────────────────────────────────────── */
.toolbar{position:sticky;top:0;z-index:40;background:rgba(255,255,255,.96);backdrop-filter:blur(10px);border-bottom:1px solid var(--line)}
.toolbar .inner{display:flex;flex-wrap:wrap;gap:10px;align-items:center;padding:12px 0}
.chip{border:2px solid var(--line);background:#fff;color:#5A6B85;font-family:inherit;font-size:13px;font-weight:700;
  padding:8px 15px;border-radius:999px;cursor:pointer;transition:.15s}
.chip:hover{border-color:#CFDBEC}
.chip.on{background:var(--navy);border-color:var(--navy);color:#fff}
.chip.on.blue{background:var(--blue);border-color:var(--blue)}
.tsep{width:1px;height:26px;background:var(--line);margin:0 4px}
.tlabel{font-size:11px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:var(--muted)}

/* ── Panneau d'options ──────────────────────────────────────────── */
.options{background:var(--light);border-bottom:1px solid var(--line)}
.options .grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:18px;padding:22px 0}
.field label{display:block;font-size:11px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--dark);margin-bottom:7px}
.field input[type=text],.field select{width:100%;border:2px solid #D8E4F5;background:#fff;border-radius:12px;padding:10px 13px;font-family:inherit;font-size:14px;color:var(--gray)}
.field input[type=text]:focus,.field select:focus{outline:none;border-color:var(--blue)}
.field .hint{font-size:12px;color:var(--muted);margin-top:6px;line-height:1.45}
.filebtn{display:inline-flex;align-items:center;gap:8px;background:#fff;border:2px dashed #B9CDE8;border-radius:12px;padding:10px 14px;
  font-size:13px;font-weight:700;color:var(--dark);cursor:pointer;width:100%}
.filebtn:hover{border-color:var(--blue);background:#F7FBFF}
.switch{display:inline-flex;align-items:center;gap:9px;font-size:14px;font-weight:600;color:var(--gray);cursor:pointer}
.switch input{width:18px;height:18px;accent-color:var(--dark)}

/* ── Sections & cartes ──────────────────────────────────────────── */
section.block{padding:52px 0 8px}
.sechead{display:flex;align-items:flex-end;justify-content:space-between;gap:20px;flex-wrap:wrap;margin-bottom:26px}
.sechead .k{display:flex;align-items:center;gap:9px;margin-bottom:6px}
.sechead .k s{width:30px;height:2px;background:var(--blue);display:block}
.sechead .k em{font-style:normal;color:var(--blue);font-weight:700;font-size:12px;letter-spacing:.18em;text-transform:uppercase}
.sechead h2{font-family:'AllRound Gothic','Gill Sans',sans-serif;font-weight:800;font-size:clamp(24px,3vw,34px);color:var(--navy)}
.sechead p{color:var(--muted);font-size:14px;margin:8px 0 0;max-width:640px;line-height:1.55}

.gallery{display:grid;gap:26px}
.gallery.g-square{grid-template-columns:repeat(auto-fill,minmax(300px,1fr))}
.gallery.g-tall{grid-template-columns:repeat(auto-fill,minmax(240px,1fr))}
.gallery.g-paper{grid-template-columns:repeat(auto-fill,minmax(290px,1fr))}
.gallery.g-wide{grid-template-columns:repeat(auto-fill,minmax(420px,1fr))}

.card{border:1px solid var(--line);border-radius:20px;overflow:hidden;background:#fff;transition:transform .2s,box-shadow .2s;display:flex;flex-direction:column}
.card[hidden]{display:none}
section.block[hidden]{display:none}
.card:hover{transform:translateY(-3px);box-shadow:0 18px 44px rgba(32,72,164,.13)}
.card .art{background:#F4F7FC;padding:14px;display:flex;align-items:center;justify-content:center;cursor:zoom-in;min-height:120px}
.card .art svg{width:100%;height:auto;display:block;border-radius:6px;box-shadow:0 3px 14px rgba(20,40,90,.14)}
/* Réserve visuelle avant le rendu paresseux */
.card .art .ph{width:100%;aspect-ratio:1/1;border-radius:6px;background:
  linear-gradient(100deg,#EEF3FA 30%,#F8FAFD 50%,#EEF3FA 70%) #EEF3FA;
  background-size:220% 100%;animation:sk 1.4s ease-in-out infinite}
@keyframes sk{0%{background-position:120% 0}100%{background-position:-60% 0}}
.card .meta{padding:14px 16px 4px}
.card .meta h3{font-size:15px;font-weight:700;color:var(--navy);line-height:1.3}
.card .tags{display:flex;flex-wrap:wrap;gap:6px;margin-top:9px}
.card .tags b{font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;padding:4px 9px;border-radius:6px;background:var(--light);color:var(--dark)}
.card .tags b.px{background:#F3F5F9;color:var(--muted)}
.card .dl{padding:12px 16px 16px;margin-top:auto}
.dlrow{display:flex;align-items:center;gap:7px;flex-wrap:wrap;margin-top:9px}
.dlrow > u{font-style:normal;text-decoration:none;font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--muted);width:34px;flex-shrink:0}
.btn{border:none;font-family:inherit;font-size:12px;font-weight:700;padding:8px 12px;border-radius:9px;cursor:pointer;transition:.15s;display:inline-flex;align-items:center;gap:5px}
.btn.png{background:var(--light);color:var(--dark)}
.btn.png:hover{background:#DCE9FC}
.btn.pdf{background:var(--navy);color:#fff}
.btn.pdf:hover{background:var(--dark)}
.btn.ghost{background:#fff;border:2px solid var(--line);color:#5A6B85;padding:7px 12px}
.btn.ghost:hover{border-color:var(--blue);color:var(--dark)}
.btn:disabled{opacity:.5;cursor:progress}
.btn.big{font-size:14px;padding:12px 20px;border-radius:12px}
.btn.solid{background:var(--blue);color:#fff}
.btn.solid:hover{background:var(--bluedark)}

/* ── Aide / pied ────────────────────────────────────────────────── */
.note{background:#FFFBF2;border:1px solid #F6E3BE;border-left:4px solid var(--yellow);border-radius:14px;padding:16px 18px;font-size:14px;line-height:1.6;color:#6B5722;margin:34px 0 0}
.note b{color:#4A3B10}
footer.foot{margin-top:60px;background:var(--navy);color:#B9CBEE;padding:30px 0;font-size:13px}
footer.foot a{color:#fff;font-weight:600;text-decoration:none}

/* ── Visionneuse plein écran ────────────────────────────────────── */
.lightbox{position:fixed;inset:0;z-index:90;background:rgba(8,18,42,.88);display:none;align-items:center;justify-content:center;padding:28px}
.lightbox.on{display:flex}
.lightbox .box{max-width:min(94vw,900px);max-height:92vh;overflow:auto;background:#fff;border-radius:16px;padding:14px}
.lightbox .box svg{width:100%;height:auto;display:block}
.lightbox .close{position:absolute;top:18px;right:22px;background:#fff;border:none;width:42px;height:42px;border-radius:50%;font-size:18px;cursor:pointer;color:var(--navy)}

#hidden-qr{position:absolute;left:-9999px;top:0}
.toast{position:fixed;left:50%;bottom:26px;transform:translate(-50%,120%);background:var(--navy);color:#fff;
  padding:13px 22px;border-radius:999px;font-size:14px;font-weight:600;z-index:95;transition:transform .28s;box-shadow:0 10px 30px rgba(0,0,0,.25)}
.toast.on{transform:translate(-50%,0)}
</style>
</head>
<body>

<div class="tribar"><i></i><i></i><i></i></div>

<header class="hero">
  <svg class="ring" viewBox="0 0 200 200" fill="none" aria-hidden="true">
    <circle cx="100" cy="100" r="95" stroke="#3DAEF5" stroke-width="2"/>
    <circle cx="100" cy="100" r="68" stroke="#F5A623" stroke-width="2"/>
    <circle cx="100" cy="100" r="42" stroke="#D0021B" stroke-width="2"/>
  </svg>
  <div class="wrap">
    <div class="brand">
      <img src="{{ asset('images/logomjat.png') }}" alt="Logo Madin' Jeunes Ambition">
      <div>
        <b>Madin' Jeunes Ambition</b>
        <span>Association de jeunes bénévoles · Fort-de-France, Martinique</span>
      </div>
    </div>
    <h1>Kit de communication<br><em>MJ'Adhésion</em> — Saison 2026-2027</h1>
    <p class="lead">Tous les visuels de la campagne d'adhésion et de réadhésion, prêts à publier et à imprimer :
       posts Instagram, stories, affiches, flyers, bannières et vidéos motion design. Sept directions graphiques —
       de l'esprit des visuels de l'an dernier (mosaïque de photos, « Relève le défi ! », QR bordeaux) à des
       propositions plus contemporaines. PNG jusqu'à 300&nbsp;dpi, PDF A5 / A4 / A3, vidéo MP4.</p>
    <div class="badges">
      <span><i class="fas fa-square"></i> Posts 1080×1080</span>
      <span><i class="fas fa-mobile-screen"></i> Stories 1080×1920</span>
      <span><i class="fas fa-file-pdf"></i> Affiches A5 / A4 / A3</span>
      <span><i class="fas fa-note-sticky"></i> Flyer recto-verso</span>
      <span><i class="fas fa-panorama"></i> Bannières web</span>
      <span><i class="fas fa-film"></i> Vidéos motion 5 s</span>
    </div>
  </div>
</header>

<div class="toolbar">
  <div class="wrap inner">
    <span class="tlabel">Format</span>
    <button class="chip on" data-filter="group" data-value="all">Tout</button>
    <button class="chip" data-filter="group" data-value="post">Posts</button>
    <button class="chip" data-filter="group" data-value="story">Stories</button>
    <button class="chip" data-filter="group" data-value="affiche">Affiches</button>
    <button class="chip" data-filter="group" data-value="flyer">Flyers</button>
    <button class="chip" data-filter="group" data-value="banner">Bannières</button>
    <button class="chip" data-filter="group" data-value="motion">Vidéos</button>
    <span class="tsep"></span>
    <span class="tlabel">Style</span>
    <button class="chip on blue" data-filter="style" data-value="all">Tous</button>
    <button class="chip" data-filter="style" data-value="blanc">Fond blanc</button>
    <button class="chip" data-filter="style" data-value="navy">Fond navy</button>
    <button class="chip" data-filter="style" data-value="pastel">Pastel</button>
    <button class="chip" data-filter="style" data-value="mosaic">Mosaïque</button>
    <button class="chip" data-filter="style" data-value="blocs">Blocs</button>
    <button class="chip" data-filter="style" data-value="typo">Typo XXL</button>
    <button class="chip" data-filter="style" data-value="photo">Photo</button>
    <span class="tsep"></span>
    <button class="btn ghost" id="btn-all-png"><i class="fas fa-download"></i> Tous les PNG affichés</button>
    <button class="btn ghost" id="btn-all-pdf"><i class="fas fa-file-pdf"></i> Tous les PDF A4</button>
  </div>
</div>

<div class="options">
  <div class="wrap">
    <div class="grid">
      <div class="field">
        <label for="opt-season">Mention de saison</label>
        <input type="text" id="opt-season" value="SAISON 2026-2027" maxlength="30">
        <div class="hint">Apparaît sur tous les visuels sous le titre.</div>
      </div>
      <div class="field">
        <label for="opt-url">Lien du QR code &amp; des visuels</label>
        <input type="text" id="opt-url" value="https://mja-martinique.com/adhesion">
        <div class="hint">Utilise un lien tracké depuis l'admin (Sources) si tu veux mesurer les scans.</div>
      </div>
      <div class="field">
        <label>Photos (styles « Photo » et « Mosaïque »)</label>
        <label class="filebtn" for="opt-photo"><i class="fas fa-images"></i> <span id="photo-name">10 photos de membres pré-chargées</span></label>
        <input type="file" id="opt-photo" accept="image/*" multiple style="display:none">
        <div class="hint">Pré-rempli avec vos photos de membres récupérées sur Instagram — chaque visuel en montre des différentes.
          Dépose tes propres fichiers pour les remplacer : conseillé pour l'impression, les photos Instagram étant compressées.</div>
      </div>
      <div class="field">
        <label for="opt-qrcolor">Couleur du QR code</label>
        <select id="opt-qrcolor">
          <option value="#9B1C1E">Bordeaux (comme la saison 2025)</option>
          <option value="#1A3D8A">Navy MJA</option>
          <option value="#111111">Noir</option>
        </select>
        <div class="hint">Le bordeaux reprend l'anneau du logo et les visuels de l'an dernier.</div>
      </div>
      <div class="field">
        <label>Affichages</label>
        <label class="switch"><input type="checkbox" id="opt-price" checked> Afficher la cotisation (20 €)</label><br>
        <label class="switch" style="margin-top:8px"><input type="checkbox" id="opt-qr" checked> Afficher le QR code</label>
        <div class="hint">Décoche le QR pour les posts si tu préfères « lien en bio ».</div>
      </div>
    </div>
  </div>
</div>

<main class="wrap">

  <section class="block" data-group="post">
    <div class="sechead">
      <div>
        <div class="k"><s></s><em>Instagram · Facebook</em></div>
        <h2>Posts carrés — 1080 × 1080</h2>
        <p>Pour le feed. Publie-les seuls ou enchaîne-les en carrousel : annonce, réadhésion, pourquoi adhérer, comment adhérer, chiffres clés.</p>
      </div>
    </div>
    <div class="gallery g-square" id="gal-post"></div>
  </section>

  <section class="block" data-group="story">
    <div class="sechead">
      <div>
        <div class="k"><s></s><em>Stories · Reels · Statut WhatsApp</em></div>
        <h2>Stories verticales — 1080 × 1920</h2>
        <p>Zones hautes et basses laissées libres pour le nom du compte, les stickers et le lien.</p>
      </div>
    </div>
    <div class="gallery g-tall" id="gal-story"></div>
  </section>

  <section class="block" data-group="affiche">
    <div class="sechead">
      <div>
        <div class="k"><s></s><em>Impression</em></div>
        <h2>Affiches — A5 / A4 / A3</h2>
        <p>Mêmes visuels, trois tailles : lycées, maisons de quartier, médiathèques, commerces partenaires. PDF pour l'imprimeur, PNG 300 dpi pour un tirage bureautique.</p>
      </div>
    </div>
    <div class="gallery g-paper" id="gal-affiche"></div>
  </section>

  <section class="block" data-group="flyer">
    <div class="sechead">
      <div>
        <div class="k"><s></s><em>À distribuer</em></div>
        <h2>Flyer A5 — recto / verso</h2>
        <p>Recto accrocheur, verso complet : les 3 étapes de l'adhésion, ce que MJA apporte, les infos pratiques et les contacts.</p>
      </div>
    </div>
    <div class="gallery g-paper" id="gal-flyer"></div>
  </section>

  <section class="block" data-group="banner">
    <div class="sechead">
      <div>
        <div class="k"><s></s><em>Web</em></div>
        <h2>Bannières &amp; couvertures</h2>
        <p>Couverture Facebook 1640 × 624 et image de partage 1200 × 630 (aperçu des liens sur WhatsApp, Facebook, LinkedIn).</p>
      </div>
    </div>
    <div class="gallery g-wide" id="gal-banner"></div>
  </section>

  <section class="block" data-group="motion">
    <div class="sechead">
      <div>
        <div class="k"><s></s><em>Motion design</em></div>
        <h2>Vidéos animées — 5 secondes</h2>
        <p>Même charte, en mouvement : le logo apparaît, le titre monte ligne par ligne, le soulignement jaune se déploie,
           les pastilles éclosent puis le bouton pulse. Idéal en reel, en story ou sur un écran d'accueil.
           « Aperçu animé » joue l'animation dans la carte ; le téléchargement produit un vrai fichier vidéo
           (MP4 si le navigateur le permet, sinon WebM).</p>
      </div>
    </div>
    <div class="gallery g-square" id="gal-motion"></div>
  </section>

  <div class="note">
    <b>Bon à savoir avant impression.</b> Les PDF sont générés à 300 dpi à la taille exacte de la page (A5 148×210, A4 210×297, A3 297×420 mm),
    sans fond perdu : les visuels gardent une marge intérieure de 17 mm, donc un léger rognage de l'imprimeur ne coupe aucun texte.
    Si ton imprimeur demande un fond perdu de 3 mm, envoie-lui le PNG 300 dpi en lui précisant « mise à l'échelle 103 % ».
  </div>
</main>

<footer class="foot">
  <div class="wrap">
    Madin' Jeunes Ambition · 22, passage du Cœur sur la Main — 97200 Fort-de-France ·
    <a href="mailto:contact@mja-martinique.com">contact@mja-martinique.com</a> ·
    <a href="https://www.instagram.com/madin_jeunes_ambition/" target="_blank" rel="noopener">@madin_jeunes_ambition</a> ·
    <a href="{{ route('adhesion') }}">mja-martinique.com/adhesion</a>
  </div>
</footer>

<div class="lightbox" id="lightbox">
  <button class="close" id="lb-close" aria-label="Fermer">✕</button>
  <div class="box" id="lb-box"></div>
</div>
<div id="hidden-qr"></div>
<div class="toast" id="toast"></div>

<script src="{{ asset('vendor/qrcode/qrcode.min.js') }}"></script>
<script>
/* Base des URL, fournie par Laravel : la page fonctionne quel que soit
   le sous-dossier de déploiement. */
var BASE_URL = @json(rtrim(asset('/'), '/') . '/');
function ASSET(path){ return BASE_URL + path; }

/* =====================================================================
   1. Constantes de marque
   ===================================================================== */
var C = {
  blue:'#3DAEF5', bluedark:'#1E93D6', yellow:'#F5A623', red:'#D0021B',
  dark:'#2048A4', navy:'#1A3D8A', mid:'#3262CC', light:'#EBF4FF',
  gray:'#333333', white:'#FFFFFF', ink:'#0B1E45',
  /* Bordeaux de l'anneau du logo — utilisé sur les visuels d'adhésion 2025
     pour le QR code et le « INSCRIS-TOI ! ». */
  bordeaux:'#9B1C1E'
};
var FAM_AG = "AllRound Gothic, Gill Sans, Montserrat, sans-serif";
var FAM_GS = "Gill Sans, Open Sans, sans-serif";

var ORG   = "MADIN' JEUNES AMBITION";
var TAG   = "Association de jeunes bénévoles · Martinique";
var INSTA = "@madin_jeunes_ambition";
var SITE  = "mja-martinique.com/adhesion";
var ADDR  = "22, passage du Cœur sur la Main — 97200 Fort-de-France";
var MAIL  = "contact@mja-martinique.com";
var TEL   = "0696 43 88 21";

/* Options globales, pilotées par le panneau du haut */
var OPT = {
  season:"SAISON 2026-2027", url:"https://mja-martinique.com/adhesion",
  photo:null,        /* 1re photo — styles « Photo » */
  photos:[],         /* toutes les photos — style « Mosaïque » */
  price:true, qr:true,
  qrColor:C.bordeaux /* bordeaux comme sur les visuels 2025 ; navy possible */
};

/* Ressources encodées en base64 (nécessaires pour l'export : un SVG
   rasterisé dans un <canvas> n'a accès à aucune ressource externe). */
var ASSETS = { logo:null, fonts:'', qr:null, photos:{} };

/* Photothèque par défaut : photos de membres extraites des publications
   Instagram de l'association (collage d'adhésion de novembre 2025 et visuels
   « Ti Déj » / « Journée de l'amitié »). Classées de la meilleure résolution
   à la plus faible. Elles servent tant que l'utilisateur n'a rien déposé. */
var DEFAULT_PHOTOS = [
  'images/kit/membres-01.jpg', 'images/kit/membres-06.jpg', 'images/kit/membres-02.jpg',
  'images/kit/membres-03.jpg', 'images/kit/membres-07.jpg', 'images/kit/membres-04.jpg',
  'images/kit/membres-08.jpg', 'images/kit/membres-05.jpg', 'images/kit/membres-09.jpg',
  'images/kit/membres-10.jpg'
];

/* Décalage dans la photothèque, propre au visuel en cours de rendu : c'est ce
   qui fait que deux mosaïques ne montrent pas les mêmes photos. */
var PIDX = 0;
var EMBED_PHOTOS = false;

/** Source d'image à l'index `i` (bouclage). Renvoie une data-URI à l'export. */
function photoAt(i){
  if (OPT.photos.length) return OPT.photos[((i % OPT.photos.length) + OPT.photos.length) % OPT.photos.length];
  var n = DEFAULT_PHOTOS.length, path = DEFAULT_PHOTOS[((i % n) + n) % n];
  if (EMBED_PHOTOS && ASSETS.photos[path]) return 'data:image/jpeg;base64,' + ASSETS.photos[path];
  return ASSET(path);
}

/* =====================================================================
   2. Utilitaires
   ===================================================================== */
function esc(s){
  return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
function toast(msg){
  var t = document.getElementById('toast');
  t.textContent = msg; t.classList.add('on');
  clearTimeout(toast._t); toast._t = setTimeout(function(){ t.classList.remove('on'); }, 2600);
}
function bufToB64(buf){
  var bytes = new Uint8Array(buf), out = '', chunk = 0x8000;
  for (var i = 0; i < bytes.length; i += chunk) {
    out += String.fromCharCode.apply(null, bytes.subarray(i, i + chunk));
  }
  return btoa(out);
}
async function fetchB64(url){
  var r = await fetch(url);
  if (!r.ok) throw new Error('Ressource introuvable : ' + url);
  return bufToB64(await r.arrayBuffer());
}

/* Mesure de texte réelle (les polices de marque sont chargées dans la page) :
   permet d'ajuster la largeur des pastilles/boutons et la taille des titres XXL. */
var _mc = document.createElement('canvas').getContext('2d');
function measure(text, size, weight, fam){
  _mc.font = (weight||700) + ' ' + size + 'px ' + (fam === FAM_AG ? '"AllRound Gothic","Gill Sans"' : '"Gill Sans"');
  return _mc.measureText(text).width;
}
/** Plus grande taille de police telle que `text` tienne dans `maxW`. */
function fitSize(text, maxW, maxSize, weight, fam){
  var w = measure(text, 100, weight, fam);
  if (!w) return maxSize;
  return Math.min(maxSize, Math.floor(100 * maxW / w));
}

/* =====================================================================
   3. Primitives SVG
   ===================================================================== */
function T(x, y, s, o){
  o = o || {};
  var fam = o.f === 'ag' ? FAM_AG : FAM_GS;
  return '<text x="' + x + '" y="' + y + '"'
    + ' font-family="' + fam + '"'
    + ' font-size="' + (o.size || 32) + '"'
    + ' font-weight="' + (o.w === undefined ? 700 : o.w) + '"'
    + ' fill="' + (o.fill || C.gray) + '"'
    + (o.anchor ? ' text-anchor="' + o.anchor + '"' : '')
    + (o.ls ? ' letter-spacing="' + o.ls + '"' : '')
    + (o.it ? ' font-style="italic"' : '')
    + (o.op !== undefined ? ' opacity="' + o.op + '"' : '')
    + '>' + esc(s) + '</text>';
}
/** Texte qui se réduit automatiquement pour tenir dans `maxW`.
    Indispensable pour les accroches longues (« RÉADHÉSION — ON COMPTE SUR TOI »)
    qui, selon le format, dépasseraient du cadre. */
function TFit(x, y, str, maxW, o){
  o = o || {};
  var fam = o.f === 'ag' ? FAM_AG : FAM_GS;
  var size = Math.min(o.size || 32, fitSize(str, maxW, o.size || 32, o.w === undefined ? 700 : o.w, fam));
  var c = {}; Object.keys(o).forEach(function(k){ c[k] = o[k]; });
  c.size = size;
  if (o.ls) c.ls = o.ls * size / (o.size || 32);
  return T(x, y, str, c);
}
/** Idem, sur plusieurs lignes : une seule taille commune à toutes. */
function TLFit(x, y, lines, maxW, o){
  var size = o.size;
  var fam = o.f === 'ag' ? FAM_AG : FAM_GS;
  for (var i = 0; i < lines.length; i++) size = Math.min(size, fitSize(lines[i], maxW, size, o.w === undefined ? 700 : o.w, fam));
  var c = {}; Object.keys(o).forEach(function(k){ c[k] = o[k]; });
  c.size = size; c.lh = (o.lh || o.size * 1.28) * size / o.size;
  return TL(x, y, lines, c);
}
/** Bloc de lignes empilées. */
function TL(x, y, lines, o){
  var out = '', lh = o.lh || (o.size * 1.28);
  for (var i = 0; i < lines.length; i++) out += T(x, y + i * lh, lines[i], o);
  return out;
}
/** Le sigle MJA tricolore. */
function wordmark(x, y, size, anchor){
  return '<text x="' + x + '" y="' + y + '" font-family="' + FAM_AG + '" font-size="' + size
    + '" font-weight="800"' + (anchor ? ' text-anchor="' + anchor + '"' : '') + '>'
    + '<tspan fill="' + C.blue + '">M</tspan><tspan fill="' + C.yellow + '">J</tspan><tspan fill="' + C.red + '">A</tspan>'
    + '</text>';
}
/** Filet tricolore MJA. */
function triBar(x, y, w, h){
  var t = w / 3;
  return '<rect x="' + x + '" y="' + y + '" width="' + (t + 1) + '" height="' + h + '" fill="' + C.blue + '"/>'
       + '<rect x="' + (x + t) + '" y="' + y + '" width="' + (t + 1) + '" height="' + h + '" fill="' + C.yellow + '"/>'
       + '<rect x="' + (x + 2 * t) + '" y="' + y + '" width="' + t + '" height="' + h + '" fill="' + C.red + '"/>';
}
/** Anneaux concentriques du logo, en filigrane. */
function rings(cx, cy, r, op){
  var sw = Math.max(2, r * 0.022);
  return '<g fill="none" stroke-width="' + sw + '" opacity="' + op + '">'
    + '<circle cx="' + cx + '" cy="' + cy + '" r="' + r + '" stroke="' + C.blue + '"/>'
    + '<circle cx="' + cx + '" cy="' + cy + '" r="' + (r * 0.73) + '" stroke="' + C.yellow + '"/>'
    + '<circle cx="' + cx + '" cy="' + cy + '" r="' + (r * 0.48) + '" stroke="' + C.red + '"/>'
    + '</g>';
}
function logoHref(embed){
  return embed && ASSETS.logo ? 'data:image/png;base64,' + ASSETS.logo : ASSET('images/logomjat.png');
}
/** Logo MJA. Sur fond sombre ou photo, on le pose sur une plaque blanche
    arrondie (même traitement que sur le site) pour qu'il reste lisible. */
function logoImg(x, y, size, embed, plate){
  var href = logoHref(embed), s = '';
  if (plate) {
    var pd = size * 0.10;
    s += '<rect x="' + (x - pd) + '" y="' + (y - pd) + '" width="' + (size + pd * 2) + '" height="' + (size + pd * 2)
      + '" rx="' + (size * 0.18) + '" fill="#FFFFFF"/>';
  }
  return s + '<image x="' + x + '" y="' + y + '" width="' + size + '" height="' + size
    + '" href="' + href + '" xlink:href="' + href + '" preserveAspectRatio="xMidYMid meet"/>';
}
/** Filigrane : le logo répété en très basse opacité — signature visuelle MJA
    reprise des visuels d'adhésion de la saison précédente. */
function watermark(W, H, uid, embed, op, tile){
  tile = tile || Math.min(W, H) * 0.26;
  var href = logoHref(embed);
  return '<defs><pattern id="wm' + uid + '" x="0" y="0" width="' + tile + '" height="' + tile
    + '" patternUnits="userSpaceOnUse">'
    + '<image x="' + (tile * 0.1) + '" y="' + (tile * 0.1) + '" width="' + (tile * 0.8) + '" height="' + (tile * 0.8)
    + '" href="' + href + '" xlink:href="' + href + '" preserveAspectRatio="xMidYMid meet"/>'
    + '</pattern></defs>'
    + '<rect width="' + W + '" height="' + H + '" fill="url(#wm' + uid + ')" opacity="' + (op || 0.07) + '"/>';
}
/** Soulignement jaune sous un mot clé (code graphique repris de « DEVENEZ BÉNÉVOLE ! »). */
function underline(x, y, text, size, fill){
  var w = measure(text, size, 800, FAM_AG);
  return '<rect x="' + x + '" y="' + (y + size * 0.14) + '" width="' + w + '" height="' + (size * 0.15)
    + '" rx="' + (size * 0.05) + '" fill="' + (fill || C.yellow) + '"/>';
}
/** Flèche courbe dessinée, pointant vers le QR (comme « INSCRIS-TOI ! ➜ »). */
function curvedArrow(x1, y1, x2, y2, sw, fill){
  /* Point de contrôle décalé à l'opposé de la cible : la courbe part vers la
     droite, redescend, puis revient pointer le QR — sans croiser le texte. */
  var cx = x1 + (x1 - x2) * 0.45, cy = y1 + (y2 - y1) * 0.62;
  var ang = Math.atan2(y2 - cy, x2 - cx), hl = sw * 3.4;
  var p1 = [x2 - hl * Math.cos(ang - 0.45), y2 - hl * Math.sin(ang - 0.45)];
  var p2 = [x2 - hl * Math.cos(ang + 0.45), y2 - hl * Math.sin(ang + 0.45)];
  return '<path d="M' + x1 + ' ' + y1 + ' Q' + cx + ' ' + cy + ' ' + x2 + ' ' + y2 + '" fill="none" stroke="'
    + fill + '" stroke-width="' + sw + '" stroke-linecap="round"/>'
    + '<path d="M' + x2 + ' ' + y2 + ' L' + p1[0] + ' ' + p1[1] + ' L' + p2[0] + ' ' + p2[1] + ' Z" fill="' + fill + '"/>';
}
/** Pastille dont la largeur s'adapte au texte mesuré. */
function pill(x, y, text, size, bg, fg, padX, h){
  padX = padX || size * 0.85; h = h || size * 2.3;
  var w = measure(text, size, 700, FAM_GS) + padX * 2;
  return '<rect x="' + x + '" y="' + y + '" width="' + w + '" height="' + h + '" rx="' + (h / 2) + '" fill="' + bg + '"/>'
       + T(x + w / 2, y + h / 2 + size * 0.36, text, { size: size, fill: fg, anchor: 'middle' });
}
function pillWidth(text, size, padX){ return measure(text, size, 700, FAM_GS) + (padX || size * 0.85) * 2; }

/** Bouton d'appel à l'action. */
function cta(x, y, w, h, label, bg, fg, size){
  size = size || h * 0.36;
  return '<rect x="' + x + '" y="' + y + '" width="' + w + '" height="' + h + '" rx="' + (h * 0.28) + '" fill="' + bg + '"/>'
    + T(x + w / 2, y + h / 2 + size * 0.35, label, { size: size, fill: fg, anchor: 'middle', f: 'ag', w: 800, ls: size * 0.03 });
}
/** Carte blanche + QR code, avec libellé. */
function qrCard(x, y, size, label, labelFill){
  if (!OPT.qr || !ASSETS.qr) return '';
  var pad = size * 0.11, box = size + pad * 2;
  var s = '<rect x="' + x + '" y="' + y + '" width="' + box + '" height="' + box + '" rx="' + (box * 0.13) + '" fill="#FFFFFF"/>'
    + '<image x="' + (x + pad) + '" y="' + (y + pad) + '" width="' + size + '" height="' + size
    + '" href="' + ASSETS.qr + '" xlink:href="' + ASSETS.qr + '"/>';
  if (label) s += T(x + box / 2, y + box + size * 0.2, label, { size: size * 0.13, fill: labelFill || C.navy, anchor: 'middle', ls: 1 });
  return s;
}
/** Puce « check » ronde. */
function checkRow(x, y, text, size, dotFill, textFill, dotIconFill){
  var r = size * 0.62;
  return '<circle cx="' + (x + r) + '" cy="' + (y - size * 0.32) + '" r="' + r + '" fill="' + dotFill + '"/>'
    + '<path d="M' + (x + r * 0.55) + ' ' + (y - size * 0.32) + ' l' + (r * 0.32) + ' ' + (r * 0.34) + ' l' + (r * 0.6) + ' -' + (r * 0.72) + '" '
    + 'stroke="' + (dotIconFill || '#FFFFFF') + '" stroke-width="' + (r * 0.22) + '" fill="none" stroke-linecap="round" stroke-linejoin="round"/>'
    + T(x + r * 2 + size * 0.5, y, text, { size: size, w: 500, fill: textFill });
}

/* =====================================================================
   4. Palettes de style
   ===================================================================== */
function pal(style){
  if (style === 'navy') return {
    fg:'#FFFFFF', fg2:'#C4D4F3', kicker:C.yellow, accent:C.yellow, alt:C.yellow,
    pillBg:'rgba(255,255,255,.14)', pillFg:'#FFFFFF', ctaBg:C.yellow, ctaFg:C.navy,
    rule:'rgba(255,255,255,.20)', foot:'#9FB3DF', card:'rgba(255,255,255,.09)', dot:C.blue, qrLabel:'#FFFFFF'
  };
  if (style === 'photo') return {
    fg:'#FFFFFF', fg2:'#DEE6F7', kicker:C.yellow, accent:C.yellow, alt:C.yellow,
    pillBg:'rgba(255,255,255,.20)', pillFg:'#FFFFFF', ctaBg:C.yellow, ctaFg:C.navy,
    rule:'rgba(255,255,255,.28)', foot:'#DDE6F7', card:'rgba(11,30,69,.55)', dot:C.blue, qrLabel:'#FFFFFF'
  };
  /* Pastel : dégradé arc-en-ciel très clair, texte navy — reprend le fond
     du visuel « DEVENEZ BÉNÉVOLE ! » de la saison précédente. */
  if (style === 'pastel') return {
    fg:C.navy, fg2:'#5A6B85', kicker:C.red, accent:C.dark, alt:C.red,
    pillBg:'rgba(255,255,255,.72)', pillFg:C.dark, ctaBg:C.navy, ctaFg:'#FFFFFF',
    rule:'rgba(26,61,138,.16)', foot:'#7A879B', card:'rgba(255,255,255,.66)', dot:C.blue, qrLabel:C.navy
  };
  /* blanc & typo : fond blanc */
  return {
    fg:C.navy, fg2:'#6C7A91', kicker:C.blue, accent:C.blue, alt:C.blue,
    pillBg:C.light, pillFg:C.dark, ctaBg:C.navy, ctaFg:'#FFFFFF',
    rule:'#E4EAF4', foot:'#98A5B8', card:'#F5F8FD', dot:C.blue, qrLabel:C.navy
  };
}

/* Fond selon le style. */
function bgMarkup(style, W, H, uid, embed){
  if (style === 'navy') {
    return '<rect width="' + W + '" height="' + H + '" fill="url(#gNavy' + uid + ')"/>'
      + rings(W * 0.93, H * 0.06, Math.min(W, H) * 0.30, 0.20)
      + rings(W * 0.06, H * 0.97, Math.min(W, H) * 0.22, 0.13);
  }
  if (style === 'photo') {
    var img, src = OPT.photo || photoAt(PIDX);
    if (src) {
      img = '<image x="0" y="0" width="' + W + '" height="' + H + '" href="' + src + '" xlink:href="' + src
          + '" preserveAspectRatio="xMidYMid slice"/>';
    } else {
      /* Aucune photo chargée : fond neutre + petit repère en haut à droite,
         volontairement hors des zones de texte. */
      var U = Math.min(W, H), bs = U * 0.030;
      var lab = 'Ajoute une photo (panneau Options)';
      var bwd = measure(lab, bs, 600, FAM_GS) + bs * 1.6;
      img = '<rect width="' + W + '" height="' + H + '" fill="#8FA6C9"/>'
        + '<rect x="' + (W - bwd - U * 0.05) + '" y="' + (U * 0.05) + '" width="' + bwd + '" height="' + (bs * 2.2)
        + '" rx="' + (bs * 1.1) + '" fill="#0B1E45" opacity=".45"/>'
        + T(W - bwd / 2 - U * 0.05, U * 0.05 + bs * 1.5, lab,
            { size: bs, fill: '#FFFFFF', anchor: 'middle', w: 600, op: .95 });
    }
    return img + '<rect width="' + W + '" height="' + H + '" fill="url(#gShade' + uid + ')"/>';
  }
  if (style === 'pastel') {
    return '<defs><linearGradient id="gPast' + uid + '" x1="0" y1="0" x2="1" y2="1">'
      + '<stop offset="0" stop-color="#FFE3E8"/><stop offset=".28" stop-color="#FFF2DC"/>'
      + '<stop offset=".55" stop-color="#DCF0FF"/><stop offset=".8" stop-color="#D8F3E4"/>'
      + '<stop offset="1" stop-color="#FBF4D2"/></linearGradient></defs>'
      + '<rect width="' + W + '" height="' + H + '" fill="url(#gPast' + uid + ')"/>'
      + watermark(W, H, uid, embed, 0.06)
      + rings(W * 0.93, H * 0.07, Math.min(W, H) * 0.26, 0.22);
  }
  /* blanc / typo */
  return '<rect width="' + W + '" height="' + H + '" fill="#FFFFFF"/>'
    + '<circle cx="' + (W * 1.02) + '" cy="' + (-H * 0.04) + '" r="' + (Math.min(W, H) * 0.42) + '" fill="' + C.light + '"/>'
    + watermark(W, H, uid, embed, 0.05)
    + rings(W * 0.93, H * 0.07, Math.min(W, H) * 0.26, 0.30);
}

/* Enveloppe SVG : dimensions, dégradés, polices embarquées à l'export. */
function svgWrap(W, H, uid, inner, embed){
  var fonts = embed && ASSETS.fonts ? '<style type="text/css"><![CDATA[' + ASSETS.fonts + ']]></style>' : '';
  return '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" '
    + 'width="' + W + '" height="' + H + '" viewBox="0 0 ' + W + ' ' + H + '">'
    + '<defs>' + fonts
    + '<linearGradient id="gNavy' + uid + '" x1="0" y1="0" x2="1" y2="1">'
    + '<stop offset="0" stop-color="' + C.navy + '"/><stop offset=".45" stop-color="' + C.dark + '"/><stop offset="1" stop-color="' + C.mid + '"/></linearGradient>'
    + '<linearGradient id="gShade' + uid + '" x1="0" y1="0" x2="0" y2="1">'
    + '<stop offset="0" stop-color="' + C.ink + '" stop-opacity=".38"/>'
    + '<stop offset=".42" stop-color="' + C.ink + '" stop-opacity=".58"/>'
    + '<stop offset="1" stop-color="' + C.ink + '" stop-opacity=".93"/></linearGradient>'
    + '</defs>' + inner + '</svg>';
}

/* =====================================================================
   5. Contenus (variantes de message)
   ===================================================================== */
function price(txt, fallback){ return OPT.price ? txt : fallback; }

function V(variant){
  var season = OPT.season || '';
  var D = {
    annonce: {
      slogan: "Rejoins l'aventure MJA !",
      kicker: "CAMPAGNE D'ADHÉSION",
      t: ["MJ'ADHÉSION"],
      season: season,
      big: ["MJ'", "ADHÉ", "SION"],
      sub: ["Tu as entre 16 et 35 ans et tu veux t'engager", "en Martinique ? Rejoins l'équipe MJA."],
      subShort: ["16-35 ans, envie d'agir", "en Martinique ? Rejoins-nous."],
      pills: ["16 – 35 ans", price("Cotisation 20 €", "Adhésion simple"), "100 % bénévole"],
      cta: "J'ADHÈRE EN LIGNE", ctaShort: "J'ADHÈRE",
      checks: ["Ouvert à tous les jeunes de 16 à 35 ans",
               price("Cotisation annuelle de 20 € seulement", "Une cotisation annuelle symbolique"),
               "Adhésion 100 % en ligne, photo comprise"]
    },
    readhesion: {
      slogan: "On compte sur toi !",
      kicker: "RÉADHÉSION — ON COMPTE SUR TOI",
      t: ["JE RENOUVELLE", "MON ADHÉSION"],
      season: season,
      big: ["RÉ", "ADHÉ", "SION"],
      sub: ["Déjà membre de MJA ? Réadhère en 3 minutes", "et continue l'aventure avec nous."],
      subShort: ["Déjà membre ? Réadhère", "en 3 minutes."],
      pills: [price("Réadhésion 20 €", "Réadhésion"), "3 minutes", "100 % en ligne"],
      cta: "JE RÉADHÈRE", ctaShort: "JE RÉADHÈRE",
      checks: ["Tu es déjà membre : ta réadhésion prend 3 minutes",
               price("20 € pour toute la saison " + season.replace('SAISON ', ''), "Une cotisation annuelle symbolique"),
               "Carte bancaire, virement ou espèces"]
    },
    pourquoi: {
      kicker: "POURQUOI ADHÉRER ?",
      t: ["4 BONNES", "RAISONS"],
      season: season,
      big: ["POUR", "QUOI", "ADHÉRER"],
      items: [
        ["AGIR", "Des actions concrètes pour la jeunesse martiniquaise."],
        ["RENCONTRER", "43 membres actifs et 60 sympathisants engagés."],
        ["APPRENDRE", "Projets, événements, communication : tu montes en compétences."],
        ["COMPTER", "Une voix à l'assemblée générale et dans nos décisions."]
      ],
      cta: "J'ADHÈRE EN LIGNE"
    },
    comment: {
      kicker: "COMMENT ADHÉRER ?",
      t: ["3 ÉTAPES", "ET C'EST FAIT"],
      season: season,
      big: ["3", "ÉTAPES"],
      steps: [
        ["1", "Remplis le formulaire", "Sur " + SITE + ", avec ta photo."],
        ["2", "Règle ta cotisation", price("20 € par carte bancaire, virement ou espèces.", "Par carte bancaire, virement ou espèces.")],
        ["3", "Bienvenue chez MJA", "Tu reçois ton mail de bienvenue et tu rejoins l'équipe."]
      ],
      cta: "JE COMMENCE"
    },
    chiffres: {
      kicker: "MADIN' JEUNES AMBITION",
      t: ["MJA EN", "CHIFFRES"],
      season: season,
      big: ["MJA", "EN", "CHIFFRES"],
      stats: [["2011", "année de création"], ["43", "membres actifs"], ["60", "sympathisants"], ["5", "pôles d'action"]],
      cta: "JE REJOINS L'ÉQUIPE"
    },
    /* ── Variantes reprenant les slogans MJA de la saison précédente ── */
    defi: {
      kicker: "CAMPAGNE D'ADHÉSION",
      t: ["RELÈVE", "LE DÉFI !"],
      season: season,
      big: ["RELÈVE", "LE", "DÉFI !"],
      slogan: "Relève le défi !",
      signature: ["Madin' Jeunes Ambition", "Relève tous les défis !"],
      para: ["Envie de t'investir, de grandir et d'apprendre ?",
             "Pour les jeunes, par des jeunes ?",
             "Viens découvrir l'expérience associative de",
             "Madin' Jeunes Ambition !"],
      sub: ["Envie de t'investir, de grandir et d'apprendre ?", "Pour les jeunes, par des jeunes ?"],
      subShort: ["Pour les jeunes,", "par des jeunes."],
      pills: ["16 – 35 ans", price("Cotisation 20 €", "Adhésion simple"), "100 % bénévole"],
      cta: "INSCRIS-TOI !", ctaShort: "INSCRIS-TOI !",
      checks: ["Envie de t'investir, de grandir et d'apprendre ?",
               "Pour les jeunes, par des jeunes",
               price("Cotisation annuelle de 20 €", "Une cotisation annuelle symbolique")]
    },
    benevole: {
      kicker: "REJOINS LA TEAM MJA",
      t: ["DEVENEZ", "BÉNÉVOLE !"],
      season: season,
      big: ["DEVENEZ", "BÉNÉ", "VOLE !"],
      slogan: "Venez comme vous êtes !",
      signature: ["Madin' Jeunes Ambition", "Pour les jeunes, par des jeunes"],
      actions: ["Prévention pour la non violence",
                "Prévention sécurité routière et addictions",
                "Engagement et valorisation de l'image des jeunes",
                "Solidarité",
                "Loisirs, détentes, développement personnel…"],
      sub: ["Nos actions : prévention, solidarité, engagement,", "loisirs et développement personnel."],
      subShort: ["Venez comme", "vous êtes !"],
      pills: ["16 – 35 ans", "Toutes les envies", "Venez comme vous êtes"],
      cta: "JE DEVIENS BÉNÉVOLE", ctaShort: "JE M'ENGAGE",
      checks: ["Prévention, solidarité, engagement, loisirs",
               "Pour les jeunes, par des jeunes",
               "Venez comme vous êtes !"]
    },
    temoignage: {
      kicker: "PAROLE DE MEMBRE",
      t: ["ILS L'ONT", "FAIT"],
      season: season,
      big: ["REJOINS", "NOUS"],
      quote: ["« Chez MJA, j'ai trouvé", "une équipe qui transforme", "les idées en actions. »"],
      author: "— Un membre de Madin' Jeunes Ambition",
      cta: "J'ADHÈRE À MON TOUR"
    }
  };
  return D[variant];
}

/* =====================================================================
   6. Blocs d'en-tête / de pied communs
   ===================================================================== */
function headerBlock(M, W, style, p, embed, k){
  var logo = k.logo, name = k.name, tag = k.tag, ruleY = k.rule;
  var s = logoImg(M, k.y, logo, embed, style === 'navy' || style === 'photo');
  s += T(M + logo + k.gap, k.y + logo * 0.44, ORG, { size: name, fill: p.fg, ls: name * 0.05 });
  s += T(M + logo + k.gap, k.y + logo * 0.44 + tag * 1.65, TAG, { size: tag, w: 500, fill: p.fg2 });
  if (ruleY) s += '<rect x="' + M + '" y="' + ruleY + '" width="' + (W - 2 * M) + '" height="2" fill="' + p.rule + '"/>';
  return s;
}
function footerBlock(M, W, y, p, size){
  return T(M, y, INSTA, { size: size, fill: p.foot, w: 600 })
    + T(W - M, y, SITE, { size: size, fill: p.accent, anchor: 'end', w: 700 });
}

/* =====================================================================
   7. Layout — POST 1080 × 1080
   ===================================================================== */
function renderPost(style, variant, uid, embed){
  var W = 1080, H = 1080, M = 76, p = pal(style), v = V(variant);
  if (style === 'typo') return svgWrap(W, H, uid, typoBody(W, H, M, variant, uid, embed), embed);

  var s = bgMarkup(style, W, H, uid, embed);
  s += triBar(0, 0, W, 13);
  s += headerBlock(M, W, style, p, embed, { y: 44, logo: 148, gap: 26, name: 30, tag: 22, rule: 224 });

  var y = 292;
  s += TFit(M, y, v.kicker, W - 2 * M, { size: 25, fill: p.kicker, ls: 4.6 });

  if (variant === 'annonce' || variant === 'readhesion' || variant === 'defi') {
    var titles = v.t, ts = titles.length > 1 ? 96 : 104;
    for (var t0 = 0; t0 < titles.length; t0++) ts = Math.min(ts, fitSize(titles[t0], W - 2 * M, ts, 800, FAM_AG));
    s += TL(M, y + 108, titles, { size: ts, f: 'ag', w: 800, fill: p.fg, lh: ts * 1.02 });
    var afterT = y + 108 + (titles.length - 1) * ts * 1.02;
    s += T(M, afterT + 62, v.season, { size: 34, fill: p.alt, ls: 4, f: 'ag', w: 800 });
    s += TL(M, afterT + 130, v.sub, { size: 29, w: 500, fill: p.fg2, lh: 42 });

    /* Pastilles */
    var px = M, py = afterT + 196;
    for (var i = 0; i < v.pills.length; i++) {
      s += pill(px, py, v.pills[i], 24, p.pillBg, p.pillFg);
      px += pillWidth(v.pills[i], 24) + 12;
    }

    /* Appel à l'action + QR */
    var hasQr = OPT.qr && ASSETS.qr;
    var cw = hasQr ? 520 : W - 2 * M;
    s += cta(M, 806, cw, 100, v.ctaShort, p.ctaBg, p.ctaFg, 38);
    s += T(M, 946, OPT.url.replace(/^https?:\/\//, ''), { size: 26, fill: p.fg2, w: 600 });
    if (hasQr) s += qrCard(W - M - 196, 790, 172, null, p.qrLabel);
  }

  /* « DEVENEZ BÉNÉVOLE ! » : titre souligné en jaune + liste des actions,
     comme le visuel de recrutement de bénévoles de la saison précédente. */
  if (variant === 'benevole') {
    var tb = 96;
    for (var q = 0; q < v.t.length; q++) tb = Math.min(tb, fitSize(v.t[q], W - 2 * M, tb, 800, FAM_AG));
    s += underline(M, y + 108 + (v.t.length - 1) * tb * 1.02, v.t[v.t.length - 1], tb);
    s += TL(M, y + 108, v.t, { size: tb, f: 'ag', w: 800, fill: p.fg, lh: tb * 1.02 });
    var ay = y + 108 + (v.t.length - 1) * tb * 1.02 + 96;
    s += T(M, ay, 'NOS ACTIONS', { size: 28, fill: C.red, ls: 4 });
    for (var r = 0; r < v.actions.length; r++) {
      s += T(M, ay + 54 + r * 42, '·  ' + v.actions[r], { size: 26, w: 500, fill: p.fg2 });
    }
    s += T(M, ay + 54 + v.actions.length * 42 + 26, 'POUR LES JEUNES, PAR DES JEUNES', { size: 24, fill: p.fg, ls: 3.4 });
    s += cta(M, 890, W - 2 * M, 96, v.ctaShort, p.ctaBg, p.ctaFg, 34);
  }

  if (variant === 'pourquoi') {
    var ts2 = fitSize(v.t[0], W - 2 * M, 82, 800, FAM_AG);
    s += TL(M, y + 96, v.t, { size: ts2, f: 'ag', w: 800, fill: p.fg, lh: ts2 * 1.0 });
    var gy = y + 96 + ts2 * 1.0 + 62, gw = (W - 2 * M - 24) / 2, gh = 162;
    for (var j = 0; j < 4; j++) {
      var cx = M + (j % 2) * (gw + 24), cy = gy + Math.floor(j / 2) * (gh + 18);
      s += '<rect x="' + cx + '" y="' + cy + '" width="' + gw + '" height="' + gh + '" rx="22" fill="' + p.card + '"/>';
      s += '<rect x="' + cx + '" y="' + cy + '" width="' + gw + '" height="6" rx="3" fill="' + [C.blue, C.yellow, C.red, C.dark][j] + '"/>';
      s += T(cx + 26, cy + 62, v.items[j][0], { size: 27, fill: p.fg, ls: 2 });
      s += wrapText(v.items[j][1], cx + 26, cy + 100, gw - 52, 21, 30, { w: 500, fill: p.fg2 });
    }
    s += cta(M, 896, W - 2 * M, 88, v.cta, p.ctaBg, p.ctaFg, 34);
  }

  if (variant === 'comment') {
    var ts3 = fitSize(v.t[1], W - 2 * M, 76, 800, FAM_AG);
    s += TL(M, y + 92, v.t, { size: ts3, f: 'ag', w: 800, fill: p.fg, lh: ts3 * 1.0 });
    var sy = y + 92 + ts3 + 74;
    for (var k = 0; k < 3; k++) {
      var by = sy + k * 138;
      s += '<circle cx="' + (M + 38) + '" cy="' + (by + 8) + '" r="38" fill="' + [C.blue, C.yellow, C.red][k] + '"/>';
      s += T(M + 38, by + 22, v.steps[k][0], { size: 40, fill: k === 1 ? C.navy : '#FFFFFF', anchor: 'middle', f: 'ag', w: 800 });
      s += T(M + 104, by, v.steps[k][1], { size: 31, fill: p.fg });
      s += wrapText(v.steps[k][2], M + 104, by + 40, W - 2 * M - 104, 23, 32, { w: 500, fill: p.fg2 });
    }
    s += cta(M, 896, W - 2 * M, 88, v.cta, p.ctaBg, p.ctaFg, 34);
  }

  if (variant === 'chiffres') {
    var ts4 = fitSize(v.t[1], W - 2 * M, 84, 800, FAM_AG);
    s += TL(M, y + 96, v.t, { size: ts4, f: 'ag', w: 800, fill: p.fg, lh: ts4 * 1.0 });
    var qy = y + 96 + ts4 + 70, qw = (W - 2 * M - 24) / 2, qh = 158;
    for (var m = 0; m < 4; m++) {
      var qx = M + (m % 2) * (qw + 24), qyy = qy + Math.floor(m / 2) * (qh + 22);
      s += '<rect x="' + qx + '" y="' + qyy + '" width="' + qw + '" height="' + qh + '" rx="22" fill="' + p.card + '"/>';
      s += T(qx + 28, qyy + 92, v.stats[m][0], { size: 66, fill: [C.blue, C.yellow, C.red, C.dark][m], f: 'ag', w: 800 });
      s += T(qx + 28, qyy + 130, v.stats[m][1], { size: 22, w: 500, fill: p.fg2 });
    }
    s += cta(M, 896, W - 2 * M, 88, v.cta, p.ctaBg, p.ctaFg, 32);
  }

  if (variant === 'temoignage') {
    var qs = 54;
    s += T(M, y + 92, '“', { size: 150, fill: p.accent, f: 'ag', w: 800 });
    s += TL(M, y + 190, v.quote, { size: qs, f: 'ag', w: 800, fill: p.fg, lh: qs * 1.18 });
    s += T(M, y + 190 + v.quote.length * qs * 1.18 + 20, v.author, { size: 25, w: 500, fill: p.fg2 });
    s += cta(M, 872, W - 2 * M, 96, v.cta, p.ctaBg, p.ctaFg, 34);
  }

  s += footerBlock(M, W, H - 42, p, 22);
  s += triBar(0, H - 11, W, 11);
  return svgWrap(W, H, uid, s, embed);
}

/* Retour à la ligne automatique sur une largeur donnée (mesure réelle). */
function wrapText(text, x, y, maxW, size, lh, o){
  o = o || {};
  var words = String(text).split(' '), lines = [], cur = '';
  for (var i = 0; i < words.length; i++) {
    var test = cur ? cur + ' ' + words[i] : words[i];
    if (measure(test, size, o.w || 500, FAM_GS) > maxW && cur) { lines.push(cur); cur = words[i]; }
    else cur = test;
  }
  if (cur) lines.push(cur);
  var out = '';
  for (var j = 0; j < lines.length; j++) out += T(x, y + j * lh, lines[j], { size: size, w: o.w || 500, fill: o.fill || C.gray, anchor: o.anchor });
  return out;
}

/* =====================================================================
   8. Style « Typo XXL » — mise en page dédiée (fond blanc)
   ===================================================================== */
function typoBody(W, H, M, variant, uid, embed){
  var v = V(variant), lines = v.big, avail = W - 2 * M;
  var s = '<rect width="' + W + '" height="' + H + '" fill="#FFFFFF"/>';
  s += watermark(W, H, uid, embed, 0.055);
  s += rings(W * 0.94, H * 0.055, Math.min(W, H) * 0.23, 0.26);
  s += triBar(0, 0, W, H * 0.014);

  /* En-tête : identité */
  var lg = H * 0.115;
  s += logoImg(M, H * 0.045, lg, embed);
  s += T(M + lg + W * 0.024, H * 0.045 + lg * 0.44, ORG, { size: H * 0.026, fill: C.navy, ls: H * 0.0013 });
  s += T(M + lg + W * 0.024, H * 0.045 + lg * 0.44 + H * 0.032, TAG, { size: H * 0.020, w: 500, fill: '#6C7A91' });

  /* Bloc typographique : la plus grande taille qui tienne en largeur ET en hauteur. */
  var maxLineH = (H * 0.40) / lines.length, size = maxLineH / 0.86;
  for (var i = 0; i < lines.length; i++) size = Math.min(size, fitSize(lines[i], avail, size, 800, FAM_AG));
  var lh = size * 0.86, top = H * 0.26;
  var colors = [C.dark, C.blue, C.red, C.navy];

  /* Soulignement jaune sous le dernier mot (code repris de « DEVENEZ BÉNÉVOLE ! »). */
  s += underline(M, top + (lines.length - 1) * lh, lines[lines.length - 1], size);
  for (var j = 0; j < lines.length; j++) {
    s += T(M, top + j * lh, lines[j], { size: size, f: 'ag', w: 800, fill: colors[j % colors.length], ls: -size * 0.02 });
  }

  var yb = top + (lines.length - 1) * lh + size * 0.30;
  s += '<rect x="' + M + '" y="' + yb + '" width="' + avail + '" height="3" fill="' + C.navy + '"/>';
  s += TFit(M, yb + H * 0.048, v.kicker, avail, { size: H * 0.024, fill: C.blue, ls: H * 0.0042 });
  s += TLFit(M, yb + H * 0.098, (v.sub || v.quote || [TAG]), avail, { size: H * 0.028, w: 500, fill: '#5C6B82', lh: H * 0.039 });

  /* Pied : saison + lien à gauche, QR à droite */
  var qs = H * 0.135, hasQr = OPT.qr && ASSETS.qr;
  var footW = hasQr ? (W - 2 * M - qs - W * 0.05) : avail;
  s += TFit(M, H - H * 0.128, v.season || OPT.season, footW, { size: H * 0.030, fill: C.navy, f: 'ag', w: 800, ls: 2 });
  s += TFit(M, H - H * 0.082, OPT.url.replace(/^https?:\/\//, ''), footW, { size: H * 0.026, fill: C.dark, w: 700 });
  s += TFit(M, H - H * 0.034, hasQr ? INSTA : INSTA + '   ·   ' + ORG, footW, { size: H * 0.019, fill: '#98A5B8', w: 600 });
  if (hasQr) {
    s += '<image x="' + (W - M - qs) + '" y="' + (H - H * 0.185) + '" width="' + qs + '" height="' + qs
      + '" href="' + ASSETS.qr + '" xlink:href="' + ASSETS.qr + '"/>';
    s += T(W - M - qs / 2, H - H * 0.026, 'SCANNE-MOI', { size: H * 0.019, fill: C.navy, anchor: 'middle', ls: 1.5 });
  } else {
    s += wordmark(W - M, H - H * 0.075, H * 0.10, 'end');
  }
  s += triBar(0, H - H * 0.014, W, H * 0.014);
  return s;
}

/* =====================================================================
   9. Layout — STORY 1080 × 1920
   ===================================================================== */
function renderStory(style, variant, uid, embed){
  var W = 1080, H = 1920, M = 88, p = pal(style), v = V(variant);
  if (style === 'typo') return svgWrap(W, H, uid, typoBody(W, H, M, variant, uid, embed), embed);

  var s = bgMarkup(style, W, H, uid, embed);
  s += triBar(0, 0, W, 15);

  /* Zone haute libre (nom de compte, stickers) : on démarre à 250 px. */
  s += logoImg(W / 2 - 116, 250, 232, embed, style === 'navy' || style === 'photo');
  s += T(W / 2, 546, ORG, { size: 32, fill: p.fg, anchor: 'middle', ls: 5 });
  s += T(W / 2, 590, TAG, { size: 23, w: 500, fill: p.fg2, anchor: 'middle' });

  s += TFit(W / 2, 660, v.kicker, W - 2 * M, { size: 27, fill: p.kicker, anchor: 'middle', ls: 5 });

  if (variant === 'annonce' || variant === 'readhesion' || variant === 'defi' || variant === 'benevole') {
    var ts = 999;
    for (var i = 0; i < v.t.length; i++) ts = Math.min(ts, fitSize(v.t[i], W - 2 * M, 108, 800, FAM_AG));
    if (variant === 'benevole') s += underline(W / 2 - measure(v.t[v.t.length - 1], ts, 800, FAM_AG) / 2, 790 + (v.t.length - 1) * ts * 1.05, v.t[v.t.length - 1], ts);
    s += TL(W / 2, 790, v.t, { size: ts, f: 'ag', w: 800, fill: p.fg, anchor: 'middle', lh: ts * 1.05 });
    var aft = 790 + (v.t.length - 1) * ts * 1.05;
    s += T(W / 2, aft + 80, v.season, { size: 38, fill: p.alt, anchor: 'middle', f: 'ag', w: 800, ls: 4 });
    s += TL(W / 2, aft + 168, v.sub, { size: 32, w: 500, fill: p.fg2, anchor: 'middle', lh: 46 });

    /* Pastilles centrées sur une ligne */
    var tot = 0, sz = 26;
    for (var a = 0; a < v.pills.length; a++) tot += pillWidth(v.pills[a], sz) + 14;
    tot -= 14;
    var px = W / 2 - tot / 2, py = aft + 236;
    for (var b = 0; b < v.pills.length; b++) {
      s += pill(px, py, v.pills[b], sz, p.pillBg, p.pillFg);
      px += pillWidth(v.pills[b], sz) + 14;
    }
    s += cta(M, 1330, W - 2 * M, 118, v.ctaShort, p.ctaBg, p.ctaFg, 44);
    s += T(W / 2, 1500, OPT.url.replace(/^https?:\/\//, ''), { size: 28, fill: p.fg2, anchor: 'middle', w: 600 });
    if (OPT.qr && ASSETS.qr) s += qrCard(W / 2 - 118, 1548, 208, 'SCANNE POUR ADHÉRER', p.qrLabel);
    else s += T(W / 2, 1610, 'Lien en bio ↑', { size: 30, fill: p.accent, anchor: 'middle' });
  }

  if (variant === 'comment') {
    s += TL(W / 2, 780, v.t, { size: 74, f: 'ag', w: 800, fill: p.fg, anchor: 'middle', lh: 78 });
    for (var k = 0; k < 3; k++) {
      var by = 980 + k * 190;
      s += '<rect x="' + M + '" y="' + by + '" width="' + (W - 2 * M) + '" height="160" rx="26" fill="' + p.card + '"/>';
      s += '<circle cx="' + (M + 66) + '" cy="' + (by + 80) + '" r="40" fill="' + [C.blue, C.yellow, C.red][k] + '"/>';
      s += T(M + 66, by + 95, v.steps[k][0], { size: 42, fill: k === 1 ? C.navy : '#FFFFFF', anchor: 'middle', f: 'ag', w: 800 });
      s += T(M + 130, by + 66, v.steps[k][1], { size: 30, fill: p.fg });
      s += wrapText(v.steps[k][2], M + 130, by + 106, W - 2 * M - 160, 22, 30, { w: 500, fill: p.fg2 });
    }
    s += cta(M, 1580, W - 2 * M, 112, v.cta, p.ctaBg, p.ctaFg, 40);
  }

  s += T(W / 2, H - 190, OPT.season, { size: 24, fill: p.foot, anchor: 'middle', ls: 4 });
  s += T(W / 2, H - 148, INSTA, { size: 26, fill: p.foot, anchor: 'middle', w: 600 });
  s += triBar(0, H - 15, W, 15);
  return svgWrap(W, H, uid, s, embed);
}

/* =====================================================================
   10. Layout — PAPIER (ratio A) 1240 × 1754  → A5 / A4 / A3
   ===================================================================== */
function renderPaper(style, variant, uid, embed){
  var W = 1240, H = 1754, M = 106, p = pal(style), v = V(variant);
  if (style === 'typo') return svgWrap(W, H, uid, typoBody(W, H, M, variant, uid, embed), embed);

  var s = bgMarkup(style, W, H, uid, embed);
  s += triBar(0, 0, W, 18);
  s += headerBlock(M, W, style, p, embed, { y: 58, logo: 188, gap: 30, name: 33, tag: 24, rule: 282 });

  s += TFit(M, 364, v.kicker, W - 2 * M, { size: 27, fill: p.kicker, ls: 5.2 });

  var ts = 999;
  for (var i = 0; i < v.t.length; i++) ts = Math.min(ts, fitSize(v.t[i], W - 2 * M, 128, 800, FAM_AG));
  s += TL(M, 470, v.t, { size: ts, f: 'ag', w: 800, fill: p.fg, lh: ts * 1.02 });
  var aft = 470 + (v.t.length - 1) * ts * 1.02;

  s += T(M, aft + 82, v.season, { size: 42, fill: p.alt, f: 'ag', w: 800, ls: 4 });
  s += TL(M, aft + 160, v.sub, { size: 33, w: 500, fill: p.fg2, lh: 47 });

  /* Les 3 arguments */
  var cy = aft + 268;
  for (var j = 0; j < v.checks.length; j++) {
    s += checkRow(M, cy + j * 66, v.checks[j], 29, p.dot, p.fg2);
  }

  /* Appel à l'action */
  s += cta(M, 1120, W - 2 * M, 132, v.cta, p.ctaBg, p.ctaFg, 48);
  s += T(W / 2, 1306, OPT.url.replace(/^https?:\/\//, ''), { size: 34, fill: p.accent, anchor: 'middle', w: 700 });

  /* QR + informations pratiques */
  var infoX = M;
  if (OPT.qr && ASSETS.qr) {
    s += qrCard(M, 1358, 176, null, p.qrLabel);
    infoX = M + 176 + 176 * 0.22 + 34;
  }
  s += T(infoX, 1408, 'SCANNE OU RENDS-TOI SUR', { size: 22, fill: p.fg2, ls: 3 });
  s += T(infoX, 1452, SITE, { size: 30, fill: p.fg, w: 700 });
  s += T(infoX, 1500, price('16 – 35 ans  ·  Cotisation 20 €  ·  100 % bénévole', '16 – 35 ans  ·  100 % bénévole'), { size: 24, w: 500, fill: p.fg2 });
  s += T(infoX, 1540, INSTA + '   ·   ' + MAIL, { size: 22, w: 500, fill: p.fg2 });

  s += '<rect x="' + M + '" y="1600" width="' + (W - 2 * M) + '" height="2" fill="' + p.rule + '"/>';
  s += T(M, 1652, ADDR, { size: 21, w: 500, fill: p.foot });
  s += T(W - M, 1652, 'Tél. ' + TEL, { size: 21, w: 600, fill: p.foot, anchor: 'end' });
  s += triBar(0, H - 18, W, 18);
  return svgWrap(W, H, uid, s, embed);
}

/* =====================================================================
   11. Layout — FLYER A5 verso (fond blanc, informatif)
   ===================================================================== */
function renderVerso(style, variant, uid, embed){
  var W = 1240, H = 1754, M = 106, p = pal('blanc');
  var vc = V('comment'), vp = V('pourquoi');
  var s = '<rect width="' + W + '" height="' + H + '" fill="#FFFFFF"/>';
  s += rings(W * 0.95, H * 0.03, 240, 0.16);
  s += triBar(0, 0, W, 18);

  s += logoImg(M, 60, 120, embed);
  s += T(M + 146, 118, ORG, { size: 29, fill: C.navy, ls: 1.4 });
  s += T(M + 146, 156, TAG, { size: 21, w: 500, fill: '#6C7A91' });
  s += T(W - M, 132, OPT.season, { size: 26, fill: C.blue, anchor: 'end', f: 'ag', w: 800, ls: 3 });
  s += '<rect x="' + M + '" y="220" width="' + (W - 2 * M) + '" height="2" fill="#E4EAF4"/>';

  /* Les 3 étapes */
  s += T(M, 306, 'COMMENT ADHÉRER ?', { size: 26, fill: C.blue, ls: 5 });
  s += T(M, 380, '3 étapes, et c\'est fait', { size: 58, fill: C.navy, f: 'ag', w: 800 });
  for (var k = 0; k < 3; k++) {
    var by = 452 + k * 152;
    s += '<rect x="' + M + '" y="' + by + '" width="' + (W - 2 * M) + '" height="132" rx="22" fill="#F5F8FD"/>';
    s += '<circle cx="' + (M + 62) + '" cy="' + (by + 66) + '" r="38" fill="' + [C.blue, C.yellow, C.red][k] + '"/>';
    s += T(M + 62, by + 81, vc.steps[k][0], { size: 40, fill: k === 1 ? C.navy : '#FFFFFF', anchor: 'middle', f: 'ag', w: 800 });
    s += T(M + 126, by + 56, vc.steps[k][1], { size: 29, fill: C.navy });
    s += wrapText(vc.steps[k][2], M + 126, by + 96, W - 2 * M - 160, 22, 30, { w: 500, fill: '#6C7A91' });
  }

  /* Ce que MJA apporte */
  s += T(M, 966, 'CE QUE TU TROUVES CHEZ MJA', { size: 26, fill: C.blue, ls: 5 });
  var gw = (W - 2 * M - 22) / 2;
  for (var j = 0; j < 4; j++) {
    var gx = M + (j % 2) * (gw + 22), gy = 1006 + Math.floor(j / 2) * 118;
    s += '<rect x="' + gx + '" y="' + gy + '" width="' + gw + '" height="104" rx="18" fill="#FFFFFF" stroke="#E4EAF4" stroke-width="2"/>';
    s += '<rect x="' + gx + '" y="' + gy + '" width="' + gw + '" height="5" rx="2.5" fill="' + [C.blue, C.yellow, C.red, C.dark][j] + '"/>';
    s += T(gx + 22, gy + 46, vp.items[j][0], { size: 23, fill: C.navy, ls: 1.6 });
    s += wrapText(vp.items[j][1], gx + 22, gy + 76, gw - 44, 18, 24, { w: 500, fill: '#6C7A91' });
  }

  /* Infos pratiques */
  s += '<rect x="' + M + '" y="1266" width="' + (W - 2 * M) + '" height="286" rx="26" fill="' + C.navy + '"/>';
  s += triBar(M, 1266, W - 2 * M, 8);
  s += T(M + 40, 1338, 'INFOS PRATIQUES', { size: 24, fill: C.yellow, ls: 5 });
  var infos = [
    ['Qui ?', 'Tous les jeunes de 16 à 35 ans, en Martinique.'],
    ['Combien ?', OPT.price ? 'Cotisation annuelle de 20 € (première adhésion et réadhésion).' : 'Une cotisation annuelle symbolique.'],
    ['Comment payer ?', 'Carte bancaire en ligne, virement ou espèces.'],
    ['Où ?', SITE + '  ·  ' + MAIL + '  ·  Tél. ' + TEL]
  ];
  for (var n = 0; n < infos.length; n++) {
    var iy = 1394 + n * 44;
    s += T(M + 40, iy, infos[n][0], { size: 23, fill: '#FFFFFF' });
    s += T(M + 40 + 210, iy, infos[n][1], { size: 23, w: 500, fill: '#C4D4F3' });
  }

  /* QR + pied */
  if (OPT.qr && ASSETS.qr) {
    s += '<image x="' + (W - M - 150) + '" y="1574" width="150" height="150" href="' + ASSETS.qr + '" xlink:href="' + ASSETS.qr + '"/>';
    s += T(W - M - 176, 1620, 'Scanne et adhère', { size: 24, fill: C.navy, anchor: 'end' });
    s += T(W - M - 176, 1656, SITE, { size: 22, fill: C.blue, anchor: 'end', w: 700 });
  }
  s += T(M, 1616, ADDR, { size: 22, w: 500, fill: '#98A5B8' });
  s += T(M, 1652, INSTA + '   ·   ' + MAIL, { size: 22, w: 600, fill: '#98A5B8' });
  s += wordmark(M, 1716, 44);
  s += triBar(0, H - 18, W, 18);
  return svgWrap(W, H, uid, s, embed);
}

/* =====================================================================
   11 bis. Layout — MOSAÏQUE DE PHOTOS
   Hommage direct au visuel d'adhésion MJA de novembre 2025 : bandeaux de
   photos de membres en haut et en bas, bloc central blanc filigrané,
   slogan en italique bleu, « INSCRIS-TOI ! » bordeaux + flèche vers le QR,
   signature jaune et bordeaux.
   ===================================================================== */
function photoTile(x, y, w, h, idx){
  var src = photoAt(PIDX + idx);
  return '<image x="' + x + '" y="' + y + '" width="' + w + '" height="' + h + '" href="' + src
    + '" xlink:href="' + src + '" preserveAspectRatio="xMidYMid slice"/>';
}

function renderMosaic(style, variant, uid, embed, W, H){
  var v = V(variant), M = W * 0.075;
  var band = H * 0.165, cTop = band, cBot = H - band;
  var tw = W / 3;
  var s = '<rect width="' + W + '" height="' + H + '" fill="#FFFFFF"/>';

  /* Bandeaux de photos */
  for (var i = 0; i < 3; i++) {
    s += photoTile(i * tw, 0, tw + 1, band, i);
    s += photoTile(i * tw, cBot, tw + 1, band, i + 3);
  }
  /* Bloc central — le filigrane est confiné entre les deux bandeaux. */
  s += '<rect x="0" y="' + cTop + '" width="' + W + '" height="' + (cBot - cTop) + '" fill="#FFFFFF"/>';
  s += '<defs><clipPath id="mid' + uid + '"><rect x="0" y="' + cTop + '" width="' + W + '" height="' + (cBot - cTop) + '"/></clipPath></defs>';
  s += '<g clip-path="url(#mid' + uid + ')">' + watermark(W, H, uid, embed, 0.075, W * 0.30) + '</g>';
  s += triBar(0, cTop, W, H * 0.008);
  s += triBar(0, cBot - H * 0.008, W, H * 0.008);

  /* Logo */
  var lg = H * 0.125;
  s += logoImg(W / 2 - lg / 2, cTop + H * 0.025, lg, embed);

  /* Slogan en italique (Gill Sans Italic, comme le « Relève le défi ! » 2025) */
  var slogan = v.slogan || v.t.join(' ');
  var ss = fitSize(slogan, W * 0.80, H * 0.115, 700, FAM_GS);
  s += T(W / 2, cTop + H * 0.235, slogan, { size: ss, fill: C.blue, anchor: 'middle', it: true, w: 700 });

  /* « INSCRIS-TOI ! » + flèche + QR */
  var isz = H * 0.040, ibase = cTop + H * 0.325;
  s += T(M, ibase, 'INSCRIS-TOI !', { size: isz, fill: C.bordeaux, w: 800, ls: isz * 0.02 });
  var qs = H * 0.175, qy = cTop + H * 0.345;
  if (OPT.qr && ASSETS.qr) {
    s += '<image x="' + M + '" y="' + qy + '" width="' + qs + '" height="' + qs
      + '" href="' + ASSETS.qr + '" xlink:href="' + ASSETS.qr + '"/>';
    var tx = M + measure('INSCRIS-TOI !', isz, 800, FAM_GS);
    s += curvedArrow(tx + W * 0.025, ibase - isz * 0.30, M + qs * 0.92, qy + qs * 0.10, H * 0.007, '#1A1A1A');
  } else {
    s += T(M, qy + qs * 0.3, OPT.url.replace(/^https?:\/\//, ''), { size: H * 0.026, fill: C.dark, w: 700 });
  }

  /* Paragraphe d'accroche */
  var para = v.para || v.sub || [TAG];
  s += TL(M + qs + W * 0.05, cTop + H * 0.40, para, { size: H * 0.024, w: 500, fill: '#2B2B2B', lh: H * 0.033 });

  /* Signature */
  var sig = v.signature || ["Madin' Jeunes Ambition", OPT.season];
  s += T(W / 2, cBot - H * 0.075, sig[0], { size: H * 0.048, fill: C.yellow, anchor: 'middle', it: true, w: 800 });
  s += T(W / 2, cBot - H * 0.022, sig[1], { size: H * 0.040, fill: C.bordeaux, anchor: 'middle', it: true, w: 800 });

  return svgWrap(W, H, uid, s, embed);
}

/* =====================================================================
   11 ter. Layout — BLOCS (composition éditoriale moderne)
   Aplats de couleur asymétriques dans lesquels le texte est réservé.
   Même palette MJA, écriture beaucoup plus contemporaine que 2025.
   ===================================================================== */
function renderBlocs(style, variant, uid, embed, W, H){
  var v = V(variant), M = W * 0.062, pad = W * 0.05;
  var s = '<rect width="' + W + '" height="' + H + '" fill="#FFFFFF"/>';

  /* Toutes les mesures dérivent de la LARGEUR : la composition tient donc
     aussi bien en carré qu'en story ou en A4. */
  var aw = W * 0.63, ah = W * 0.46, bw = W - aw, bh = ah / 2;

  /* Aplat principal : le titre, réservé en blanc */
  s += '<rect x="0" y="0" width="' + aw + '" height="' + ah + '" fill="' + C.navy + '"/>';
  var lines = v.t, tsz = (ah - pad * 2) / (lines.length * 1.06);
  for (var i = 0; i < lines.length; i++) tsz = Math.min(tsz, fitSize(lines[i], aw - pad * 2, tsz, 800, FAM_AG));
  var t0 = pad + tsz * 0.82 + (ah - pad * 2 - lines.length * tsz * 1.06) / 2;
  s += TL(pad, t0, lines, { size: tsz, f: 'ag', w: 800, fill: '#FFFFFF', lh: tsz * 1.06 });

  /* Aplat jaune : la saison — aplat bleu : le logo */
  s += '<rect x="' + aw + '" y="0" width="' + bw + '" height="' + bh + '" fill="' + C.yellow + '"/>';
  var sz = fitSize(OPT.season, bw - pad * 0.7, bh * 0.34, 800, FAM_AG);
  s += T(aw + bw / 2, bh / 2 + sz * 0.36, OPT.season, { size: sz, f: 'ag', w: 800, fill: C.navy, anchor: 'middle' });
  s += '<rect x="' + aw + '" y="' + bh + '" width="' + bw + '" height="' + bh + '" fill="' + C.blue + '"/>';
  var lg = Math.min(bw, bh) * 0.60;
  s += logoImg(aw + bw / 2 - lg / 2, bh + bh / 2 - lg / 2, lg, embed, true);

  /* Filet rouge de séparation */
  var rule = W * 0.012;
  s += '<rect x="0" y="' + ah + '" width="' + W + '" height="' + rule + '" fill="' + C.red + '"/>';

  /* ── Zone basse ────────────────────────────────────────────────
     Le bloc « lien + réseau » est ancré en bas, l'appel à l'action juste
     au-dessus, et le texte est centré dans l'espace qui reste.          */
  var qs = W * 0.155, gap = W * 0.045, textW = W - 2 * M - qs - gap;
  var kick = W * 0.024, subS = W * 0.030, chkS = W * 0.022;
  var ctaH = W * 0.085, ctaS = W * 0.036;

  var ctaTop = H - M - ctaH - W * 0.115;
  var urlY   = ctaTop + ctaH + W * 0.052;
  var instaY = urlY + W * 0.042;

  var subLines = v.sub || [TAG];
  var checks = (v.checks || v.pills || []).slice(0, 3);
  var subLH = subS * 1.42, chkStep = chkS * 2.0;
  var groupH = kick * 1.7 + subLines.length * subLH + W * 0.022 + checks.length * chkStep;
  var top = ah + rule;
  var gy = top + Math.max(W * 0.06, (ctaTop - top - groupH) / 2);

  s += TFit(M, gy, v.kicker, W - 2 * M, { size: kick, fill: C.blue, ls: kick * 0.18 });
  var cur = gy + kick * 1.7;
  s += TL(M, cur, subLines, { size: subS, w: 500, fill: '#4A5A73', lh: subLH });
  cur += subLines.length * subLH + W * 0.022;

  var chkTop = cur;
  for (var j = 0; j < checks.length; j++) {
    s += checkRow(M, cur + j * chkStep, checks[j], chkS, C.blue, '#4A5A73');
  }
  /* QR aligné sur le bloc d'arguments */
  if (OPT.qr && ASSETS.qr) {
    var qy = chkTop + (checks.length * chkStep) / 2 - qs / 2 - chkS;
    s += '<image x="' + (W - M - qs) + '" y="' + qy + '" width="' + qs + '" height="' + qs
      + '" href="' + ASSETS.qr + '" xlink:href="' + ASSETS.qr + '"/>';
    s += T(W - M - qs / 2, qy + qs + chkS * 1.1, 'SCANNE-MOI', { size: chkS * 0.8, fill: C.navy, anchor: 'middle', ls: 1 });
  }

  s += cta(M, ctaTop, W - 2 * M, ctaH, v.ctaShort || v.cta, C.navy, '#FFFFFF', ctaS);
  s += T(M, urlY, OPT.url.replace(/^https?:\/\//, ''), { size: W * 0.026, fill: C.dark, w: 700 });
  s += T(W - M, instaY, INSTA, { size: W * 0.022, fill: '#98A5B8', anchor: 'end', w: 600 });
  s += T(M, instaY, ORG, { size: W * 0.022, fill: '#98A5B8', w: 600, ls: 1 });
  return svgWrap(W, H, uid, s, embed);
}

/* =====================================================================
   12. Layout — BANNIÈRES horizontales
   ===================================================================== */
function renderWide(style, variant, uid, embed, W, H){
  var p = pal(style), v = V(variant), M = Math.round(H * 0.13);
  var bar = Math.max(6, H * 0.018);
  var s = bgMarkup(style, W, H, uid, embed);
  s += triBar(0, 0, W, bar);

  /* Bloc de droite : QR (ou, à défaut, le sigle) — définit la largeur du texte. */
  var qs = H * 0.42, pad = H * 0.03, box = qs + pad * 2;
  var rightX = W - M - box, qrY = H * 0.13;
  var hasQr = OPT.qr && ASSETS.qr;
  var colW = (hasQr ? rightX : W - M) - M - M * 0.6;

  /* Bloc de gauche : identité */
  var lg = H * 0.25;
  s += logoImg(M, H * 0.09, lg, embed, style === 'navy' || style === 'photo');
  s += T(M + lg + H * 0.05, H * 0.09 + H * 0.105, ORG, { size: H * 0.046, fill: p.fg, ls: H * 0.004 });
  s += T(M + lg + H * 0.05, H * 0.09 + H * 0.16, TAG, { size: H * 0.036, w: 500, fill: p.fg2 });

  /* Titre + saison */
  var title = (variant === 'readhesion') ? 'JE RENOUVELLE' : "MJ'ADHÉSION";
  var ts = fitSize(title, colW, H * 0.24, 800, FAM_AG);
  s += T(M, H * 0.55, title, { size: ts, f: 'ag', w: 800, fill: p.fg });
  s += T(M, H * 0.685, OPT.season, { size: H * 0.058, fill: p.alt, f: 'ag', w: 800, ls: 3 });

  /* Appel à l'action + lien */
  var label = v.ctaShort || v.cta, cs = H * 0.055;
  var cw = measure(label, cs, 800, FAM_AG) + cs * 1.6, ch = H * 0.145;
  s += cta(M, H * 0.775, cw, ch, label, p.ctaBg, p.ctaFg, cs);
  s += T(M + cw + H * 0.05, H * 0.775 + ch * 0.62, OPT.url.replace(/^https?:\/\//, ''),
         { size: H * 0.05, fill: p.accent, w: 700 });

  /* QR + mentions */
  if (hasQr) {
    s += '<rect x="' + rightX + '" y="' + qrY + '" width="' + box + '" height="' + box + '" rx="' + (box * 0.11) + '" fill="#FFFFFF"/>';
    s += '<image x="' + (rightX + pad) + '" y="' + (qrY + pad) + '" width="' + qs + '" height="' + qs + '" href="' + ASSETS.qr + '" xlink:href="' + ASSETS.qr + '"/>';
    s += T(rightX + box / 2, qrY + box + H * 0.10, 'SCANNE ET ADHÈRE', { size: H * 0.042, fill: p.qrLabel, anchor: 'middle', ls: 2 });
    s += T(rightX + box / 2, qrY + box + H * 0.175, INSTA, { size: H * 0.040, fill: p.foot, anchor: 'middle', w: 600 });
  } else {
    s += wordmark(W - M, H * 0.42, H * 0.26, 'end');
    s += T(W - M, H * 0.62, INSTA, { size: H * 0.042, fill: p.foot, anchor: 'end', w: 600 });
  }
  s += triBar(0, H - bar, W, bar);
  return svgWrap(W, H, uid, s, embed);
}

/* =====================================================================
   13. Catalogue des visuels
   ===================================================================== */
var POST = { w:1080, h:1080 }, STORY = { w:1080, h:1920 }, PAP = { w:1240, h:1754 };

var NAMES = {
  annonce:    "Annonce de campagne",
  readhesion: "Appel à la réadhésion",
  defi:       "Relève le défi !",
  benevole:   "Devenez bénévole !",
  pourquoi:   "4 bonnes raisons d'adhérer",
  comment:    "Les 3 étapes de l'adhésion",
  chiffres:   "MJA en chiffres",
  temoignage: "Parole de membre",
  verso:      "Flyer verso — infos complètes"
};

var CARDS = [];
var _nMosaic = 0, _nPhoto = 0;
/** Enregistre une série de visuels.
    Chaque visuel « Mosaïque » démarre 6 photos plus loin que le précédent
    (donc un jeu de photos différent) ; chaque visuel « Photo » prend la
    photo suivante de la bibliothèque. */
function add(group, dim, style, variants, extra){
  variants.forEach(function(variant){
    var c = { group:group, style:style, variant:variant, w:dim.w, h:dim.h,
              name: NAMES[variant] || variant, id:'c' + (CARDS.length + 1) };
    if (style === 'mosaic')     { c.pidx = _nMosaic * 6; _nMosaic++; }
    else if (style === 'photo') { c.pidx = _nPhoto;      _nPhoto++;  }
    if (extra) Object.keys(extra).forEach(function(k){ c[k] = extra[k]; });
    CARDS.push(c);
  });
}

/* ── Posts carrés ───────────────────────────────────────────────── */
add('post', POST, 'blanc',  ['annonce','readhesion','defi','pourquoi','comment','chiffres','temoignage']);
add('post', POST, 'navy',   ['annonce','readhesion','defi','pourquoi','comment','chiffres']);
add('post', POST, 'pastel', ['annonce','benevole','defi','pourquoi']);
add('post', POST, 'typo',   ['annonce','readhesion','defi']);
add('post', POST, 'blocs',  ['annonce','readhesion','defi']);
add('post', POST, 'mosaic', ['defi','annonce']);
add('post', POST, 'photo',  ['annonce','readhesion','temoignage']);

/* ── Stories ────────────────────────────────────────────────────── */
add('story', STORY, 'navy',   ['annonce','readhesion','defi','comment']);
add('story', STORY, 'blanc',  ['annonce','readhesion','comment']);
add('story', STORY, 'pastel', ['annonce','benevole']);
add('story', STORY, 'typo',   ['annonce','readhesion','defi']);
add('story', STORY, 'blocs',  ['annonce','defi']);
add('story', STORY, 'photo',  ['annonce','readhesion']);

/* ── Affiches (ratio A → A5 / A4 / A3) ──────────────────────────── */
add('affiche', PAP, 'blanc',  ['annonce','readhesion','defi','benevole'], { paper:true });
add('affiche', PAP, 'navy',   ['annonce','readhesion','defi'],            { paper:true });
add('affiche', PAP, 'pastel', ['annonce','benevole'],                     { paper:true });
add('affiche', PAP, 'typo',   ['annonce','readhesion','defi'],            { paper:true });
add('affiche', PAP, 'blocs',  ['annonce','defi'],                         { paper:true });
add('affiche', PAP, 'mosaic', ['defi'],                                   { paper:true });
add('affiche', PAP, 'photo',  ['annonce','readhesion'],                   { paper:true });

/* ── Flyers A5 ──────────────────────────────────────────────────── */
add('flyer', PAP, 'blanc',  ['annonce'], { paper:true, suffix:'recto' });
add('flyer', PAP, 'navy',   ['annonce'], { paper:true, suffix:'recto' });
add('flyer', PAP, 'pastel', ['benevole'],{ paper:true, suffix:'recto' });
add('flyer', PAP, 'mosaic', ['defi'],    { paper:true, suffix:'recto' });
add('flyer', PAP, 'blocs',  ['annonce'], { paper:true, suffix:'recto' });
add('flyer', PAP, 'photo',  ['annonce'], { paper:true, suffix:'recto' });
add('flyer', PAP, 'blanc',  ['verso'],   { paper:true, verso:true });

/* ── Bannières ──────────────────────────────────────────────────── */
add('banner', { w:1640, h:624 }, 'navy',   ['annonce','defi'],  { wide:true, label:'Couverture Facebook' });
add('banner', { w:1640, h:624 }, 'blanc',  ['annonce'],         { wide:true, label:'Couverture Facebook' });
add('banner', { w:1640, h:624 }, 'pastel', ['benevole'],        { wide:true, label:'Couverture Facebook' });
add('banner', { w:1640, h:624 }, 'photo',  ['annonce'],         { wide:true, label:'Couverture Facebook' });
add('banner', { w:1200, h:630 }, 'navy',   ['annonce'],         { wide:true, label:'Image de partage' });
add('banner', { w:1200, h:630 }, 'blanc',  ['readhesion'],      { wide:true, label:'Image de partage' });

/* ── Vidéos motion design ───────────────────────────────────────── */
add('motion', POST,               'navy',   ['annonce','defi'],   { motion:true, label:'Vidéo carrée' });
add('motion', POST,               'blanc',  ['readhesion'],       { motion:true, label:'Vidéo carrée' });
add('motion', POST,               'pastel', ['benevole'],         { motion:true, label:'Vidéo carrée' });
add('motion', STORY,              'navy',   ['annonce','defi'],   { motion:true, label:'Vidéo story / reel' });
add('motion', STORY,              'blanc',  ['readhesion'],       { motion:true, label:'Vidéo story / reel' });
add('motion', { w:1920, h:1080 }, 'navy',   ['annonce'],          { motion:true, label:'Vidéo 16:9 (écran, YouTube)' });

var STYLE_LABEL = {
  blanc:'Fond blanc', navy:'Fond navy', typo:'Typo XXL', photo:'Photo',
  pastel:'Pastel', mosaic:'Mosaïque', blocs:'Blocs'
};
var GROUP_LABEL = { post:'Post', story:'Story', affiche:'Affiche', flyer:'Flyer', banner:'Bannière', motion:'Vidéo' };

/** Compose le SVG d'un visuel. `embed` = ressources en base64 (export). */
function buildSvg(card, embed){
  var uid = '-' + card.id + (embed ? 'x' : '');
  PIDX = card.pidx || 0;
  EMBED_PHOTOS = !!embed;
  if (card.verso)  return renderVerso(card.style, card.variant, uid, embed);
  if (card.style === 'mosaic') return renderMosaic(card.style, card.variant, uid, embed, card.w, card.h);
  if (card.style === 'blocs' && !card.wide) return renderBlocs(card.style, card.variant, uid, embed, card.w, card.h);
  if (card.wide)   return renderWide(card.style, card.variant, uid, embed, card.w, card.h);
  if (card.paper)  return renderPaper(card.style, card.variant, uid, embed);
  if (card.h === 1920) return renderStory(card.style, card.variant, uid, embed);
  return renderPost(card.style, card.variant, uid, embed);
}

/* =====================================================================
   14. Motion design — storyboard paramétrique
   Chaque élément a sa fenêtre d'apparition ; `motionFrame(card, t)` rend
   l'image à l'instant t ∈ [0,1]. Le même code sert à l'aperçu animé dans
   la page et à l'encodage de la vidéo, image par image.
   ===================================================================== */
var MOTION_DUR = 5.0;   /* secondes d'animation */
var MOTION_HOLD = 0.7;  /* maintien de la dernière image */
var MOTION_FPS = 25;

function ease(x){ x = x < 0 ? 0 : (x > 1 ? 1 : x); return 1 - Math.pow(1 - x, 3); }
/** Progression d'un segment [a,b] de la timeline. */
function seg(t, a, b){ return ease((t - a) / (b - a)); }
/** Rebond léger pour les apparitions « pop ». */
function pop(x){ return x >= 1 ? 1 : 1 + 0.14 * Math.sin(Math.PI * ease(x)) ; }

/** Enveloppe animée : opacité, translation, échelle autour d'un point. */
function anim(inner, o){
  var tr = '';
  if (o.dx || o.dy) tr += 'translate(' + (o.dx || 0).toFixed(2) + ',' + (o.dy || 0).toFixed(2) + ') ';
  if (o.k !== undefined && Math.abs(o.k - 1) > 0.0005) {
    tr += 'translate(' + o.cx.toFixed(2) + ',' + o.cy.toFixed(2) + ') scale(' + o.k.toFixed(4) + ') '
        + 'translate(' + (-o.cx).toFixed(2) + ',' + (-o.cy).toFixed(2) + ') ';
  }
  return '<g' + (tr ? ' transform="' + tr + '"' : '')
    + (o.op !== undefined ? ' opacity="' + Math.max(0, Math.min(1, o.op)).toFixed(3) + '"' : '') + '>'
    + inner + '</g>';
}

function motionFrame(card, t, embed){
  var W = card.w, H = card.h, uid = '-m' + card.id + (embed ? 'x' : '');
  var style = card.style, p = pal(style), v = V(card.variant);
  PIDX = card.pidx || 0;
  EMBED_PHOTOS = !!embed;
  /* Unité typographique commune : la plus petite dimension. La composition
     garde ainsi les mêmes proportions en carré, en 9:16 et en 16:9. */
  var U = Math.min(W, H), cx = W / 2, M = W * 0.07;
  var s = bgMarkup(style, W, H, uid, embed);

  /* ── Mesures du bloc de contenu, pour le centrer verticalement ── */
  var lg = U * 0.155;
  var orgS = U * 0.030, tagS = U * 0.022, kickS = U * 0.026;
  var lines = v.t, tmax = U * 0.135, tsz = tmax;
  for (var i = 0; i < lines.length; i++) tsz = Math.min(tsz, fitSize(lines[i], W * 0.84, tsz, 800, FAM_AG));
  var lh = tsz * 1.06;
  var seasS = U * 0.034;

  /* Pastilles : on réduit la taille jusqu'à ce que la rangée tienne. */
  var pills = v.pills || [], psz = U * 0.024, gapP = W * 0.014, totP = 0;
  for (var g = 0; g < 8; g++) {
    totP = 0;
    for (var a = 0; a < pills.length; a++) totP += pillWidth(pills[a], psz) + gapP;
    totP -= gapP;
    if (totP <= W * 0.90 || psz < U * 0.012) break;
    psz *= 0.93;
  }
  var pillH = pills.length ? psz * 2.3 : 0;

  var label = v.ctaShort || v.cta, ctaS = U * 0.036, ctaH = U * 0.088;
  var ctaW = Math.min(W - 2 * M, measure(label, ctaS, 800, FAM_AG) + ctaS * 2.6);
  var urlS = U * 0.026, instaS = U * 0.024;

  var total = lg + U * 0.062 + tagS * 1.5 + U * 0.075
            + U * 0.11 + (lines.length - 1) * lh + U * 0.085
            + U * 0.035 + pillH + U * 0.075 + ctaH + U * 0.055 + U * 0.048;
  var y = Math.max(H * 0.05, (H - total) * 0.46);

  /* Filet tricolore : essuyage depuis la gauche (haut) et la droite (bas). */
  var bar = U * 0.013, wipe = seg(t, 0, 0.32);
  s += '<defs><clipPath id="wt' + uid + '"><rect x="0" y="0" width="' + (W * wipe) + '" height="' + bar + '"/></clipPath>'
     + '<clipPath id="wb' + uid + '"><rect x="' + (W * (1 - wipe)) + '" y="' + (H - bar) + '" width="' + (W * wipe) + '" height="' + bar + '"/></clipPath></defs>';
  s += '<g clip-path="url(#wt' + uid + ')">' + triBar(0, 0, W, bar) + '</g>';
  s += '<g clip-path="url(#wb' + uid + ')">' + triBar(0, H - bar, W, bar) + '</g>';

  /* 1. Logo — zoom léger + fondu */
  var a1 = seg(t, 0.00, 0.20);
  s += anim(logoImg(cx - lg / 2, y, lg, embed, style === 'navy' || style === 'photo'),
            { op:a1, k:0.72 + 0.28 * pop(a1), cx:cx, cy:y + lg / 2 });
  y += lg;

  /* 2. Identité */
  var a2 = seg(t, 0.10, 0.28);
  y += U * 0.062;
  s += anim(TFit(cx, y, ORG, W * 0.86, { size:orgS, fill:p.fg, anchor:'middle', ls:orgS * 0.055 }), { op:a2, dy:(1 - a2) * U * 0.03 });
  y += tagS * 1.5;
  s += anim(TFit(cx, y, TAG, W * 0.86, { size:tagS, w:500, fill:p.fg2, anchor:'middle' }), { op:a2, dy:(1 - a2) * U * 0.03 });

  /* 3. Accroche */
  var a3 = seg(t, 0.20, 0.36);
  y += U * 0.075;
  s += anim(TFit(cx, y, v.kicker, W * 0.86, { size:kickS, fill:p.kicker, anchor:'middle', ls:kickS * 0.17 }),
            { op:a3, dy:(1 - a3) * U * 0.025 });

  /* 4. Titre en cascade + soulignement jaune qui se déploie */
  y += U * 0.11;
  var lastY = y + (lines.length - 1) * lh;
  var uw = measure(lines[lines.length - 1], tsz, 800, FAM_AG), au = seg(t, 0.50, 0.66);
  s += '<rect x="' + (cx - uw / 2) + '" y="' + (lastY + tsz * 0.14) + '" width="' + (uw * au)
     + '" height="' + (tsz * 0.13) + '" rx="' + (tsz * 0.05) + '" fill="' + C.yellow + '"/>';
  for (var j = 0; j < lines.length; j++) {
    var aj = seg(t, 0.28 + j * 0.08, 0.46 + j * 0.08);
    s += anim(T(cx, y + j * lh, lines[j], { size:tsz, f:'ag', w:800, fill:p.fg, anchor:'middle' }),
              { op:aj, dy:(1 - aj) * U * 0.045 });
  }
  y = lastY;

  /* 5. Saison */
  var a5 = seg(t, 0.46, 0.60);
  y += U * 0.085;
  s += anim(T(cx, y, OPT.season, { size:seasS, fill:p.alt, anchor:'middle', f:'ag', w:800, ls:2 }),
            { op:a5, k:0.86 + 0.14 * pop(a5), cx:cx, cy:y });

  /* 6. Pastilles, une par une */
  y += U * 0.035;
  var px = cx - totP / 2;
  for (var b = 0; b < pills.length; b++) {
    var ab = seg(t, 0.54 + b * 0.05, 0.66 + b * 0.05), pw = pillWidth(pills[b], psz);
    s += anim(pill(px, y, pills[b], psz, p.pillBg, p.pillFg),
              { op:ab, k:0.72 + 0.28 * pop(ab), cx:px + pw / 2, cy:y + psz * 1.15 });
    px += pw + gapP;
  }
  y += pillH;

  /* 7. Appel à l'action (pulsation discrète en fin d'animation) */
  var a7 = seg(t, 0.68, 0.82), puls = t > 0.86 ? 1 + 0.018 * Math.sin((t - 0.86) * 26) : 1;
  y += U * 0.075;
  s += anim(cta(cx - ctaW / 2, y, ctaW, ctaH, label, p.ctaBg, p.ctaFg, ctaS),
            { op:a7, k:(0.86 + 0.14 * pop(a7)) * puls, cx:cx, cy:y + ctaH / 2 });

  /* 8. QR, aligné sur le bouton */
  if (OPT.qr && ASSETS.qr) {
    var qs = U * 0.115, qx = W - M - qs, qy = y + ctaH / 2 - qs / 2, aq = seg(t, 0.84, 0.96);
    s += anim('<rect x="' + (qx - qs * 0.1) + '" y="' + (qy - qs * 0.1) + '" width="' + (qs * 1.2) + '" height="' + (qs * 1.2)
            + '" rx="' + (qs * 0.14) + '" fill="#FFFFFF"/>'
            + '<image x="' + qx + '" y="' + qy + '" width="' + qs + '" height="' + qs
            + '" href="' + ASSETS.qr + '" xlink:href="' + ASSETS.qr + '"/>',
              { op:aq, k:0.7 + 0.3 * pop(aq), cx:qx + qs / 2, cy:qy + qs / 2 });
  }
  y += ctaH;

  /* 9. Lien + réseau */
  y += U * 0.055;
  s += anim(TFit(cx, y, OPT.url.replace(/^https?:\/\//, ''), W * 0.8, { size:urlS, fill:p.accent, anchor:'middle', w:700 }),
            { op:seg(t, 0.78, 0.92) });
  y += U * 0.048;
  s += anim(T(cx, y, INSTA, { size:instaS, fill:p.foot, anchor:'middle', w:600 }), { op:seg(t, 0.88, 1.0) });

  return svgWrap(W, H, uid, s, embed);
}

/* =====================================================================
   15. Exports — PNG, PDF, vidéo
   ===================================================================== */
var MM_PT = 72 / 25.4;
/* A3 est à 220 dpi : à 300 dpi le canvas dépasse la limite de surface de
   certains navigateurs. 220 dpi reste largement suffisant pour une affiche. */
var PAPER = {
  A5: { mm:[148, 210], dpi:300 },
  A4: { mm:[210, 297], dpi:300 },
  A3: { mm:[297, 420], dpi:220 }
};
function paperPx(fmt){ var d = PAPER[fmt]; return [Math.round(d.mm[0] / 25.4 * d.dpi), Math.round(d.mm[1] / 25.4 * d.dpi)]; }
function paperPt(fmt){ var d = PAPER[fmt]; return [d.mm[0] * MM_PT, d.mm[1] * MM_PT]; }

/* Le navigateur rasterise un SVG chargé dans une <img> à sa taille
   intrinsèque : on réécrit width/height à la taille de sortie pour un rendu
   net, le viewBox conservant les coordonnées de mise en page. */
function sizeSvg(svg, w, h){
  return svg.replace(/width="[\d.]+" height="[\d.]+"/, 'width="' + w + '" height="' + h + '"');
}
/** Un SVG chargé dans une <img> est parsé en XML STRICT : la moindre erreur
    de syntaxe le fait échouer silencieusement. On rejoue donc le parsing avec
    DOMParser pour remonter la cause exacte au lieu d'un message opaque. */
function xmlProblem(svg){
  try {
    var doc = new DOMParser().parseFromString(svg, 'image/svg+xml');
    var err = doc.querySelector('parsererror');
    if (err) return err.textContent.replace(/\s+/g, ' ').slice(0, 300);
  } catch (e) { return e.message; }
  return null;
}
/** SVG → data-URI base64 (UTF-8).
    On n'utilise PAS de blob: URL : la CSP du site autorise `img-src 'self' data:`
    mais pas `blob:`, une <img src="blob:…"> serait donc bloquée. Le base64
    est aussi bien plus compact que l'encodage pour-cent sur du texte accentué. */
function svgDataUri(svg){
  return 'data:image/svg+xml;base64,' + bufToB64(new TextEncoder().encode(svg).buffer);
}
function loadSvgImage(svg){
  return new Promise(function(resolve, reject){
    var img = new Image();
    img.onload  = function(){ resolve({ img:img, url:null }); };
    img.onerror = function(){
      var why = xmlProblem(svg);
      reject(new Error(why ? 'SVG invalide — ' + why : "Le navigateur a refusé de charger le visuel."));
    };
    img.src = svgDataUri(svg);
  });
}
async function rasterize(svg, outW, outH, reuse){
  var r = await loadSvgImage(sizeSvg(svg, outW, outH));
  var cv = reuse || document.createElement('canvas');
  cv.width = outW; cv.height = outH;
  var ctx = cv.getContext('2d');
  ctx.fillStyle = '#FFFFFF'; ctx.fillRect(0, 0, outW, outH);
  ctx.drawImage(r.img, 0, 0, outW, outH);
  return cv;
}
function saveBlob(blob, filename){
  var a = document.createElement('a');
  a.href = URL.createObjectURL(blob);
  a.download = filename;
  document.body.appendChild(a); a.click(); a.remove();
  setTimeout(function(){ URL.revokeObjectURL(a.href); }, 6000);
}
function slug(s){
  return String(s).toLowerCase().normalize('NFD').replace(/[̀-ͯ]/g, '')
    .replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
}
function fileBase(card){
  return 'mjadhesion-' + card.group + '-' + slug(card.variant) + '-' + card.style
       + (card.suffix ? '-' + card.suffix : '');
}

/** SVG d'un visuel : dernière image du storyboard pour les vidéos. */
function svgFor(card, embed){
  return card.motion ? motionFrame(card, 1, embed) : buildSvg(card, embed);
}
async function exportPng(card, outW, outH, suffix){
  var cv = await rasterize(svgFor(card, true), outW, outH);
  await new Promise(function(res){
    cv.toBlob(function(b){ saveBlob(b, fileBase(card) + (suffix || '') + '.png'); res(); }, 'image/png');
  });
}

/** PDF minimal (1 page, 1 image JPEG DCTDecode) — aucune librairie externe.
    `box` = {x, y, w, h} en points : position de l'image sur la page. */
function jpegToPdf(jpegDataUrl, pxW, pxH, ptW, ptH, box){
  box = box || { x:0, y:0, w:ptW, h:ptH };
  var bin = atob(jpegDataUrl.split(',')[1]);
  var objs = [];
  objs.push('<< /Type /Catalog /Pages 2 0 R >>');
  objs.push('<< /Type /Pages /Kids [3 0 R] /Count 1 >>');
  objs.push('<< /Type /Page /Parent 2 0 R /MediaBox [0 0 ' + ptW.toFixed(2) + ' ' + ptH.toFixed(2) + ']'
    + ' /Resources << /XObject << /Im0 5 0 R >> >> /Contents 4 0 R >>');
  var cs = 'q ' + box.w.toFixed(2) + ' 0 0 ' + box.h.toFixed(2) + ' '
    + box.x.toFixed(2) + ' ' + box.y.toFixed(2) + ' cm /Im0 Do Q';
  objs.push('<< /Length ' + cs.length + ' >>\nstream\n' + cs + '\nendstream');
  objs.push('<< /Type /XObject /Subtype /Image /Width ' + pxW + ' /Height ' + pxH
    + ' /ColorSpace /DeviceRGB /BitsPerComponent 8 /Filter /DCTDecode /Length ' + bin.length + ' >>\nstream\n' + bin + '\nendstream');

  var out = '%PDF-1.4\n%\xE2\xE3\xCF\xD3\n', offsets = [];
  for (var i = 0; i < objs.length; i++) {
    offsets.push(out.length);
    out += (i + 1) + ' 0 obj\n' + objs[i] + '\nendobj\n';
  }
  var xref = out.length;
  out += 'xref\n0 ' + (objs.length + 1) + '\n0000000000 65535 f \n';
  for (var j = 0; j < offsets.length; j++) out += ('0000000000' + offsets[j]).slice(-10) + ' 00000 n \n';
  out += 'trailer\n<< /Size ' + (objs.length + 1) + ' /Root 1 0 R >>\nstartxref\n' + xref + '\n%%EOF';

  var bytes = new Uint8Array(out.length);
  for (var k = 0; k < out.length; k++) bytes[k] = out.charCodeAt(k) & 0xFF;
  return new Blob([bytes], { type:'application/pdf' });
}

async function exportPdf(card, fmt){
  var svg = svgFor(card, true), pt, px, box, cv;
  if (card.paper) {
    /* Format papier : le visuel occupe toute la page (même ratio A). */
    px = paperPx(fmt); pt = paperPt(fmt);
    cv = await rasterize(svg, px[0], px[1]);
    box = { x:0, y:0, w:pt[0], h:pt[1] };
  } else {
    /* Visuel web : page A4 dans l'orientation adaptée, visuel centré avec
       10 mm de marge — jamais déformé. */
    var a4 = paperPt('A4'), landscape = card.w > card.h;
    pt = landscape ? [a4[1], a4[0]] : [a4[0], a4[1]];
    var marg = 10 * MM_PT;
    var k = Math.min((pt[0] - 2 * marg) / card.w, (pt[1] - 2 * marg) / card.h);
    box = { w:card.w * k, h:card.h * k, x:0, y:0 };
    box.x = (pt[0] - box.w) / 2; box.y = (pt[1] - box.h) / 2;
    var scale = (box.w / 72 * 300) / card.w;   /* 300 dpi de la taille imprimée */
    cv = await rasterize(svg, Math.round(card.w * scale), Math.round(card.h * scale));
    px = [cv.width, cv.height];
  }
  saveBlob(jpegToPdf(cv.toDataURL('image/jpeg', 0.94), px[0], px[1], pt[0], pt[1], box),
           fileBase(card) + '-' + (card.paper ? fmt : 'A4') + '.pdf');
}

/** Format vidéo le mieux supporté par le navigateur. */
function pickVideoMime(){
  var cands = ['video/mp4;codecs=avc1.42E01E', 'video/mp4',
               'video/webm;codecs=vp9', 'video/webm;codecs=vp8', 'video/webm'];
  if (!window.MediaRecorder) return null;
  for (var i = 0; i < cands.length; i++) {
    if (MediaRecorder.isTypeSupported(cands[i])) return cands[i];
  }
  return null;
}
/**
 * Export vidéo en deux temps :
 *  1. rendu de chaque image du storyboard en JPEG (aussi lent qu'il faut) ;
 *  2. relecture à la cadence réelle dans un canvas enregistré par
 *     MediaRecorder — la durée de la vidéo est donc exacte.
 */
async function exportVideo(card, onProgress){
  var mime = pickVideoMime();
  if (!mime) throw new Error("Ce navigateur ne sait pas enregistrer de vidéo (essaie Chrome ou Edge).");

  var W = card.w, H = card.h, n = Math.round(MOTION_FPS * MOTION_DUR), frames = [];
  var work = document.createElement('canvas');
  for (var i = 0; i < n; i++) {
    var cv = await rasterize(motionFrame(card, i / (n - 1), true), W, H, work);
    frames.push(await new Promise(function(r){ cv.toBlob(r, 'image/jpeg', 0.93); }));
    if (onProgress && i % 8 === 0) onProgress('Rendu ' + (i + 1) + '/' + n + ' images…');
  }

  var out = document.createElement('canvas');
  out.width = W; out.height = H;
  var ctx = out.getContext('2d');
  ctx.fillStyle = '#FFFFFF'; ctx.fillRect(0, 0, W, H);

  var stream = out.captureStream(MOTION_FPS);
  var rec = new MediaRecorder(stream, { mimeType:mime, videoBitsPerSecond:Math.min(14e6, Math.round(W * H * 0.11)) });
  var chunks = [];
  rec.ondataavailable = function(e){ if (e.data && e.data.size) chunks.push(e.data); };
  var done = new Promise(function(r){ rec.onstop = r; });
  rec.start();

  if (onProgress) onProgress('Encodage de la vidéo…');
  var t0 = performance.now();
  for (var f = 0; f < n; f++) {
    var bm = await createImageBitmap(frames[f]);
    var wait = (t0 + f * 1000 / MOTION_FPS) - performance.now();
    if (wait > 0) await new Promise(function(r){ setTimeout(r, wait); });
    ctx.drawImage(bm, 0, 0);
    if (bm.close) bm.close();
  }
  await new Promise(function(r){ setTimeout(r, MOTION_HOLD * 1000); });
  rec.stop();
  await done;
  stream.getTracks().forEach(function(tr){ tr.stop(); });

  var ext = mime.indexOf('mp4') >= 0 ? '.mp4' : '.webm';
  saveBlob(new Blob(chunks, { type:mime }), fileBase(card) + ext);
  return ext;
}

/* =====================================================================
   16. Galerie
   ===================================================================== */
function cardMarkup(card){
  var tags = '<b>' + (card.label || GROUP_LABEL[card.group]) + '</b><b>' + STYLE_LABEL[card.style] + '</b>'
    + '<b class="px">' + card.w + ' × ' + card.h + '</b>';
  var rows = '';
  if (card.motion) {
    rows += '<div class="dlrow"><u>Vidéo</u>'
      + '<button class="btn ghost" data-act="play"><i class="fas fa-play"></i> Aperçu animé</button>'
      + '<button class="btn pdf" data-act="video"><i class="fas fa-film"></i> Télécharger la vidéo</button></div>';
    rows += '<div class="dlrow"><u>PNG</u>'
      + '<button class="btn png" data-act="png" data-fmt="x1">Image finale ' + card.w + ' px</button></div>';
  } else if (card.paper) {
    rows += '<div class="dlrow"><u>PNG</u>'
      + '<button class="btn png" data-act="png" data-fmt="A5">A5 ' + PAPER.A5.dpi + ' dpi</button>'
      + '<button class="btn png" data-act="png" data-fmt="A4">A4 ' + PAPER.A4.dpi + ' dpi</button>'
      + '<button class="btn png" data-act="png" data-fmt="A3">A3 ' + PAPER.A3.dpi + ' dpi</button></div>';
    rows += '<div class="dlrow"><u>PDF</u>'
      + '<button class="btn pdf" data-act="pdf" data-fmt="A5"><i class="fas fa-file-pdf"></i> A5</button>'
      + '<button class="btn pdf" data-act="pdf" data-fmt="A4"><i class="fas fa-file-pdf"></i> A4</button>'
      + '<button class="btn pdf" data-act="pdf" data-fmt="A3"><i class="fas fa-file-pdf"></i> A3</button></div>';
  } else {
    rows += '<div class="dlrow"><u>PNG</u>'
      + '<button class="btn png" data-act="png" data-fmt="x1">' + card.w + ' px</button>'
      + '<button class="btn png" data-act="png" data-fmt="x2">×2</button>'
      + '<button class="btn pdf" data-act="pdf" data-fmt="A4"><i class="fas fa-file-pdf"></i> PDF</button></div>';
  }
  return '<article class="card" data-id="' + card.id + '" data-group="' + card.group + '" data-style="' + card.style + '">'
    + '<div class="art" data-act="zoom"><div class="ph"></div></div>'
    + '<div class="meta"><h3>' + esc(card.name) + '</h3><div class="tags">' + tags + '</div></div>'
    + '<div class="dl">' + rows + '</div>'
    + '</article>';
}

function renderGallery(){
  var byGroup = { post:'', story:'', affiche:'', flyer:'', banner:'', motion:'' };
  CARDS.forEach(function(c){ byGroup[c.group] += cardMarkup(c); });
  Object.keys(byGroup).forEach(function(g){
    var el = document.getElementById('gal-' + g);
    if (el) el.innerHTML = byGroup[g];
  });
}

/* Rendu paresseux : un aperçu n'est composé que lorsqu'il approche de
   l'écran — indispensable avec une soixantaine de visuels. */
var drawn = {}, io = null;
function drawCard(id){
  var card = cardById(id), el = document.querySelector('.card[data-id="' + id + '"] .art');
  if (!card || !el) return;
  el.innerHTML = svgFor(card, false);
  drawn[id] = true;
}
function setupLazy(){
  if (io) io.disconnect();
  drawn = {};
  io = new IntersectionObserver(function(entries){
    entries.forEach(function(e){
      if (e.isIntersecting) { drawCard(e.target.dataset.id); io.unobserve(e.target); }
    });
  }, { rootMargin:'400px 0px' });
  document.querySelectorAll('.card').forEach(function(el){ io.observe(el); });
}
/** Redessine les aperçus déjà visibles (après changement d'option). */
function refreshArt(){
  Object.keys(drawn).forEach(function(id){ drawCard(id); });
}
function cardById(id){
  for (var i = 0; i < CARDS.length; i++) if (CARDS[i].id === id) return CARDS[i];
  return null;
}

/* =====================================================================
   17. Interactions
   ===================================================================== */
var filters = { group:'all', style:'all' };
function applyFilters(){
  document.querySelectorAll('.card').forEach(function(el){
    var okG = filters.group === 'all' || el.dataset.group === filters.group;
    var okS = filters.style === 'all' || el.dataset.style === filters.style;
    el.hidden = !(okG && okS);
  });
  document.querySelectorAll('section.block').forEach(function(sec){
    sec.hidden = sec.querySelectorAll('.card:not([hidden])').length === 0;
  });
}
function visibleCards(){
  return CARDS.filter(function(c){
    var el = document.querySelector('.card[data-id="' + c.id + '"]');
    return el && !el.hidden;
  });
}

document.querySelectorAll('.chip').forEach(function(btn){
  btn.addEventListener('click', function(){
    var f = btn.dataset.filter;
    document.querySelectorAll('.chip[data-filter="' + f + '"]').forEach(function(b){ b.classList.remove('on'); });
    btn.classList.add('on');
    filters[f] = btn.dataset.value;
    applyFilters();
  });
});

/* Aperçu animé : une seule carte à la fois, ~30 images/s. */
var playing = null;
function stopPreview(){
  if (!playing) return;
  cancelAnimationFrame(playing.raf);
  drawCard(playing.id);
  playing = null;
}
function playPreview(card, el){
  stopPreview();
  var t0 = performance.now(), last = -1;
  playing = { id:card.id, raf:0 };
  (function step(now){
    var t = Math.min(1, (now - t0) / (MOTION_DUR * 1000));
    if (t - last > 0.028 || t === 1) { el.innerHTML = motionFrame(card, t, false); last = t; }
    if (t < 1) playing.raf = requestAnimationFrame(step);
    else playing = null;
  })(t0);
}

document.addEventListener('click', async function(e){
  var btn = e.target.closest('[data-act]');
  if (!btn) return;
  var cardEl = btn.closest('.card');
  if (!cardEl) return;
  var card = cardById(cardEl.dataset.id);
  if (!card) return;
  var act = btn.dataset.act;

  if (act === 'zoom') {
    if (!drawn[card.id]) drawCard(card.id);
    document.getElementById('lb-box').innerHTML = svgFor(card, false);
    document.getElementById('lightbox').classList.add('on');
    return;
  }
  if (act === 'play') { playPreview(card, cardEl.querySelector('.art')); return; }

  btn.disabled = true;
  try {
    if (act === 'png') {
      if (card.paper) {
        var px = paperPx(btn.dataset.fmt);
        await exportPng(card, px[0], px[1], '-' + btn.dataset.fmt + '-' + PAPER[btn.dataset.fmt].dpi + 'dpi');
      } else {
        var m = btn.dataset.fmt === 'x2' ? 2 : 1;
        await exportPng(card, card.w * m, card.h * m, m === 2 ? '-x2' : '');
      }
      toast('Téléchargement lancé.');
    } else if (act === 'pdf') {
      await exportPdf(card, btn.dataset.fmt);
      toast('Téléchargement lancé.');
    } else if (act === 'video') {
      var ext = await exportVideo(card, toast);
      toast('Vidéo ' + ext.replace('.', '').toUpperCase() + ' téléchargée.');
    }
  } catch (err) {
    console.error(err);
    toast('Erreur : ' + err.message);
  }
  btn.disabled = false;
});

document.getElementById('lb-close').addEventListener('click', function(){
  document.getElementById('lightbox').classList.remove('on');
});
document.getElementById('lightbox').addEventListener('click', function(e){
  if (e.target === this) this.classList.remove('on');
});
document.addEventListener('keydown', function(e){
  if (e.key === 'Escape') { document.getElementById('lightbox').classList.remove('on'); stopPreview(); }
});

/* Téléchargements groupés (séquentiels, pour ne pas saturer le navigateur) */
document.getElementById('btn-all-png').addEventListener('click', async function(){
  var list = visibleCards().filter(function(c){ return !c.motion; }), btn = this;
  if (!list.length) { toast('Aucun visuel dans la sélection.'); return; }
  btn.disabled = true;
  for (var i = 0; i < list.length; i++) {
    var c = list[i];
    toast('PNG ' + (i + 1) + '/' + list.length + '…');
    if (c.paper) { var px = paperPx('A4'); await exportPng(c, px[0], px[1], '-A4-300dpi'); }
    else await exportPng(c, c.w, c.h, '');
    await new Promise(function(r){ setTimeout(r, 320); });
  }
  btn.disabled = false;
  toast(list.length + ' visuels téléchargés.');
});
document.getElementById('btn-all-pdf').addEventListener('click', async function(){
  var list = visibleCards().filter(function(c){ return c.paper; }), btn = this;
  if (!list.length) { toast('Aucun format papier dans la sélection.'); return; }
  btn.disabled = true;
  for (var i = 0; i < list.length; i++) {
    toast('PDF ' + (i + 1) + '/' + list.length + '…');
    await exportPdf(list[i], 'A4');
    await new Promise(function(r){ setTimeout(r, 320); });
  }
  btn.disabled = false;
  toast(list.length + ' PDF téléchargés.');
});

/* Panneau d'options */
var deb;
function onOptChange(regenQr){
  clearTimeout(deb);
  deb = setTimeout(function(){ if (regenQr) makeQr(); refreshArt(); }, 240);
}
document.getElementById('opt-season').addEventListener('input', function(){ OPT.season = this.value.toUpperCase(); onOptChange(false); });
document.getElementById('opt-url').addEventListener('input', function(){ OPT.url = this.value.trim(); onOptChange(true); });
document.getElementById('opt-price').addEventListener('change', function(){ OPT.price = this.checked; refreshArt(); });
document.getElementById('opt-qr').addEventListener('change', function(){ OPT.qr = this.checked; refreshArt(); });
document.getElementById('opt-qrcolor').addEventListener('change', function(){
  OPT.qrColor = this.value; makeQr(); refreshArt();
});
document.getElementById('opt-photo').addEventListener('change', function(){
  var files = Array.prototype.slice.call(this.files || []);
  if (!files.length) return;
  Promise.all(files.map(function(f){
    return new Promise(function(res){
      var fr = new FileReader();
      fr.onload = function(){ res(fr.result); };
      fr.readAsDataURL(f);
    });
  })).then(function(urls){
    OPT.photos = urls;
    OPT.photo = urls[0];
    document.getElementById('photo-name').textContent =
      urls.length === 1 ? files[0].name : urls.length + ' photos sélectionnées';
    refreshArt();
    toast(urls.length + ' photo(s) intégrée(s) aux visuels Photo et Mosaïque.');
  });
});

/* =====================================================================
   18. QR code (librairie locale) → data-URL réutilisable dans les SVG
   ===================================================================== */
function makeQr(){
  var host = document.getElementById('hidden-qr');
  host.innerHTML = '';
  if (!OPT.url) { ASSETS.qr = null; return; }
  try {
    new QRCode(host, {
      text: OPT.url, width: 560, height: 560,
      colorDark: OPT.qrColor, colorLight: '#ffffff',
      correctLevel: QRCode.CorrectLevel.M
    });
    var cv = host.querySelector('canvas'), im = host.querySelector('img');
    ASSETS.qr = cv ? cv.toDataURL('image/png') : (im ? im.src : null);
  } catch (e) {
    console.warn('QR indisponible', e);
    ASSETS.qr = null;
  }
}

/** Auto-test : tente de rasteriser chaque visuel et rapporte les échecs.
    Accessible via ?selftest=1 — le résultat est écrit dans #selftest. */
async function selfTest(){
  var out = [], box = document.createElement('pre');
  box.id = 'selftest';
  box.style.cssText = 'padding:16px;font-size:13px;white-space:pre-wrap;background:#111;color:#0f0;margin:0';
  box.textContent = 'SELFTEST en cours…';
  document.body.insertBefore(box, document.body.firstChild);
  for (var i = 0; i < CARDS.length; i++) {
    var c = CARDS[i];
    try {
      await rasterize(svgFor(c, true), Math.round(c.w / 6), Math.round(c.h / 6));
    } catch (e) {
      out.push(c.id + ' ' + c.group + '/' + c.style + '/' + c.variant + ' :: ' + e.message);
    }
  }
  var nl = String.fromCharCode(10);
  box.textContent = 'SELFTEST ' + (out.length ? 'ECHECS ' + out.length + nl + out.join(nl)
                                              : 'OK ' + CARDS.length + ' visuels');
  return out;
}

/* =====================================================================
   19. Démarrage
   ===================================================================== */
async function boot(){
  /* Les polices doivent être prêtes avant toute mesure de texte. */
  try {
    await Promise.all([
      document.fonts.load('400 32px "Gill Sans"'),
      document.fonts.load('700 32px "Gill Sans"'),
      document.fonts.load('italic 700 32px "Gill Sans"'),
      document.fonts.load('800 32px "AllRound Gothic"')
    ]);
    await document.fonts.ready;
  } catch (e) { /* on continue avec les métriques de repli */ }

  makeQr();
  renderGallery();

  /* Filtres pré-sélectionnés par l'URL : ?format=affiche&style=mosaic
     — pratique pour envoyer à quelqu'un un lien déjà filtré. */
  var qs = new URLSearchParams(location.search);
  ['group', 'style'].forEach(function(f){
    var val = qs.get(f === 'group' ? 'format' : 'style');
    if (!val) return;
    var chip = document.querySelector('.chip[data-filter="' + f + '"][data-value="' + val + '"]');
    if (!chip) return;
    document.querySelectorAll('.chip[data-filter="' + f + '"]').forEach(function(b){ b.classList.remove('on'); });
    chip.classList.add('on');
    filters[f] = val;
  });

  applyFilters();
  setupLazy();

  if (qs.get('selftest')) { window.__selftest = selfTest(); }
  /* Ressources base64 pour l'export : un SVG rasterisé dans un canvas n'a
     accès à aucune ressource externe, il faut donc tout embarquer. */
  try {
    var res = await Promise.all([
      fetchB64(ASSET('images/logomjat.png')),
      fetchB64(ASSET('fonts/Gill_Sans.woff2')),
      fetchB64(ASSET('fonts/Gill_Sans_Bold.woff2')),
      fetchB64(ASSET('fonts/Gill_Sans_Italic.woff2')),
      fetchB64(ASSET('fonts/AllRoundGothic-Bold.woff2'))
    ]);
    ASSETS.logo = res[0];
    ASSETS.fonts =
      "@font-face{font-family:'Gill Sans';font-style:normal;font-weight:400;src:url(data:font/woff2;base64," + res[1] + ") format('woff2');}"
    + "@font-face{font-family:'Gill Sans';font-style:normal;font-weight:500 900;src:url(data:font/woff2;base64," + res[2] + ") format('woff2');}"
    + "@font-face{font-family:'Gill Sans';font-style:italic;font-weight:400 900;src:url(data:font/woff2;base64," + res[3] + ") format('woff2');}"
    + "@font-face{font-family:'AllRound Gothic';font-style:normal;font-weight:600 900;src:url(data:font/woff2;base64," + res[4] + ") format('woff2');}"
    + "text{font-kerning:normal;}";

    /* Photothèque en base64 : sans ça, les visuels Photo et Mosaïque
       ressortiraient vides à l'export (le SVG rasterisé est isolé). */
    await Promise.all(DEFAULT_PHOTOS.map(async function(path){
      try { ASSETS.photos[path] = await fetchB64(ASSET(path)); } catch (e) {}
    }));
  } catch (e) {
    console.warn('Polices non embarquées : les exports utiliseront une police de repli.', e);
    toast("Attention : polices non embarquées, vérifie un export avant diffusion.");
  }
}
boot();
</script>
</body>
</html>
