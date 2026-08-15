@extends('errors.layout', [
    'code'     => '405',
    'ton'      => 'jaune',
    'titre'    => "Action impossible ici",
    'message'  => "Cette adresse existe, mais elle n'accepte pas ce type de demande. C'est presque toujours le signe d'un lien ou d'un formulaire mal formé.",
    'conseils' => [
        "Revenez à la page précédente et recommencez l'opération normalement.",
        "Si le problème vient d'un lien reçu par email, prévenez-nous.",
    ],
])

@section('actions')@endsection
