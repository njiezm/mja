@extends('layouts.app')
@section('title', "Politique de confidentialité — Madin'Jeunes Ambition")
@section('og_image', asset('images/partage/defaut.jpg'))
@section('meta_description', "Politique de confidentialité et protection des données personnelles (RGPD) de l'association Madin'Jeunes Ambition.")
@section('og_type', 'website')

@section('content')

<section class="hero-gradient text-white py-16 relative overflow-hidden">
    <div class="absolute -right-16 -top-16 w-64 h-64 opacity-10 pointer-events-none">
        <svg viewBox="0 0 200 200" fill="none"><circle cx="100" cy="100" r="95" stroke="#3DAEF5" stroke-width="2"/><circle cx="100" cy="100" r="65" stroke="#F5A623" stroke-width="2"/></svg>
    </div>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-sm text-gray-400 mb-3 font-display font-semibold">
            <a href="{{ route('home') }}" class="hover:text-mja-yellow transition-colors">Accueil</a>
            <span class="mx-2 text-gray-600">/</span> Politique de confidentialité
        </div>
        <h1 class="font-display font-black text-4xl sm:text-5xl mb-4">Politique de confidentialité</h1>
        <p class="text-gray-300 text-lg max-w-2xl">Protection de vos données personnelles conformément au RGPD.</p>
    </div>
</section>

<div class="flex h-1"><div class="flex-1 bg-mja-blue"></div><div class="flex-1 bg-mja-yellow"></div><div class="flex-1 bg-mja-red"></div></div>

<section class="py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10 text-gray-600 text-sm leading-relaxed">

        <p class="text-xs text-gray-400">Dernière mise à jour : {{ now()->locale('fr')->isoFormat('D MMMM Y') }}</p>

        <p>L'association <strong>Madin'Jeunes Ambition</strong> attache une grande importance à la protection de vos données personnelles. La présente politique décrit quelles données nous collectons, pourquoi, combien de temps nous les conservons, et quels sont vos droits, conformément au Règlement Général sur la Protection des Données (RGPD) et à la loi « Informatique et Libertés ».</p>

        <div>
            <h2 class="font-display font-bold text-xl text-mja-gray mb-3">1. Responsable du traitement</h2>
            <p>Le responsable du traitement est l'association Madin'Jeunes Ambition, dont le siège est situé 22, passage du Cœur sur la Main, 97200 Fort-de-France, Martinique.<br>
            Contact : <a href="mailto:{{ config('mja.contact_email') }}" class="text-mja-blue hover:underline">{{ config('mja.contact_email') }}</a></p>
        </div>

        <div>
            <h2 class="font-display font-bold text-xl text-mja-gray mb-3">2. Données collectées et finalités</h2>

            <div class="mt-4 rounded-xl border border-gray-100 overflow-hidden">
                <table class="w-full text-left text-xs sm:text-sm">
                    <thead class="bg-gray-50 text-mja-gray font-display font-bold">
                        <tr>
                            <th class="px-4 py-3">Formulaire</th>
                            <th class="px-4 py-3">Données collectées</th>
                            <th class="px-4 py-3">Finalité</th>
                            <th class="px-4 py-3">Base légale</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr>
                            <td class="px-4 py-3 font-semibold text-mja-gray align-top">Contact</td>
                            <td class="px-4 py-3 align-top">Nom, adresse email, téléphone (facultatif), sujet et message</td>
                            <td class="px-4 py-3 align-top">Répondre à votre demande</td>
                            <td class="px-4 py-3 align-top">Consentement</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 font-semibold text-mja-gray align-top">Adhésion</td>
                            <td class="px-4 py-3 align-top">Civilité, nom, prénom, date de naissance, profession, coordonnées (email, téléphone, adresse postale), taille de t-shirt, permis, personne à contacter en cas d'urgence</td>
                            <td class="px-4 py-3 align-top">Gestion de l'adhésion et de la vie associative, organisation des activités</td>
                            <td class="px-4 py-3 align-top">Consentement / exécution des mesures liées à l'adhésion</td>
                        </tr>
                        <tr class="bg-red-50/40">
                            <td class="px-4 py-3 font-semibold text-mja-gray align-top">Adhésion <span class="text-mja-red">(donnée sensible)</span></td>
                            <td class="px-4 py-3 align-top">Problèmes de santé éventuels</td>
                            <td class="px-4 py-3 align-top">Assurer votre sécurité lors des activités (notamment sportives)</td>
                            <td class="px-4 py-3 align-top"><strong>Consentement explicite</strong> (art. 9.2.a RGPD)</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <p class="mt-4">Les données de santé sont des données particulières au sens de l'article 9 du RGPD. Elles ne sont collectées qu'avec votre <strong>consentement explicite</strong>, sont strictement limitées à ce qui est nécessaire à votre sécurité, et font l'objet d'un accès restreint.</p>
        </div>

        <div>
            <h2 class="font-display font-bold text-xl text-mja-gray mb-3">3. Destinataires des données</h2>
            <p>Vos données sont exclusivement destinées aux membres habilités du bureau de l'association, dans la limite de leurs attributions. Elles ne sont <strong>ni vendues, ni louées, ni cédées</strong> à des tiers à des fins commerciales, et ne font l'objet d'<strong>aucun transfert en dehors de l'Union européenne</strong>.</p>
        </div>

        <div>
            <h2 class="font-display font-bold text-xl text-mja-gray mb-3">4. Durée de conservation</h2>
            <ul class="mt-2 space-y-1.5 list-disc pl-5">
                <li><strong>Messages de contact :</strong> conservés jusqu'à 12 mois après le dernier échange, puis supprimés.</li>
                <li><strong>Données d'adhésion :</strong> conservées pendant la durée de l'adhésion, puis archivées <span class="text-gray-400">[À COMPLÉTER : ex. 3 ans]</span> à des fins de gestion associative et d'obligations légales, avant suppression.</li>
                <li><strong>Données de santé :</strong> supprimées dès la fin de l'adhésion.</li>
            </ul>
        </div>

        <div>
            <h2 class="font-display font-bold text-xl text-mja-gray mb-3">5. Sécurité</h2>
            <p>L'association met en œuvre des mesures techniques et organisationnelles appropriées pour protéger vos données contre tout accès non autorisé, perte ou divulgation (accès par mot de passe à l'espace d'administration, connexions chiffrées, accès restreint aux données sensibles).</p>
        </div>

        <div>
            <h2 class="font-display font-bold text-xl text-mja-gray mb-3">6. Vos droits</h2>
            <p>Conformément au RGPD, vous disposez des droits suivants sur vos données : droit d'<strong>accès</strong>, de <strong>rectification</strong>, d'<strong>effacement</strong>, de <strong>limitation</strong>, d'<strong>opposition</strong>, de <strong>portabilité</strong>, ainsi que le droit de <strong>retirer votre consentement</strong> à tout moment.</p>
            <p class="mt-2">Pour exercer ces droits, contactez-nous à
                <a href="mailto:{{ config('mja.contact_email') }}" class="text-mja-blue hover:underline">{{ config('mja.contact_email') }}</a>.
                Nous nous engageons à répondre dans un délai d'un mois.</p>
            <p class="mt-2">Si vous estimez, après nous avoir contactés, que vos droits ne sont pas respectés, vous pouvez introduire une réclamation auprès de la
                <a href="https://www.cnil.fr" target="_blank" rel="noopener" class="text-mja-blue hover:underline">CNIL</a>
                (Commission Nationale de l'Informatique et des Libertés).</p>
        </div>

        <div>
            <h2 class="font-display font-bold text-xl text-mja-gray mb-3">7. Cookies</h2>
            <p>Ce site n'utilise qu'un cookie de session strictement nécessaire à son fonctionnement. Aucun cookie publicitaire ou de mesure d'audience n'est déposé, aucun consentement n'est donc requis à ce titre.</p>
        </div>

    </div>
</section>

@endsection
