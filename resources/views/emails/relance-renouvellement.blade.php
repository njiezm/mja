<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head>
<body style="margin:0;padding:0;background:#f4f6f9;font-family:'Helvetica Neue',Arial,sans-serif;color:#333">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f6f9;padding:32px 0">
  <tr><td align="center">
    <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,.08)">

      <tr><td style="background:#2048A4;padding:28px 32px">
        <span style="font-size:26px;font-weight:900;letter-spacing:1px">
          <span style="color:#3DAEF5">M</span><span style="color:#F5A623">J</span><span style="color:#D0021B">A</span>
        </span>
        <div style="color:#bdd4f5;font-size:11px;font-weight:600;letter-spacing:2px;text-transform:uppercase;margin-top:2px">Madin'Jeunes Ambition</div>
      </td></tr>
      <tr><td style="height:4px;padding:0;background:linear-gradient(to right,#3DAEF5 33%,#F5A623 33%,#F5A623 66%,#D0021B 66%)"></td></tr>

      <tr><td style="padding:36px 32px">
        @php
            $periode    = \App\Models\AdhesionPeriod::current();
            $cotisation = \App\Support\Cotisation::formatee();
        @endphp

        <h1 style="margin:0 0 8px;font-size:22px;font-weight:800;color:#2048A4">
          On continue ensemble{{ $periode ? ' en ' . $periode->label : '' }} ?
        </h1>

        <p style="margin:0 0 20px;color:#555;font-size:15px;line-height:1.6">
          Bonjour <strong>{{ $adhesion->prenom }}</strong>,<br>
          votre adhésion{{ $adhesion->period ? ' ' . $adhesion->period->label : '' }} arrive à son terme.
          Merci pour votre engagement cette année — nous serions ravis de vous compter à nouveau parmi nous.
        </p>

        <table width="100%" cellpadding="0" cellspacing="0" style="background:#f0f5ff;border-left:4px solid #3DAEF5;border-radius:0 8px 8px 0;margin-bottom:24px">
          <tr><td style="padding:16px 20px">
            <p style="margin:0;font-size:14px;color:#2048A4;font-weight:700">Rien à ressaisir</p>
            <p style="margin:6px 0 0;font-size:13px;color:#555;line-height:1.6">
              Le lien ci-dessous ouvre votre formulaire <strong>déjà pré-rempli</strong> avec vos informations
              (et votre photo). Vérifiez ce qui a changé, corrigez si besoin, réglez la cotisation
              de {{ $cotisation }} : c'est terminé en deux minutes.
            </p>
          </td></tr>
        </table>

        <table cellpadding="0" cellspacing="0" style="margin:0 0 24px">
          <tr><td style="background:#F5A623;border-radius:10px">
            <a href="{{ $lien ?: route('adhesion') }}" style="display:inline-block;padding:14px 28px;color:#14264D;font-weight:800;font-size:15px;text-decoration:none">
              Renouveler mon adhésion
            </a>
          </td></tr>
        </table>

        <p style="font-size:13px;color:#999;line-height:1.7;margin:0 0 16px">
          Ce lien est personnel : ne le transmettez pas. Il reste valable 90 jours.
        </p>

        <p style="font-size:14px;color:#777;line-height:1.7;margin:0">
          Vous ne souhaitez pas renouveler ? Aucun souci, ignorez simplement cet email —
          et merci pour tout ce que vous avez apporté à MJA.
        </p>
      </td></tr>

      <tr><td style="background:#f0f5ff;padding:20px 32px;text-align:center">
        <p style="margin:0;font-size:11px;color:#999">Madin'Jeunes Ambition — Martinique et au-delà</p>
      </td></tr>

    </table>
  </td></tr>
</table>
</body>
</html>
