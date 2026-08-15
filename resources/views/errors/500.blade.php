@extends('errors.layout', [
    'code'     => '500',
    'ton'      => 'rouge',
    'titre'    => "Une erreur s'est produite",
    'message'  => "Le problème vient de notre côté, pas du vôtre. L'incident a été enregistré et sera examiné par l'équipe technique.",
    'conseils' => [
        "Réessayez dans quelques minutes : beaucoup d'incidents se règlent seuls.",
        "Si vous étiez en train d'adhérer et que vous avez été débité, écrivez-nous : rien ne sera perdu.",
        "Décrivez-nous ce que vous faisiez au moment de l'erreur, cela nous aide beaucoup.",
    ],
])

@section('actions')@endsection
