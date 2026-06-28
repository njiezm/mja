<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head>
<body style="margin:0;padding:0;background:#f4f6f9;font-family:'Helvetica Neue',Arial,sans-serif;color:#333">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f6f9;padding:32px 0">
  <tr><td align="center">
    <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,.08)">

      {{-- Header --}}
      <tr><td style="background:#1A3D8A;padding:28px 32px">
        <span style="font-size:26px;font-weight:900;letter-spacing:1px">
          <span style="color:#3DAEF5">M</span><span style="color:#F5A623">J</span><span style="color:#D0021B">A</span>
        </span>
        <span style="color:#bdd4f5;font-size:13px;font-weight:600;margin-left:12px">Backoffice — Nouvelle adhésion</span>
      </td></tr>
      <tr><td style="height:4px;padding:0;background:linear-gradient(to right,#3DAEF5 33%,#F5A623 33%,#F5A623 66%,#D0021B 66%)"></td></tr>

      {{-- Alert --}}
      <tr><td style="padding:28px 32px 0">
        <table width="100%" cellpadding="0" cellspacing="0" style="background:#2048A4;border-radius:10px;margin-bottom:24px">
          <tr><td style="padding:18px 22px">
            <p style="margin:0;color:#fff;font-size:16px;font-weight:800">Nouvelle demande d'adhésion</p>
            <p style="margin:6px 0 0;color:#bdd4f5;font-size:13px">
              Reçue le {{ now()->locale('fr')->isoFormat('D MMMM YYYY à HH:mm') }}
            </p>
          </td></tr>
        </table>

        {{-- Toutes les données --}}
        <table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e5ecff;border-radius:10px;overflow:hidden;margin-bottom:16px">
          <tr><td colspan="2" style="padding:12px 20px;background:#2048A4">
            <span style="color:#fff;font-weight:700;font-size:13px;text-transform:uppercase;letter-spacing:1px">Identité & Coordonnées</span>
          </td></tr>
          @foreach([
            ['Type',                $adhesion->label_premiere_adhesion],
            ['Civilité',            $adhesion->civilite],
            ['Nom',                 $adhesion->nom],
            ['Prénom',              $adhesion->prenom],
            ['Date de naissance',   $adhesion->date_naissance],
            ['Profession',          $adhesion->profession],
            ['Téléphone',           $adhesion->telephone],
            ['Email',               $adhesion->email],
            ['Adresse postale',     $adhesion->adresse_postale],
          ] as [$label, $val])
          <tr style="border-bottom:1px solid #f0f4ff">
            <td style="padding:9px 20px;font-size:13px;color:#666;font-weight:600;width:40%;background:#fafbff">{{ $label }}</td>
            <td style="padding:9px 20px;font-size:13px;color:#1a1a1a">{{ $val }}</td>
          </tr>
          @endforeach
        </table>

        <table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e5ecff;border-radius:10px;overflow:hidden;margin-bottom:24px">
          <tr><td colspan="2" style="padding:12px 20px;background:#2048A4">
            <span style="color:#fff;font-weight:700;font-size:13px;text-transform:uppercase;letter-spacing:1px">Informations complémentaires</span>
          </td></tr>
          @foreach([
            ['Taille T-shirt',      $adhesion->taille_tshirt],
            ['Permis',              $adhesion->permis],
            ['Santé / Allergies',   $adhesion->problemes_sante ?: '—'],
            ['Urgence contact',     $adhesion->urgence_contact],
            ['Droit à l\'image',    $adhesion->droit_image ? 'Accepté' : 'Refusé'],
          ] as [$label, $val])
          <tr style="border-bottom:1px solid #f0f4ff">
            <td style="padding:9px 20px;font-size:13px;color:#666;font-weight:600;width:40%;background:#fafbff">{{ $label }}</td>
            <td style="padding:9px 20px;font-size:13px;color:#1a1a1a">{{ $val }}</td>
          </tr>
          @endforeach
        </table>

        <a href="{{ url('/admin/adhesions') }}" style="display:inline-block;background:#2048A4;color:#fff;font-weight:700;font-size:14px;padding:12px 24px;border-radius:8px;text-decoration:none;margin-bottom:28px">
          Voir dans le backoffice →
        </a>
      </td></tr>

      {{-- Footer --}}
      <tr><td style="background:#f0f5ff;padding:16px 32px;text-align:center">
        <p style="margin:0;font-size:11px;color:#999">Madin'Jeunes Ambition — Notification automatique backoffice</p>
      </td></tr>

    </table>
  </td></tr>
</table>
</body>
</html>
