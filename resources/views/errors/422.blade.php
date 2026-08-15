@extends('errors.layout', [
    'code'     => '422',
    'ton'      => 'jaune',
    'titre'    => "Informations incomplètes",
    'message'  => "Certaines informations envoyées n'ont pas pu être acceptées. Le formulaire vous indique normalement les champs à corriger.",
    'conseils' => [
        "Revenez au formulaire : les champs en défaut y sont signalés en rouge.",
        "Vérifiez les formats attendus — date en JJ/MM/AAAA, adresse email complète.",
    ],
])

@section('actions')@endsection
