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
        <div style="color:#bdd4f5;font-size:11px;font-weight:600;letter-spacing:2px;text-transform:uppercase;margin-top:2px">Madin'Jeunes Ambition — Espace adhérent</div>
      </td></tr>
      <tr><td style="height:4px;padding:0;background:linear-gradient(to right,#3DAEF5 33%,#F5A623 33%,#F5A623 66%,#D0021B 66%)"></td></tr>

      <tr><td style="padding:36px 32px">
        <h1 style="margin:0 0 12px;font-size:22px;font-weight:800;color:#2048A4">
          {{ $nouveauCompte ? 'Votre espace adhérent est prêt' : 'Votre nouveau mot de passe' }}
        </h1>
        <p style="margin:0 0 24px;color:#555;font-size:15px;line-height:1.7">
          Bonjour{{ $member->adhesion ? ' ' . $member->adhesion->prenom : '' }},<br>
          @if($nouveauCompte)
            un accès à votre espace adhérent Madin'Jeunes Ambition vient d'être créé pour vous.
          @else
            le mot de passe de votre espace adhérent vient d'être réinitialisé par l'association.
            <strong>L'ancien mot de passe ne fonctionne plus.</strong>
          @endif
        </p>

        <table width="100%" cellpadding="0" cellspacing="0" style="background:#f0f5ff;border-radius:10px;margin-bottom:28px;overflow:hidden">
          <tr><td colspan="2" style="padding:14px 20px;background:#2048A4">
            <span style="color:#fff;font-weight:700;font-size:13px;text-transform:uppercase;letter-spacing:1px">Vos identifiants</span>
          </td></tr>
          <tr style="border-bottom:1px solid #dce8ff">
            <td style="padding:12px 20px;font-size:13px;color:#666;font-weight:600;width:35%">Adresse email</td>
            <td style="padding:12px 20px;font-size:14px;color:#2048A4;font-weight:700">{{ $member->email }}</td>
          </tr>
          <tr>
            <td style="padding:12px 20px;font-size:13px;color:#666;font-weight:600">Mot de passe</td>
            <td style="padding:12px 20px;font-size:16px;color:#2048A4;font-weight:700;font-family:monospace;letter-spacing:1px">{{ $plainPassword }}</td>
          </tr>
        </table>

        <table width="100%" cellpadding="0" cellspacing="0" style="background:#fffbeb;border-left:4px solid #F5A623;border-radius:0 8px 8px 0;margin-bottom:28px">
          <tr><td style="padding:14px 18px">
            <p style="margin:0;font-size:13px;color:#92400e;line-height:1.6">
              <strong>Conseil :</strong> ce mot de passe est temporaire. Changez-le depuis
              « Modifier mes informations » après votre connexion.
            </p>
          </td></tr>
        </table>

        <a href="{{ route('member.login') }}" style="display:inline-block;background:#2048A4;color:#fff;font-weight:700;font-size:14px;padding:14px 28px;border-radius:8px;text-decoration:none;margin-bottom:28px">
          Accéder à mon espace →
        </a>

        <p style="font-size:13px;color:#999;margin:0">
          Vous n'êtes pas à l'origine de cette demande ? Contactez l'association.
        </p>
      </td></tr>

      <tr><td style="background:#f0f5ff;padding:16px 32px;text-align:center">
        <p style="margin:0;font-size:11px;color:#999">Madin'Jeunes Ambition — Notification automatique</p>
      </td></tr>

    </table>
  </td></tr>
</table>
</body>
</html>
