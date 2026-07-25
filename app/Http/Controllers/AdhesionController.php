<?php

namespace App\Http\Controllers;

use App\Mail\AdhesionConfirmation;
use App\Mail\AdhesionNotification;
use App\Mail\AdhesionStatusUpdate;
use App\Models\Adhesion;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AdhesionController extends Controller
{
    public function create()
    {
        return view('adhesion', [
            'stripeEnabled'     => StripeService::enabled(),
            'cotisationAmount'  => \App\Models\Setting::get('cotisation_amount', 20),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'premiere_adhesion' => 'required|in:premiere,readhesion,information',
            'civilite'          => 'required|in:Madame,Monsieur',
            'nom'               => 'required|string|max:100',
            'prenom'            => 'required|string|max:100',
            'date_naissance'    => ['required', 'string', 'max:20', 'regex:/^\d{2}\/\d{2}\/\d{4}$/'],
            'profession'        => 'required|string|max:150',
            'indicatif'         => 'nullable|string|max:6',
            'telephone'         => 'required|string|max:30',
            'email'             => 'required|email|max:150',
            'adresse_postale'   => 'required|string',
            'taille_tshirt'     => 'required|in:S,M,L,XL,2XL,3XL',
            'permis'            => 'required|in:Oui,Non',
            'problemes_sante'   => 'nullable|string',
            'urgence_contact'   => 'required|string|max:300',
            'photo'             => 'required_unless:premiere_adhesion,information|nullable|image|max:5120',
            'moyen_paiement'    => 'required_unless:premiere_adhesion,information|nullable|in:cheque,espece,virement,en_ligne',
            'droit_image'       => 'required|accepted',
            'rgpd_consentement' => 'required|accepted',
        ], [
            'premiere_adhesion.required'  => 'Ce champ est obligatoire.',
            'premiere_adhesion.in'        => 'Valeur invalide.',
            'civilite.required'           => 'La civilité est obligatoire.',
            'nom.required'                => 'Le nom est obligatoire.',
            'prenom.required'             => 'Le prénom est obligatoire.',
            'date_naissance.required'     => 'La date de naissance est obligatoire.',
            'date_naissance.regex'        => 'Le format doit être JJ/MM/AAAA.',
            'profession.required'         => 'La profession est obligatoire.',
            'telephone.required'          => 'Le numéro de téléphone est obligatoire.',
            'email.required'              => "L'adresse email est obligatoire.",
            'email.email'                 => "L'adresse email n'est pas valide.",
            'adresse_postale.required'    => "L'adresse postale est obligatoire.",
            'taille_tshirt.required'      => 'La taille de T-shirt est obligatoire.',
            'permis.required'             => 'Ce champ est obligatoire.',
            'urgence_contact.required'    => "La personne à contacter en cas d'urgence est obligatoire.",
            'photo.required_unless'       => 'La photo est obligatoire pour une adhésion.',
            'photo.image'                 => 'Le fichier doit être une image (JPG, PNG…).',
            'photo.max'                   => 'La photo ne doit pas dépasser 5 Mo.',
            'moyen_paiement.required_unless' => 'Choisissez un moyen de paiement.',
            'moyen_paiement.in'           => 'Moyen de paiement invalide.',
            'droit_image.required'        => "L'autorisation du droit à l'image est obligatoire.",
            'droit_image.accepted'        => "Vous devez accepter le droit à l'image pour finaliser votre adhésion.",
            'rgpd_consentement.required'  => 'Le consentement au traitement de vos données est obligatoire.',
            'rgpd_consentement.accepted'  => 'Vous devez consentir au traitement de vos données pour finaliser votre adhésion.',
        ]);

        $validated['droit_image'] = true;
        $validated['rgpd_consentement'] = true;

        $indicatif = $validated['indicatif'] ?? null;
        unset($validated['indicatif']);
        $validated['telephone'] = trim(($indicatif ? $indicatif . ' ' : '') . $validated['telephone']);

        $validated['source_id'] = $request->session()->get('mja_source_id');

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('adhesions/photos', 'public');
        } else {
            unset($validated['photo']);
        }

        $validated['statut'] = match (true) {
            $validated['premiere_adhesion'] === 'information'    => 'prise_infos',
            ($validated['moyen_paiement'] ?? null) === 'en_ligne' => 'en_attente_paiement',
            default                                              => 'nouvelle',
        };

        $adhesion = Adhesion::create($validated);

        // Notification à l'association (toujours) — liste configurable en back-office.
        try {
            Mail::to(\App\Models\Setting::notificationEmails())->send(new AdhesionNotification($adhesion));
        } catch (\Throwable $e) {
            Log::error('Mail notification adhésion échoué : ' . $e->getMessage());
        }

        // Paiement en ligne → redirection vers Stripe Checkout.
        if ($adhesion->moyen_paiement === 'en_ligne' && StripeService::enabled()) {
            $url = StripeService::createCheckoutSession(
                $adhesion,
                route('adhesion.paiement.succes') . '?session_id={CHECKOUT_SESSION_ID}',
                route('adhesion.paiement.annule', ['adhesion' => $adhesion->id]),
            );

            if ($url) {
                return redirect()->away($url);
            }

            return back()->with('error', "Le paiement en ligne est momentanément indisponible. Votre demande a bien été enregistrée, nous vous recontacterons.");
        }

        // Email de confirmation (moyens hors ligne + prise d'informations).
        try {
            Mail::to($adhesion->email)->send(new AdhesionConfirmation($adhesion));
        } catch (\Throwable $e) {
            Log::error('Mail confirmation adhésion échoué : ' . $e->getMessage());
        }

        return back()->with('success', true);
    }

    /** Retour Stripe : paiement réussi → statut « payée » + email de bienvenue. */
    public function paiementSucces(Request $request)
    {
        $sessionId = $request->query('session_id');

        if (! $sessionId || ! StripeService::enabled()) {
            return redirect()->route('adhesion');
        }

        $session = StripeService::retrieveSession($sessionId);

        if ($session && ($session['payment_status'] ?? null) === 'paid') {
            $id = $session['metadata']['adhesion_id'] ?? ($session['client_reference_id'] ?? null);
            $adhesion = $id ? Adhesion::find($id) : null;

            if ($adhesion && ! $adhesion->isAdherent()) {
                $adhesion->update(['statut' => 'payee']);
                $adhesion->ensureAccountToken();
                try {
                    Mail::to($adhesion->email)->send(new AdhesionStatusUpdate($adhesion));
                } catch (\Throwable $e) {
                    Log::error('Mail paiement adhésion échoué : ' . $e->getMessage());
                }
            }

            return redirect()->route('adhesion')->with('success', true)->with('paye', true);
        }

        return redirect()->route('adhesion')->with('error', "Le paiement n'a pas pu être confirmé. Contactez-nous si vous avez été débité.");
    }

    /** Retour Stripe : paiement annulé. */
    public function paiementAnnule(Request $request)
    {
        return redirect()->route('adhesion')
            ->with('error', "Paiement annulé. Votre demande reste enregistrée, vous pourrez régler plus tard.");
    }
}
