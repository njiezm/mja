<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

/**
 * Compose les vignettes de partage (Open Graph) du site.
 *
 * Quand un lien est collé sur WhatsApp, Facebook, LinkedIn ou dans un SMS,
 * l'aperçu affiché vient d'une image de 1200 x 630. Sans elle, le logo carré
 * du site était étiré ou rogné, et tous les liens se ressemblaient.
 *
 * Les images produites sont écrites dans public/images/partage/ et versionnées
 * avec le projet : la commande ne tourne qu'au moment où l'on veut les
 * régénérer, jamais en production.
 *
 *   php artisan mja:images-partage
 */
class GenererImagesPartage extends Command
{
    protected $signature = 'mja:images-partage {--force : Écrase les images déjà présentes}';

    protected $description = 'Génère les vignettes de partage (1200x630) des pages publiques';

    private const LARGEUR = 1200;
    private const HAUTEUR = 630;

    /** Page → titre, sous-titre, photo de fond (relative à public/). */
    private const PAGES = [
        'defaut' => [
            "Madin'Jeunes Ambition",
            'Association de jeunes engagés en Martinique et au-delà',
            'images/kit/equipe-03.jpg',
        ],
        'accueil' => [
            'Les jeunes de la Martinique se mobilisent',
            'Actions éducatives, culturelles, sociales, sportives et de santé',
            'images/kit/Groupe Pic 2026.JPG',
        ],
        'a-propos' => [
            'Qui sommes-nous ?',
            "Le bureau, l'équipe et les valeurs de l'association",
            'images/kit/equipe-01.jpg',
        ],
        'adhesion' => [
            "Rejoins l'aventure",
            'Adhère à Madin’Jeunes Ambition',
            'images/kit/equipe-04.jpg',
        ],
        'projets' => [
            'Nos projets',
            "Des actions concrètes, toute l'année",
            'images/kit/equipe-05.jpg',
        ],
        'evenements' => [
            'Nos événements',
            "L'agenda de l'association",
            'images/kit/MJA Beach Party 2.jpg',
        ],
        'actualites' => [
            'Nos actualités',
            "Ce que fait l'association, au fil des mois",
            'images/kit/equipe-06.jpg',
        ],
        'ressources' => [
            'Nos ressources',
            'Documents, guides et liens utiles',
            'images/kit/IMG_3368.jpg',
        ],
        'contact' => [
            'Nous écrire',
            "Une question ? L'équipe vous répond",
            'images/kit/equipe-02.jpg',
        ],
        'don' => [
            "Soutenir l'association",
            'Chaque don finance une action de terrain',
            'images/kit/IMG_3366.jpg',
        ],
        'sns' => [
            'Fwi Ti Dèj — Santé Nutrition Sport',
            'Petits-déjeuners solidaires et prévention santé',
            'images/kit/IMG_3367.jpg',
        ],
    ];

    /** Emplacements où chercher une police vectorielle utilisable par GD. */
    private const POLICES = [
        'gras'    => ['C:/Windows/Fonts/arialbd.ttf', '/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf',
                      '/usr/share/fonts/truetype/liberation/LiberationSans-Bold.ttf', '/Library/Fonts/Arial Bold.ttf'],
        'normale' => ['C:/Windows/Fonts/arial.ttf', '/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf',
                      '/usr/share/fonts/truetype/liberation/LiberationSans-Regular.ttf', '/Library/Fonts/Arial.ttf'],
        'italique' => ['C:/Windows/Fonts/ariali.ttf', '/usr/share/fonts/truetype/dejavu/DejaVuSans-Oblique.ttf',
                       '/usr/share/fonts/truetype/liberation/LiberationSans-Italic.ttf'],
    ];

    private array $polices = [];

    public function handle(): int
    {
        if (! extension_loaded('gd')) {
            $this->error("L'extension GD est absente : impossible de composer les images.");

            return self::FAILURE;
        }

        foreach (self::POLICES as $style => $chemins) {
            foreach ($chemins as $chemin) {
                if (is_file($chemin)) {
                    $this->polices[$style] = $chemin;
                    break;
                }
            }
        }

        if (! isset($this->polices['gras'])) {
            $this->error('Aucune police TrueType trouvée — le texte serait illisible.');
            $this->line('Installez une police (DejaVu, Liberation) ou lancez la commande depuis un poste Windows.');

            return self::FAILURE;
        }

        $dossier = public_path('images/partage');
        if (! is_dir($dossier)) {
            mkdir($dossier, 0755, true);
        }

        $faits = 0;
        $ignores = 0;

        foreach (self::PAGES as $slug => [$titre, $sousTitre, $photo]) {
            $destination = $dossier . '/' . $slug . '.jpg';

            if (is_file($destination) && ! $this->option('force')) {
                $ignores++;
                continue;
            }

            $image = $this->composer($titre, $sousTitre, $photo);

            if (! $image) {
                $this->warn("Photo introuvable pour « {$slug} » : {$photo}");
                continue;
            }

            imagejpeg($image, $destination, 86);
            imagedestroy($image);
            $faits++;
            $this->line("  {$slug}.jpg");
        }

        $this->info("{$faits} image(s) composée(s) dans public/images/partage/.");

        if ($ignores) {
            $this->line("{$ignores} déjà présente(s) — utilisez --force pour les refaire.");
        }

        return self::SUCCESS;
    }

    /** @return \GdImage|false */
    private function composer(string $titre, string $sousTitre, string $photo)
    {
        $toile = imagecreatetruecolor(self::LARGEUR, self::HAUTEUR);
        imagefilledrectangle($toile, 0, 0, self::LARGEUR, self::HAUTEUR, imagecolorallocate($toile, 26, 61, 138));

        if (! $this->poserPhoto($toile, public_path($photo))) {
            imagedestroy($toile);

            return false;
        }

        $this->voile($toile);
        $this->filets($toile);
        $this->identite($toile);
        $this->texte($toile, $titre, $sousTitre);

        return $toile;
    }

    /** Photo de fond, recadrée « couvrante » : elle remplit sans se déformer. */
    private function poserPhoto($toile, string $chemin): bool
    {
        if (! is_file($chemin)) {
            return false;
        }

        $info = @getimagesize($chemin);
        $source = match ($info[2] ?? null) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($chemin),
            IMAGETYPE_PNG  => @imagecreatefrompng($chemin),
            IMAGETYPE_WEBP => @imagecreatefromwebp($chemin),
            default        => false,
        };

        if (! $source) {
            return false;
        }

        $lp = imagesx($source);
        $hp = imagesy($source);
        $k = max(self::LARGEUR / $lp, self::HAUTEUR / $hp);
        $lc = (int) round(self::LARGEUR / $k);
        $hc = (int) round(self::HAUTEUR / $k);

        imagecopyresampled(
            $toile, $source,
            0, 0,
            (int) round(($lp - $lc) / 2), (int) round(($hp - $hc) / 3), // cadrage un peu haut : les visages
            self::LARGEUR, self::HAUTEUR, $lc, $hc,
        );
        imagedestroy($source);

        return true;
    }

    /**
     * Voile dégradé bleu nuit : le texte doit rester lisible quelle que soit
     * la photo, y compris sur un ciel blanc.
     */
    private function voile($toile): void
    {
        // Opaque sur les deux tiers gauches, où se trouve le texte, puis levée
        // progressive : la photo reste visible sans jamais gêner la lecture.
        for ($x = 0; $x < self::LARGEUR; $x++) {
            $part = min(1, max(0, ($x - 560) / 520));
            $alpha = (int) round(14 + 108 * $part);
            $couleur = imagecolorallocatealpha($toile, 11, 30, 69, min(127, $alpha));
            imagefilledrectangle($toile, $x, 0, $x, self::HAUTEUR, $couleur);
        }

        // Assombrissement général du bas, pour l'adresse du site.
        for ($y = self::HAUTEUR - 150; $y < self::HAUTEUR; $y++) {
            $part = ($y - (self::HAUTEUR - 150)) / 150;
            $couleur = imagecolorallocatealpha($toile, 11, 30, 69, (int) round(127 - 55 * $part));
            imagefilledrectangle($toile, 0, $y, self::LARGEUR, $y, $couleur);
        }
    }

    /** Les trois filets de la charte, en haut et en bas. */
    private function filets($toile): void
    {
        $tiers = (int) (self::LARGEUR / 3);
        $couleurs = [[61, 174, 245], [245, 166, 35], [208, 2, 27]];

        foreach ([[0, 9], [self::HAUTEUR - 9, self::HAUTEUR]] as [$y1, $y2]) {
            foreach ($couleurs as $i => [$r, $v, $b]) {
                $x2 = $i === 2 ? self::LARGEUR : ($i + 1) * $tiers;
                imagefilledrectangle($toile, $i * $tiers, $y1, $x2, $y2, imagecolorallocate($toile, $r, $v, $b));
            }
        }
    }

    /** Logo, nom de l'association et signature. */
    private function identite($toile): void
    {
        $blanc = imagecolorallocate($toile, 255, 255, 255);
        $bleuClair = imagecolorallocate($toile, 189, 212, 245);

        $this->pastille($toile, 64, 54, 104, 104, 22, $blanc);

        $logo = @imagecreatefromjpeg(public_path('images/logo.jpg'))
            ?: @imagecreatefrompng(public_path('images/logomjat.png'));

        if ($logo) {
            imagecopyresampled($toile, $logo, 74, 64, 0, 0, 84, 84, imagesx($logo), imagesy($logo));
            imagedestroy($logo);
        }

        $this->ecrire($toile, "MADIN' JEUNES AMBITION", 190, 100, 27, $blanc, 'gras', 1.6);
        $this->ecrire($toile, 'Relève tous les défis !', 190, 136, 20, $bleuClair, 'italique');
        $this->ecrire($toile, 'mja-martinique.com', 64, self::HAUTEUR - 44, 22, $bleuClair, 'gras', 0.6);
    }

    /** Titre et sous-titre de la page, alignés en bas à gauche. */
    private function texte($toile, string $titre, string $sousTitre): void
    {
        $blanc = imagecolorallocate($toile, 255, 255, 255);
        $bleuClair = imagecolorallocate($toile, 200, 220, 250);
        $jaune = imagecolorallocate($toile, 245, 166, 35);

        // La composition se construit depuis le bas : l'adresse du site est
        // ancrée, le sous-titre se pose au-dessus, le titre au-dessus encore.
        // Sans cela, un titre long repoussait le sous-titre sur l'adresse.
        $largeurMax = 800;

        $sousLignes = array_slice($this->decouper($sousTitre, 24, $largeurMax, 'normale'), 0, 2);
        $basSousTitre = self::HAUTEUR - 86;
        $hautSousTitre = $basSousTitre - (count($sousLignes) - 1) * 32;

        // Deux lignes de titre au maximum : au-delà, l'aperçu devient un pavé.
        $taille = 58;
        $lignes = $this->decouper($titre, $taille, $largeurMax, 'gras');

        while (count($lignes) > 2 && $taille > 32) {
            $taille -= 3;
            $lignes = $this->decouper($titre, $taille, $largeurMax, 'gras');
        }

        $interligne = (int) round($taille * 1.2);
        $basTitre = $hautSousTitre - 50;
        $hautTitre = $basTitre - (count($lignes) - 1) * $interligne;

        imagefilledrectangle($toile, 64, $hautTitre - $taille - 34, 64 + 74, $hautTitre - $taille - 28, $jaune);

        foreach ($lignes as $i => $ligne) {
            $this->ecrire($toile, $ligne, 64, $hautTitre + $i * $interligne, $taille, $blanc, 'gras');
        }

        foreach ($sousLignes as $i => $ligne) {
            $this->ecrire($toile, $ligne, 64, $hautSousTitre + $i * 32, 24, $bleuClair, 'normale');
        }
    }

    /** Rectangle à coins arrondis — GD n'en propose pas. */
    private function pastille($toile, int $x, int $y, int $l, int $h, int $r, int $couleur): void
    {
        imagefilledrectangle($toile, $x + $r, $y, $x + $l - $r, $y + $h, $couleur);
        imagefilledrectangle($toile, $x, $y + $r, $x + $l, $y + $h - $r, $couleur);

        foreach ([[$x + $r, $y + $r], [$x + $l - $r, $y + $r],
                  [$x + $r, $y + $h - $r], [$x + $l - $r, $y + $h - $r]] as [$cx, $cy]) {
            imagefilledellipse($toile, $cx, $cy, $r * 2, $r * 2, $couleur);
        }
    }

    private function ecrire($toile, string $texte, int $x, int $y, int $taille, int $couleur,
                            string $style = 'normale', float $espacement = 0): void
    {
        $police = $this->polices[$style] ?? $this->polices['gras'];

        if ($espacement <= 0) {
            imagettftext($toile, $taille, 0, $x, $y, $couleur, $police, $texte);

            return;
        }

        // GD ne gère pas l'interlettrage : on place les caractères un à un.
        foreach (preg_split('//u', $texte, -1, PREG_SPLIT_NO_EMPTY) as $caractere) {
            imagettftext($toile, $taille, 0, $x, $y, $couleur, $police, $caractere);
            $boite = imagettfbbox($taille, 0, $police, $caractere);
            $x += (int) round(($boite[2] - $boite[0]) + $espacement);
        }
    }

    /** @return string[] */
    private function decouper(string $texte, int $taille, int $largeurMax, string $style): array
    {
        $police = $this->polices[$style] ?? $this->polices['gras'];
        $lignes = [];
        $courante = '';

        foreach (preg_split('/\s+/u', $texte) as $mot) {
            $essai = $courante === '' ? $mot : $courante . ' ' . $mot;
            $boite = imagettfbbox($taille, 0, $police, $essai);

            if (($boite[2] - $boite[0]) > $largeurMax && $courante !== '') {
                $lignes[] = $courante;
                $courante = $mot;
            } else {
                $courante = $essai;
            }
        }

        if ($courante !== '') {
            $lignes[] = $courante;
        }

        return $lignes;
    }
}
