<?php

namespace App\Console\Commands;

use App\Models\Adhesion;
use App\Models\Member;
use Illuminate\Console\Command;

/**
 * Diagnostic de la chaîne « photo » du trombinoscope :
 *   public/images/membres_actus/<slug>.jpg   (source, versionnée)
 *        → seeder →
 *   storage/app/public/adhesions/photos/     (disque public, hors git)
 *        → public/storage (lien symbolique) →
 *   <img src="/storage/adhesions/photos/…">
 */
class DiagPhotosMembres extends Command
{
    protected $signature = 'mja:diag-photos';

    protected $description = 'Vérifie pourquoi les photos du trombinoscope ne s\'affichent pas';

    public function handle(): int
    {
        $ok = true;

        // 1. Comptes affichés dans le trombinoscope
        $membres = Member::where('show_in_directory', true)->with('adhesion')->get()
            ->filter(fn ($m) => $m->adhesion !== null);

        $this->line('Adhésions en base ........ ' . Adhesion::count());
        $this->line('Comptes adhérents ........ ' . Member::count());
        $this->line('Visibles trombinoscope ... ' . $membres->count());

        if ($membres->isEmpty()) {
            $this->error('→ Aucun compte visible : le seeder n\'a pas encore tourné sur ce serveur.');
            $this->line('  php artisan db:seed --class=MembresActuelsSeeder --force');

            return self::FAILURE;
        }

        // 2. Lien symbolique public/storage
        if (is_dir(public_path('storage'))) {
            $this->line('Lien public/storage ...... ok');
        } else {
            $this->error('Lien public/storage ...... MANQUANT  →  php artisan storage:link');
            $ok = false;
        }

        // 3. Images source livrées par git
        $sources = glob(public_path('images/membres_actus/*.{jpg,jpeg,png,webp,JPG,JPEG,PNG,WEBP}'), GLOB_BRACE);
        $this->line('Images source ............ ' . count($sources) . ' dans public/images/membres_actus');

        if (! $sources) {
            $this->error('→ Le dossier source est vide : les images ne sont pas arrivées sur le serveur.');
            $ok = false;
        }

        // 4. Colonne photo + fichier réellement présent sur le disque public
        $sansPhoto = $membres->filter(fn ($m) => ! $m->adhesion->photo);
        $fichierManquant = $membres->filter(
            fn ($m) => $m->adhesion->photo
                && ! file_exists(storage_path('app/public/' . $m->adhesion->photo))
        );

        $this->line('Colonne photo remplie .... ' . ($membres->count() - $sansPhoto->count()) . '/' . $membres->count());
        $this->line('Fichiers sur le disque ... ' . count(glob(storage_path('app/public/adhesions/photos/*'))));

        if ($sansPhoto->isNotEmpty()) {
            $ok = false;
            $this->newLine();
            $this->warn('Colonne photo vide (' . $sansPhoto->count() . ') — le seeder a tourné avant l\'arrivée des images :');
            $this->line('  ' . $sansPhoto->map(fn ($m) => $m->adhesion->nom . ' ' . $m->adhesion->prenom)->implode(', '));
            $this->newLine();
            $this->line('  Relancer (sans risque, idempotent) :');
            $this->line('  php artisan db:seed --class=MembresActuelsSeeder --force');
        }

        if ($fichierManquant->isNotEmpty()) {
            $ok = false;
            $this->newLine();
            $this->error('Chemin en base mais fichier absent du disque (' . $fichierManquant->count() . ') :');
            foreach ($fichierManquant as $m) {
                $this->line('  ' . $m->adhesion->nom . ' → ' . $m->adhesion->photo);
            }
        }

        $this->newLine();
        $this->{$ok ? 'info' : 'warn'}($ok ? 'Chaîne photo complète.' : 'Voir les points ci-dessus.');

        return $ok ? self::SUCCESS : self::FAILURE;
    }
}
