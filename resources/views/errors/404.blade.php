@extends('errors.layout', [
    'code'     => '404',
    'ton'      => 'bleu',
    'titre'    => "Cette page est introuvable",
    'message'  => "La page que vous cherchez n'existe pas, ou n'existe plus. Elle a peut-être été déplacée, ou l'adresse comporte une faute de frappe.",
    'conseils' => [
        "Vérifiez l'adresse saisie, un caractère suffit à tout changer.",
        "Le contenu a peut-être été retiré ou renommé depuis votre dernière visite.",
        "Utilisez la recherche du site pour retrouver ce que vous cherchiez.",
    ],
])

@section('actions')<a class="btn g" href="/recherche">Rechercher</a>@endsection
