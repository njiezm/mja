@extends('errors.layout', [
    'code'     => '504',
    'ton'      => 'rouge',
    'titre'    => "Le serveur a mis trop de temps",
    'message'  => "La réponse n'est pas arrivée dans le délai imparti. Le site est peut-être très sollicité, ou votre connexion est trop faible pour aboutir.",
    'conseils' => [
        "Vérifiez votre connexion — un réseau mobile faible suffit à provoquer ceci.",
        "Réessayez dans quelques instants.",
        "Si cela se répète toute la journée, prévenez-nous.",
    ],
])

@section('actions')@endsection
