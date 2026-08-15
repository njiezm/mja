@extends('errors.layout', [
    'code'     => '502',
    'ton'      => 'rouge',
    'titre'    => "Le site ne répond pas correctement",
    'message'  => "Le serveur qui héberge le site n'a pas répondu comme prévu. C'est passager dans la très grande majorité des cas.",
    'conseils' => [
        "Rechargez la page dans une minute ou deux.",
        "Vérifiez aussi votre connexion internet, elle peut être en cause.",
    ],
])

@section('actions')@endsection
