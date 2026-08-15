@extends('layouts.app')
@section('title', "Mentions légales — Madin'Jeunes Ambition")
@section('og_image', asset('images/partage/defaut.jpg'))
@section('meta_description', "Mentions légales du site de l'association Madin'Jeunes Ambition.")
@section('og_type', 'website')

@section('content')

<section class="hero-gradient text-white py-16 relative overflow-hidden">
    <div class="absolute -right-16 -top-16 w-64 h-64 opacity-10 pointer-events-none">
        <svg viewBox="0 0 200 200" fill="none"><circle cx="100" cy="100" r="95" stroke="#3DAEF5" stroke-width="2"/><circle cx="100" cy="100" r="65" stroke="#F5A623" stroke-width="2"/></svg>
    </div>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-sm text-gray-400 mb-3 font-display font-semibold">
            <a href="{{ route('home') }}" class="hover:text-mja-yellow transition-colors">Accueil</a>
            <span class="mx-2 text-gray-600">/</span> Mentions légales
        </div>
        <h1 class="font-display font-black text-4xl sm:text-5xl mb-4">Mentions légales</h1>
        <p class="text-gray-300 text-lg max-w-2xl">Informations légales relatives au site et à l'association.</p>
    </div>
</section>

<div class="flex h-1"><div class="flex-1 bg-mja-blue"></div><div class="flex-1 bg-mja-yellow"></div><div class="flex-1 bg-mja-red"></div></div>

<section class="py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10 text-gray-600 text-sm leading-relaxed">

        <p class="text-xs text-gray-400">Dernière mise à jour : {{ now()->locale('fr')->isoFormat('D MMMM Y') }}</p>

        <div>
            <h2 class="font-display font-bold text-xl text-mja-gray mb-3">1. Éditeur du site</h2>
            <p>Le présent site est édité par l'association <strong>Madin'Jeunes Ambition (MJA)</strong>, association régie par la loi du 1<sup>er</sup> juillet 1901.</p>
            <ul class="mt-3 space-y-1.5">
                <li><strong>Siège social :</strong> 22, passage du Cœur sur la Main, 97200 Fort-de-France, Martinique</li>
                <li><strong>Adresse email :</strong> <a href="mailto:{{ config('mja.contact_email') }}" class="text-mja-blue hover:underline">{{ config('mja.contact_email') }}</a></li>
                <li><strong>Numéro RNA (déclaration en préfecture) :</strong> <span class="text-gray-400">[À COMPLÉTER]</span></li>
                <li><strong>Numéro SIRET (le cas échéant) :</strong> <span class="text-gray-400">[À COMPLÉTER]</span></li>
                <li><strong>Directeur / Directrice de la publication :</strong> le/la Président·e de l'association <span class="text-gray-400">[À COMPLÉTER : nom]</span></li>
            </ul>
        </div>

        <div>
            <h2 class="font-display font-bold text-xl text-mja-gray mb-3">2. Hébergement</h2>
            <p>Le site est hébergé par :</p>
            <ul class="mt-3 space-y-1.5">
                <li><strong>Hébergeur :</strong> <span class="text-gray-400">[À COMPLÉTER : nom de l'hébergeur]</span></li>
                <li><strong>Adresse :</strong> <span class="text-gray-400">[À COMPLÉTER]</span></li>
                <li><strong>Contact :</strong> <span class="text-gray-400">[À COMPLÉTER]</span></li>
            </ul>
        </div>

        <div>
            <h2 class="font-display font-bold text-xl text-mja-gray mb-3">3. Propriété intellectuelle</h2>
            <p>L'ensemble des contenus présents sur ce site (textes, logos, images, vidéos, éléments graphiques et charte visuelle « MJA ») sont, sauf mention contraire, la propriété exclusive de l'association Madin'Jeunes Ambition ou de leurs auteurs respectifs. Toute reproduction, représentation, modification ou diffusion, totale ou partielle, sans l'autorisation écrite préalable de l'association est interdite et constituerait une contrefaçon sanctionnée par le Code de la propriété intellectuelle.</p>
        </div>

        <div>
            <h2 class="font-display font-bold text-xl text-mja-gray mb-3">4. Données personnelles</h2>
            <p>Le site collecte des données personnelles via ses formulaires de contact et d'adhésion. Les modalités de traitement, vos droits et les durées de conservation sont détaillés dans notre
                <a href="{{ route('confidentialite') }}" class="text-mja-blue font-semibold hover:underline">Politique de confidentialité</a>.</p>
        </div>

        <div>
            <h2 class="font-display font-bold text-xl text-mja-gray mb-3">5. Cookies</h2>
            <p>Ce site utilise uniquement un cookie de session strictement nécessaire à son bon fonctionnement (maintien de la session et protection contre la falsification de requêtes). Ce cookie ne nécessite pas de consentement préalable et n'est utilisé à aucune fin publicitaire ou de mesure d'audience.</p>
        </div>

        <div>
            <h2 class="font-display font-bold text-xl text-mja-gray mb-3">6. Responsabilité</h2>
            <p>L'association s'efforce d'assurer l'exactitude et la mise à jour des informations diffusées sur ce site, sans pouvoir en garantir l'exhaustivité. Les liens vers des sites tiers (réseaux sociaux, partenaires) sont fournis à titre informatif ; l'association ne saurait être tenue responsable de leur contenu.</p>
        </div>

        <div>
            <h2 class="font-display font-bold text-xl text-mja-gray mb-3">7. Contact</h2>
            <p>Pour toute question relative aux présentes mentions légales, vous pouvez nous écrire à
                <a href="mailto:{{ config('mja.contact_email') }}" class="text-mja-blue hover:underline">{{ config('mja.contact_email') }}</a>
                ou via notre <a href="{{ route('contact') }}" class="text-mja-blue hover:underline">formulaire de contact</a>.</p>
        </div>

    </div>
</section>

@endsection
