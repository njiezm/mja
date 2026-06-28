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
        <h1 style="margin:0 0 8px;font-size:22px;font-weight:800;color:#2048A4">Réinitialisation de mot de passe</h1>
        <p style="margin:0 0 24px;color:#555;font-size:15px;line-height:1.6">
          Vous recevez cet email car une demande de réinitialisation de mot de passe a été effectuée pour votre compte backoffice <strong>Madin'Jeunes Ambition</strong>.
        </p>

        <p style="margin:0 0 20px;color:#555;font-size:14px;line-height:1.6">
          Cliquez sur le bouton ci-dessous pour choisir un nouveau mot de passe. Ce lien est valable <strong>60 minutes</strong>.
        </p>

        <table cellpadding="0" cellspacing="0" style="margin-bottom:28px">
          <tr>
            <td style="border-radius:8px;background:#2048A4">
              <a href="{{ $resetUrl }}" style="display:inline-block;color:#fff;font-weight:700;font-size:15px;padding:14px 32px;text-decoration:none;border-radius:8px">
                Réinitialiser mon mot de passe →
              </a>
            </td>
          </tr>
        </table>

        <table width="100%" cellpadding="0" cellspacing="0" style="background:#fffbeb;border-left:4px solid #F5A623;border-radius:0 8px 8px 0;margin-bottom:24px">
          <tr><td style="padding:14px 18px">
            <p style="margin:0;font-size:13px;color:#92400e;line-height:1.6">
              Si vous n'avez pas demandé cette réinitialisation, ignorez cet email. Votre mot de passe restera inchangé.
            </p>
          </td></tr>
        </table>

        <p style="font-size:12px;color:#aaa;margin:0 0 8px">Si le bouton ne fonctionne pas, copiez ce lien dans votre navigateur :</p>
        <p style="font-size:12px;color:#2048A4;word-break:break-all;margin:0">{{ $resetUrl }}</p>
      </td></tr>

      <tr><td style="background:#f0f5ff;padding:16px 32px;text-align:center">
        <p style="margin:0;font-size:11px;color:#999">Madin'Jeunes Ambition — Notification automatique backoffice</p>
      </td></tr>

    </table>
  </td></tr>
</table>
</body>
</html>
