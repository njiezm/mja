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
        <p style="margin:0 0 20px;color:#555;font-size:15px;line-height:1.6">
          Bonjour <strong>{{ $adhesion->prenom }} {{ $adhesion->nom }}</strong>,
        </p>

        @switch($adhesion->statut)
          @case('payee')
            <h1 style="margin:0 0 12px;font-size:22px;font-weight:800;color:#16a34a">Vous êtes officiellement adhérent(e) ! 🎉</h1>
            <p style="margin:0 0 16px;color:#555;font-size:15px;line-height:1.7">
              Nous confirmons la réception de votre cotisation. Bienvenue dans la famille <strong>Madin'Jeunes Ambition</strong> !
            </p>
            @if($adhesion->accountCreationUrl())
            <p style="margin:0 0 16px;color:#555;font-size:15px;line-height:1.7">
              Créez dès maintenant votre <strong>espace membre</strong> pour gérer vos informations et accéder au trombinoscope de l'association :
            </p>
            <p style="margin:0 0 8px">
              <a href="{{ $adhesion->accountCreationUrl() }}" style="display:inline-block;background:#3DAEF5;color:#fff;text-decoration:none;font-weight:700;font-size:15px;padding:12px 24px;border-radius:10px">Créer mon espace membre</a>
            </p>
            <p style="margin:0;color:#999;font-size:12px;line-height:1.6">Ce lien est personnel et valable 30 jours.</p>
            @else
            <p style="margin:0;color:#555;font-size:15px;line-height:1.7">
              Votre espace membre est déjà actif. Connectez-vous à tout moment depuis le site.
            </p>
            @endif
            @break
          @case('en_attente_paiement')
            <h1 style="margin:0 0 12px;font-size:22px;font-weight:800;color:#F59E0B">Votre adhésion est en attente de paiement</h1>
            <p style="margin:0;color:#555;font-size:15px;line-height:1.7">
              Votre dossier a été validé. Il ne reste plus qu'à régler votre <strong>cotisation de 20 €</strong>
              @switch($adhesion->moyen_paiement)
                @case('cheque') par chèque à l'ordre de « Madin'Jeunes Ambition ».@break
                @case('espece') en espèces au local de l'association.@break
                @case('virement') par virement (IBAN : <strong>[À COMPLÉTER]</strong>).@break
                @default pour finaliser votre inscription.
              @endswitch
              Dès réception, vous deviendrez officiellement adhérent(e).
            </p>
            @break
          @case('refusee')
            <h1 style="margin:0 0 12px;font-size:22px;font-weight:800;color:#2048A4">Votre demande d'adhésion</h1>
            <p style="margin:0;color:#555;font-size:15px;line-height:1.7">
              Nous vous remercions de l'intérêt que vous portez à Madin'Jeunes Ambition. Après étude, nous ne sommes malheureusement pas en mesure de donner suite à votre demande pour le moment. N'hésitez pas à nous recontacter.
            </p>
            @break
          @default
            <h1 style="margin:0 0 12px;font-size:22px;font-weight:800;color:#2048A4">Suivi de votre adhésion</h1>
            <p style="margin:0;color:#555;font-size:15px;line-height:1.7">Le statut de votre demande a été mis à jour.</p>
        @endswitch

        <p style="font-size:14px;color:#777;line-height:1.7;margin:24px 0 0">
          Pour toute question, contactez-nous à <a href="mailto:{{ config('mja.contact_email') }}" style="color:#2048A4;font-weight:600">{{ config('mja.contact_email') }}</a>.
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
