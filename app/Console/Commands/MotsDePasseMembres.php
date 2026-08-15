<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Gestion des mots de passe « espace adhérent » en ligne de commande.
 *
 *   php artisan mja:mdp-membres                  → état des comptes
 *   php artisan mja:mdp-membres --importer-csv   → rend lisibles les mdp EXISTANTS (ne les change pas)
 *   php artisan mja:mdp-membres --regenerer --email=x@y  → nouveau mdp pour un compte
 *
 * Les mots de passe sont hachés : un mot de passe existant n'est récupérable
 * QUE via --importer-csv, à partir du CSV produit par le seeder sur ce serveur.
 */
class MotsDePasseMembres extends Command
{
    protected $signature = 'mja:mdp-membres
                            {--importer-csv : Rend lisibles les mots de passe existants à partir du CSV (sans les modifier)}
                            {--regenerer : Génère un NOUVEAU mot de passe (l\'ancien cesse de fonctionner)}
                            {--email= : Limiter à cette adresse}';

    protected $description = 'Consulte, importe ou régénère les mots de passe des comptes adhérents';

    private const FICHIER = 'membres-actus-comptes.csv';

    public function handle(): int
    {
        if ($this->option('importer-csv')) {
            return $this->importerCsv();
        }

        if ($this->option('regenerer')) {
            return $this->regenerer();
        }

        return $this->etat();
    }

    /** État des comptes : lisible / non lisible. */
    private function etat(): int
    {
        $membres = $this->cibles();

        if ($membres->isEmpty()) {
            $this->error('Aucun compte trouvé.');

            return self::FAILURE;
        }

        $lisibles = $membres->filter(fn ($m) => $m->getDecryptedPassword() !== null);

        $this->table(
            ['Nom', 'Prénom', 'Email', 'Mot de passe'],
            $membres->map(fn ($m) => [
                $m->adhesion?->nom,
                $m->adhesion?->prenom,
                $m->email,
                $m->getDecryptedPassword() ?? '— non lisible —',
            ])->all()
        );

        $this->newLine();
        $this->line($membres->count() . ' compte(s), dont ' . $lisibles->count() . ' avec mot de passe lisible.');

        if ($lisibles->count() < $membres->count()) {
            $this->newLine();
            $this->line('Pour rendre lisibles les mots de passe EXISTANTS (sans les changer) :');
            $this->line('  php artisan mja:mdp-membres --importer-csv');
        }

        return self::SUCCESS;
    }

    /**
     * Lit le CSV du seeder et, pour chaque ligne dont le mot de passe correspond
     * réellement au hash en base, enregistre la copie chiffrée. Aucun mot de
     * passe n'est modifié : on ne fait que rendre lisible l'existant.
     */
    private function importerCsv(): int
    {
        if (! Storage::disk('local')->exists(self::FICHIER)) {
            $this->error('Fichier introuvable : storage/app/private/' . self::FICHIER);
            $this->line('Ce CSV est produit par le seeder, sur le serveur où il a tourné.');

            return self::FAILURE;
        }

        $lignes = array_filter(explode("\n", Storage::disk('local')->get(self::FICHIER)));

        $importes = 0;
        $deja = 0;
        $obsoletes = [];
        $inconnus = [];

        foreach ($lignes as $ligne) {
            $ligne = trim($ligne);

            if ($ligne === '' || str_starts_with($ligne, '#') || str_starts_with($ligne, 'Nom;')) {
                continue;
            }

            $cols = str_getcsv($ligne, ';', '"');

            if (count($cols) < 4) {
                continue;
            }

            $email = Str::lower(trim($cols[2]));
            $mdp = $cols[3];

            $member = User::where('email', $email)->first();

            if (! $member) {
                $inconnus[] = $email;
                continue;
            }

            // Le mot de passe du CSV correspond-il vraiment au hash en base ?
            if (! Hash::check($mdp, $member->password)) {
                $obsoletes[] = $email;
                continue;
            }

            if ($member->getDecryptedPassword() !== null) {
                $deja++;
                continue;
            }

            // On chiffre le mot de passe EXISTANT sans toucher au hash.
            $member->password_encrypted = \Illuminate\Support\Facades\Crypt::encryptString($mdp);
            $member->save();
            $importes++;
        }

        $this->info($importes . ' mot(s) de passe rendu(s) lisible(s), sans aucune modification.');

        if ($deja) {
            $this->line($deja . ' déjà lisible(s).');
        }

        if ($obsoletes) {
            $this->newLine();
            $this->warn(count($obsoletes) . ' ligne(s) du CSV ne correspondent PAS au mot de passe en base :');
            $this->line('  ' . implode(', ', $obsoletes));
            $this->line('  → CSV d\'un autre serveur ou d\'un run précédent. Ces comptes doivent être régénérés :');
            $this->line('    php artisan mja:mdp-membres --regenerer --email=…');
        }

        if ($inconnus) {
            $this->newLine();
            $this->warn(count($inconnus) . ' email(s) du CSV sans compte en base : ' . implode(', ', $inconnus));
        }

        return self::SUCCESS;
    }

    /** Génère un NOUVEAU mot de passe — action destructive, confirmée. */
    private function regenerer(): int
    {
        $membres = $this->cibles();

        if ($membres->isEmpty()) {
            $this->error('Aucun compte trouvé.');

            return self::FAILURE;
        }

        if (! $this->option('email')) {
            $this->warn('Vous allez remplacer le mot de passe de ' . $membres->count() . ' compte(s).');
            $this->warn('Les mots de passe actuels cesseront immédiatement de fonctionner.');

            if (! $this->confirm('Continuer ?', false)) {
                $this->line('Annulé.');

                return self::SUCCESS;
            }
        }

        $lignes = [];

        foreach ($membres as $m) {
            $mdp = User::motDePasseTemporaire();
            $m->setPasswordAndCopy($mdp);
            $m->save();

            $lignes[] = [$m->adhesion?->nom, $m->adhesion?->prenom, $m->email, $mdp];
        }

        $this->table(['Nom', 'Prénom', 'Email', 'Nouveau mot de passe'], $lignes);
        $this->newLine();
        $this->info(count($lignes) . ' mot(s) de passe régénéré(s).');
        $this->line('Consultables aussi en back-office : Comptes adhérents (super admin).');

        return self::SUCCESS;
    }

    /** @return \Illuminate\Database\Eloquent\Collection<int, User> */
    private function cibles()
    {
        // Ne cible que les comptes rattachés à une adhésion.
        $query = User::with('adhesion')->whereNotNull('adhesion_id');

        if ($email = $this->option('email')) {
            $query->where('email', Str::lower(trim($email)));
        }

        return $query->get();
    }
}
