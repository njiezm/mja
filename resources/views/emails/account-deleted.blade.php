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
        <h1 style="margin:0 0 12px;font-size:22px;font-weight:800;color:#2048A4">Votre compte a été supprimé</h1>
        <p style="margin:0 0 16px;color:#555;font-size:15px;line-height:1.7">
          Bonjour,<br>
          Votre compte membre Madin'Jeunes Ambition a bien été supprimé, comme demandé.
        </p>

        <table width="100%" cellpadding="0" cellspacing="0" style="background:#fffbeb;border-left:4px solid #F5A623;border-radius:0 8px 8px 0;margin-bottom:24px">
          <tr><td style="padding:16px 20px">
            <p style="margin:0 0 6px;font-weight:800;font-size:14px;color:#92400e">Changé d'avis ?</p>
            <p style="margin:0;font-size:13px;color:#555;line-height:1.6">
              Vous avez <strong>jusqu'au {{ $purgeDate }}</strong> pour restaurer votre compte. Passé ce délai, il sera <strong>définitivement supprimé</strong> et ne pourra plus être récupéré.
            </p>
          </td></tr>
        </table>

        <p style="margin:0 0 8px">
          <a href="{{ $restoreUrl }}" style="display:inline-block;background:#3DAEF5;color:#fff;text-decoration:none;font-weight:700;font-size:15px;padding:12px 24px;border-radius:10px">Restaurer mon compte</a>
        </p>
        <p style="margin:16px 0 0;color:#999;font-size:12px;line-height:1.6">
          Si vous êtes bien à l'origine de cette suppression et ne souhaitez pas restaurer votre compte, vous pouvez ignorer cet email.
        </p>
      </td></tr>

      <tr><td style="background:#f0f5ff;padding:20px 32px;text-align:center">
        <p style="margin:0;font-size:11px;color:#999">Madin'Jeunes Ambition — Fort-de-France, Martinique</p>
      </td></tr>

    </table>
  </td></tr>
</table>
</body>
</html>
