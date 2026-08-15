@extends('errors.layout', [
    'code'     => '401',
    'ton'      => 'bleu',
    'titre'    => "Connexion nécessaire",
    'message'  => "Cette page est réservée aux personnes connectées. Identifiez-vous pour y accéder.",
    'conseils' => [
        "Adhérent : connectez-vous à votre espace.",
        "Équipe : passez par la connexion du back-office.",
        "Mot de passe oublié ? Un lien de réinitialisation vous sera envoyé par email.",
    ],
])

@section('actions')<a class="btn g" href="/espace/connexion">Espace adherent</a>@endsection
