<?php

namespace App\Http\Requests;

use App\Models\Adhesion;
use App\Support\Telephone;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Règles communes au formulaire d'adhésion et à l'écran de renouvellement :
 * les deux enregistrent la même chose, seule la façon d'y arriver diffère.
 */
class AdhesionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Une prise d'informations ne collecte que le strict nécessaire pour
     * répondre : identité, coordonnées, question. Tout le reste — date de
     * naissance, profession, santé, contact d'urgence, droit à l'image,
     * cotisation — ne concerne que les adhésions.
     */
    private function priseDInformations(): bool
    {
        return $this->input('premiere_adhesion') === 'information';
    }

    public function rules(): array
    {
        // `nullable` avant les règles suivantes : un champ absent est ignoré
        // plutôt que rejeté, ce qui permet à `required_unless` de décider seul.
        $siAdhesion = 'required_unless:premiere_adhesion,information|nullable';

        return [
            'premiere_adhesion' => 'required|in:premiere,readhesion,information',
            'civilite'          => 'required|in:Madame,Monsieur',
            'nom'               => 'required|string|max:100',
            'prenom'            => 'required|string|max:100',
            'date_naissance'    => [$siAdhesion, 'string', 'max:20', 'regex:/^\d{2}\/\d{2}\/\d{4}$/'],
            'profession'        => $siAdhesion . '|string|max:150',
            'indicatif'         => 'nullable|string|max:6',
            'telephone'         => 'required|string|max:30',
            'email'             => 'required|email|max:150',
            'message'           => 'required_if:premiere_adhesion,information|nullable|string|max:2000',
            'reseaux_sociaux'   => 'nullable|array',
            'reseaux_sociaux.*' => 'nullable|string|max:150',
            'taille_tshirt'     => $siAdhesion . '|in:S,M,L,XL,2XL,3XL',
            'permis'            => $siAdhesion . '|in:Oui,Non',
            'problemes_sante'   => 'nullable|string',
            'urgence_contact'   => $siAdhesion . '|string|max:300',
            // La photo est facultative : elle peut être transmise plus tard
            // depuis l'espace adhérent.
            'photo'             => 'nullable|image|max:5120',
            'moyen_paiement'    => $siAdhesion . '|in:cheque,espece,virement,en_ligne',
            'payment_intent_id' => 'nullable|string|max:255',
            'droit_image'       => $siAdhesion . '|accepted',
            'rgpd_consentement' => 'required|accepted',
        ];
    }

    public function messages(): array
    {
        return [
            'premiere_adhesion.required'     => 'Ce champ est obligatoire.',
            'premiere_adhesion.in'           => 'Valeur invalide.',
            'civilite.required'              => 'La civilité est obligatoire.',
            'nom.required'                   => 'Le nom est obligatoire.',
            'prenom.required'                => 'Le prénom est obligatoire.',
            'date_naissance.required'        => 'La date de naissance est obligatoire.',
            'date_naissance.regex'           => 'Le format doit être JJ/MM/AAAA.',
            'profession.required'            => 'La profession est obligatoire.',
            'telephone.required'             => 'Le numéro de téléphone est obligatoire.',
            'email.required'                 => "L'adresse email est obligatoire.",
            'email.email'                    => "L'adresse email n'est pas valide.",
            'message.required_if'            => 'Dites-nous ce que vous souhaitez savoir.',
            'date_naissance.required_unless' => 'La date de naissance est obligatoire.',
            'profession.required_unless'     => 'La profession est obligatoire.',
            'taille_tshirt.required_unless'  => 'La taille de T-shirt est obligatoire.',
            'permis.required_unless'         => 'Ce champ est obligatoire.',
            'urgence_contact.required_unless' => "La personne à contacter en cas d'urgence est obligatoire.",
            'droit_image.required_unless'    => "L'autorisation du droit à l'image est obligatoire.",
            'taille_tshirt.required'         => 'La taille de T-shirt est obligatoire.',
            'permis.required'                => 'Ce champ est obligatoire.',
            'urgence_contact.required'       => "La personne à contacter en cas d'urgence est obligatoire.",
            'photo.image'                    => 'Le fichier doit être une image (JPG, PNG…).',
            'photo.max'                      => 'La photo ne doit pas dépasser 5 Mo.',
            'moyen_paiement.required_unless' => 'Choisissez un moyen de paiement.',
            'moyen_paiement.in'              => 'Moyen de paiement invalide.',
            'droit_image.required'           => "L'autorisation du droit à l'image est obligatoire.",
            'droit_image.accepted'           => "Vous devez accepter le droit à l'image pour finaliser votre adhésion.",
            'rgpd_consentement.required'     => 'Le consentement au traitement de vos données est obligatoire.',
            'rgpd_consentement.accepted'     => 'Vous devez consentir au traitement de vos données pour finaliser votre adhésion.',
        ];
    }

    /**
     * Données prêtes à être enregistrées : téléphone recomposé avec son
     * indicatif, réseaux sociaux nettoyés, consentements normalisés.
     */
    public function donneesAdhesion(): array
    {
        $donnees = $this->safe()->except(['indicatif', 'payment_intent_id', 'photo']);

        $donnees['telephone']         = Telephone::complet($this->input('indicatif'), $this->input('telephone'));
        $donnees['reseaux_sociaux']   = $this->reseauxNettoyes();
        $donnees['rgpd_consentement'] = true;
        // Le droit à l'image n'est pas demandé lors d'une prise d'informations :
        // il ne doit donc pas être enregistré comme accordé.
        $donnees['droit_image']       = ! $this->priseDInformations();

        return $donnees;
    }

    /**
     * Ne conserve que les réseaux réellement renseignés, et seulement ceux que
     * le formulaire propose — un champ vide ne doit pas encombrer la fiche.
     *
     * @return array<string, string>|null
     */
    private function reseauxNettoyes(): ?array
    {
        $saisis = (array) $this->input('reseaux_sociaux', []);
        $connus = array_keys(Adhesion::RESEAUX);
        $propre = [];

        foreach ($saisis as $cle => $valeur) {
            $valeur = trim((string) $valeur);

            if ($valeur !== '' && in_array($cle, $connus, true)) {
                $propre[$cle] = ltrim($valeur, '@');
            }
        }

        return $propre ?: null;
    }
}
