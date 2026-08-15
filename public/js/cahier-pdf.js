/* =====================================================================
   Cahier du projet — export PDF.

   Parcourt le même modèle de blocs que la page HTML : une seule source de
   contenu, deux rendus. Le moteur (pages, texte, images) vient de
   mja-pdf.js ; ici on ne décrit que la mise en page du document.
   ===================================================================== */
(function () {
'use strict';

var P = window.MjaPdf;
var bouton = document.getElementById('btn-pdf');
var imprimer = document.getElementById('btn-imprimer');

if (imprimer) imprimer.addEventListener('click', function () { window.print(); });
if (!P || !bouton || typeof CAHIER === 'undefined') return;

var A4 = P.A4, C = P.COUL;
var MARGE = 48;
var L = A4.w - 2 * MARGE;
var BAS = A4.h - 62;          /* limite basse avant pied de page */

/* ── Rendu d'un bloc ─────────────────────────────────────────────── */

function place(doc, besoin, entete) {
  if (doc.y + besoin > BAS) entete();
}

function paragraphe(doc, texte, entete) {
  var lignes = doc.decouper(texte, 10, L);
  /* On coupe le paragraphe entre deux lignes plutôt que de le repousser
     entier : un pavé de dix lignes créerait sinon des pages à moitié vides. */
  for (var i = 0; i < lignes.length; i++) {
    place(doc, 15, entete);
    doc.texte(MARGE, doc.y + 10, lignes[i], { size: 10, c: C.texte });
    doc.y += 14.5;
  }
  doc.y += 7;
}

function sousTitre(doc, texte, entete) {
  place(doc, 40, entete);
  doc.y += 8;
  doc.texte(MARGE, doc.y + 11, texte, { size: 12, gras: true, c: C.encre });
  doc.y += 16;
  doc.rect(MARGE, doc.y, 26, 2, C.jaune);
  doc.y += 12;
}

function liste(doc, items, entete) {
  items.forEach(function (item) {
    var lignes = doc.decouper(item, 10, L - 16);
    place(doc, lignes.length * 14 + 4, entete);
    doc.rectArrondi(MARGE + 2, doc.y + 5, 4, 4, 2, C.blue);
    for (var i = 0; i < lignes.length; i++) {
      doc.texte(MARGE + 16, doc.y + 10, lignes[i], { size: 10, c: C.texte });
      doc.y += 14;
    }
    doc.y += 3;
  });
  doc.y += 6;
}

function etapes(doc, items, entete) {
  var lcle = 150;
  items.forEach(function (paire) {
    var lignes = doc.decouper(paire[1], 10, L - lcle - 12);
    place(doc, Math.max(lignes.length * 14, 16) + 6, entete);
    doc.texteAjuste(MARGE, doc.y + 10, paire[0], lcle - 10, { size: 10, gras: true, c: C.dark });
    for (var i = 0; i < lignes.length; i++) {
      doc.texte(MARGE + lcle, doc.y + 10, lignes[i], { size: 10, c: C.texte });
      doc.y += 14;
    }
    doc.y += 5;
  });
  doc.y += 4;
}

function tableau(doc, def, entete) {
  var nCol = def.entetes.length;

  /* Largeurs proportionnelles au contenu le plus long de chaque colonne,
     bornées pour qu'aucune ne dévore les autres. */
  var poids = def.entetes.map(function (e, i) {
    var max = P.larg(e, 9, true);
    def.lignes.forEach(function (l) { max = Math.max(max, P.larg(String(l[i] || ''), 9, false)); });
    return Math.min(Math.max(max, 50), L * 0.55);
  });
  var total = poids.reduce(function (a, b) { return a + b; }, 0);
  var larg = poids.map(function (p) { return p / total * L; });

  function ligneEntete() {
    doc.rect(MARGE, doc.y, L, 20, C.bleuPale);
    var x = MARGE;
    def.entetes.forEach(function (e, i) {
      doc.texteAjuste(x + 7, doc.y + 13.5, e.toUpperCase(), larg[i] - 12,
                      { size: 7.5, gras: true, c: C.navy, ls: 0.4 });
      x += larg[i];
    });
    doc.y += 20;
  }

  place(doc, 60, entete);
  ligneEntete();

  def.lignes.forEach(function (ligne, n) {
    /* Hauteur nécessaire : la cellule la plus haute commande la ligne. */
    var cellules = ligne.map(function (v, i) { return doc.decouper(String(v || ''), 9, larg[i] - 14); });
    var h = Math.max.apply(null, cellules.map(function (c) { return c.length; })) * 12 + 9;

    if (doc.y + h > BAS) { entete(); ligneEntete(); }

    if (n % 2) doc.rect(MARGE, doc.y, L, h, [0.976, 0.984, 0.996]);
    var x = MARGE;
    cellules.forEach(function (c, i) {
      for (var j = 0; j < c.length; j++) {
        doc.texte(x + 7, doc.y + 12 + j * 12, c[j],
                  { size: 9, gras: i === 0, c: i === 0 ? C.encre : C.texte });
      }
      x += larg[i];
    });
    doc.y += h;
    doc.rect(MARGE, doc.y, L, 0.6, C.trait);
  });

  doc.y += 14;
}

function note(doc, texte, entete) {
  var lignes = doc.decouper(texte, 9.5, L - 34);
  var h = lignes.length * 13 + 18;
  place(doc, h + 6, entete);
  doc.rectArrondi(MARGE, doc.y, L, h, 7, C.creme);
  doc.rectArrondi(MARGE + 12, doc.y + h / 2 - 3, 6, 6, 3, C.jaune);
  for (var i = 0; i < lignes.length; i++) {
    doc.texte(MARGE + 28, doc.y + 16 + i * 13, lignes[i], { size: 9.5, c: [0.471, 0.349, 0.110] });
  }
  doc.y += h + 12;
}

function code(doc, texte, entete) {
  var lignes = String(texte).split('\n');
  var h = lignes.length * 12 + 16;
  place(doc, h + 6, entete);
  doc.rectArrondi(MARGE, doc.y, L, h, 7, C.encre);
  for (var i = 0; i < lignes.length; i++) {
    /* Helvetica plutôt qu'une chasse fixe : le PDF n'embarque que les
       polices standard, et Courier rendrait mal les accents ici absents. */
    doc.texte(MARGE + 14, doc.y + 20 + i * 12, lignes[i], { size: 8.5, c: [0.847, 0.894, 0.973] });
  }
  doc.y += h + 12;
}

/* ── Composition ─────────────────────────────────────────────────── */
function composer(logo) {
  var doc = new P.Doc({ marge: MARGE, titre: "Cahier du projet — Site Madin'Jeunes Ambition" });
  var page = 0;

  function filets(y, h) {
    var t = A4.w / 3;
    doc.rect(0, y, t + 0.5, h, C.blue);
    doc.rect(t, y, t + 0.5, h, C.jaune);
    doc.rect(2 * t, y, t, h, C.rouge);
  }

  function entete() {
    page = doc.nouvellePage();
    doc.rect(0, 0, A4.w, 3, C.navy);
    if (logo) doc.image(logo, MARGE, 20, 22, 22);
    doc.texte(MARGE + (logo ? 30 : 0), 36, 'Cahier du projet — MJA', { size: 9, gras: true, c: C.gris });
    doc.texte(A4.w - MARGE, 36, 'Documentation technique', { size: 9, c: C.gris, align: 'right' });
    doc.rect(MARGE, 46, L, 0.8, C.trait);
    doc.y = 72;
  }

  /* ---- Page de garde ---- */
  doc.nouvellePage();
  doc.rect(0, 0, A4.w, 268, C.navy);
  filets(0, 7);

  if (logo) {
    doc.rectArrondi(MARGE, 46, 66, 66, 11, C.blanc);
    doc.image(logo, MARGE + 6, 52, 54, 54);
  }
  doc.texte(MARGE + 82, 72, "MADIN' JEUNES AMBITION", { size: 15, gras: true, c: C.blanc, ls: 0.6 });
  doc.texte(MARGE + 82, 90, 'Relève tous les défis !', { size: 11, ital: true, c: C.bleuClair });

  doc.texte(MARGE, 168, 'CAHIER DU PROJET', { size: 30, gras: true, c: C.blanc });
  doc.texte(MARGE, 198, 'Documentation technique et fonctionnelle du site', { size: 14, c: [0.788, 0.859, 0.980] });
  doc.texte(MARGE, 224, "Pour toute personne qui reprend ou découvre le projet", { size: 10.5, c: C.bleuClair });

  var stats = [['135', 'routes'], ['18', 'tables'], ['88', 'gabarits'], ['32', 'migrations']];
  var cw = (L - 3 * 10) / 4;
  stats.forEach(function (s, i) {
    var x = MARGE + i * (cw + 10);
    doc.rectArrondi(x, 300, cw, 54, 8, C.bleuPale);
    doc.texte(x + cw / 2, 326, s[0], { size: 20, gras: true, c: C.navy, align: 'center' });
    doc.texte(x + cw / 2, 343, s[1], { size: 9, c: C.gris, align: 'center' });
  });

  /* Sommaire en page de garde */
  var y = 392;
  doc.texte(MARGE, y, 'SOMMAIRE', { size: 10, gras: true, c: C.dark, ls: 0.6 });
  y += 8;
  doc.rect(MARGE, y, 34, 2.4, C.jaune);
  y += 24;

  CAHIER.forEach(function (s, i) {
    doc.rectArrondi(MARGE, y - 11, 18, 18, 5, C.navy);
    doc.texte(MARGE + 9, y + 1, String(i + 1), { size: 9, gras: true, c: C.blanc, align: 'center' });
    doc.texte(MARGE + 28, y + 1, s.titre, { size: 11, gras: true, c: C.encre });
    y += 26;
  });

  /* ---- Corps ---- */
  entete();

  CAHIER.forEach(function (section, i) {
    place(doc, 90, entete);
    doc.rectArrondi(MARGE, doc.y, 24, 24, 6, C.navy);
    doc.texte(MARGE + 12, doc.y + 16, String(i + 1), { size: 12, gras: true, c: C.blanc, align: 'center' });
    doc.texte(MARGE + 34, doc.y + 17, section.titre, { size: 17, gras: true, c: C.navy });
    doc.y += 30;
    doc.rect(MARGE, doc.y, L, 2, C.jaune);
    doc.y += 18;

    section.blocs.forEach(function (bloc) {
      var type = bloc[0], contenu = bloc[1];
      if (type === 'p')            paragraphe(doc, contenu, entete);
      else if (type === 'sous')    sousTitre(doc, contenu, entete);
      else if (type === 'liste')   liste(doc, contenu, entete);
      else if (type === 'etapes')  etapes(doc, contenu, entete);
      else if (type === 'table')   tableau(doc, contenu, entete);
      else if (type === 'note')    note(doc, contenu, entete);
      else if (type === 'code')    code(doc, contenu, entete);
    });

    doc.y += 10;
  });

  /* ---- Pied de page, hors page de garde ---- */
  for (var p = 1; p < doc.pages.length; p++) {
    doc.flux = doc.pages[p];
    doc.rect(MARGE, A4.h - 44, L, 0.8, C.trait);
    doc.texte(MARGE, A4.h - 30, "Madin' Jeunes Ambition — Cahier du projet", { size: 8, c: C.gris });
    doc.texte(A4.w - MARGE, A4.h - 30, 'Page ' + p + ' / ' + (doc.pages.length - 1),
              { size: 8, c: C.gris, align: 'right' });
  }

  return doc;
}

/* ── Déclenchement ───────────────────────────────────────────────── */
bouton.addEventListener('click', function () {
  var initial = bouton.innerHTML;
  bouton.disabled = true;
  bouton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Génération…';

  P.chargerImage(bouton.dataset.logo, 'Logo').then(function (logo) {
    var doc = composer(logo ? 'Logo' : null);
    if (logo) doc.ajouterImage(logo);
    P.telecharger(doc.produire(), 'cahier-projet-mja.pdf');
  }).catch(function (e) {
    alert('La génération du PDF a échoué : ' + e.message);
  }).then(function () {
    bouton.disabled = false;
    bouton.innerHTML = initial;
  });
});

})();
