<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Carte de membre — {{ $adhesion->prenom }} {{ $adhesion->nom }}</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo.jpg') }}">
    <link rel="stylesheet" href="{{ asset('css/gill-sans.css') }}">
    <link rel="stylesheet" href="{{ asset('fonts/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome/css/all.min.css') }}">
<style>
/* =====================================================================
   Carte de membre au format carte bancaire — 85,6 × 54 mm, comme une
   carte d'identité, un permis ou une carte étudiante. Les dimensions
   sont en millimètres pour que l'impression tombe juste au découpage.
   ===================================================================== */
:root{
  --navy:#1A3D8A; --dark:#2048A4; --mid:#3262CC; --blue:#3DAEF5;
  --yellow:#F5A623; --red:#D0021B; --ink:#0B1E45; --gris:#6C7A91; --bord:#E4EAF4;
}
*{box-sizing:border-box}
body{margin:0;padding:26px 20px;background:#EEF2F8;color:#333;
     font-family:'Gill Sans','Open Sans',sans-serif;font-size:15px}
.page{max-width:820px;margin:0 auto}
.font-round{font-family:'AllRound Gothic','Gill Sans',sans-serif}

.barre{display:flex;gap:10px;align-items:center;margin-bottom:20px;flex-wrap:wrap}
.barre a,.barre button{font:inherit;font-size:13.5px;font-weight:700;border:0;border-radius:10px;
                        padding:9px 16px;cursor:pointer;text-decoration:none;display:inline-flex;
                        align-items:center;gap:7px}
.barre a{background:#E4EAF4;color:var(--dark)}
.barre button{background:var(--dark);color:#fff}
.barre .astuce{margin-left:auto;font-size:12px;color:var(--gris);max-width:340px}

/* ── La carte ─────────────────────────────────────────────────────── */
.duo{display:flex;gap:18px;flex-wrap:wrap;margin-bottom:26px}
.carte{
  width:85.6mm;height:54mm;border-radius:3.2mm;overflow:hidden;position:relative;
  color:#fff;box-shadow:0 10px 26px rgba(16,34,74,.28);
  background:linear-gradient(135deg,#14306E 0%,#1A3D8A 42%,#2A55B4 100%);
  /* Sans cette règle, le navigateur imprime les aplats en blanc. */
  -webkit-print-color-adjust:exact;print-color-adjust:exact;
}
.carte *{-webkit-print-color-adjust:exact;print-color-adjust:exact}

/* Anneaux du logo, en filigrane */
.carte .anneaux{position:absolute;right:-14mm;top:-12mm;width:46mm;height:46mm;opacity:.13}
.carte .anneaux svg{width:100%;height:100%}

.carte .filet{position:absolute;left:0;right:0;height:1.5mm;display:flex}
.carte .filet.haut{top:0}.carte .filet.bas{bottom:0}
.carte .filet i{flex:1}
.f1{background:var(--blue)}.f2{background:var(--yellow)}.f3{background:var(--red)}

.carte .entete{position:absolute;top:3.4mm;left:4mm;right:4mm;display:flex;align-items:center;gap:2.2mm}
.carte .entete img{width:8.5mm;height:8.5mm;object-fit:contain;background:#fff;border-radius:1.6mm;padding:.5mm}
.carte .entete .org{font-size:2.5mm;font-weight:800;letter-spacing:.35mm;line-height:1.25}
.carte .entete .sig{font-size:1.9mm;color:#BDD4F5;font-style:italic}
.carte .entete .type{margin-left:auto;text-align:right;font-size:1.9mm;font-weight:800;
                     letter-spacing:.4mm;color:var(--yellow);text-transform:uppercase;line-height:1.3}
.carte .entete .type .saison{display:block;font-size:2.6mm;color:#fff;letter-spacing:.2mm;margin-top:.5mm}

.carte .corps{position:absolute;top:15mm;left:4mm;right:4mm;bottom:6.5mm;display:flex;gap:3.6mm}

.photo{width:20mm;height:26mm;border-radius:1.6mm;object-fit:cover;flex:none;
       border:.4mm solid rgba(255,255,255,.55);background:rgba(255,255,255,.12)}
.photo.vide{display:flex;align-items:center;justify-content:center;font-size:9mm;
            font-weight:800;color:rgba(255,255,255,.55)}

.champs{flex:1;min-width:0;display:flex;flex-direction:column;justify-content:center;gap:1.5mm}
.champ .cle{font-size:1.75mm;letter-spacing:.32mm;text-transform:uppercase;color:#9DBCEC;font-weight:700}
.champ .val{font-size:3.1mm;font-weight:800;line-height:1.15;white-space:nowrap;
            overflow:hidden;text-overflow:ellipsis}
.champ .val.grand{font-size:4mm;letter-spacing:.02mm}

.pied{position:absolute;left:4mm;right:4mm;bottom:3mm;display:flex;align-items:flex-end;gap:3mm}
.statut{margin-left:auto;font-size:1.9mm;font-weight:800;letter-spacing:.3mm;text-transform:uppercase;
        background:rgba(255,255,255,.16);border:.25mm solid rgba(255,255,255,.34);
        border-radius:6mm;padding:.7mm 2.2mm;white-space:nowrap}

/* ── Verso ────────────────────────────────────────────────────────── */
.carte.verso{background:#F6F9FE;color:var(--ink);box-shadow:0 10px 26px rgba(16,34,74,.16)}
.carte.verso .bande{position:absolute;left:0;right:0;top:6mm;height:9mm;background:var(--ink)}
.carte.verso .zone{position:absolute;left:4mm;right:4mm;top:18mm;bottom:6mm;display:flex;gap:3.5mm}
.carte.verso .mentions{flex:1;font-size:2mm;line-height:1.45;color:#4A5A73}
.carte.verso .mentions b{color:var(--navy)}
.carte.verso .signature{width:26mm;flex:none;display:flex;flex-direction:column;justify-content:flex-end}
.carte.verso .signature .trait{border-bottom:.3mm solid #9FB2CE;height:8mm}
.carte.verso .signature .lg{font-size:1.75mm;color:var(--gris);margin-top:.8mm;text-align:center}
.carte.verso .basverso{position:absolute;left:4mm;right:4mm;bottom:2.4mm;display:flex;
                        justify-content:space-between;font-size:1.8mm;color:var(--gris)}

/* ── Zone à découper ──────────────────────────────────────────────── */
.decoupe{margin-top:26px;border:2px dashed #A9B9D2;border-radius:10px;padding:22px 20px 20px;
         position:relative;background:#fff}
.decoupe .etiquette{position:absolute;top:-11px;left:22px;background:#EEF2F8;padding:0 10px;
                    font-size:12px;font-weight:700;color:var(--gris);letter-spacing:.3px}
.decoupe .ciseaux{position:absolute;top:-13px;right:20px;background:#EEF2F8;padding:0 8px;
                  font-size:15px;color:#8FA3C0}
.decoupe .rappel{margin-top:14px;font-size:11.5px;color:var(--gris);text-align:center}

/* ── Attestation ──────────────────────────────────────────────────── */
.attestation{background:#fff;border:1px solid var(--bord);border-radius:16px;padding:30px 34px;
             color:#4A5A73;font-size:14.5px;line-height:1.7}
.attestation h1{margin:0 0 22px;text-align:center;font-size:20px;font-weight:800;color:var(--ink)}
.attestation .nom{text-align:center;font-size:18px;font-weight:800;color:var(--navy);margin:18px 0;
                  background:#F2F7FF;border:1px solid #DCE8FA;border-radius:12px;padding:14px 18px}
.attestation .nom .saison-att{display:block;font-size:13px;font-weight:700;color:var(--gris);
                              text-transform:uppercase;letter-spacing:.8px;margin-top:5px}
.attestation .fin{color:var(--gris);font-size:13.5px}
.attestation .cachet{margin-top:26px;display:flex;align-items:center;gap:12px;justify-content:flex-end;color:var(--gris)}
.attestation .cachet img{height:44px;width:auto;opacity:.85}

@media print{
  @page{size:A4 portrait;margin:14mm}
  body{background:#fff;padding:0;font-size:12pt}
  .no-print{display:none!important}
  .attestation{border:0;padding:0;font-size:11pt;line-height:1.6}
  .attestation h1{margin:0 0 6mm;font-size:15pt}
  .attestation .nom{font-size:13pt;margin:5mm 0}
  .decoupe{margin-top:12mm;page-break-inside:avoid;break-inside:avoid;background:#fff}
  .decoupe .etiquette,.decoupe .ciseaux{background:#fff}
  .duo{gap:5mm;margin-bottom:0}
  .carte{box-shadow:none}
}
</style>
</head>
<body>
<div class="page">

    <div class="barre no-print">
        <a href="{{ route('member.dashboard') }}"><i class="fas fa-arrow-left"></i> Mon espace</a>
        <button id="btn-pdf" data-carte='{{ json_encode([
        'civilite'  => $adhesion->civilite,
        'prenom'    => $adhesion->prenom,
        'nom'       => $adhesion->nom,
        'initiale'  => mb_strtoupper(mb_substr($adhesion->prenom, 0, 1)),
        'saison'    => $adhesion->period?->label ?? 'saison en cours',
        'depuis'    => $adhesion->created_at?->format('m/Y') ?? '—',
        'date'      => now()->locale('fr')->isoFormat('D MMMM Y'),
        'email'     => config('mja.contact_email'),
        'site'      => 'mja-martinique.com',
        'logoUrl'   => asset('images/logo.jpg'),
        'photoUrl'  => $adhesion->photo ? \Illuminate\Support\Facades\Storage::url($adhesion->photo) : null,
        'fichier'   => \Illuminate\Support\Str::slug($adhesion->prenom . '-' . $adhesion->nom),
    ], JSON_UNESCAPED_UNICODE) }}'><i class="fas fa-file-pdf"></i> Télécharger le PDF</button>
        {{-- <button id="btn-imprimer" style="background:#E4EAF4;color:var(--dark)"><i class="fas fa-print"></i> Imprimer</button>
        <span class="astuce">
            Le PDF est généré directement, avec les couleurs — pas besoin de passer par
            l'impression du navigateur.
        </span> --}}
    </div>

    {{-- ── Attestation ──────────────────────────────────────────── --}}
    <div class="attestation">
        <h1>Attestation d'adhésion</h1>
        <p>L'association <strong>Madin'Jeunes Ambition</strong>, association déclarée régie par la loi
           du 1<sup>er</sup> juillet 1901, atteste que&nbsp;:</p>
        <div class="nom">
            {{ $adhesion->civilite }} {{ $adhesion->prenom }} {{ $adhesion->nom }}
            <span class="saison-att">{{ $adhesion->period?->label ?? 'Saison en cours' }}</span>
        </div>
        <p>est <strong>adhérent(e)</strong> de l'association pour la
           <strong>{{ $adhesion->period?->label ?? 'saison en cours' }}</strong>
           et à jour de sa cotisation.</p>
        <p class="fin">Fait en Martinique, le {{ now()->locale('fr')->isoFormat('D MMMM Y') }}.</p>
        <div class="cachet">
            <img src="{{ asset('images/logomjat.png') }}" alt="MJA">
            <span style="font-size:12.5px">Madin'Jeunes Ambition</span>
        </div>
    </div>

    {{-- ── Carte à détacher, en bas de page comme sur une attestation ── --}}
    <div class="decoupe">
        <span class="etiquette">Votre carte de membre — à découper</span>
        <span class="ciseaux">&#9986;</span>
    <div class="duo">
        {{-- ── Recto ────────────────────────────────────────────── --}}
        <div class="carte">
            <div class="filet haut"><i class="f1"></i><i class="f2"></i><i class="f3"></i></div>

            <div class="anneaux" aria-hidden="true">
                <svg viewBox="0 0 200 200" fill="none">
                    <circle cx="100" cy="100" r="95" stroke="#FFFFFF" stroke-width="3"/>
                    <circle cx="100" cy="100" r="72" stroke="#F5A623" stroke-width="3"/>
                    <circle cx="100" cy="100" r="49" stroke="#D0021B" stroke-width="3"/>
                </svg>
            </div>

            <div class="entete">
                <img src="{{ asset('images/logo.jpg') }}" alt="">
                <div>
                    <div class="org">MADIN' JEUNES AMBITION</div>
                    <div class="sig">Relève tous les défis !</div>
                </div>
                <div class="type">Carte de membre
                    <span class="saison">{{ $adhesion->period?->label ?? 'Saison en cours' }}</span>
                </div>
            </div>

            <div class="corps">
                @if($adhesion->photo)
                <img class="photo" src="{{ \Illuminate\Support\Facades\Storage::url($adhesion->photo) }}" alt="">
                @else
                <div class="photo vide">{{ mb_strtoupper(mb_substr($adhesion->prenom, 0, 1)) }}</div>
                @endif

                <div class="champs">
                    <div class="champ">
                        <div class="cle">Nom</div>
                        <div class="val grand">{{ mb_strtoupper($adhesion->nom) }}</div>
                    </div>
                    <div class="champ">
                        <div class="cle">Prénom</div>
                        <div class="val grand">{{ $adhesion->prenom }}</div>
                    </div>
                    <div class="champ">
                        <div class="cle">Membre depuis</div>
                        <div class="val">{{ $adhesion->created_at?->format('m/Y') ?? '—' }}</div>
                    </div>
                </div>
            </div>

            <div class="pied">
                <span class="statut">Adhérent à jour</span>
            </div>

            <div class="filet bas"><i class="f1"></i><i class="f2"></i><i class="f3"></i></div>
        </div>

        {{-- ── Verso ────────────────────────────────────────────── --}}
        <div class="carte verso">
            <div class="filet haut"><i class="f1"></i><i class="f2"></i><i class="f3"></i></div>
            <div class="bande"></div>

            <div class="zone">
                <div class="mentions">
                    <b>Carte nominative et incessible.</b>
                    Elle atteste de la qualité de membre de l'association Madin'Jeunes Ambition
                    pour la saison indiquée au recto, et donne accès aux activités réservées aux
                    adhérents ainsi qu'au vote en assemblée générale.<br><br>
                    Association déclarée régie par la loi du 1<sup>er</sup> juillet 1901.<br>
                    En cas de perte, prévenir l'association.
                </div>
                <div class="signature">
                    <div class="trait"></div>
                    <div class="lg">Signature du titulaire</div>
                </div>
            </div>

            <div class="basverso">
                <span>{{ config('mja.contact_email') }}</span>
                <span>mja-martinique.com</span>
            </div>

            <div class="filet bas"><i class="f1"></i><i class="f2"></i><i class="f3"></i></div>
        </div>
    </div>
        <div class="rappel">
            Découpez suivant les pointillés, pliez au milieu et collez dos à dos :
            vous obtenez une carte au format d'une carte bancaire (85,6 × 54 mm).
        </div>
    </div>
</div>
<script src="{{ asset('js/mja-pdf.js') }}"></script>
<script>
/* =====================================================================
   Carte de membre en PDF vectoriel.

   Le document reprend la logique d'une attestation administrative : le texte
   en haut, la carte à détacher en bas dans un cadre en pointillés. Les
   dimensions de la carte sont celles d'une carte bancaire (85,6 × 54 mm),
   pour qu'une fois découpée elle entre dans un portefeuille.
   ===================================================================== */
(function () {
'use strict';

var P = window.MjaPdf;
if (!P) return;

var bouton = document.getElementById('btn-pdf');
var imprimer = document.getElementById('btn-imprimer');
if (imprimer) imprimer.addEventListener('click', function () { window.print(); });
if (!bouton) return;

var D = JSON.parse(bouton.dataset.carte);
var A4 = P.A4, MM = P.MM, C = P.COUL;
var MARGE = 18 * MM;

/* Dimensions d'une carte bancaire. */
var CARTE = { w: 85.6 * MM, h: 54 * MM, r: 3.2 * MM };

function filets(doc, x, y, w, h) {
  var t = w / 3;
  doc.rect(x, y, t + 0.4, h, C.blue);
  doc.rect(x + t, y, t + 0.4, h, C.jaune);
  doc.rect(x + 2 * t, y, t, h, C.rouge);
}

/* ── Recto ────────────────────────────────────────────────────────── */
function recto(doc, x, y, logo, photo) {
  var w = CARTE.w, h = CARTE.h;

  doc.rectArrondi(x, y, w, h, CARTE.r, C.navy);

  /* Aplat plus clair en diagonale, pour éviter un bloc de couleur plat. */
  doc.commencerDecoupe(x, y, w, h, CARTE.r);
  doc.rect(x + w * 0.46, y, w * 0.54, h, C.mid);
  doc.rect(x + w * 0.62, y, w * 0.38, h, C.dark);
  doc.finirDecoupe();

  doc.commencerDecoupe(x, y, w, h, CARTE.r);
  filets(doc, x, y, w, 1.5 * MM);
  filets(doc, x, y + h - 1.5 * MM, w, 1.5 * MM);
  doc.finirDecoupe();

  /* En-tête : logo, nom de l'association, type de carte et saison. */
  var lg = 8.5 * MM, hx = x + 4 * MM, hy = y + 3.4 * MM;
  if (logo) {
    doc.rectArrondi(hx, hy, lg, lg, 1.6 * MM, C.blanc);
    doc.image(logo, hx + 0.5 * MM, hy + 0.5 * MM, lg - MM, lg - MM);
  }
  var tx = hx + lg + 2.2 * MM;
  doc.texte(tx, hy + 3.2 * MM, "MADIN' JEUNES AMBITION", { size: 7, gras: true, c: C.blanc, ls: 0.5 });
  doc.texte(tx, hy + 6.4 * MM, 'Relève tous les défis !', { size: 5.4, ital: true, c: C.bleuClair });

  var dx = x + w - 4 * MM;
  doc.texte(dx, hy + 2.8 * MM, 'CARTE DE MEMBRE', { size: 5.4, gras: true, c: C.jaune, align: 'right', ls: 0.5 });
  doc.texteAjuste(dx, hy + 6.8 * MM, D.saison, w * 0.42, { size: 7.4, gras: true, c: C.blanc, align: 'right' });

  /* Photo d'identité, au format portrait. */
  var px = x + 4 * MM, py = y + 15 * MM, pw = 20 * MM, ph = 26 * MM;
  if (photo) {
    doc.commencerDecoupe(px, py, pw, ph, 1.6 * MM);
    /* Recadrage « couvrant » : la photo remplit le cadre sans déformation. */
    var k = Math.max(pw / D.photoW, ph / D.photoH);
    doc.image(photo, px + (pw - D.photoW * k) / 2, py + (ph - D.photoH * k) / 2, D.photoW * k, D.photoH * k);
    doc.finirDecoupe();
  } else {
    doc.rectArrondi(px, py, pw, ph, 1.6 * MM, C.dark);
    doc.texte(px + pw / 2, py + ph / 2 + 4 * MM, D.initiale,
              { size: 26, gras: true, c: C.bleuClair, align: 'center' });
  }

  /* Champs, comme sur une pièce d'identité. */
  var cx = px + pw + 3.6 * MM, cw = x + w - cx - 4 * MM, cy = py + 4 * MM;
  [['NOM', D.nom.toUpperCase(), 11], ['PRÉNOM', D.prenom, 11], ['MEMBRE DEPUIS', D.depuis, 9]]
    .forEach(function (champ) {
      doc.texte(cx, cy, champ[0], { size: 5, gras: true, c: C.bleuClair, ls: 0.6 });
      doc.texteAjuste(cx, cy + 4.4 * MM, champ[1], cw, { size: champ[2], gras: true, c: C.blanc });
      cy += 8.4 * MM;
    });

  /* Mention de validité, en bas à droite. */
  var etiquette = 'ADHÉRENT À JOUR';
  var le = P.larg(etiquette, 5.4, true) + 4 * MM;
  doc.rectArrondi(x + w - 4 * MM - le, y + h - 7.4 * MM, le, 4.4 * MM, 2.2 * MM, C.mid);
  doc.texte(x + w - 4 * MM - le / 2, y + h - 4.4 * MM, etiquette,
            { size: 5.4, gras: true, c: C.blanc, align: 'center', ls: 0.3 });
}

/* ── Verso ────────────────────────────────────────────────────────── */
function verso(doc, x, y) {
  var w = CARTE.w, h = CARTE.h;

  doc.rectArrondi(x, y, w, h, CARTE.r, [0.965, 0.976, 0.996]);

  doc.commencerDecoupe(x, y, w, h, CARTE.r);
  filets(doc, x, y, w, 1.5 * MM);
  doc.rect(x, y + 6 * MM, w, 9 * MM, C.encre);
  filets(doc, x, y + h - 1.5 * MM, w, 1.5 * MM);
  doc.finirDecoupe();

  var mx = x + 4 * MM, mw = w * 0.60;
  var my = y + 19 * MM;

  doc.texte(mx, my, 'Carte nominative et incessible.', { size: 6.2, gras: true, c: C.navy });
  my += 3.4 * MM;
  my += doc.paragraphe(mx, my,
    "Elle atteste de la qualité de membre de l'association Madin'Jeunes Ambition pour la saison "
    + "indiquée au recto, et donne accès aux activités réservées aux adhérents ainsi qu'au vote "
    + "en assemblée générale.",
    5.6, mw, { c: C.texte, lh: 7.4 });
  my += 2 * MM;
  doc.paragraphe(mx, my,
    "Association déclarée régie par la loi du 1er juillet 1901. En cas de perte, prévenir l'association.",
    5.6, mw, { c: C.gris, lh: 7.4 });

  /* Emplacement de signature. */
  var sx = x + w - 4 * MM - 26 * MM, sy = y + h - 12 * MM;
  doc.rect(sx, sy, 26 * MM, 0.3 * MM, [0.624, 0.698, 0.808]);
  doc.texte(sx + 13 * MM, sy + 3.4 * MM, 'Signature du titulaire',
            { size: 5, c: C.gris, align: 'center' });

  doc.texte(x + 4 * MM, y + h - 3.4 * MM, D.email, { size: 5, c: C.gris });
  doc.texte(x + w - 4 * MM, y + h - 3.4 * MM, D.site, { size: 5, c: C.gris, align: 'right' });
}

/* ── Page complète ────────────────────────────────────────────────── */
function composer(logo, photo) {
  var doc = new P.Doc({ marge: MARGE, titre: 'Carte de membre — ' + D.prenom + ' ' + D.nom });
  doc.nouvellePage();
  var L = A4.w - 2 * MARGE;

  /* En-tête de l'attestation */
  doc.rect(0, 0, A4.w, 5, C.navy);
  filets(doc, 0, 0, A4.w, 5);

  var y = MARGE + 6;
  if (logo) {
    doc.image(logo, MARGE, y, 46, 46);
    doc.texte(MARGE + 58, y + 18, "MADIN' JEUNES AMBITION", { size: 12, gras: true, c: C.navy, ls: 0.6 });
    doc.texte(MARGE + 58, y + 33, 'Relève tous les défis !', { size: 9.5, ital: true, c: C.dark });
    y += 66;
  }

  doc.texte(A4.w / 2, y + 16, "ATTESTATION D'ADHÉSION", { size: 17, gras: true, c: C.encre, align: 'center' });
  y += 26;
  doc.rect(A4.w / 2 - 30, y, 60, 2.2, C.jaune);
  y += 30;

  y += doc.paragraphe(MARGE, y,
    "L'association Madin'Jeunes Ambition, association déclarée régie par la loi du 1er juillet 1901, atteste que :",
    10.5, L, { c: C.texte, lh: 15 });

  y += 14;
  doc.rectArrondi(MARGE, y, L, 50, 8, C.bleuPale);
  doc.texte(A4.w / 2, y + 22, D.civilite + ' ' + D.prenom + ' ' + D.nom,
            { size: 14, gras: true, c: C.navy, align: 'center' });
  doc.texte(A4.w / 2, y + 38, D.saison.toUpperCase(),
            { size: 9, gras: true, c: C.gris, align: 'center', ls: 0.8 });
  y += 66;

  y += doc.paragraphe(MARGE, y,
    "est adhérent(e) de l'association pour la " + D.saison + " et à jour de sa cotisation.",
    10.5, L, { c: C.texte, lh: 15 });

  y += 16;
  doc.texte(MARGE, y, 'Fait en Martinique, le ' + D.date + '.', { size: 9.5, c: C.gris });

  /* ── Zone à découper ──────────────────────────────────────────── */
  var zoneY = A4.h - MARGE - (CARTE.h + 66);
  var zoneH = CARTE.h + 58;

  doc.rectPointille(MARGE, zoneY, L, zoneH, 8, [0.663, 0.725, 0.824], 1, '5 4');

  doc.rect(MARGE + 16, zoneY - 5, P.larg('VOTRE CARTE DE MEMBRE — À DÉCOUPER', 7.5, true) + 12, 10, C.blanc);
  doc.texte(MARGE + 22, zoneY + 3, 'VOTRE CARTE DE MEMBRE — À DÉCOUPER',
            { size: 7.5, gras: true, c: C.gris, ls: 0.5 });

  var ecart = 12;
  var largeurDuo = CARTE.w * 2 + ecart;
  var cx = MARGE + (L - largeurDuo) / 2, cy = zoneY + 20;

  recto(doc, cx, cy, logo, photo);
  verso(doc, cx + CARTE.w + ecart, cy);

  doc.texte(A4.w / 2, zoneY + zoneH - 10,
            'Découpez suivant les pointillés, puis collez le recto et le verso dos à dos.',
            { size: 7.5, c: C.gris, align: 'center' });

  /* Pied de page */
  doc.rect(MARGE, A4.h - MARGE + 10, L, 0.8, C.trait);
  doc.texte(MARGE, A4.h - MARGE + 24, "Madin' Jeunes Ambition — Relève tous les défis !",
            { size: 8, c: C.gris });
  doc.texte(A4.w - MARGE, A4.h - MARGE + 24, D.site, { size: 8, c: C.gris, align: 'right' });

  return doc;
}

/* ── Déclenchement ────────────────────────────────────────────────── */
bouton.addEventListener('click', function () {
  var initial = bouton.innerHTML;
  bouton.disabled = true;
  bouton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Génération…';

  Promise.all([
    P.chargerImage(D.logoUrl, 'Logo'),
    D.photoUrl ? P.chargerImage(D.photoUrl, 'Photo') : Promise.resolve(null)
  ]).then(function (images) {
    var logo = images[0], photo = images[1];

    /* Les dimensions de la photo servent à la recadrer sans la déformer :
       la composition n'a lieu qu'une fois les images chargées. */
    if (photo) { D.photoW = photo.w; D.photoH = photo.h; }

    var doc = composer(logo ? 'Logo' : null, photo ? 'Photo' : null);
    if (logo) doc.ajouterImage(logo);
    if (photo) doc.ajouterImage(photo);

    P.telecharger(doc.produire(), 'carte-membre-' + D.fichier + '.pdf');
  }).catch(function (e) {
    alert('La génération du PDF a échoué : ' + e.message);
  }).then(function () {
    bouton.disabled = false;
    bouton.innerHTML = initial;
  });
});

})();
</script>
</body>
</html>
