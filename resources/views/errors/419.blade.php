@extends('errors.layout', [
    'code'     => '419',
    'ton'      => 'jaune',
    'titre'    => "Votre session a expiré",
    'message'  => "La page est restée ouverte trop longtemps, et le formulaire n'est plus valable. Rien n'est perdu : il suffit de recommencer.",
    'conseils' => [
        "Revenez en arrière, rechargez la page, puis renvoyez le formulaire.",
        "Si vous étiez connecté, reconnectez-vous avant de réessayer.",
        "Pensez à copier un texte long avant de l'envoyer, par précaution.",
    ],
])

@section('actions')@endsection
