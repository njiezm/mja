/* =====================================================================
   Moteur PDF vectoriel MJA — sans librairie externe.

   Écrit les objets PDF à la main : pages A4, polices standard (Helvetica,
   dont les métriques sont celles d'Arial, ce qui permet de mesurer le texte
   avec un canvas), texte sélectionnable, fichier léger.

   Partagé par les pages qui produisent un document : plan de communication,
   carte de membre. Chaque page fournit sa propre mise en page ; le moteur ne
   connaît que des rectangles, du texte et des images.
   ===================================================================== */
(function (global) {
'use strict';

var A4 = { w: 595.28, h: 841.89 };

var COUL = {
  navy:     [0.102, 0.239, 0.541],
  dark:     [0.125, 0.282, 0.643],
  mid:      [0.165, 0.333, 0.800],
  blue:     [0.239, 0.682, 0.961],
  jaune:    [0.961, 0.651, 0.137],
  rouge:    [0.816, 0.008, 0.106],
  encre:    [0.043, 0.118, 0.271],
  gris:     [0.424, 0.478, 0.569],
  texte:    [0.290, 0.353, 0.451],
  trait:    [0.894, 0.918, 0.957],
  creme:    [1.000, 0.973, 0.898],
  bleuPale: [0.949, 0.969, 1.000],
  blanc:    [1, 1, 1],
  bleuClair:[0.616, 0.737, 0.925]
};

/* Millimètres → points typographiques. */
var MM = 72 / 25.4;

/** « #14306E » → [0.078, 0.188, 0.431] : reprendre les couleurs du CSS tel quel. */
function hex(h) {
  var n = parseInt(String(h).replace('#', ''), 16);
  return [(n >> 16 & 255) / 255, (n >> 8 & 255) / 255, (n & 255) / 255];
}

/* ── Mesure : Arial est métriquement compatible avec Helvetica ───────── */
var mc = document.createElement('canvas').getContext('2d');
function larg(txt, taille, gras, ital, mono) {
  /* Courier est une chasse fixe : 0,6 em par caractere, exactement. Le mesurer
     au canvas donnerait la police du navigateur, pas celle du PDF. */
  if (mono) return String(txt).length * taille * 0.6;
  mc.font = (ital ? 'italic ' : '') + (gras ? 'bold ' : '') + taille + 'px Helvetica, Arial, sans-serif';
  return mc.measureText(txt).width;
}

/* ── Encodage WinAnsi : les accents français sortent corrects ────────── */
var HORS_LATIN1 = { '€': 128, '‚': 130, 'ƒ': 131, '„': 132, '…': 133,
  '†': 134, '‡': 135, 'ˆ': 136, '‰': 137, 'Š': 138, '‹': 139,
  'Œ': 140, 'Ž': 142, '‘': 145, '’': 146, '“': 147, '”': 148,
  '•': 149, '–': 150, '—': 151, '˜': 152, '™': 153, 'š': 154,
  '›': 155, 'œ': 156, 'ž': 158, 'Ÿ': 159 };

/* Signes absents de WinAnsi, remplacés par un équivalent lisible plutôt que
   par un point d'interrogation : une flèche perdue rend une phrase muette. */
var REPLIS = {
  '→': '>', '←': '<', '↔': '<>', '⟶': '>', '⇒': '=>', '⇐': '<=',
  '≥': '>=', '≤': '<=', '≠': '!=', '≈': '~',
  '✓': 'v', '✔': 'v', '✗': 'x', '✂': '', '★': '*', '●': '-', '▪': '-'
};

function versWinAnsi(str) {
  var out = '';
  for (var i = 0; i < str.length; i++) {
    var c = str[i], code = str.charCodeAt(i);
    if (HORS_LATIN1[c] !== undefined) { out += String.fromCharCode(HORS_LATIN1[c]); continue; }
    if (REPLIS[c] !== undefined) { out += REPLIS[c]; continue; }
    out += code <= 255 ? c : '?';
  }
  return out;
}

function echapper(str) {
  return versWinAnsi(String(str)).replace(/\\/g, '\\\\').replace(/\(/g, '\\(').replace(/\)/g, '\\)');
}

/* ── Document ───────────────────────────────────────────────────────── */
function Doc(options) {
  options = options || {};
  this.marge = options.marge === undefined ? 46 : options.marge;
  this.titre = options.titre || 'Document';
  this.auteur = options.auteur || "Madin' Jeunes Ambition";
  this.pages = [];
  this.flux = null;
  this.images = [];
  this.degrades = [];   /* nuanciers axiaux, déclarés en ressources de page */
  this.etats = [];      /* états graphiques : opacité */
  this.y = 0;
}

Doc.prototype.nouvellePage = function () {
  this.flux = [];
  this.pages.push(this.flux);
  this.y = this.marge;
  return this.pages.length;
};

/** Repère écran (origine en haut) → repère PDF (origine en bas). */
function pdfY(y) { return A4.h - y; }
Doc.prototype.pdfY = pdfY;

Doc.prototype.couleur = function (c, trait) {
  this.flux.push(c[0].toFixed(3) + ' ' + c[1].toFixed(3) + ' ' + c[2].toFixed(3) + (trait ? ' RG' : ' rg'));
};

Doc.prototype.rect = function (x, y, w, h, c) {
  this.couleur(c);
  this.flux.push(x.toFixed(2) + ' ' + pdfY(y + h).toFixed(2) + ' ' + w.toFixed(2) + ' ' + h.toFixed(2) + ' re f');
};

/** Rectangle à coins arrondis (quatre courbes de Bézier). */
Doc.prototype.cheminArrondi = function (x, y, w, h, r) {
  r = Math.min(r, w / 2, h / 2);
  var k = r * 0.5523, b = pdfY(y + h), t = pdfY(y);
  this.flux.push(
    (x + r).toFixed(2) + ' ' + b.toFixed(2) + ' m',
    (x + w - r).toFixed(2) + ' ' + b.toFixed(2) + ' l',
    (x + w - r + k).toFixed(2) + ' ' + b.toFixed(2) + ' ' + (x + w).toFixed(2) + ' ' + (b + r - k).toFixed(2) + ' ' + (x + w).toFixed(2) + ' ' + (b + r).toFixed(2) + ' c',
    (x + w).toFixed(2) + ' ' + (t - r).toFixed(2) + ' l',
    (x + w).toFixed(2) + ' ' + (t - r + k).toFixed(2) + ' ' + (x + w - r + k).toFixed(2) + ' ' + t.toFixed(2) + ' ' + (x + w - r).toFixed(2) + ' ' + t.toFixed(2) + ' c',
    (x + r).toFixed(2) + ' ' + t.toFixed(2) + ' l',
    (x + r - k).toFixed(2) + ' ' + t.toFixed(2) + ' ' + x.toFixed(2) + ' ' + (t - r + k).toFixed(2) + ' ' + x.toFixed(2) + ' ' + (t - r).toFixed(2) + ' c',
    x.toFixed(2) + ' ' + (b + r).toFixed(2) + ' l',
    x.toFixed(2) + ' ' + (b + r - k).toFixed(2) + ' ' + (x + r - k).toFixed(2) + ' ' + b.toFixed(2) + ' ' + (x + r).toFixed(2) + ' ' + b.toFixed(2) + ' c'
  );
};

Doc.prototype.rectArrondi = function (x, y, w, h, r, c) {
  this.couleur(c);
  this.cheminArrondi(x, y, w, h, r);
  this.flux.push('f');
};

/** Contour plein d'un rectangle arrondi. */
Doc.prototype.contourArrondi = function (x, y, w, h, r, c, epaisseur) {
  this.couleur(c, true);
  this.flux.push((epaisseur || 1).toFixed(2) + ' w');
  this.cheminArrondi(x, y, w, h, r);
  this.flux.push('s');
};

/** Ligne brisée — les liaisons d'un schéma. Bouts et angles arrondis. */
Doc.prototype.ligne = function (points, c, epaisseur) {
  this.couleur(c, true);
  this.flux.push((epaisseur || 1).toFixed(2) + ' w', '1 J', '1 j');
  this.flux.push(points.map(function (p, i) {
    return p[0].toFixed(2) + ' ' + pdfY(p[1]).toFixed(2) + (i ? ' l' : ' m');
  }).join(' '), 'S', '0 J', '0 j');
};

/** Contour en pointillés — trait de découpe. */
Doc.prototype.rectPointille = function (x, y, w, h, r, c, epaisseur, motif) {
  this.couleur(c, true);
  this.flux.push((epaisseur || 1).toFixed(2) + ' w', '[' + (motif || '4 3') + '] 0 d');
  this.cheminArrondi(x, y, w, h, r);
  this.flux.push('s', '[] 0 d');
};

/* ── Dégradés ────────────────────────────────────────────────────────
   Un aplat découpé en deux ou trois rectangles laisse une arête franche —
   une « barre » au milieu de la carte. On passe donc par un vrai nuancier
   axial (ShadingType 2), le même dégradé continu que le CSS.
   ------------------------------------------------------------------- */
function f3(v) { return v.toFixed(3); }

function fonctionExp(c0, c1) {
  return '<< /FunctionType 2 /Domain [0 1] /C0 [' + c0.map(f3).join(' ')
       + '] /C1 [' + c1.map(f3).join(' ') + '] /N 1 >>';
}

/** Enchaîne les segments entre chaque paire d'arrêts (FunctionType 3). */
function fonctionArrets(arrets) {
  if (arrets.length === 2) return fonctionExp(arrets[0][1], arrets[1][1]);
  var fns = [], bornes = [], encode = [];
  for (var i = 0; i < arrets.length - 1; i++) {
    fns.push(fonctionExp(arrets[i][1], arrets[i + 1][1]));
    encode.push('0 1');
    if (i > 0) bornes.push(arrets[i][0].toFixed(4));
  }
  return '<< /FunctionType 3 /Domain [0 1] /Functions [' + fns.join(' ') + ']'
       + ' /Bounds [' + bornes.join(' ') + '] /Encode [' + encode.join(' ') + '] >>';
}

/**
 * Remplit un rectangle d'un dégradé.
 *
 * `arrets` : [[position 0→1, couleur], …]. `sens` vaut 'diagonale' (comme un
 * linear-gradient 135deg), 'horizontale' ou 'verticale'. Le nuancier déborde
 * volontairement (Extend) : appelé dans une découpe arrondie, il en épouse
 * exactement la forme.
 */
Doc.prototype.degrade = function (x, y, w, h, arrets, sens) {
  var x0, y0, x1, y1;
  if (sens === 'horizontale')    { x0 = x;       y0 = pdfY(y + h / 2); x1 = x + w; y1 = y0; }
  else if (sens === 'verticale') { x0 = x + w / 2; y0 = pdfY(y);       x1 = x0;    y1 = pdfY(y + h); }
  else                           { x0 = x;       y0 = pdfY(y);         x1 = x + w; y1 = pdfY(y + h); }

  var nom = 'Sh' + this.degrades.length;
  this.degrades.push({
    nom: nom,
    dict: '<< /ShadingType 2 /ColorSpace /DeviceRGB /Coords ['
        + x0.toFixed(2) + ' ' + y0.toFixed(2) + ' ' + x1.toFixed(2) + ' ' + y1.toFixed(2)
        + '] /Function ' + fonctionArrets(arrets) + ' /Extend [true true] >>'
  });

  this.flux.push('q', x.toFixed(2) + ' ' + pdfY(y + h).toFixed(2) + ' '
    + w.toFixed(2) + ' ' + h.toFixed(2) + ' re W n', '/' + nom + ' sh', 'Q');
};

/** Cercle (quatre courbes de Bézier), plein ou en contour. */
Doc.prototype.cercle = function (cx, cy, r, o) {
  o = o || {};
  var k = r * 0.5523, y = pdfY(cy);
  this.flux.push(
    (cx + r).toFixed(2) + ' ' + y.toFixed(2) + ' m',
    (cx + r).toFixed(2) + ' ' + (y + k).toFixed(2) + ' ' + (cx + k).toFixed(2) + ' ' + (y + r).toFixed(2) + ' ' + cx.toFixed(2) + ' ' + (y + r).toFixed(2) + ' c',
    (cx - k).toFixed(2) + ' ' + (y + r).toFixed(2) + ' ' + (cx - r).toFixed(2) + ' ' + (y + k).toFixed(2) + ' ' + (cx - r).toFixed(2) + ' ' + y.toFixed(2) + ' c',
    (cx - r).toFixed(2) + ' ' + (y - k).toFixed(2) + ' ' + (cx - k).toFixed(2) + ' ' + (y - r).toFixed(2) + ' ' + cx.toFixed(2) + ' ' + (y - r).toFixed(2) + ' c',
    (cx + k).toFixed(2) + ' ' + (y - r).toFixed(2) + ' ' + (cx + r).toFixed(2) + ' ' + (y - k).toFixed(2) + ' ' + (cx + r).toFixed(2) + ' ' + y.toFixed(2) + ' c'
  );
  if (o.remplissage) { this.couleur(o.remplissage); this.flux.push('f'); }
  else {
    this.couleur(o.contour || COUL.blanc, true);
    this.flux.push((o.epaisseur || 1).toFixed(2) + ' w', 's');
  }
};

/** Opacité, dans un q…Q : reproduit un filigrane CSS (opacity: .13). */
Doc.prototype.opacite = function (alpha) {
  var cle = alpha.toFixed(3), nom = null;
  for (var i = 0; i < this.etats.length; i++) if (this.etats[i].alpha === cle) nom = this.etats[i].nom;
  if (!nom) { nom = 'GS' + this.etats.length; this.etats.push({ nom: nom, alpha: cle }); }
  this.flux.push('/' + nom + ' gs');
};

/** Découpe un contenu par un rectangle arrondi (pour les photos). */
Doc.prototype.commencerDecoupe = function (x, y, w, h, r) {
  this.flux.push('q');
  this.cheminArrondi(x, y, w, h, r);
  this.flux.push('W n');
};
Doc.prototype.finirDecoupe = function () { this.flux.push('Q'); };

Doc.prototype.texte = function (x, y, str, o) {
  o = o || {};
  var taille = o.size || 10;
  var police = o.mono ? (o.gras ? '/F5' : '/F4') : (o.gras ? '/F2' : (o.ital ? '/F3' : '/F1'));
  var l = larg(str, taille, o.gras, o.ital, o.mono);
  if (o.align === 'right') x -= l;
  else if (o.align === 'center') x -= l / 2;
  this.couleur(o.c || COUL.encre);
  var ecart = o.ls ? ' ' + o.ls.toFixed(2) + ' Tc' : '';
  this.flux.push('BT ' + police + ' ' + taille + ' Tf' + ecart + ' '
    + x.toFixed(2) + ' ' + pdfY(y).toFixed(2) + ' Td (' + echapper(str) + ') Tj ET'
    + (o.ls ? ' BT 0 Tc ET' : ''));
  return l;
};

/** Plus grande taille telle que `str` tienne dans `maxW`. */
Doc.prototype.tailleAjustee = function (str, maxW, taille, gras) {
  var t = taille;
  while (t > 4 && larg(str, t, gras) > maxW) t -= 0.25;
  return t;
};

Doc.prototype.texteAjuste = function (x, y, str, maxW, o) {
  o = o || {};
  var t = this.tailleAjustee(str, maxW, o.size || 10, o.gras);
  var copie = {}; for (var k in o) copie[k] = o[k];
  copie.size = t;
  return this.texte(x, y, str, copie);
};

/** Coupe un mot plus large que la colonne — un nom de route n'a pas d'espace. */
function tronconner(mot, taille, maxW, gras, mono) {
  var bouts = [], cur = '';
  for (var i = 0; i < mot.length; i++) {
    if (cur && larg(cur + mot[i], taille, gras, false, mono) > maxW) {
      /* Couper après un séparateur reste lisible : « admin.adhesions. » puis
         « periode » vaut mieux qu'une coupure au milieu d'un mot. */
      var coupe = Math.max(cur.lastIndexOf('.'), cur.lastIndexOf('/'), cur.lastIndexOf('-'), cur.lastIndexOf('_'));
      if (coupe >= cur.length / 3) {
        bouts.push(cur.slice(0, coupe + 1));
        cur = cur.slice(coupe + 1);
      } else { bouts.push(cur); cur = ''; }
    }
    cur += mot[i];
  }
  if (cur) bouts.push(cur);
  return bouts;
}

Doc.prototype.decouper = function (str, taille, maxW, gras, mono) {
  var mots = String(str).split(/\s+/), lignes = [], cur = '';
  for (var i = 0; i < mots.length; i++) {
    var mot = mots[i];

    /* Un mot seul qui déborde de la colonne : on le coupe, sinon il empiète
       sur la colonne voisine et rend les deux illisibles. */
    if (larg(mot, taille, gras, false, mono) > maxW) {
      if (cur) { lignes.push(cur); cur = ''; }
      var bouts = tronconner(mot, taille, maxW, gras, mono);
      for (var j = 0; j < bouts.length - 1; j++) lignes.push(bouts[j]);
      cur = bouts[bouts.length - 1];
      continue;
    }

    var essai = cur ? cur + ' ' + mot : mot;
    if (larg(essai, taille, gras, false, mono) > maxW && cur) { lignes.push(cur); cur = mot; }
    else { cur = essai; }
  }
  if (cur) lignes.push(cur);
  return lignes;
};

Doc.prototype.paragraphe = function (x, y, str, taille, maxW, o) {
  o = o || {};
  var lignes = this.decouper(str, taille, maxW, o.gras), lh = o.lh || taille * 1.35;
  var copie = {}; for (var k in o) copie[k] = o[k];
  copie.size = taille;
  for (var i = 0; i < lignes.length; i++) {
    var ligne = lignes[i];
    var px = x;
    if (o.align === 'center') px = x + maxW / 2;
    else if (o.align === 'right') px = x + maxW;
    this.texte(px, y + i * lh, ligne, copie);
  }
  return lignes.length * lh;
};

Doc.prototype.image = function (nom, x, y, w, h) {
  this.flux.push('q ' + w.toFixed(2) + ' 0 0 ' + h.toFixed(2) + ' '
    + x.toFixed(2) + ' ' + pdfY(y + h).toFixed(2) + ' cm /' + nom + ' Do Q');
};

Doc.prototype.ajouterImage = function (image) {
  if (!image) return null;
  for (var i = 0; i < this.images.length; i++) if (this.images[i].nom === image.nom) return image.nom;
  this.images.push(image);
  return image.nom;
};

Doc.prototype.produire = function () {
  var objs = [], nPages = this.pages.length, i;
  var idPage = [], idContenus = [], idImages = {}, num = 3;

  for (i = 0; i < nPages; i++) idPage.push(num++);
  for (i = 0; i < nPages; i++) idContenus.push(num++);
  var idF1 = num++, idF2 = num++, idF3 = num++, idF4 = num++, idF5 = num++;
  this.images.forEach(function (im) { idImages[im.nom] = num++; });

  objs[0] = '<< /Type /Catalog /Pages 2 0 R >>';
  objs[1] = '<< /Type /Pages /Kids [' + idPage.map(function (n) { return n + ' 0 R'; }).join(' ')
          + '] /Count ' + nPages + ' >>';

  var res = '<< /Font << /F1 ' + idF1 + ' 0 R /F2 ' + idF2 + ' 0 R /F3 ' + idF3 + ' 0 R'
          + ' /F4 ' + idF4 + ' 0 R /F5 ' + idF5 + ' 0 R >>';
  if (this.images.length) {
    res += ' /XObject << ' + this.images.map(function (im) {
      return '/' + im.nom + ' ' + idImages[im.nom] + ' 0 R';
    }).join(' ') + ' >>';
  }
  /* Nuanciers et états graphiques tiennent en dictionnaires directs : pas
     d'objet indirect à numéroter, donc rien à casser dans la table xref. */
  if (this.degrades.length) {
    res += ' /Shading << ' + this.degrades.map(function (d) {
      return '/' + d.nom + ' ' + d.dict;
    }).join(' ') + ' >>';
  }
  if (this.etats.length) {
    res += ' /ExtGState << ' + this.etats.map(function (e) {
      return '/' + e.nom + ' << /Type /ExtGState /ca ' + e.alpha + ' /CA ' + e.alpha + ' >>';
    }).join(' ') + ' >>';
  }
  res += ' >>';

  for (i = 0; i < nPages; i++) {
    objs[idPage[i] - 1] = '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 ' + A4.w + ' ' + A4.h + ']'
      + ' /Resources ' + res + ' /Contents ' + idContenus[i] + ' 0 R >>';
    var flux = this.pages[i].join('\n');
    objs[idContenus[i] - 1] = '<< /Length ' + flux.length + ' >>\nstream\n' + flux + '\nendstream';
  }

  objs[idF1 - 1] = '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica /Encoding /WinAnsiEncoding >>';
  objs[idF2 - 1] = '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold /Encoding /WinAnsiEncoding >>';
  objs[idF3 - 1] = '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Oblique /Encoding /WinAnsiEncoding >>';
  /* Chasse fixe : indispensable pour que les schemas en caracteres restent alignes. */
  objs[idF4 - 1] = '<< /Type /Font /Subtype /Type1 /BaseFont /Courier /Encoding /WinAnsiEncoding >>';
  objs[idF5 - 1] = '<< /Type /Font /Subtype /Type1 /BaseFont /Courier-Bold /Encoding /WinAnsiEncoding >>';

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
      + echapper(this.titre) + ') /Author (' + echapper(this.auteur) + ') >> >>\nstartxref\n' + xref + '\n%%EOF';

  var bytes = new Uint8Array(out.length);
  for (i = 0; i < out.length; i++) bytes[i] = out.charCodeAt(i) & 0xFF;
  return new Blob([bytes], { type: 'application/pdf' });
};

/* ── Chargement d'images ─────────────────────────────────────────────── */

/** Dimensions d'un JPEG, lues dans ses marqueurs SOF. */
function tailleJpeg(oct) {
  var p = 2;
  while (p < oct.length) {
    if (oct[p] !== 0xFF) { p++; continue; }
    var m = oct[p + 1];
    if (m >= 0xC0 && m <= 0xCF && m !== 0xC4 && m !== 0xC8 && m !== 0xCC) {
      return { h: (oct[p + 5] << 8) | oct[p + 6], w: (oct[p + 7] << 8) | oct[p + 8] };
    }
    p += 2 + ((oct[p + 2] << 8) | oct[p + 3]);
  }
  return null;
}

/**
 * Charge une image et la prépare pour le PDF.
 *
 * Un PDF n'accepte ici que du JPEG (filtre DCTDecode) : les PNG et WebP sont
 * donc réencodés via un canvas. C'est aussi ce qui permet d'accepter
 * n'importe quel format sans code supplémentaire.
 */
function chargerImage(url, nom, qualite) {
  return new Promise(function (resolve) {
    var img = new Image();
    img.crossOrigin = 'anonymous';

    img.onload = function () {
      try {
        var cv = document.createElement('canvas');
        cv.width = img.naturalWidth;
        cv.height = img.naturalHeight;
        var c = cv.getContext('2d');
        c.fillStyle = '#FFFFFF';
        c.fillRect(0, 0, cv.width, cv.height);   /* fond blanc sous la transparence */
        c.drawImage(img, 0, 0);

        var donnees = cv.toDataURL('image/jpeg', qualite || 0.92).split(',')[1];
        var bin = atob(donnees);
        var oct = new Uint8Array(bin.length);
        for (var i = 0; i < bin.length; i++) oct[i] = bin.charCodeAt(i);
        var t = tailleJpeg(oct) || { w: cv.width, h: cv.height };

        resolve({ nom: nom, bin: bin, w: t.w, h: t.h });
      } catch (e) {
        resolve(null);   /* image protégée (CORS) : on continue sans elle */
      }
    };

    img.onerror = function () { resolve(null); };
    img.src = url;
  });
}

/** Déclenche le téléchargement d'un Blob. */
function telecharger(blob, nomFichier) {
  var a = document.createElement('a');
  a.href = URL.createObjectURL(blob);
  a.download = nomFichier;
  document.body.appendChild(a);
  a.click();
  a.remove();
  setTimeout(function () { URL.revokeObjectURL(a.href); }, 5000);
}

global.MjaPdf = {
  A4: A4, MM: MM, COUL: COUL, hex: hex,
  Doc: Doc, larg: larg, chargerImage: chargerImage, telecharger: telecharger
};

})(window);
