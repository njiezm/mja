@extends('errors.layout', [
    'code'     => '503',
    'ton'      => 'bleu',
    'titre'    => "Site en maintenance",
    'message'  => "Le site est momentanément indisponible, le temps d'une mise à jour. Nous serons de retour très vite.",
    'conseils' => [
        "Revenez d'ici quelques minutes.",
        "Suivez-nous sur les réseaux sociaux, nous y annonçons les interruptions.",
        "Pour une urgence, écrivez-nous : nous lisons nos emails pendant la maintenance.",
    ],
])

@section('actions')@endsection
