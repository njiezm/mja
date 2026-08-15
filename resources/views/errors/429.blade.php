@extends('errors.layout', [
    'code'     => '429',
    'ton'      => 'jaune',
    'titre'    => "Un peu trop vite",
    'message'  => "Beaucoup de demandes ont été envoyées depuis votre appareil en très peu de temps. Le site met la main en pause quelques instants — c'est une protection, pas une sanction.",
    'conseils' => [
        "Patientez une minute, puis réessayez.",
        "Évitez de cliquer plusieurs fois de suite sur un bouton d'envoi.",
        "Si vous n'avez rien fait de tel, votre connexion est peut-être partagée avec d'autres personnes.",
    ],
])

@section('actions')@endsection
