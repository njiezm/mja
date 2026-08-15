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
        @php $cotisation = \App\Support\Cotisation::formatee(); @endphp

        <h1 style="margin:0 0 8px;font-size:22px;font-weight:800;color:#2048A4">Votre cotisation n'a pas encore été reçue</h1>

        <p style="margin:0 0 20px;color:#555;font-size:15px;line-height:1.6">
          Bonjour <strong>{{ $adhesion->prenom }}</strong>,<br>
          votre demande d'adhésion du {{ $adhesion->created_at?->locale('fr')->isoFormat('D MMMM Y') }} est bien enregistrée,
          mais nous n'avons pas encore reçu votre cotisation de <strong>{{ $cotisation }}</strong>.
          Tant qu'elle n'est pas réglée, votre adhésion n'est pas active.
        </p>

        <table width="100%" cellpadding="0" cellspacing="0" style="background:#fffbeb;border-left:4px solid #F5A623;border-radius:0 8px 8px 0;margin-bottom:24px">
          <tr><td style="padding:16px 20px">
            <p style="margin:0 0 8px;font-weight:800;font-size:14px;color:#92400e">
              Vous aviez choisi : {{ $adhesion->label_moyen_paiement }}
            </p>
            @switch($adhesion->moyen_paiement)
              @case('cheque')
                <p style="margin:0;font-size:13px;color:#555;line-height:1.6">
                  Chèque de {{ $cotisation }} à l'ordre de « Madin'Jeunes Ambition », à remettre à un membre du bureau.
                </p>
                @break
              @case('espece')
                <p style="margin:0;font-size:13px;color:#555;line-height:1.6">
                  {{ $cotisation }} en espèces, auprès d'un membre du bureau lors d'une réunion ou d'un événement.
                </p>
                @break
              @case('virement')
                <p style="margin:0;font-size:13px;color:#555;line-height:1.6">
                  Virement de {{ $cotisation }}@if(\App\Models\Setting::has('iban')) — IBAN : <strong>{{ \App\Models\Setting::get('iban') }}</strong>@if(\App\Models\Setting::has('bic')), BIC : <strong>{{ \App\Models\Setting::get('bic') }}</strong>@endif @endif
                  (indiquez vos nom et prénom en référence).
                </p>
                @break
            @endswitch
          </td></tr>
        </table>

        @if(\App\Services\StripeService::enabled())
        <p style="margin:0 0 20px;color:#555;font-size:15px;line-height:1.6">
          Vous préférez régler <strong>en ligne, tout de suite</strong> ? C'est possible par carte bancaire :
        </p>
        <table cellpadding="0" cellspacing="0" style="margin:0 0 24px">
          <tr><td style="background:#1A7BB8;border-radius:10px">
            <a href="{{ route('adhesion') }}" style="display:inline-block;padding:13px 26px;color:#fff;font-weight:700;font-size:14px;text-decoration:none">
              Régler ma cotisation par carte
            </a>
          </td></tr>
        </table>
        @endif

        <p style="font-size:14px;color:#777;line-height:1.7;margin:0">
          Si vous avez déjà réglé, ignorez ce message — nos remerciements et nos excuses pour le doublon.<br>
          Une question ? Répondez simplement à cet email.
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
