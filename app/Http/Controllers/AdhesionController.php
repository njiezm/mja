<?php

namespace App\Http\Controllers;

use App\Models\Adhesion;
use Illuminate\Http\Request;

class AdhesionController extends Controller
{
    public function create()
    {
        return view('adhesion');
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
            'telephone'         => 'required|string|max:30',
            'email'             => 'required|email|max:150',
            'adresse_postale'   => 'required|string',
            'taille_tshirt'     => 'required|in:S,M,L,XL,2XL,3XL',
            'permis'            => 'required|in:Oui,Non',
            'problemes_sante'   => 'nullable|string',
            'urgence_contact'   => 'required|string|max:300',
            'droit_image'       => 'required|accepted',
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
            'droit_image.required'        => "L'autorisation du droit à l'image est obligatoire.",
            'droit_image.accepted'        => "Vous devez accepter le droit à l'image pour finaliser votre adhésion.",
        ]);

        $validated['droit_image'] = true;

        Adhesion::create($validated);

        return back()->with('success', true);
    }
}
