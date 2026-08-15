@php
    /**
     * Monteur vidéo MJA — assemblage de rushes en réel prêt à publier.
     *
     * Tout se passe dans le navigateur : les fichiers ne quittent jamais le
     * poste. Les rushes déposés dans public/videos/kit sont proposés d'office ;
     * l'utilisateur peut en déposer d'autres à la volée.
     */
    $lister = function (string $dossier, string $motif) {
        $chemin = public_path($dossier);
        $fichiers = is_dir($chemin) ? (glob($chemin . '/' . $motif, GLOB_BRACE) ?: []) : [];
        sort($fichiers);

        return array_map(fn ($f) => [
            'url'  => asset($dossier . '/' . rawurlencode(basename($f))),
            'nom'  => basename($f),
            'poids' => round(filesize($f) / 1048576, 1),
        ], $fichiers);
    };

    $videos = $lister('videos/kit', '*.{mp4,MP4,webm,WEBM,mov,MOV,m4v,M4V}');

    // Les photos du carrousel et du kit servent aussi de plans fixes.
    $photos = array_merge(
        $lister('images/carrousel', '*.{jpg,JPG,jpeg,JPEG,png,PNG,webp,WEBP}'),
        $lister('images/kit', '*.{jpg,JPG,jpeg,JPEG,png,PNG,webp,WEBP}'),
    );
@endphp
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex, nofollow">
<title>Monteur vidéo — Madin'Jeunes Ambition</title>
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
body{margin:0;background:var(--fond);color:#333;font-family:'Gill Sans','Open Sans',sans-serif;font-size:15px;line-height:1.5}
.wrap{max-width:1400px;margin:0 auto;padding:0 20px}

header{background:linear-gradient(135deg,#1A3D8A 0%,#2048A4 48%,#3262CC 100%);color:#fff;padding:26px 0 24px;position:relative;overflow:hidden}
header .bar{position:absolute;left:0;right:0;top:0;height:6px;display:flex}
header .bar i{flex:1}
.b1{background:var(--blue)}.b2{background:var(--yellow)}.b3{background:var(--red)}
.idt{display:flex;align-items:center;gap:14px;margin-bottom:14px}
.idt img{height:52px;width:52px;object-fit:contain;background:#fff;border-radius:12px;padding:4px}
.idt .n{font-weight:800;font-size:17px;letter-spacing:.7px}
.idt .s{font-size:12.5px;color:#BDD4F5;font-style:italic}
h1{margin:0 0 4px;font-size:26px;font-weight:800}
header p{margin:0;color:#C9DBFA;font-size:14px;max-width:760px}

.grille{display:grid;grid-template-columns:300px 1fr 330px;gap:18px;margin:20px 0 40px;align-items:start}
@media (max-width:1180px){.grille{grid-template-columns:1fr}}

.bloc{background:#fff;border:1px solid var(--bord);border-radius:16px;overflow:hidden}
.bloc > h2{margin:0;padding:13px 16px;font-size:14px;font-weight:800;color:var(--navy);
           border-bottom:1px solid var(--bord);background:#FAFCFF;display:flex;align-items:center;gap:8px}
.bloc > h2 .cpt{margin-left:auto;font-weight:600;color:var(--gris);font-size:12px}
.bloc .corps{padding:14px 16px}

/* ── Bibliothèque ─────────────────────────────────────────────── */
.depot{border:2px dashed #C9D8EE;border-radius:12px;padding:16px;text-align:center;color:var(--gris);
       font-size:13px;cursor:pointer;transition:.15s;margin-bottom:12px}
.depot:hover,.depot.survol{border-color:var(--blue);background:#F2F8FF;color:var(--dark)}
.media{display:grid;grid-template-columns:repeat(3,1fr);gap:8px;max-height:360px;overflow-y:auto}
.vignette{position:relative;border:1px solid var(--bord);border-radius:9px;overflow:hidden;cursor:pointer;
          aspect-ratio:1;background:#0B1E45;transition:.15s}
.vignette:hover{border-color:var(--blue);transform:translateY(-2px)}
.vignette img,.vignette video{width:100%;height:100%;object-fit:cover;display:block}
.vignette .t{position:absolute;left:4px;top:4px;background:rgba(11,30,69,.82);color:#fff;font-size:9px;
             font-weight:800;letter-spacing:.5px;border-radius:5px;padding:2px 5px}
.vignette .plus{position:absolute;right:4px;bottom:4px;width:20px;height:20px;border-radius:50%;
                background:var(--yellow);color:var(--navy);font-size:11px;display:flex;align-items:center;justify-content:center}

/* ── Montage ──────────────────────────────────────────────────── */
.clips{max-height:520px;overflow-y:auto;padding:12px 14px;display:flex;flex-direction:column;gap:9px}
.clip{border:1px solid var(--bord);border-radius:12px;padding:9px 11px;background:#fff;display:flex;gap:10px;
      align-items:center;cursor:grab}
.clip.tire{opacity:.4}
.clip.cible{border-color:var(--blue);box-shadow:0 0 0 3px rgba(61,174,245,.18)}
.clip .apercu{width:52px;height:52px;border-radius:8px;overflow:hidden;flex:none;background:var(--navy);
              display:flex;align-items:center;justify-content:center;color:#fff;font-size:17px}
.clip .apercu img,.clip .apercu video{width:100%;height:100%;object-fit:cover}
.clip .info{flex:1;min-width:0}
.clip .info b{display:block;font-size:13.5px;color:var(--ink);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.clip .reglages{display:flex;flex-wrap:wrap;gap:5px;margin-top:5px}
.clip select,.clip input[type=number]{font:inherit;font-size:11.5px;border:1px solid var(--bord);border-radius:7px;
                                      padding:2px 5px;color:var(--gris);background:#fff}
.clip input[type=number]{width:52px}
.clip .actions{display:flex;flex-direction:column;gap:3px}
.clip .actions button{border:0;background:#EEF3FB;color:var(--dark);border-radius:6px;width:24px;height:20px;
                      cursor:pointer;font-size:10px}
.clip .actions button:hover{background:var(--blue);color:#fff}
.clip .actions .sup:hover{background:var(--red)}
.clip.fixe{background:#FFFBEF;border-color:#F6E2B4;cursor:default}
.clip.fixe .apercu{background:var(--yellow);color:var(--navy)}

.vide{padding:38px 16px;text-align:center;color:var(--gris);font-size:13.5px}

/* ── Réglages ─────────────────────────────────────────────────── */
.champ{margin-bottom:13px}
.champ label{display:block;font-size:12px;font-weight:800;color:var(--navy);margin-bottom:5px;
             text-transform:uppercase;letter-spacing:.6px}
.champ select,.champ input[type=text]{width:100%;font:inherit;font-size:13.5px;border:1px solid var(--bord);
                                       border-radius:9px;padding:7px 10px;background:#fff}
.champ .aide{font-size:11.5px;color:var(--gris);margin-top:4px}
.bascule{display:flex;align-items:center;gap:9px;font-size:13.5px;color:#4A5A73;margin-bottom:9px;cursor:pointer}

/* ── Aperçu ───────────────────────────────────────────────────── */
.scene{background:#0B1E45;border-radius:14px;padding:14px;display:flex;justify-content:center}
#toile{max-width:100%;max-height:56vh;border-radius:8px;background:#000;box-shadow:0 10px 30px rgba(0,0,0,.35)}
.transport{display:flex;flex-wrap:wrap;gap:9px;align-items:center;padding:12px 16px;border-top:1px solid var(--bord)}
.duree{font-size:12.5px;color:var(--gris);margin-left:auto}
.btn{border:0;border-radius:10px;padding:9px 15px;font:inherit;font-size:13.5px;font-weight:700;cursor:pointer;
     display:inline-flex;align-items:center;gap:7px;transition:.15s}
.btn.p{background:var(--bluedark);color:#fff}.btn.p:hover{background:var(--dark)}
.btn.g{background:#EEF3FB;color:var(--dark)}.btn.g:hover{background:#E2EBF8}
.btn.j{background:var(--yellow);color:var(--navy)}.btn.j:hover{background:#e0941a}
.btn:disabled{opacity:.5;cursor:not-allowed}
.etat{padding:10px 16px;font-size:12.5px;color:var(--gris);border-top:1px solid var(--bord);min-height:38px}
.etat.actif{color:var(--dark);font-weight:600}

.note{background:#FFFBEB;border:1px solid #FDE9B8;border-radius:14px;padding:16px 18px;margin-bottom:22px;
      font-size:13.5px;color:#78591C}
.note b{color:#92400E}
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
    <h1>Monteur vidéo</h1>
    <p>Assemble tes rushes en réel : une intro, des plans vidéo et photo, une outro. Réordonne, ajuste les durées,
       choisis les effets et les transitions, puis exporte. Tout se passe sur ton ordinateur — aucun fichier n'est envoyé.</p>
  </div>
</header>

<div class="wrap">

  @if(empty($videos))
  <div class="note" style="margin-top:20px">
    <b>Aucune vidéo dans la bibliothèque.</b>
    Dépose tes fichiers dans <code>public/videos/kit/</code> pour qu'ils apparaissent ici d'office,
    ou glisse-les directement dans la zone de dépôt ci-dessous — dans ce cas ils ne sont chargés que pour cette session.
    Les photos du site sont déjà disponibles comme plans fixes.
  </div>
  @endif

  <div class="grille">

    {{-- ── Bibliothèque ───────────────────────────────────────── --}}
    <div class="bloc">
      <h2><i class="fas fa-photo-film"></i> Bibliothèque
        <span class="cpt">{{ count($videos) }} vidéo(s) · {{ count($photos) }} photo(s)</span>
      </h2>
      <div class="corps">
        <div class="depot" id="depot">
          <i class="fas fa-cloud-arrow-up" style="font-size:20px;display:block;margin-bottom:6px"></i>
          Glisse tes vidéos et photos ici<br>
          <span style="font-size:11.5px">ou clique pour les choisir</span>
          <input type="file" id="fichiers" accept="video/*,image/*" multiple hidden>
        </div>
        <div class="media" id="media"></div>
      </div>
    </div>

    {{-- ── Montage ────────────────────────────────────────────── --}}
    <div class="bloc">
      <h2><i class="fas fa-timeline"></i> Montage <span class="cpt" id="cpt-clips"></span></h2>
      <div class="scene"><canvas id="toile" width="1080" height="1920"></canvas></div>
      <div class="transport">
        <button class="btn p" id="btn-lire"><i class="fas fa-play"></i> Lire</button>
        <button class="btn g" id="btn-stop"><i class="fas fa-stop"></i> Arrêter</button>
        <button class="btn j" id="btn-export"><i class="fas fa-download"></i> Exporter la vidéo</button>
        <span class="duree" id="duree">0,0 s</span>
      </div>
      <div class="etat" id="etat">Ajoute des plans depuis la bibliothèque pour composer ton réel.</div>
      <div class="clips" id="clips"></div>
    </div>

    {{-- ── Réglages ───────────────────────────────────────────── --}}
    <div class="bloc">
      <h2><i class="fas fa-sliders"></i> Réglages</h2>
      <div class="corps">
        <div class="champ">
          <label for="opt-format">Format</label>
          <select id="opt-format">
            <option value="1080x1920">Reel / Story — 9:16 (1080 × 1920)</option>
            <option value="1080x1350">Post vertical — 4:5 (1080 × 1350)</option>
            <option value="1080x1080">Post carré — 1:1 (1080 × 1080)</option>
            <option value="1920x1080">Paysage — 16:9 (1920 × 1080)</option>
            <option value="1280x720">Paysage léger — 16:9 (1280 × 720)</option>
          </select>
          <div class="aide">Les plans sont recadrés au centre, jamais déformés.</div>
        </div>

        <div class="champ">
          <label for="opt-intro">Intro</label>
          <select id="opt-intro"></select>
          <div class="aide" id="aide-intro"></div>
        </div>

        <div class="champ">
          <label for="opt-outro">Outro</label>
          <select id="opt-outro"></select>
          <div class="aide" id="aide-outro"></div>
        </div>

        <div class="champ">
          <label for="opt-accroche">Texte de l'intro</label>
          <input type="text" id="opt-accroche" value="MJ'ADHÉSION" maxlength="28">
          <div class="aide">Ligne principale du carton d'ouverture.</div>
        </div>

        <div class="champ">
          <label for="opt-sous">Sous-titre de l'intro</label>
          <input type="text" id="opt-sous" value="SAISON 2026-2027" maxlength="34">
        </div>

        <div class="champ">
          <label for="opt-transition">Transition par défaut</label>
          <select id="opt-transition">
            <option value="fondu">Fondu</option>
            <option value="coupe">Coupe franche</option>
            <option value="glisse">Glissé</option>
            <option value="fondublanc">Fondu au blanc</option>
          </select>
          <div class="aide">Modifiable plan par plan dans le montage.</div>
        </div>

        <div class="champ">
          <label for="opt-duree">Durée d'un plan photo</label>
          <select id="opt-duree">
            <option value="1.5">1,5 s — rythme rapide</option>
            <option value="2" selected>2 s — équilibré</option>
            <option value="3">3 s — posé</option>
          </select>
        </div>

        <label class="bascule"><input type="checkbox" id="opt-logo" checked> Filigrane du logo</label>
        <label class="bascule"><input type="checkbox" id="opt-barre" checked> Filet tricolore</label>
        <label class="bascule"><input type="checkbox" id="opt-son"> Garder le son des vidéos</label>

        <button class="btn g" id="btn-vider" style="width:100%;justify-content:center;margin-top:6px">
          <i class="fas fa-rotate-left"></i> Vider le montage
        </button>
      </div>
    </div>
  </div>

  <div class="note">
    <b>Récupérer tes vidéos Instagram.</b> Instagram ne permet pas à un outil tiers de télécharger des publications —
    l'accès demande une authentification et leurs conditions l'interdisent. En revanche, tu peux exporter les tiennes
    depuis ton compte : <em>Paramètres → Votre activité → Télécharger vos informations</em>, en choisissant
    « Contenu multimédia » et la qualité haute. Instagram envoie une archive par email. Mieux encore : les fichiers
    d'origine restés dans la galerie des téléphones sont de bien meilleure qualité que ceux réexportés par Instagram.
    Dépose le tout dans <code>public/videos/kit/</code>.
  </div>
</div>

<script>
(function () {
'use strict';

/* =====================================================================
   1. Données de départ
   ===================================================================== */
var C = { navy:'#1A3D8A', dark:'#2048A4', blue:'#3DAEF5', yellow:'#F5A623',
          red:'#D0021B', ink:'#0B1E45', blanc:'#FFFFFF' };
var FAM = "'Gill Sans','Montserrat',sans-serif";

var LOGO_URL = @json(asset('images/logo.jpg'));
var ORG      = "MADIN' JEUNES AMBITION";
var SLOGAN   = "RELÈVE TOUS LES DÉFIS !";
var SITE     = "mja-martinique.com";
var INSTA    = "@madin_jeunes_ambition";

/* Bibliothèque servie par le site. Les fichiers déposés par l'utilisateur
   s'y ajoutent avec une URL locale (blob:). */
var BIBLIO = [];
@foreach($videos as $v)
BIBLIO.push({ type:'video', url:@json($v['url']), nom:@json($v['nom']), poids:@json($v['poids']) });
@endforeach
@foreach($photos as $p)
BIBLIO.push({ type:'photo', url:@json($p['url']), nom:@json($p['nom']), poids:@json($p['poids']) });
@endforeach

/* Cartons d'ouverture et de fermeture. `dessin` reçoit l'avancement t ∈ [0,1]. */
var INTROS = [
  { id:'aucune', nom:'Aucune',                 duree:0,   aide:"Le montage démarre directement sur le premier plan." },
  { id:'logo',   nom:'Logo qui éclot',         duree:2.0, aide:"Le logo apparaît en zoom, le nom se pose dessous." },
  { id:'titre',  nom:'Titre plein écran',      duree:2.2, aide:"Fond navy, titre en gros, filet tricolore qui balaie." },
  { id:'flash',  nom:'Flash tricolore',        duree:1.6, aide:"Trois bandes de couleur balaient l'écran puis le titre tombe." },
  { id:'compte', nom:'Décompte 3-2-1',         duree:2.4, aide:"Décompte rythmé, utile pour accrocher dès la première seconde." }
];
var OUTROS = [
  { id:'aucune', nom:'Aucune',                 duree:0,   aide:"Le montage s'arrête sur le dernier plan." },
  { id:'appel',  nom:"Appel à l'action",       duree:2.4, aide:"« J'ADHÈRE », l'adresse du site et le compte Instagram." },
  { id:'logo',   nom:'Logo et slogan',         duree:2.0, aide:"Retour au logo, slogan en dessous." },
  { id:'contact',nom:'Coordonnées',            duree:2.6, aide:"Site, Instagram et téléphone, sur fond navy." }
];

var EFFETS = { aucun:'Aucun', zoom:'Zoom lent', dezoom:'Dézoom', gauche:'Panoramique →',
               nb:'Noir et blanc', chaud:'Teinte chaude' };
var TRANSITIONS = { fondu:'Fondu', coupe:'Coupe', glisse:'Glissé', fondublanc:'Fondu blanc' };

/* =====================================================================
   2. État
   ===================================================================== */
var MONTAGE = [];              /* [{ media, duree, effet, transition }] */
var OPT = {
  largeur:1080, hauteur:1920, intro:'logo', outro:'appel',
  accroche:"MJ'ADHÉSION", sous:'SAISON 2026-2027',
  transition:'fondu', dureePhoto:2, logo:true, barre:true, son:false
};

var toile = document.getElementById('toile');
var ctx = toile.getContext('2d');
var logoImg = new Image();
logoImg.src = LOGO_URL;

var lecture = null;            /* état de la lecture en cours */

/* =====================================================================
   3. Utilitaires de dessin
   ===================================================================== */
function W(){ return toile.width; }
function H(){ return toile.height; }
function U(){ return Math.min(W(), H()); }

function fond(couleur){
  ctx.fillStyle = couleur || C.navy;
  ctx.fillRect(0, 0, W(), H());
}

/** Dessine une source en « couvrant » le cadre, avec zoom et décalage. */
function couvrir(src, sw, sh, zoom, dx, dy){
  if (!sw || !sh) return;
  var k = Math.max(W() / sw, H() / sh) * (zoom || 1);
  var w = sw * k, h = sh * k;
  ctx.drawImage(src, (W() - w) / 2 + (dx || 0), (H() - h) / 2 + (dy || 0), w, h);
}

/** Texte centré, réduit jusqu'à tenir dans `maxW`. */
function texte(str, y, taille, couleur, gras, maxW){
  maxW = maxW || W() * 0.86;
  var t = taille;
  ctx.font = (gras ? '800 ' : '600 ') + t + 'px ' + FAM;
  while (ctx.measureText(str).width > maxW && t > 8) {
    t -= 2;
    ctx.font = (gras ? '800 ' : '600 ') + t + 'px ' + FAM;
  }
  ctx.fillStyle = couleur;
  ctx.textAlign = 'center';
  ctx.textBaseline = 'alphabetic';
  ctx.fillText(str, W() / 2, y);
  return t;
}

function filetTricolore(y, hauteur, avancement){
  var h = hauteur, l = W() * (avancement === undefined ? 1 : Math.max(0, Math.min(1, avancement)));
  var couleurs = [C.blue, C.yellow, C.red];
  for (var i = 0; i < 3; i++) {
    ctx.fillStyle = couleurs[i];
    ctx.fillRect(i * l / 3, y, l / 3 + 1, h);
  }
}

function filigrane(){
  if (!OPT.logo || !logoImg.complete || !logoImg.naturalWidth) return;
  var t = U() * 0.10, m = U() * 0.045;
  ctx.save();
  ctx.globalAlpha = 0.88;
  ctx.fillStyle = 'rgba(255,255,255,.92)';
  arrondi(W() - m - t, m, t, t, t * 0.22);
  ctx.fill();
  ctx.drawImage(logoImg, W() - m - t + t * 0.08, m + t * 0.08, t * 0.84, t * 0.84);
  ctx.restore();
}

function arrondi(x, y, w, h, r){
  r = Math.min(r, w / 2, h / 2);
  ctx.beginPath();
  ctx.moveTo(x + r, y);
  ctx.arcTo(x + w, y, x + w, y + h, r);
  ctx.arcTo(x + w, y + h, x, y + h, r);
  ctx.arcTo(x, y + h, x, y, r);
  ctx.arcTo(x, y, x + w, y, r);
  ctx.closePath();
}

function attenue(x){ x = Math.max(0, Math.min(1, x)); return 1 - Math.pow(1 - x, 3); }

/* =====================================================================
   4. Cartons d'intro et d'outro
   ===================================================================== */
function dessinerIntro(id, t){
  var u = U();
  if (id === 'logo') {
    fond(C.navy);
    var a = attenue(t / 0.5), k = 0.75 + 0.25 * a;
    if (logoImg.complete && logoImg.naturalWidth) {
      var s = u * 0.34 * k;
      ctx.save(); ctx.globalAlpha = a;
      ctx.fillStyle = '#fff';
      arrondi(W() / 2 - s / 2, H() * 0.32 - s / 2, s, s, s * 0.20); ctx.fill();
      ctx.drawImage(logoImg, W() / 2 - s * 0.42, H() * 0.32 - s * 0.42, s * 0.84, s * 0.84);
      ctx.restore();
    }
    var b = attenue((t - 0.35) / 0.45);
    ctx.save(); ctx.globalAlpha = b;
    texte(ORG, H() * 0.32 + u * 0.28, u * 0.058, '#fff', true);
    texte(SLOGAN, H() * 0.32 + u * 0.36, u * 0.040, C.yellow, true);
    ctx.restore();
    filetTricolore(H() - u * 0.022, u * 0.022, attenue(t / 0.6));

  } else if (id === 'titre') {
    fond(C.navy);
    filetTricolore(0, u * 0.026, attenue(t / 0.4));
    var c = attenue((t - 0.1) / 0.5);
    ctx.save(); ctx.globalAlpha = c;
    ctx.translate(0, (1 - c) * u * 0.06);
    texte(OPT.accroche, H() * 0.48, u * 0.15, '#fff', true);
    texte(OPT.sous, H() * 0.48 + u * 0.11, u * 0.055, C.yellow, true);
    ctx.restore();
    filetTricolore(H() - u * 0.026, u * 0.026, attenue((t - 0.2) / 0.5));

  } else if (id === 'flash') {
    fond('#fff');
    var couleurs = [C.blue, C.yellow, C.red];
    for (var i = 0; i < 3; i++) {
      var d = Math.max(0, Math.min(1, (t - i * 0.08) / 0.34));
      ctx.fillStyle = couleurs[i];
      ctx.fillRect(0, H() * (i / 3), W() * attenue(d), H() / 3 + 1);
    }
    if (t > 0.45) {
      var e = attenue((t - 0.45) / 0.5);
      ctx.save(); ctx.globalAlpha = e;
      fond('rgba(26,61,138,' + e.toFixed(3) + ')');
      texte(OPT.accroche, H() * 0.52, u * 0.14, '#fff', true);
      ctx.restore();
    }

  } else if (id === 'compte') {
    fond(C.navy);
    var n = 3 - Math.floor(t * 3);
    n = Math.max(1, Math.min(3, n));
    var phase = (t * 3) % 1;
    ctx.save();
    ctx.globalAlpha = 1 - Math.pow(phase, 3);
    texte(String(n), H() * 0.5 + u * 0.09, u * 0.34 * (1.25 - 0.25 * phase),
          [C.red, C.yellow, C.blue][n - 1], true);
    ctx.restore();
    texte(OPT.accroche, H() * 0.74, u * 0.055, '#fff', true);
    filetTricolore(H() - u * 0.022, u * 0.022, 1);
  }
}

function dessinerOutro(id, t){
  var u = U(), a = attenue(t / 0.4);
  if (id === 'appel') {
    fond(C.navy);
    filetTricolore(0, u * 0.026, 1);
    ctx.save(); ctx.globalAlpha = a;
    var pulsation = 1 + 0.04 * Math.sin(t * Math.PI * 4);
    ctx.translate(W() / 2, H() * 0.40); ctx.scale(pulsation, pulsation); ctx.translate(-W() / 2, -H() * 0.40);
    texte("J'ADHÈRE", H() * 0.42, u * 0.15, C.yellow, true);
    ctx.restore();
    ctx.save(); ctx.globalAlpha = attenue((t - 0.3) / 0.5);
    texte(SITE, H() * 0.56, u * 0.062, '#fff', true);
    texte(INSTA, H() * 0.64, u * 0.042, '#BDD4F5', true);
    ctx.restore();
    filetTricolore(H() - u * 0.026, u * 0.026, 1);

  } else if (id === 'logo') {
    fond(C.navy);
    if (logoImg.complete && logoImg.naturalWidth) {
      var s = u * 0.32;
      ctx.save(); ctx.globalAlpha = a;
      ctx.fillStyle = '#fff';
      arrondi(W() / 2 - s / 2, H() * 0.40 - s / 2, s, s, s * 0.20); ctx.fill();
      ctx.drawImage(logoImg, W() / 2 - s * 0.42, H() * 0.40 - s * 0.42, s * 0.84, s * 0.84);
      ctx.restore();
    }
    ctx.save(); ctx.globalAlpha = attenue((t - 0.25) / 0.5);
    texte(SLOGAN, H() * 0.58, u * 0.055, C.yellow, true);
    texte(SITE, H() * 0.66, u * 0.042, '#fff', true);
    ctx.restore();
    filetTricolore(H() - u * 0.022, u * 0.022, 1);

  } else if (id === 'contact') {
    fond(C.navy);
    filetTricolore(0, u * 0.022, 1);
    var lignes = [['Site', SITE], ['Instagram', INSTA], ['Téléphone', '0696 43 88 21']];
    texte('NOUS REJOINDRE', H() * 0.30, u * 0.062, C.yellow, true);
    for (var i = 0; i < lignes.length; i++) {
      var b = attenue((t - 0.15 - i * 0.12) / 0.4);
      ctx.save(); ctx.globalAlpha = b;
      ctx.translate(0, (1 - b) * u * 0.03);
      texte(lignes[i][0], H() * (0.44 + i * 0.13), u * 0.033, '#8FB2E8', true);
      texte(lignes[i][1], H() * (0.44 + i * 0.13) + u * 0.055, u * 0.048, '#fff', true);
      ctx.restore();
    }
    filetTricolore(H() - u * 0.022, u * 0.022, 1);
  }
}

/* =====================================================================
   5. Dessin d'un plan
   ===================================================================== */
function dessinerPlan(clip, t){
  var m = clip.media, src = m.element;
  if (!src) { fond('#000'); return; }

  var sw = m.type === 'video' ? src.videoWidth : src.naturalWidth;
  var sh = m.type === 'video' ? src.videoHeight : src.naturalHeight;

  var zoom = 1, dx = 0, dy = 0;
  if (clip.effet === 'zoom')   zoom = 1 + 0.14 * t;
  if (clip.effet === 'dezoom') zoom = 1.14 - 0.14 * t;
  if (clip.effet === 'gauche') { zoom = 1.12; dx = (0.5 - t) * W() * 0.10; }

  fond('#000');
  ctx.save();
  if (clip.effet === 'nb')    ctx.filter = 'grayscale(1) contrast(1.05)';
  if (clip.effet === 'chaud') ctx.filter = 'saturate(1.18) sepia(.18)';
  couvrir(src, sw, sh, zoom, dx, dy);
  ctx.restore();
}

/* =====================================================================
   6. Composition d'une image à l'instant global `temps`
   ===================================================================== */
function sequence(){
  var seq = [], t = 0;
  var intro = trouver(INTROS, OPT.intro);
  if (intro && intro.duree > 0) { seq.push({ genre:'intro', id:intro.id, debut:t, duree:intro.duree }); t += intro.duree; }

  MONTAGE.forEach(function (clip) {
    seq.push({ genre:'plan', clip:clip, debut:t, duree:clip.duree });
    t += clip.duree;
  });

  var outro = trouver(OUTROS, OPT.outro);
  if (outro && outro.duree > 0) { seq.push({ genre:'outro', id:outro.id, debut:t, duree:outro.duree }); t += outro.duree; }

  return { items:seq, total:t };
}

function trouver(liste, id){
  for (var i = 0; i < liste.length; i++) if (liste[i].id === id) return liste[i];
  return null;
}

var DUREE_TRANSITION = 0.45;

function composer(temps, seq){
  var items = seq.items;
  fond('#000');

  for (var i = 0; i < items.length; i++) {
    var it = items[i];
    if (temps < it.debut || temps >= it.debut + it.duree) continue;
    var local = (temps - it.debut) / it.duree;

    if (it.genre === 'intro')      dessinerIntro(it.id, local);
    else if (it.genre === 'outro') dessinerOutro(it.id, local);
    else                           dessinerPlan(it.clip, local);

    /* Transition avec l'élément suivant, jouée sur la fin de celui-ci. */
    var suivant = items[i + 1];
    if (suivant) {
      var reste = it.debut + it.duree - temps;
      var type = suivant.genre === 'plan' ? suivant.clip.transition : 'fondu';
      if (type !== 'coupe' && reste < DUREE_TRANSITION) {
        var p = 1 - reste / DUREE_TRANSITION;
        if (type === 'fondu') {
          ctx.save(); ctx.globalAlpha = p; ctx.fillStyle = '#000';
          ctx.fillRect(0, 0, W(), H()); ctx.restore();
        } else if (type === 'fondublanc') {
          ctx.save(); ctx.globalAlpha = p; ctx.fillStyle = '#fff';
          ctx.fillRect(0, 0, W(), H()); ctx.restore();
        } else if (type === 'glisse') {
          ctx.save(); ctx.fillStyle = C.navy;
          ctx.fillRect(W() * (1 - attenue(p)), 0, W(), H()); ctx.restore();
        }
      }
    }
    break;
  }

  if (OPT.barre) filetTricolore(H() - U() * 0.014, U() * 0.014, 1);
  filigrane();
}

/* =====================================================================
   7. Lecture
   ===================================================================== */
function arreter(){
  if (lecture) {
    cancelAnimationFrame(lecture.trame);
    lecture.videos.forEach(function (v) { try { v.pause(); } catch (e) {} });
    lecture = null;
  }
  document.getElementById('btn-lire').innerHTML = '<i class="fas fa-play"></i> Lire';
}

function lire(surFin){
  arreter();
  var seq = sequence();
  if (!seq.total) { message("Ajoute au moins un plan avant de lire."); return null; }

  var videos = [];
  MONTAGE.forEach(function (c) {
    if (c.media.type === 'video' && c.media.element) {
      c.media.element.muted = !OPT.son;
      videos.push(c.media.element);
    }
  });

  var t0 = performance.now();
  lecture = { videos:videos, trame:0, seq:seq };
  document.getElementById('btn-lire').innerHTML = '<i class="fas fa-pause"></i> En lecture';

  /* Une vidéo n'est jouée que pendant sa fenêtre : on la positionne et on la
     lance au bon moment, sinon toutes joueraient en même temps. */
  var joue = [];

  function trame(){
    var temps = (performance.now() - t0) / 1000;

    seq.items.forEach(function (it, i) {
      if (it.genre !== 'plan' || it.clip.media.type !== 'video') return;
      var el = it.clip.media.element, dedans = temps >= it.debut && temps < it.debut + it.duree;
      if (dedans && !joue[i]) {
        joue[i] = true;
        try { el.currentTime = it.clip.depart || 0; el.play(); } catch (e) {}
      } else if (!dedans && joue[i]) {
        joue[i] = false;
        try { el.pause(); } catch (e) {}
      }
    });

    composer(temps, seq);

    if (temps >= seq.total) {
      arreter();
      if (surFin) surFin();
      return;
    }
    lecture.trame = requestAnimationFrame(trame);
  }

  lecture.trame = requestAnimationFrame(trame);
  return seq;
}

/* =====================================================================
   8. Export
   ===================================================================== */
function formatVideo(){
  var candidats = ['video/mp4;codecs=avc1.42E01E', 'video/mp4',
                   'video/webm;codecs=vp9', 'video/webm;codecs=vp8', 'video/webm'];
  if (!window.MediaRecorder) return null;
  for (var i = 0; i < candidats.length; i++) {
    if (MediaRecorder.isTypeSupported(candidats[i])) return candidats[i];
  }
  return null;
}

function exporter(){
  var seq = sequence();
  if (!seq.total) { message("Ajoute au moins un plan avant d'exporter."); return; }

  var mime = formatVideo();
  if (!mime) { message("Ce navigateur ne sait pas enregistrer de vidéo. Essaie avec Chrome ou Edge."); return; }

  var flux = toile.captureStream(30);

  /* Le son des vidéos, s'il est demandé, est mixé dans un même flux. */
  var contexte = null;
  if (OPT.son) {
    try {
      contexte = new (window.AudioContext || window.webkitAudioContext)();
      var sortie = contexte.createMediaStreamDestination();
      MONTAGE.forEach(function (c) {
        if (c.media.type !== 'video' || !c.media.element) return;
        if (!c.media.source) c.media.source = contexte.createMediaElementSource(c.media.element);
        c.media.source.connect(sortie);
      });
      sortie.stream.getAudioTracks().forEach(function (p) { flux.addTrack(p); });
    } catch (e) {
      message("Le son n'a pas pu être capté ; la vidéo sera muette.");
    }
  }

  var morceaux = [];
  var enregistreur = new MediaRecorder(flux, { mimeType:mime, videoBitsPerSecond:6000000 });
  enregistreur.ondataavailable = function (e) { if (e.data && e.data.size) morceaux.push(e.data); };

  enregistreur.onstop = function () {
    var ext = mime.indexOf('mp4') !== -1 ? 'mp4' : 'webm';
    var blob = new Blob(morceaux, { type:mime });
    var a = document.createElement('a');
    a.href = URL.createObjectURL(blob);
    a.download = 'mja-reel-' + OPT.largeur + 'x' + OPT.hauteur + '.' + ext;
    document.body.appendChild(a); a.click(); a.remove();
    setTimeout(function () { URL.revokeObjectURL(a.href); }, 5000);
    if (contexte) { try { contexte.close(); } catch (e) {} }
    message('Vidéo exportée (' + ext.toUpperCase() + ', ' + seq.total.toFixed(1).replace('.', ',') + ' s).');
    boutons(false);
  };

  boutons(true);
  message('Enregistrement en cours — laisse cet onglet au premier plan pendant '
          + seq.total.toFixed(1).replace('.', ',') + ' s…');
  enregistreur.start();
  lire(function () { setTimeout(function () { enregistreur.stop(); }, 200); });
}

function boutons(occupe){
  ['btn-lire', 'btn-stop', 'btn-export'].forEach(function (id) {
    document.getElementById(id).disabled = occupe;
  });
}

function message(txt){
  var e = document.getElementById('etat');
  e.textContent = txt;
  e.classList.add('actif');
}

/* =====================================================================
   9. Interface
   ===================================================================== */
function chargerMedia(m){
  if (m.element) return Promise.resolve(m);
  return new Promise(function (resolve) {
    if (m.type === 'video') {
      var v = document.createElement('video');
      v.src = m.url; v.preload = 'auto'; v.muted = true; v.playsInline = true; v.crossOrigin = 'anonymous';
      v.addEventListener('loadedmetadata', function () {
        m.element = v;
        m.duree = isFinite(v.duration) ? v.duration : 3;
        resolve(m);
      });
      v.addEventListener('error', function () { m.element = null; resolve(m); });
    } else {
      var i = new Image();
      i.crossOrigin = 'anonymous';
      i.onload = function () { m.element = i; resolve(m); };
      i.onerror = function () { m.element = null; resolve(m); };
      i.src = m.url;
    }
  });
}

function dessinerBibliotheque(){
  var zone = document.getElementById('media');
  zone.innerHTML = '';
  BIBLIO.forEach(function (m, i) {
    var d = document.createElement('div');
    d.className = 'vignette';
    d.title = m.nom + (m.poids ? ' — ' + m.poids + ' Mo' : '');
    d.innerHTML = (m.type === 'video'
        ? '<video src="' + m.url + '#t=0.5" muted preload="metadata"></video>'
        : '<img src="' + m.url + '" alt="">')
      + '<span class="t">' + (m.type === 'video' ? 'VIDÉO' : 'PHOTO') + '</span>'
      + '<span class="plus"><i class="fas fa-plus"></i></span>';
    d.addEventListener('click', function () { ajouter(i); });
    zone.appendChild(d);
  });
}

function ajouter(index){
  var m = BIBLIO[index];
  chargerMedia(m).then(function () {
    if (!m.element) { message('Ce fichier n\'a pas pu être lu : ' + m.nom); return; }
    MONTAGE.push({
      media: m,
      duree: m.type === 'video' ? Math.min(m.duree || 3, 4) : OPT.dureePhoto,
      effet: m.type === 'photo' ? 'zoom' : 'aucun',
      transition: OPT.transition,
      depart: 0
    });
    dessinerMontage();
    apercu();
  });
}

function dessinerMontage(){
  var zone = document.getElementById('clips');
  zone.innerHTML = '';

  if (!MONTAGE.length) {
    zone.innerHTML = '<div class="vide">Aucun plan pour l\'instant.<br>Clique sur une vignette de la bibliothèque pour l\'ajouter.</div>';
    majDuree();
    return;
  }

  MONTAGE.forEach(function (clip, i) {
    var d = document.createElement('div');
    d.className = 'clip';
    d.draggable = true;
    d.dataset.index = i;

    var apercuHtml = clip.media.type === 'video'
      ? '<video src="' + clip.media.url + '#t=0.5" muted preload="metadata"></video>'
      : '<img src="' + clip.media.url + '" alt="">';

    var optEffets = Object.keys(EFFETS).map(function (k) {
      return '<option value="' + k + '"' + (clip.effet === k ? ' selected' : '') + '>' + EFFETS[k] + '</option>';
    }).join('');
    var optTrans = Object.keys(TRANSITIONS).map(function (k) {
      return '<option value="' + k + '"' + (clip.transition === k ? ' selected' : '') + '>' + TRANSITIONS[k] + '</option>';
    }).join('');

    d.innerHTML =
      '<div class="apercu">' + apercuHtml + '</div>' +
      '<div class="info"><b>' + (i + 1) + '. ' + clip.media.nom + '</b>' +
        '<div class="reglages">' +
          '<input type="number" step="0.5" min="0.5" max="15" value="' + clip.duree + '" data-champ="duree" title="Durée en secondes">' +
          '<select data-champ="effet">' + optEffets + '</select>' +
          '<select data-champ="transition">' + optTrans + '</select>' +
        '</div>' +
      '</div>' +
      '<div class="actions">' +
        '<button data-act="haut" title="Monter"><i class="fas fa-chevron-up"></i></button>' +
        '<button data-act="bas" title="Descendre"><i class="fas fa-chevron-down"></i></button>' +
        '<button class="sup" data-act="sup" title="Retirer"><i class="fas fa-xmark"></i></button>' +
      '</div>';

    d.querySelectorAll('[data-champ]').forEach(function (ch) {
      ch.addEventListener('change', function () {
        var v = ch.dataset.champ === 'duree' ? Math.max(0.5, parseFloat(ch.value) || 1) : ch.value;
        MONTAGE[i][ch.dataset.champ] = v;
        majDuree(); apercu();
      });
      ch.addEventListener('click', function (e) { e.stopPropagation(); });
    });

    d.querySelectorAll('[data-act]').forEach(function (b) {
      b.addEventListener('click', function (e) {
        e.stopPropagation();
        var a = b.dataset.act;
        if (a === 'sup') MONTAGE.splice(i, 1);
        else if (a === 'haut' && i > 0) MONTAGE.splice(i - 1, 0, MONTAGE.splice(i, 1)[0]);
        else if (a === 'bas' && i < MONTAGE.length - 1) MONTAGE.splice(i + 1, 0, MONTAGE.splice(i, 1)[0]);
        dessinerMontage(); apercu();
      });
    });

    /* Réordonnancement par glisser-déposer. */
    d.addEventListener('dragstart', function (e) {
      e.dataTransfer.setData('text/plain', String(i));
      d.classList.add('tire');
    });
    d.addEventListener('dragend', function () { d.classList.remove('tire'); });
    d.addEventListener('dragover', function (e) { e.preventDefault(); d.classList.add('cible'); });
    d.addEventListener('dragleave', function () { d.classList.remove('cible'); });
    d.addEventListener('drop', function (e) {
      e.preventDefault();
      d.classList.remove('cible');
      var de = parseInt(e.dataTransfer.getData('text/plain'), 10);
      if (isNaN(de) || de === i) return;
      MONTAGE.splice(i, 0, MONTAGE.splice(de, 1)[0]);
      dessinerMontage(); apercu();
    });

    zone.appendChild(d);
  });

  majDuree();
}

function majDuree(){
  var seq = sequence();
  document.getElementById('duree').textContent = seq.total.toFixed(1).replace('.', ',') + ' s';
  document.getElementById('cpt-clips').textContent =
    MONTAGE.length + ' plan' + (MONTAGE.length > 1 ? 's' : '');
}

/** Image fixe de repère : première image de la séquence. */
function apercu(){
  if (lecture) return;
  var seq = sequence();
  composer(seq.total ? 0.02 : 0, seq);
}

/* ── Réglages ───────────────────────────────────────────────────── */
function remplirListe(id, liste, valeur, aideId){
  var s = document.getElementById(id);
  s.innerHTML = liste.map(function (o) {
    return '<option value="' + o.id + '"' + (o.id === valeur ? ' selected' : '') + '>'
         + o.nom + (o.duree ? ' — ' + String(o.duree).replace('.', ',') + ' s' : '') + '</option>';
  }).join('');
  var maj = function () {
    var o = trouver(liste, s.value);
    document.getElementById(aideId).textContent = o ? o.aide : '';
  };
  s.addEventListener('change', maj);
  maj();
}

function brancher(){
  document.getElementById('opt-format').addEventListener('change', function () {
    var d = this.value.split('x');
    OPT.largeur = +d[0]; OPT.hauteur = +d[1];
    toile.width = OPT.largeur; toile.height = OPT.hauteur;
    apercu();
  });

  ['intro', 'outro'].forEach(function (cle) {
    document.getElementById('opt-' + cle).addEventListener('change', function () {
      OPT[cle] = this.value; majDuree(); apercu();
    });
  });

  document.getElementById('opt-accroche').addEventListener('input', function () {
    OPT.accroche = this.value.toUpperCase(); apercu();
  });
  document.getElementById('opt-sous').addEventListener('input', function () {
    OPT.sous = this.value.toUpperCase(); apercu();
  });
  document.getElementById('opt-transition').addEventListener('change', function () {
    OPT.transition = this.value;
    MONTAGE.forEach(function (c) { c.transition = OPT.transition; });
    dessinerMontage(); apercu();
  });
  document.getElementById('opt-duree').addEventListener('change', function () {
    OPT.dureePhoto = parseFloat(this.value);
    MONTAGE.forEach(function (c) { if (c.media.type === 'photo') c.duree = OPT.dureePhoto; });
    dessinerMontage(); apercu();
  });
  ['logo', 'barre', 'son'].forEach(function (cle) {
    document.getElementById('opt-' + cle).addEventListener('change', function () {
      OPT[cle] = this.checked; apercu();
    });
  });

  document.getElementById('btn-lire').addEventListener('click', function () {
    if (lecture) { arreter(); apercu(); } else { lire(); }
  });
  document.getElementById('btn-stop').addEventListener('click', function () { arreter(); apercu(); });
  document.getElementById('btn-export').addEventListener('click', exporter);
  document.getElementById('btn-vider').addEventListener('click', function () {
    if (MONTAGE.length && !confirm('Vider le montage ?')) return;
    MONTAGE = []; arreter(); dessinerMontage(); apercu();
    message('Montage vidé.');
  });

  /* Dépôt de fichiers */
  var depot = document.getElementById('depot'), champ = document.getElementById('fichiers');
  depot.addEventListener('click', function () { champ.click(); });
  champ.addEventListener('change', function () { recevoir(this.files); this.value = ''; });
  ['dragenter', 'dragover'].forEach(function (e) {
    depot.addEventListener(e, function (ev) { ev.preventDefault(); depot.classList.add('survol'); });
  });
  ['dragleave', 'drop'].forEach(function (e) {
    depot.addEventListener(e, function (ev) { ev.preventDefault(); depot.classList.remove('survol'); });
  });
  depot.addEventListener('drop', function (ev) { recevoir(ev.dataTransfer.files); });
}

function recevoir(fichiers){
  var n = 0;
  Array.prototype.forEach.call(fichiers, function (f) {
    var estVideo = f.type.indexOf('video') === 0;
    if (!estVideo && f.type.indexOf('image') !== 0) return;
    BIBLIO.push({
      type: estVideo ? 'video' : 'photo',
      url: URL.createObjectURL(f),
      nom: f.name,
      poids: Math.round(f.size / 1048576 * 10) / 10
    });
    n++;
  });
  if (n) { dessinerBibliotheque(); message(n + ' fichier(s) ajouté(s) à la bibliothèque.'); }
  else message('Aucun fichier vidéo ou image reconnu.');
}

/* ── Démarrage ──────────────────────────────────────────────────── */
remplirListe('opt-intro', INTROS, OPT.intro, 'aide-intro');
remplirListe('opt-outro', OUTROS, OPT.outro, 'aide-outro');
brancher();
dessinerBibliotheque();
dessinerMontage();
logoImg.onload = apercu;
apercu();

})();
</script>
</body>
</html>
