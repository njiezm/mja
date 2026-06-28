<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head>
<body style="margin:0;padding:0;background:#f4f6f9;font-family:'Helvetica Neue',Arial,sans-serif;color:#333">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f6f9;padding:32px 0">
  <tr><td align="center">
    <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,.08)">

      <tr><td style="background:#1A3D8A;padding:28px 32px">
        <span style="font-size:26px;font-weight:900;letter-spacing:1px">
          <span style="color:#3DAEF5">M</span><span style="color:#F5A623">J</span><span style="color:#D0021B">A</span>
        </span>
        <div style="color:#bdd4f5;font-size:11px;font-weight:600;letter-spacing:2px;text-transform:uppercase;margin-top:2px">Madin'Jeunes Ambition — Backoffice</div>
      </td></tr>
      <tr><td style="height:4px;padding:0;background:linear-gradient(to right,#3DAEF5 33%,#F5A623 33%,#F5A623 66%,#D0021B 66%)"></td></tr>

      <tr><td style="padding:36px 32px">
        <h1 style="margin:0 0 8px;font-size:22px;font-weight:800;color:#2048A4">Bienvenue dans l'équipe !</h1>
        <p style="margin:0 0 24px;color:#555;font-size:15px;line-height:1.6">
          Bonjour <strong>{{ $user->name }}</strong>,<br>
          un compte administrateur a été créé pour vous sur le backoffice de <strong>Madin'Jeunes Ambition</strong>.
        </p>

        <table width="100%" cellpadding="0" cellspacing="0" style="background:#f0f5ff;border-radius:10px;margin-bottom:28px;overflow:hidden">
          <tr><td style="padding:14px 20px;background:#2048A4">
            <span style="color:#fff;font-weight:700;font-size:13px;text-transform:uppercase;letter-spacing:1px">Vos identifiants de connexion</span>
          </td></tr>
          <tr style="border-bottom:1px solid #dce8ff">
            <td style="padding:12px 20px;font-size:13px;color:#666;font-weight:600;width:35%">Adresse email</td>
            <td style="padding:12px 20px;font-size:14px;color:#2048A4;font-weight:700">{{ $user->email }}</td>
          </tr>
          <tr style="border-bottom:1px solid #dce8ff">
            <td style="padding:12px 20px;font-size:13px;color:#666;font-weight:600">Mot de passe</td>
            <td style="padding:12px 20px;font-size:14px;color:#2048A4;font-weight:700;font-family:monospace">{{ $plainPassword }}</td>
          </tr>
          <tr>
            <td style="padding:12px 20px;font-size:13px;color:#666;font-weight:600">Rôle</td>
            <td style="padding:12px 20px;font-size:13px;color:#333">{{ $user->role === 'super_admin' ? 'Super Administrateur' : 'Administrateur' }}</td>
          </tr>
        </table>

        <table width="100%" cellpadding="0" cellspacing="0" style="background:#fffbeb;border-left:4px solid #F5A623;border-radius:0 8px 8px 0;margin-bottom:28px">
          <tr><td style="padding:14px 18px">
            <p style="margin:0;font-size:13px;color:#92400e;line-height:1.6">
              <strong>Conseil sécurité :</strong> Pensez à changer votre mot de passe lors de votre première connexion.
            </p>
          </td></tr>
        </table>

        <a href="{{ url('/admin') }}" style="display:inline-block;background:#2048A4;color:#fff;font-weight:700;font-size:14px;padding:14px 28px;border-radius:8px;text-decoration:none;margin-bottom:28px">
          Accéder au backoffice →
        </a>

        <p style="font-size:13px;color:#999;margin:0">
          En cas de problème, contactez l'équipe : <a href="mailto:contact@njiezm.fr" style="color:#2048A4">contact@njiezm.fr</a>
        </p>
      </td></tr>

      <tr><td style="background:#f0f5ff;padding:16px 32px;text-align:center">
        <p style="margin:0;font-size:11px;color:#999">Madin'Jeunes Ambition — Notification automatique backoffice</p>
      </td></tr>

    </table>
  </td></tr>
</table>
</body>
</html>
