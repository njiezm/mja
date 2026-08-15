@extends('errors.layout', [
    'code'     => '400',
    'ton'      => 'jaune',
    'titre'    => "Demande incomprise",
    'message'  => "Le site n'a pas su interpréter votre demande. Elle est peut-être incomplète, ou l'adresse a été abîmée en chemin.",
    'conseils' => [
        "Revenez en arrière et recommencez depuis la page précédente.",
        "Si vous avez copié un lien, vérifiez qu'il a été collé en entier.",
    ],
])

@section('actions')@endsection
