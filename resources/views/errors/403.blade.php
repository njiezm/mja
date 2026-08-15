@extends('errors.layout', [
    'code'     => '403',
    'ton'      => 'jaune',
    'titre'    => "Accès non autorisé",
    'message'  => "Vous êtes bien connecté, mais votre compte n'a pas les droits nécessaires sur cette page.",
    'conseils' => [
        "Vérifiez que vous êtes connecté avec le bon compte.",
        "Si vous pensez que c'est une erreur, demandez à un administrateur d'élargir vos droits.",
    ],
])

@section('actions')<a class="btn g" href="/espace">Mon espace</a>@endsection
