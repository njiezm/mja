{{--
    Gabarit commun aux pages d'erreur.

    Volontairement autonome : ni layouts.app, ni requête en base, ni feuille de
    style externe. Une page d'erreur doit s'afficher quand le reste ne marche
    plus — un gabarit qui interroge la base rejouerait l'erreur qu'il annonce.
    Seules ressources externes : le logo et, si elle répond, la police du site.

    Variables attendues : $code, $titre, $message, $conseils (tableau),
    $ton ('bleu' par défaut, 'jaune', 'rouge'), $emoji.
--}}
@php
    $ton = $ton ?? 'bleu';
    $conseils = $conseils ?? [];
    $palettes = [
        'bleu'  => ['#1A3D8A', '#2048A4', '#3262CC'],
        'jaune' => ['#B57708', '#D9930F', '#F5A623'],
        'rouge' => ['#8E0212', '#B00317', '#D0021B'],
    ];
    [$sombre, $moyen, $clair] = $palettes[$ton] ?? $palettes['bleu'];
@endphp
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex, nofollow">
<title>{{ $code }} — {{ $titre }} · Madin'Jeunes Ambition</title>
<link rel="icon" type="image/jpeg" href="/images/logo.jpg">
<link rel="stylesheet" href="/css/gill-sans.css">
<style>
*{box-sizing:border-box}
body{margin:0;min-height:100vh;display:flex;flex-direction:column;
     background:#F4F7FC;color:#3A4A63;
     font-family:'Gill Sans','Open Sans',-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;
     font-size:16px;line-height:1.6}
.filets{display:flex;height:6px;flex:none}
.filets i{flex:1}
.f1{background:#3DAEF5}.f2{background:#F5A623}.f3{background:#D0021B}

.entete{background:linear-gradient(135deg,{{ $sombre }} 0%,{{ $moyen }} 48%,{{ $clair }} 100%);
        color:#fff;padding:22px 0;flex:none}
.wrap{width:100%;max-width:760px;margin:0 auto;padding:0 22px}
.marque{display:flex;align-items:center;gap:14px;text-decoration:none;color:#fff}
.marque img{height:48px;width:48px;object-fit:contain;background:#fff;border-radius:12px;padding:4px;flex:none}
.marque .n{font-weight:800;font-size:16px;letter-spacing:.6px;line-height:1.2}
.marque .s{font-size:12.5px;opacity:.82;font-style:italic}

main{flex:1;display:flex;align-items:center;padding:44px 0 56px}
.carte{background:#fff;border:1px solid #E4EAF4;border-radius:20px;padding:40px 38px;
       box-shadow:0 14px 40px rgba(20,40,90,.08)}
.code{display:inline-flex;align-items:center;gap:12px;background:{{ $sombre }};color:#fff;
      border-radius:14px;padding:8px 18px;font-weight:800;font-size:15px;letter-spacing:1.5px}
.code .gros{font-size:26px;letter-spacing:0}
h1{margin:22px 0 12px;font-size:30px;font-weight:800;color:#0B1E45;line-height:1.2}
.message{font-size:17px;margin:0 0 24px;color:#48586F}
.conseils{margin:0 0 30px;padding:0;list-style:none;border-top:1px solid #EDF1F8}
.conseils li{padding:13px 0 13px 30px;border-bottom:1px solid #EDF1F8;position:relative;font-size:15px}
.conseils li::before{content:'';position:absolute;left:6px;top:21px;width:8px;height:8px;
                     border-radius:50%;background:{{ $clair }}}
.actions{display:flex;flex-wrap:wrap;gap:11px}
.btn{border:0;border-radius:12px;padding:13px 22px;font:inherit;font-size:15px;font-weight:700;
     cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:8px;
     transition:background .15s,color .15s}
.btn.p{background:{{ $sombre }};color:#fff}
.btn.p:hover{background:{{ $moyen }}}
.btn.g{background:#EEF3FB;color:#1A3D8A}
.btn.g:hover{background:#E1EAF8}

footer{padding:22px 0 34px;color:#7C8AA0;font-size:13.5px;text-align:center}
footer a{color:#2048A4;text-decoration:none;font-weight:600}
footer a:hover{text-decoration:underline}

@media(max-width:560px){
  .carte{padding:30px 22px;border-radius:16px}
  h1{font-size:24px}
  .actions .btn{flex:1 1 100%;justify-content:center}
}
</style>
</head>
<body>

<div class="filets"><i class="f1"></i><i class="f2"></i><i class="f3"></i></div>

<header class="entete">
    <div class="wrap">
        <a class="marque" href="/">
            <img src="/images/logo.jpg" alt="">
            <span>
                <span class="n">MADIN' JEUNES AMBITION</span><br>
                <span class="s">Relève tous les défis !</span>
            </span>
        </a>
    </div>
</header>

<main>
    <div class="wrap">
        <div class="carte">
            <div class="code"><span class="gros">{{ $code }}</span> {{ $emoji ?? '' }}</div>
            <h1>{{ $titre }}</h1>
            <p class="message">{{ $message }}</p>

            @if($conseils)
            <ul class="conseils">
                @foreach($conseils as $conseil)
                <li>{{ $conseil }}</li>
                @endforeach
            </ul>
            @endif

            <div class="actions">
                @yield('actions')
                <a class="btn p" href="/">Retour à l'accueil</a>
                <a class="btn g" href="/contact">Nous écrire</a>
            </div>
        </div>
    </div>
</main>

<footer>
    <div class="wrap">
        Madin'Jeunes Ambition — association de jeunes engagés en Martinique et au-delà.<br>
        Un souci persistant ? Écrivez-nous à
        <a href="mailto:{{ config('mja.contact_email', 'madinjeunesambition@gmail.com') }}">{{ config('mja.contact_email', 'madinjeunesambition@gmail.com') }}</a>.
    </div>
</footer>

</body>
</html>
