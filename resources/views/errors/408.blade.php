@extends('errors.layout', [
    'code'     => '408',
    'ton'      => 'jaune',
    'titre'    => "Le temps de réponse a été dépassé",
    'message'  => "Votre demande a mis trop de temps à nous parvenir. Le plus souvent, c'est la connexion internet qui a faibli en cours de route.",
    'conseils' => [
        "Vérifiez votre connexion — wifi, données mobiles, réseau instable.",
        "Réessayez dans quelques instants, souvent cela suffit.",
        "Si vous étiez en train de remplir un formulaire, vérifiez avant de renvoyer.",
    ],
])

@section('actions')@endsection
