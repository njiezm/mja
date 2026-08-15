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
                     letter-spacing:.4mm;color:var(--yellow);text-transform:uppercase}

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
.numero{font-family:'Courier New',monospace;font-size:3mm;font-weight:700;letter-spacing:.5mm;color:#D5E4FB}
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
.attestation .nom{text-align:center;font-size:18px;font-weight:800;color:var(--navy);margin:18px 0}
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
        <button onclick="window.print()"><i class="fas fa-print"></i> Imprimer / PDF</button>
        <span class="astuce">
            Dans la fenêtre d'impression, coche <b>« Graphiques d'arrière-plan »</b> :
            sans cette option le navigateur imprime les aplats en blanc.
        </span>
    </div>

    {{-- ── Attestation ──────────────────────────────────────────── --}}
    <div class="attestation">
        <h1>Attestation d'adhésion</h1>
        <p>L'association <strong>Madin'Jeunes Ambition</strong>, association déclarée régie par la loi
           du 1<sup>er</sup> juillet 1901, atteste que&nbsp;:</p>
        <div class="nom">{{ $adhesion->civilite }} {{ $adhesion->prenom }} {{ $adhesion->nom }}</div>
        <p>est <strong>adhérent(e)</strong> de l'association
           @if($adhesion->period)pour la <strong>{{ $adhesion->period->label }}</strong>@endif
           et à jour de sa cotisation, sous le numéro
           <strong>MJA-{{ str_pad($adhesion->id, 5, '0', STR_PAD_LEFT) }}</strong>.</p>
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
                <div class="type">Carte<br>de membre</div>
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
                        <div class="cle">Saison</div>
                        <div class="val">{{ $adhesion->period?->label ?? '—' }}</div>
                    </div>
                    <div class="champ">
                        <div class="cle">Membre depuis</div>
                        <div class="val">{{ $adhesion->created_at?->format('m/Y') ?? '—' }}</div>
                    </div>
                </div>
            </div>

            <div class="pied">
                <span class="numero">N° MJA-{{ str_pad($adhesion->id, 5, '0', STR_PAD_LEFT) }}</span>
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
</body>
</html>
