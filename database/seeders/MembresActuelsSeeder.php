<?php

namespace Database\Seeders;

use App\Models\Adhesion;
use App\Models\AdhesionPeriod;
use App\Models\Member;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Import des adhérents de la saison en cours (2025-2026) + création de leur
 * compte espace adhérent, SANS envoi d'email.
 *
 * Photos attendues dans  public/images/membres_actus/  nommées  nom-prenom.jpg
 * (slug, minuscules, sans accent — voir la constante $membres ci-dessous, la
 * clé « slug » de chaque ligne donne le nom de fichier exact attendu).
 *
 * Le seeder est idempotent : relancé, il met à jour les adhésions existantes
 * (repérées par email + période) et ne recrée pas les comptes déjà présents.
 *
 *   php artisan db:seed --class=MembresActuelsSeeder
 *
 * Un récapitulatif des identifiants générés est écrit dans
 *   storage/app/private/membres-actus-comptes.csv
 */
class MembresActuelsSeeder extends Seeder
{
    /** Dossier source des photos, relatif à public/. */
    private const PHOTOS_SOURCE = 'images/membres_actus';

    /** Dossier de destination sur le disque « public » (storage/app/public). */
    private const PHOTOS_DEST = 'adhesions/photos';

    /** Extensions acceptées pour les photos, par ordre de priorité. */
    private const EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp'];

    /** Saison rattachée à cet import. */
    private const PERIODE = [
        'label'      => 'Saison 2025-2026',
        'date_debut' => '2025-08-01',
        'date_fin'   => '2026-07-31',
    ];

    public function run(): void
    {
        $periode = AdhesionPeriod::firstOrCreate(
            ['label' => self::PERIODE['label']],
            [
                'date_debut' => self::PERIODE['date_debut'],
                'date_fin'   => self::PERIODE['date_fin'],
                'actif'      => true,
            ],
        );

        $credentials = [];
        $sansPhoto   = [];

        foreach ($this->membres() as $ligne) {
            $email = Str::lower(trim($ligne['email']));
            $date  = Carbon::createFromFormat('d/m/Y', $ligne['date_adhesion'])->startOfDay();

            $adhesion = Adhesion::firstOrNew([
                'email'     => $email,
                'period_id' => $periode->id,
            ]);

            $adhesion->fill([
                'premiere_adhesion' => $ligne['premiere_adhesion'],
                'civilite'          => $ligne['civilite'],
                'nom'               => $ligne['nom'],
                'prenom'            => $ligne['prenom'],
                'date_naissance'    => $ligne['date_naissance'],
                'profession'        => $ligne['profession'],
                'telephone'         => $ligne['telephone'],
                'email'             => $email,
                'adresse_postale'   => $ligne['adresse_postale'],
                'taille_tshirt'     => $ligne['taille_tshirt'],
                'permis'            => $ligne['permis'],
                'problemes_sante'   => $ligne['problemes_sante'],
                'urgence_contact'   => $ligne['urgence_contact'],
                'moyen_paiement'    => $ligne['moyen_paiement'],
                'droit_image'       => true,
                'rgpd_consentement' => true,
                'statut'            => 'payee',   // cotisation encaissée
                'lu'                => true,
                'period_id'         => $periode->id,
            ]);

            // Photo : copiée depuis public/images/membres_actus vers le disque public.
            if ($chemin = $this->copierPhoto($ligne['slug'])) {
                $adhesion->photo = $chemin;
            } else {
                $sansPhoto[] = $ligne['slug'];
            }

            $adhesion->created_at = $date;
            $adhesion->updated_at = $date;
            $adhesion->save();

            // Compte espace adhérent — aucun email n'est envoyé.
            $member = Member::withTrashed()->where('adhesion_id', $adhesion->id)->first()
                ?? Member::withTrashed()->where('email', $email)->first();

            if ($member) {
                if ($member->trashed()) {
                    $member->restore();
                }
                continue; // compte déjà existant : on ne touche pas au mot de passe
            }

            $motDePasse = $this->motDePasseTemporaire();

            Member::create([
                'adhesion_id'       => $adhesion->id,
                'email'             => $email,
                'password'          => $motDePasse,   // hashé par le cast du modèle
                'show_in_directory' => true,
            ]);

            $credentials[] = [
                $ligne['nom'],
                $ligne['prenom'],
                $email,
                $motDePasse,
            ];
        }

        $this->ecrireRecapitulatif($credentials);

        $this->command?->info(count($credentials) . ' compte(s) adhérent(s) créé(s).');

        if ($sansPhoto) {
            $this->command?->warn(
                'Photo manquante pour : ' . implode(', ', $sansPhoto)
                . ' (attendue dans public/' . self::PHOTOS_SOURCE . '/<slug>.jpg)'
            );
        }

        if ($credentials) {
            $this->command?->info('Identifiants : storage/app/private/membres-actus-comptes.csv');
        }
    }

    /**
     * Copie public/images/membres_actus/<slug>.<ext> vers le disque public et
     * renvoie le chemin relatif à stocker en base, ou null si absente.
     */
    private function copierPhoto(string $slug): ?string
    {
        foreach (self::EXTENSIONS as $ext) {
            foreach ([$ext, Str::upper($ext)] as $variante) {
                $source = public_path(self::PHOTOS_SOURCE . '/' . $slug . '.' . $variante);

                if (! is_file($source)) {
                    continue;
                }

                $destination = self::PHOTOS_DEST . '/' . $slug . '.' . Str::lower($variante);
                Storage::disk('public')->put($destination, file_get_contents($source));

                return $destination;
            }
        }

        return null;
    }

    /** Mot de passe temporaire lisible, à transmettre à l'adhérent hors email. */
    private function motDePasseTemporaire(): string
    {
        return 'MJA-' . Str::upper(Str::random(4)) . '-' . random_int(1000, 9999);
    }

    /** @param  array<int, array{0:string,1:string,2:string,3:string}>  $lignes */
    private function ecrireRecapitulatif(array $lignes): void
    {
        if (! $lignes) {
            return;
        }

        $csv = "Nom;Prenom;Email;Mot de passe temporaire\n";

        foreach ($lignes as $ligne) {
            $csv .= implode(';', array_map(
                fn ($v) => '"' . str_replace('"', '""', $v) . '"',
                $ligne
            )) . "\n";
        }

        Storage::disk('local')->put('membres-actus-comptes.csv', $csv);
    }

    /**
     * Adhérents saison 2025-2026.
     *
     * « slug » = nom du fichier photo attendu (sans extension).
     *
     * @return array<int, array<string, string|null>>
     */
    private function membres(): array
    {
        return [
            [
                'slug' => 'rose-antoinette-sarah',
                'premiere_adhesion' => 'readhesion', 'civilite' => 'Madame',
                'nom' => 'ROSE ANTOINETTE', 'prenom' => 'Sarah',
                'date_adhesion' => '26/08/2025', 'moyen_paiement' => 'espece',
                'date_naissance' => '16/02/1999', 'profession' => 'Enseignement',
                'telephone' => '0696 50 96 21', 'email' => 'sarah.ra.mja@gmail.com',
                'adresse_postale' => 'Fond-Savane 97224 Ducos',
                'taille_tshirt' => 'L', 'permis' => 'Oui',
                'problemes_sante' => 'Fruits de mer / crustacés / moustiques / poussière',
                'urgence_contact' => 'Maman 0696 532112',
            ],
            [
                'slug' => 'orsinet-oceane',
                'premiere_adhesion' => 'premiere', 'civilite' => 'Madame',
                'nom' => 'ORSINET', 'prenom' => 'Océane',
                'date_adhesion' => '30/09/2025', 'moyen_paiement' => 'virement',
                'date_naissance' => '18/06/2007', 'profession' => 'Étudiante',
                'telephone' => '0696 60 76 96', 'email' => 'oceaneorsinet@gmail.com',
                'adresse_postale' => 'N 16 rue A et Teerulien DUVILLE',
                'taille_tshirt' => 'S', 'permis' => 'Oui',
                'problemes_sante' => 'Problème de jambe',
                'urgence_contact' => 'ORSINET Marie Claude 0696607696',
            ],
            [
                'slug' => 'montout-marissa',
                'premiere_adhesion' => 'readhesion', 'civilite' => 'Madame',
                'nom' => 'MONTOUT', 'prenom' => 'Marissa',
                'date_adhesion' => '22/09/2025', 'moyen_paiement' => 'virement',
                'date_naissance' => '18/08/1996', 'profession' => 'Professeur des écoles',
                'telephone' => '0696 23 31 84', 'email' => 'marissa.montout@gmail.com',
                'adresse_postale' => 'Résidence Deshabays Le Lamentin 97232',
                'taille_tshirt' => 'L', 'permis' => 'Oui',
                'problemes_sante' => null,
                'urgence_contact' => 'Montout François 0696553652',
            ],
            [
                'slug' => 'florian-chloe',
                'premiere_adhesion' => 'readhesion', 'civilite' => 'Madame',
                'nom' => 'FLORIAN', 'prenom' => 'Chloe',
                'date_adhesion' => '15/04/2026', 'moyen_paiement' => 'virement',
                'date_naissance' => '15/05/1999', 'profession' => 'Santé',
                'telephone' => '0696 37 92 28', 'email' => 'florianchloe1@gmail.com',
                'adresse_postale' => '97232',
                'taille_tshirt' => 'S', 'permis' => 'Oui',
                'problemes_sante' => 'Aucun',
                'urgence_contact' => 'Serbin Lise 0696 31 57 73',
            ],
            [
                'slug' => 'beauregard-katleen',
                'premiere_adhesion' => 'readhesion', 'civilite' => 'Madame',
                'nom' => 'BEAUREGARD', 'prenom' => 'Katleen',
                'date_adhesion' => '07/10/2025', 'moyen_paiement' => 'virement',
                'date_naissance' => '08/03/1996', 'profession' => 'Assistante Manager',
                'telephone' => '0696 66 27 27', 'email' => 'katleenbeauregard8@gmail.com',
                'adresse_postale' => 'Le Lamentin',
                'taille_tshirt' => 'L', 'permis' => 'Oui',
                'problemes_sante' => 'Allergie crustacé et fruit de mer, intolérance au lactose',
                'urgence_contact' => 'Sellaye Sylvio 0696 52 55 77',
            ],
            [
                'slug' => 'victorin-axel',
                'premiere_adhesion' => 'readhesion', 'civilite' => 'Monsieur',
                'nom' => 'VICTORIN', 'prenom' => 'Axel',
                'date_adhesion' => '17/09/2025', 'moyen_paiement' => 'virement',
                'date_naissance' => '21/11/1996', 'profession' => "Chef d'entreprise",
                'telephone' => '0696 27 97 70', 'email' => 'victorin.axel@gmail.com',
                'adresse_postale' => 'Morne Pitault, Grande Ravine 97240 Le François',
                'taille_tshirt' => 'L', 'permis' => 'Oui',
                'problemes_sante' => 'Allergie crustacés',
                'urgence_contact' => 'ROSAMOND Raymonde - 0696 34 56 44',
            ],
            [
                'slug' => 'agesilas-lionely',
                'premiere_adhesion' => 'readhesion', 'civilite' => 'Monsieur',
                'nom' => 'AGESILAS', 'prenom' => 'Lionely',
                'date_adhesion' => '19/09/2025', 'moyen_paiement' => 'espece',
                'date_naissance' => '13/11/1996', 'profession' => 'Étudiant',
                'telephone' => '0696 41 60 80', 'email' => 'agesilas.lionely.c@gmail.com',
                'adresse_postale' => 'Quartier Wallon, 97229 Les Trois-Îlets',
                'taille_tshirt' => 'XL', 'permis' => 'Oui',
                'problemes_sante' => null,
                'urgence_contact' => 'BARDOUX Eric 0696402497',
            ],
            [
                'slug' => 'valentin-anayel',
                'premiere_adhesion' => 'readhesion', 'civilite' => 'Madame',
                'nom' => 'VALENTIN', 'prenom' => 'Anayel',
                'date_adhesion' => '28/09/2025', 'moyen_paiement' => 'virement',
                'date_naissance' => '07/02/2006', 'profession' => 'Étudiante',
                'telephone' => '0696 53 02 07', 'email' => 'anayel.valentin@icloud.com',
                'adresse_postale' => '24 lot Alizée Fleury quartier Médecin 97215 Rivière-Salée',
                'taille_tshirt' => 'M', 'permis' => 'Non',
                'problemes_sante' => null,
                'urgence_contact' => 'Valentin Dominique 0696805750',
            ],
            [
                'slug' => 'banys-mathilde',
                'premiere_adhesion' => 'readhesion', 'civilite' => 'Madame',
                'nom' => 'BANYS', 'prenom' => 'Mathilde',
                'date_adhesion' => '07/09/2025', 'moyen_paiement' => 'virement',
                'date_naissance' => '17/01/1993', 'profession' => 'Intérimaire',
                'telephone' => '0696 38 33 42', 'email' => 'arc-en-ciel972@gmx.fr',
                'adresse_postale' => '45 C avenue Victor Lamon, Pointe des Carrières, 97200 Fort-de-France',
                'taille_tshirt' => 'M', 'permis' => 'Oui',
                'problemes_sante' => null,
                'urgence_contact' => 'Fanta Xavier +596 696 84 56 44',
            ],
            [
                'slug' => 'rebeau-myriam',
                'premiere_adhesion' => 'readhesion', 'civilite' => 'Madame',
                'nom' => 'REBEAU', 'prenom' => 'Myriam',
                'date_adhesion' => '16/09/2025', 'moyen_paiement' => 'virement',
                'date_naissance' => '01/11/1996', 'profession' => 'Technicienne de laboratoire',
                'telephone' => '0696 05 01 71', 'email' => 'rebeau.myriam@gmail.com',
                'adresse_postale' => 'Quartier Escarvaille 97211 Rivière-Pilote',
                'taille_tshirt' => 'M', 'permis' => 'Oui',
                'problemes_sante' => 'Asthme',
                'urgence_contact' => 'REBEAU Marie-Madeleine - 0696 26 19 86',
            ],
            [
                'slug' => 'labridy-toraya',
                'premiere_adhesion' => 'premiere', 'civilite' => 'Madame',
                'nom' => 'LABRIDY', 'prenom' => 'Toraya',
                'date_adhesion' => '24/09/2025', 'moyen_paiement' => 'virement',
                'date_naissance' => '30/12/1999', 'profession' => 'Projet entrepreneuriat',
                'telephone' => '0696 76 05 65', 'email' => 'ltorayayanna@gmail.com',
                'adresse_postale' => 'Résidence la Source - 97250 Saint-Pierre',
                'taille_tshirt' => 'M', 'permis' => 'Oui',
                'problemes_sante' => null,
                'urgence_contact' => 'Marie-Hélène DON - 0696 76 05 65',
            ],
            [
                'slug' => 'sainvil-jolicoeur-malyca',
                'premiere_adhesion' => 'readhesion', 'civilite' => 'Madame',
                'nom' => 'SAINVIL JOLICOEUR', 'prenom' => 'Malyca',
                'date_adhesion' => '23/09/2025', 'moyen_paiement' => 'espece',
                'date_naissance' => '11/09/2002', 'profession' => 'Étudiante',
                'telephone' => '0696 33 37 29', 'email' => 'sainviljolicoeur.malyca@gmail.com',
                'adresse_postale' => '22 rue Henri Valery 97200 Fort-de-France',
                'taille_tshirt' => 'S', 'permis' => 'Non',
                'problemes_sante' => 'Problèmes de dos et des genoux.',
                'urgence_contact' => 'VICTOR Jacques - 0696442601',
            ],
            [
                'slug' => 'verin-lyncee',
                'premiere_adhesion' => 'premiere', 'civilite' => 'Madame',
                'nom' => 'VERIN', 'prenom' => 'Lyncée',
                'date_adhesion' => '16/09/2025', 'moyen_paiement' => 'virement',
                'date_naissance' => '15/08/1999', 'profession' => 'Étudiante',
                'telephone' => '0696 83 94 89', 'email' => 'lynceeverin15@gmail.com',
                'adresse_postale' => 'Cité Montgérald bâtiment D22 appartement 139 rez-de-chaussée 97200 Fort-de-France',
                'taille_tshirt' => 'XL', 'permis' => 'Oui',
                'problemes_sante' => null,
                'urgence_contact' => 'VERIN Karine - 0696657746',
            ],
            [
                'slug' => 'charlery-leila',
                'premiere_adhesion' => 'readhesion', 'civilite' => 'Madame',
                'nom' => 'CHARLERY', 'prenom' => 'Leila',
                'date_adhesion' => '04/11/2025', 'moyen_paiement' => 'virement',
                'date_naissance' => '08/11/1999', 'profession' => 'Aide-soignante',
                'telephone' => '0696 83 31 31', 'email' => 'leilacharlery97228@gmail.com',
                'adresse_postale' => 'Sainte-Luce quartier Bastopol',
                'taille_tshirt' => 'L', 'permis' => 'Oui',
                'problemes_sante' => 'Problème de dos',
                'urgence_contact' => 'Charlery Fauvette 0696 764804',
            ],
            [
                'slug' => 'wirkhani-adem',
                'premiere_adhesion' => 'readhesion', 'civilite' => 'Monsieur',
                'nom' => 'WIRKHANI', 'prenom' => 'Adem',
                'date_adhesion' => '05/12/2025', 'moyen_paiement' => 'espece',
                'date_naissance' => '11/09/2000', 'profession' => 'Poseur en isolation',
                'telephone' => '0696 19 75 43', 'email' => 'ademjoaquim@gmail.com',
                'adresse_postale' => '119 impasse Vieux Fort Goureau Saint-Joseph',
                'taille_tshirt' => 'M', 'permis' => 'Non',
                'problemes_sante' => null,
                'urgence_contact' => 'Wirkhani Adem 0696197543',
            ],
            [
                'slug' => 'ferreol-cassandra',
                'premiere_adhesion' => 'readhesion', 'civilite' => 'Madame',
                'nom' => 'FERREOL', 'prenom' => 'Cassandra',
                'date_adhesion' => '19/09/2025', 'moyen_paiement' => 'virement',
                'date_naissance' => '10/10/2008', 'profession' => 'Étudiante',
                'telephone' => '0696 10 43 63', 'email' => 'cassandraanneemanuelle1234@gmail.com',
                'adresse_postale' => 'Route du Morne Vert 97224 Ducos',
                'taille_tshirt' => '2XL', 'permis' => 'Non',
                'problemes_sante' => null,
                'urgence_contact' => 'Povia Prisca +596 696 83 85 65',
            ],
            [
                'slug' => 'derond-maureen',
                'premiere_adhesion' => 'readhesion', 'civilite' => 'Madame',
                'nom' => 'DEROND', 'prenom' => 'Maureen',
                'date_adhesion' => '12/04/2026', 'moyen_paiement' => 'virement',
                'date_naissance' => '26/07/1999', 'profession' => 'Concierge',
                'telephone' => '0696 68 91 38', 'email' => 'mauuw26@gmail.com',
                'adresse_postale' => 'Cité Godissard bât. Z6 esc. B porte 21',
                'taille_tshirt' => '2XL', 'permis' => 'Oui',
                'problemes_sante' => 'Saucisson, fruits de mer',
                'urgence_contact' => 'DEROND Claudine 0696456518',
            ],
            [
                'slug' => 'govindin-sarah',
                'premiere_adhesion' => 'readhesion', 'civilite' => 'Madame',
                'nom' => 'GOVINDIN', 'prenom' => 'Sarah',
                'date_adhesion' => '25/10/2025', 'moyen_paiement' => 'virement',
                'date_naissance' => '31/12/2006', 'profession' => 'Étudiante',
                'telephone' => '0696 06 18 03', 'email' => 'sgovindin@outlook.fr',
                'adresse_postale' => '97233',
                'taille_tshirt' => 'S', 'permis' => 'Oui',
                'problemes_sante' => null,
                'urgence_contact' => 'Briand Marie-Claire 0696061803',
            ],
            [
                'slug' => 'rene-lois',
                'premiere_adhesion' => 'readhesion', 'civilite' => 'Madame',
                'nom' => 'RENÉ', 'prenom' => 'Loïs',
                'date_adhesion' => '04/11/2025', 'moyen_paiement' => 'virement',
                'date_naissance' => '10/09/1998', 'profession' => 'Technicienne de laboratoire',
                'telephone' => '0696 54 80 27', 'email' => 'lois.katleen.rene@laposte.net',
                'adresse_postale' => '94 route de Redoute',
                'taille_tshirt' => 'S', 'permis' => 'Oui',
                'problemes_sante' => 'Allergies aux fruits de mer',
                'urgence_contact' => 'RAFFIN Sandra 0696 98 62 71',
            ],
            [
                'slug' => 'mongis-sevrinne',
                'premiere_adhesion' => 'readhesion', 'civilite' => 'Madame',
                'nom' => 'MONGIS', 'prenom' => 'Sévrinne',
                'date_adhesion' => '09/11/2025', 'moyen_paiement' => 'espece',
                'date_naissance' => '26/02/1993', 'profession' => 'Développement personnel / Formation',
                'telephone' => '0696 85 12 72', 'email' => 'mongis.sev26@gmail.com',
                'adresse_postale' => 'Garifuna bâtiment 4 porte 1 97290 Le Marin',
                'taille_tshirt' => 'L', 'permis' => 'Non',
                'problemes_sante' => 'Intolérance lactose, allergies crustacés',
                'urgence_contact' => 'Elvire MONGIS - 0696 71 02 11',
            ],
            [
                'slug' => 'peronet-jonathan',
                'premiere_adhesion' => 'readhesion', 'civilite' => 'Monsieur',
                'nom' => 'PERONET', 'prenom' => 'Jonathan',
                'date_adhesion' => '27/11/2025', 'moyen_paiement' => 'virement',
                'date_naissance' => '06/01/2000', 'profession' => 'Mécanicien',
                'telephone' => '0696 16 93 53', 'email' => 'peronetnathan@icloud.com',
                'adresse_postale' => '97225',
                'taille_tshirt' => 'L', 'permis' => 'Oui',
                'problemes_sante' => 'Allergie fruits de mer',
                'urgence_contact' => 'Peronet Clémence 0696204790',
            ],
            [
                'slug' => 'pepin-ambre',
                'premiere_adhesion' => 'premiere', 'civilite' => 'Madame',
                'nom' => 'PEPIN', 'prenom' => 'Ambre',
                'date_adhesion' => '10/10/2025', 'moyen_paiement' => 'espece',
                'date_naissance' => '09/09/2008', 'profession' => 'Lycéenne',
                'telephone' => '0696521616', 'email' => 'ambrepep@gmail.com',
                'adresse_postale' => '97215',
                'taille_tshirt' => 'S', 'permis' => 'Non',
                'problemes_sante' => null,
                'urgence_contact' => 'Pepin Roselly 0696364049',
            ],
            [
                'slug' => 'gabelus-alexandra',
                'premiere_adhesion' => 'premiere', 'civilite' => 'Madame',
                'nom' => 'GABELUS', 'prenom' => 'Alexandra',
                'date_adhesion' => '10/10/2025', 'moyen_paiement' => 'espece',
                'date_naissance' => '08/03/2009', 'profession' => 'Lycéenne',
                'telephone' => '0696544547', 'email' => 'alexandragabelus@gmail.com',
                'adresse_postale' => '97224',
                'taille_tshirt' => 'S', 'permis' => 'Non',
                'problemes_sante' => 'Allergies aux abeilles',
                'urgence_contact' => 'GABELUS Sylvie 0696077529',
            ],
            [
                'slug' => 'eruam-mailie',
                'premiere_adhesion' => 'premiere', 'civilite' => 'Madame',
                'nom' => 'ERUAM', 'prenom' => 'Maïlie',
                'date_adhesion' => '30/09/2025', 'moyen_paiement' => 'espece',
                'date_naissance' => '30/10/2007', 'profession' => 'Étudiante',
                'telephone' => '0696742429', 'email' => 'eruammailie@gmail.com',
                'adresse_postale' => '358 route de Balata 97200',
                'taille_tshirt' => 'M', 'permis' => 'Non',
                'problemes_sante' => 'Anti-inflammatoires, asthmatique, hypersensible',
                'urgence_contact' => 'ERUAM Jessica 0696939641',
            ],
            [
                'slug' => 'henry-jasmine',
                'premiere_adhesion' => 'readhesion', 'civilite' => 'Madame',
                'nom' => 'HENRY', 'prenom' => 'Jasmine',
                'date_adhesion' => '05/10/2025', 'moyen_paiement' => 'virement',
                'date_naissance' => '07/05/1999', 'profession' => 'Chargée de gestion administrative et financière',
                'telephone' => '0696113568', 'email' => 'minamine972@gmail.com',
                'adresse_postale' => 'Morne Morissot Résidence la Principauté appt 38 bât. Florila 97200 Fort-de-France',
                'taille_tshirt' => 'M', 'permis' => 'Oui',
                'problemes_sante' => 'Allergie kiwi et ananas, crise psychogène et non épileptique, végétarienne (ni poisson, ni viande, ni crustacé)',
                'urgence_contact' => 'Henry Aurore +590690217726',
            ],
            [
                'slug' => 'joseph-malick',
                'premiere_adhesion' => 'readhesion', 'civilite' => 'Monsieur',
                'nom' => 'JOSEPH', 'prenom' => 'Malick',
                'date_adhesion' => '09/09/2025', 'moyen_paiement' => 'virement',
                'date_naissance' => '12/01/2001', 'profession' => 'Gérant de société (sécurité privée)',
                'telephone' => '0696537570', 'email' => 'malick.joseph12@gmail.com',
                'adresse_postale' => 'Quartier Là-Haut, Résidence Le Vallon, appt 15, 97215 Rivière-Salée',
                'taille_tshirt' => '3XL', 'permis' => 'Oui',
                'problemes_sante' => null,
                'urgence_contact' => 'JOSEPH Arielle - 0696 31 46 49',
            ],
            [
                'slug' => 'milcent-luigi',
                'premiere_adhesion' => 'readhesion', 'civilite' => 'Monsieur',
                'nom' => 'MILCENT', 'prenom' => 'Luigi',
                'date_adhesion' => '09/10/2025', 'moyen_paiement' => 'espece',
                'date_naissance' => '03/03/2006', 'profession' => 'RSMA',
                'telephone' => '0696481119', 'email' => 'milcent.luidgi@gmail.com',
                'adresse_postale' => 'Quartier Gaschette Résidence les Îles 2, 97231 Le Robert',
                'taille_tshirt' => 'XL', 'permis' => 'Oui',
                'problemes_sante' => null,
                'urgence_contact' => 'Micholet Corinne 0696814781',
            ],
            [
                'slug' => 'nella-jeremy',
                'premiere_adhesion' => 'readhesion', 'civilite' => 'Monsieur',
                'nom' => 'NELLA', 'prenom' => 'Jeremy',
                'date_adhesion' => '22/12/2025', 'moyen_paiement' => 'espece',
                'date_naissance' => '29/12/1990', 'profession' => 'Sans profession',
                'telephone' => '0696749469', 'email' => 'jeremy.nella@gmail.com',
                'adresse_postale' => '39 rue de la Rocade 97200',
                'taille_tshirt' => 'M', 'permis' => 'Oui',
                'problemes_sante' => 'Lactose',
                'urgence_contact' => 'Nella Jeremy 0696749469',
            ],
            [
                'slug' => 'lexee-leana',
                'premiere_adhesion' => 'premiere', 'civilite' => 'Madame',
                'nom' => 'LEXEE', 'prenom' => 'Léana',
                'date_adhesion' => '27/11/2025', 'moyen_paiement' => 'virement',
                'date_naissance' => '05/06/1996', 'profession' => "En recherche d'emploi",
                'telephone' => '0696324139', 'email' => 'nanou163@gmail.com',
                'adresse_postale' => 'Quartier Bellay Lot. Salomon appt 2 97228 Sainte-Luce',
                'taille_tshirt' => 'M', 'permis' => 'Oui',
                'problemes_sante' => "Reconnue handicapée, arthrite",
                'urgence_contact' => 'Lexee Lila 0696 32 41 39',
            ],
            [
                'slug' => 'murat-callie',
                'premiere_adhesion' => 'premiere', 'civilite' => 'Madame',
                'nom' => 'MURAT', 'prenom' => 'Callie',
                'date_adhesion' => '12/11/2025', 'moyen_paiement' => 'espece',
                'date_naissance' => '04/04/2009', 'profession' => 'Lycéenne (1re)',
                'telephone' => '0696813414', 'email' => 'callie.04murat@gmail.com',
                'adresse_postale' => 'Lotissement Durocher',
                'taille_tshirt' => 'S', 'permis' => 'Non',
                'problemes_sante' => 'Règles douloureuses',
                'urgence_contact' => 'MARINE Tania - 0696336056',
            ],
            [
                'slug' => 'louisma-deterville-anya',
                'premiere_adhesion' => 'premiere', 'civilite' => 'Madame',
                'nom' => 'LOUISMA DETERVILLE', 'prenom' => 'Anya',
                'date_adhesion' => '15/12/2025', 'moyen_paiement' => 'espece',
                'date_naissance' => '20/10/2010', 'profession' => 'Étudiante',
                'telephone' => '0696254943', 'email' => 'loyismadetervilleanya57@gmail.com',
                'adresse_postale' => '97200',
                'taille_tshirt' => 'S', 'permis' => 'Non',
                'problemes_sante' => 'Aucune',
                'urgence_contact' => 'Louisma Deterville Anya 0696254943',
            ],
            [
                'slug' => 'granville-anissa',
                'premiere_adhesion' => 'premiere', 'civilite' => 'Madame',
                'nom' => 'GRANVILLE', 'prenom' => 'Anissa',
                'date_adhesion' => '05/02/2026', 'moyen_paiement' => 'espece',
                'date_naissance' => '02/06/2010', 'profession' => 'Étudiante',
                'telephone' => '0696 05 27 21', 'email' => 'granvilleanissa3@gmail.com',
                'adresse_postale' => '97200',
                'taille_tshirt' => 'S', 'permis' => 'Non',
                'problemes_sante' => null,
                'urgence_contact' => 'BECRIT Tony 0696849062',
            ],
            [
                'slug' => 'becrit-aylin',
                'premiere_adhesion' => 'premiere', 'civilite' => 'Madame',
                'nom' => 'BECRIT', 'prenom' => 'Aylin',
                'date_adhesion' => '13/11/2025', 'moyen_paiement' => 'espece',
                'date_naissance' => '15/03/2009', 'profession' => 'Lycéenne',
                'telephone' => '0696376212', 'email' => 'aylinbecrit972@gmail.com',
                'adresse_postale' => '97233',
                'taille_tshirt' => 'S', 'permis' => 'Non',
                'problemes_sante' => null,
                'urgence_contact' => 'Borgella Michelange 0696 93 02 73',
            ],
            [
                'slug' => 'lejuste-corine',
                'premiere_adhesion' => 'premiere', 'civilite' => 'Madame',
                'nom' => 'LEJUSTE', 'prenom' => 'Corine',
                'date_adhesion' => '04/11/2025', 'moyen_paiement' => 'espece',
                'date_naissance' => '04/10/2009', 'profession' => 'Lycéenne',
                'telephone' => '0696887996', 'email' => 'lejustecorine@gmail.com',
                'adresse_postale' => '10 Fond-Batelière, Schœlcher 97233',
                'taille_tshirt' => 'S', 'permis' => 'Non',
                'problemes_sante' => null,
                'urgence_contact' => 'Borgella Michelange 0696 93 02 73',
            ],
            [
                'slug' => 'occivilu-thecia',
                'premiere_adhesion' => 'premiere', 'civilite' => 'Madame',
                'nom' => 'OCCIVILU', 'prenom' => 'Thécia',
                'date_adhesion' => '18/11/2025', 'moyen_paiement' => 'espece',
                'date_naissance' => '06/07/2009', 'profession' => 'Étudiante',
                'telephone' => '0696902619', 'email' => 'thecia.occivil6@gmail.com',
                'adresse_postale' => 'Fort-de-France',
                'taille_tshirt' => 'S', 'permis' => 'Non',
                'problemes_sante' => null,
                'urgence_contact' => 'Mère 0696716012',
            ],
            [
                'slug' => 'hierso-julien-noa',
                'premiere_adhesion' => 'premiere', 'civilite' => 'Madame',
                'nom' => 'HIERSO JULIEN', 'prenom' => 'Noa',
                'date_adhesion' => '18/11/2025', 'moyen_paiement' => 'virement',
                'date_naissance' => '26/08/2009', 'profession' => 'Lycéenne',
                'telephone' => '0696060974', 'email' => 'noa972.h@gmail.com',
                'adresse_postale' => '131 quartier Goureau, voie communale dite de Bahuaut, 97212 Saint-Joseph',
                'taille_tshirt' => 'S', 'permis' => 'Non',
                'problemes_sante' => 'Allergie au crabe',
                'urgence_contact' => 'HIERSO JULIEN Véronique 0696 94 94 74',
            ],
            [
                'slug' => 'pamphile-lorelia',
                'premiere_adhesion' => 'premiere', 'civilite' => 'Madame',
                'nom' => 'PAMPHILE', 'prenom' => 'Lorelia',
                'date_adhesion' => '03/12/2025', 'moyen_paiement' => 'espece',
                'date_naissance' => '01/09/2009', 'profession' => 'Lycéenne',
                'telephone' => '0696892429', 'email' => 'pamphilelorelia001@gmail.com',
                'adresse_postale' => '97200',
                'taille_tshirt' => 'S', 'permis' => 'Non',
                'problemes_sante' => null,
                'urgence_contact' => 'Pamphile Nathalie 0696892429',
            ],
            [
                'slug' => 'zaire-kevin',
                'premiere_adhesion' => 'premiere', 'civilite' => 'Monsieur',
                'nom' => 'ZAÏRE', 'prenom' => 'Kévin',
                'date_adhesion' => '15/12/2025', 'moyen_paiement' => 'espece',
                'date_naissance' => '11/07/1997', 'profession' => 'Responsable service clients',
                'telephone' => '0696094490', 'email' => 'kevin.zaire.972@gmail.com',
                'adresse_postale' => 'Quartier Thoraille bâtiment Bordeaux porte 607, 97215 Rivière-Salée',
                'taille_tshirt' => 'XL', 'permis' => 'Oui',
                'problemes_sante' => null,
                'urgence_contact' => 'Zaïre Marie-Aline 0696962053',
            ],
            [
                'slug' => 'henry-clarisse',
                'premiere_adhesion' => 'premiere', 'civilite' => 'Madame',
                'nom' => 'HENRY', 'prenom' => 'Clarisse',
                'date_adhesion' => '02/12/2025', 'moyen_paiement' => 'virement',
                'date_naissance' => '18/08/1993', 'profession' => 'Technicienne de laboratoire',
                'telephone' => '0696885740', 'email' => 'clarissehenry18@gmail.com',
                'adresse_postale' => 'Avenue Wanakaera 97211 Rivière-Pilote',
                'taille_tshirt' => '2XL', 'permis' => 'Oui',
                'problemes_sante' => null,
                'urgence_contact' => 'Henry Ruth 0696343615',
            ],
            [
                'slug' => 'joseph-loudia',
                'premiere_adhesion' => 'premiere', 'civilite' => 'Madame',
                'nom' => 'JOSEPH', 'prenom' => 'Loudia',
                'date_adhesion' => '15/12/2025', 'moyen_paiement' => 'espece',
                'date_naissance' => '08/05/1999', 'profession' => "En recherche d'emploi",
                'telephone' => '0696100051', 'email' => 'jloudia972@gmail.com',
                'adresse_postale' => 'Quartier Thoraille, bâtiment Bordeaux, appartement 607',
                'taille_tshirt' => 'XL', 'permis' => 'Non',
                'problemes_sante' => 'Pas de problème de santé',
                'urgence_contact' => 'Zaïre Kevin 0696094490',
            ],
            [
                'slug' => 'ponsar-jeremie',
                'premiere_adhesion' => 'premiere', 'civilite' => 'Monsieur',
                'nom' => 'PONSAR', 'prenom' => 'Jeremie',
                'date_adhesion' => '17/12/2025', 'moyen_paiement' => 'virement',
                'date_naissance' => '26/08/1996', 'profession' => 'Compositeur de musique',
                'telephone' => '0696778355', 'email' => 'jeremie.ponsar@outlook.com',
                'adresse_postale' => '13 lotissement Panorama',
                'taille_tshirt' => 'L', 'permis' => 'Oui',
                'problemes_sante' => null,
                'urgence_contact' => 'Patrick Ponsar 0696369336',
            ],
            [
                'slug' => 'bordin-julian',
                'premiere_adhesion' => 'readhesion', 'civilite' => 'Monsieur',
                'nom' => 'BORDIN', 'prenom' => 'Julian',
                'date_adhesion' => '24/11/2025', 'moyen_paiement' => 'espece',
                'date_naissance' => '07/10/1999', 'profession' => 'Conducteur de poids lourds',
                'telephone' => '+596 696 77 34 35', 'email' => 'julianbordin@gmail.com',
                'adresse_postale' => '22 lotissement Panorama',
                'taille_tshirt' => 'M', 'permis' => 'Oui',
                'problemes_sante' => null,
                'urgence_contact' => 'Maman ou Sarah',
            ],
        ];
    }
}
