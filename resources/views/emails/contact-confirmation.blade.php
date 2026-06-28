<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head>
<body style="margin:0;padding:0;background:#f4f6f9;font-family:'Helvetica Neue',Arial,sans-serif;color:#333">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f6f9;padding:32px 0">
  <tr><td align="center">
    <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,.08)">

      {{-- Header --}}
      <tr><td style="background:#2048A4;padding:28px 32px">
        <span style="font-size:26px;font-weight:900;letter-spacing:1px">
          <span style="color:#3DAEF5">M</span><span style="color:#F5A623">J</span><span style="color:#D0021B">A</span>
        </span>
        <div style="color:#bdd4f5;font-size:11px;font-weight:600;letter-spacing:2px;text-transform:uppercase;margin-top:2px">Madin'Jeunes Ambition</div>
      </td></tr>
      <tr><td style="height:4px;padding:0;background:linear-gradient(to right,#3DAEF5 33%,#F5A623 33%,#F5A623 66%,#D0021B 66%)"></td></tr>

      {{-- Body --}}
      <tr><td style="padding:36px 32px">
        <h1 style="margin:0 0 8px;font-size:22px;font-weight:800;color:#2048A4">Message bien reçu !</h1>
        <p style="margin:0 0 24px;color:#555;font-size:15px;line-height:1.6">
          Bonjour <strong>{{ $contact->nom }}</strong>,<br>
          nous avons bien reçu votre message et nous vous répondrons dans les plus brefs délais.
        </p>

        {{-- Copie du message --}}
        <table width="100%" cellpadding="0" cellspacing="0" style="background:#f0f5ff;border-radius:10px;margin-bottom:28px;overflow:hidden">
          <tr><td style="padding:14px 20px;background:#2048A4">
            <span style="color:#fff;font-weight:700;font-size:13px;text-transform:uppercase;letter-spacing:1px">Copie de votre message</span>
          </td></tr>
          @foreach([
            ['Sujet',     $contact->sujet],
            ['Email',     $contact->email],
            ['Téléphone', $contact->telephone ?: '—'],
          ] as [$label, $val])
          <tr style="border-bottom:1px solid #dce8ff">
            <td style="padding:10px 20px;font-size:13px;color:#666;font-weight:600;width:35%">{{ $label }}</td>
            <td style="padding:10px 20px;font-size:13px;color:#2048A4;font-weight:700">{{ $val }}</td>
          </tr>
          @endforeach
          <tr>
            <td colspan="2" style="padding:14px 20px;font-size:13px;color:#444;line-height:1.7;border-top:1px solid #dce8ff">
              {{ $contact->message }}
            </td>
          </tr>
        </table>

        <p style="font-size:14px;color:#777;line-height:1.7;margin:0">
          Pour toute question urgente : <a href="mailto:contact@njiezm.fr" style="color:#2048A4;font-weight:600">contact@njiezm.fr</a>
        </p>
      </td></tr>

      {{-- Footer --}}
      <tr><td style="background:#f0f5ff;padding:20px 32px;text-align:center">
        <p style="margin:0;font-size:11px;color:#999">Madin'Jeunes Ambition — Fort-de-France, Martinique</p>
        <p style="margin:4px 0 0;font-size:11px;color:#bbb">Cet email vous a été envoyé automatiquement suite à votre prise de contact.</p>
      </td></tr>

    </table>
  </td></tr>
</table>
</body>
</html>
