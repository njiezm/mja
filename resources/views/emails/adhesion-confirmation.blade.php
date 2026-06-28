<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head>
<body style="margin:0;padding:0;background:#f4f6f9;font-family:'Helvetica Neue',Arial,sans-serif;color:#333">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f6f9;padding:32px 0">
  <tr><td align="center">
    <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,.08)">

      {{-- Header --}}
      <tr><td style="background:#2048A4;padding:28px 32px">
        <table width="100%" cellpadding="0" cellspacing="0">
          <tr>
            <td>
              <span style="font-size:26px;font-weight:900;letter-spacing:1px">
                <span style="color:#3DAEF5">M</span><span style="color:#F5A623">J</span><span style="color:#D0021B">A</span>
              </span>
              <div style="color:#bdd4f5;font-size:11px;font-weight:600;letter-spacing:2px;text-transform:uppercase;margin-top:2px">Madin'Jeunes Ambition</div>
            </td>
          </tr>
        </table>
      </td></tr>
      <tr><td style="height:4px;padding:0;background:linear-gradient(to right,#3DAEF5 33%,#F5A623 33%,#F5A623 66%,#D0021B 66%)"></td></tr>

      {{-- Body --}}
      <tr><td style="padding:36px 32px">
        <h1 style="margin:0 0 8px;font-size:22px;font-weight:800;color:#2048A4">Demande d'adhésion reçue !</h1>
        <p style="margin:0 0 24px;color:#555;font-size:15px;line-height:1.6">
          Bonjour <strong>{{ $adhesion->civilite }} {{ $adhesion->prenom }} {{ $adhesion->nom }}</strong>,<br>
          nous avons bien reçu votre demande d'adhésion à <strong>Madin'Jeunes Ambition</strong>. Voici un récapitulatif de vos informations.
        </p>

        {{-- Récap --}}
        <table width="100%" cellpadding="0" cellspacing="0" style="background:#f0f5ff;border-radius:10px;margin-bottom:24px;overflow:hidden">
          <tr><td style="padding:14px 20px;background:#2048A4">
            <span style="color:#fff;font-weight:700;font-size:13px;text-transform:uppercase;letter-spacing:1px">Vos informations</span>
          </td></tr>
          @foreach([
            ['Type de démarche',    $adhesion->label_premiere_adhesion],
            ['Civilité',            $adhesion->civilite],
            ['Nom',                 $adhesion->nom],
            ['Prénom',              $adhesion->prenom],
            ['Date de naissance',   $adhesion->date_naissance],
            ['Profession',          $adhesion->profession],
            ['Téléphone',           $adhesion->telephone],
            ['Email',               $adhesion->email],
            ['Adresse',             $adhesion->adresse_postale],
            ['Taille T-shirt',      $adhesion->taille_tshirt],
            ['Permis de conduire',  $adhesion->permis],
          ] as [$label, $val])
          <tr style="border-bottom:1px solid #dce8ff">
            <td style="padding:10px 20px;font-size:13px;color:#666;font-weight:600;width:45%">{{ $label }}</td>
            <td style="padding:10px 20px;font-size:13px;color:#2048A4;font-weight:700">{{ $val }}</td>
          </tr>
          @endforeach
          @if($adhesion->problemes_sante)
          <tr style="border-bottom:1px solid #dce8ff">
            <td style="padding:10px 20px;font-size:13px;color:#666;font-weight:600">Santé / Allergies</td>
            <td style="padding:10px 20px;font-size:13px;color:#2048A4;font-weight:700">{{ $adhesion->problemes_sante }}</td>
          </tr>
          @endif
        </table>

        {{-- Prochaines étapes --}}
        <table width="100%" cellpadding="0" cellspacing="0" style="background:#fffbeb;border-left:4px solid #F5A623;border-radius:0 8px 8px 0;margin-bottom:28px">
          <tr><td style="padding:16px 20px">
            <p style="margin:0 0 10px;font-weight:800;font-size:14px;color:#92400e">Prochaines étapes</p>
            <p style="margin:0 0 6px;font-size:13px;color:#555;line-height:1.6">
              1. Régler la <strong>cotisation de 20 €</strong> pour finaliser votre inscription.
            </p>
            <p style="margin:0 0 6px;font-size:13px;color:#555;line-height:1.6">
              2. Envoyer votre photo au secrétariat :<br>
              &nbsp;&nbsp;&nbsp;<strong>+596 696 43 88 21</strong> (Secrétaire)<br>
              &nbsp;&nbsp;&nbsp;<strong>+596 696 43 88 38</strong> (Secrétaire adjointe)
            </p>
            <p style="margin:0;font-size:13px;color:#555;line-height:1.6">
              3. Vous serez présenté(e) aux autres membres très prochainement.
            </p>
          </td></tr>
        </table>

        <p style="font-size:14px;color:#777;line-height:1.7;margin:0">
          Pour toute question, contactez-nous à <a href="mailto:contact@njiezm.fr" style="color:#2048A4;font-weight:600">contact@njiezm.fr</a> ou sur nos réseaux sociaux.<br>
          À très bientôt dans l'équipe !
        </p>
      </td></tr>

      {{-- Footer --}}
      <tr><td style="background:#f0f5ff;padding:20px 32px;text-align:center">
        <p style="margin:0;font-size:11px;color:#999">Madin'Jeunes Ambition — Fort-de-France, Martinique</p>
        <p style="margin:4px 0 0;font-size:11px;color:#bbb">Cet email vous a été envoyé automatiquement suite à votre demande d'adhésion.</p>
      </td></tr>

    </table>
  </td></tr>
</table>
</body>
</html>
