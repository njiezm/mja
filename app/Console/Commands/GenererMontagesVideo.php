<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

/**
 * Rend en vidéo les ébauches décrites dans public/videos/kit/montage.json.
 *
 * Le monteur du navigateur (/kit-video) sert à ajuster et à réexporter ; cette
 * commande, elle, produit les fichiers déjà prêts à publier, pour n'avoir rien
 * à faire quand il faut poster vite.
 *
 * Les réels sortent muets : sur Instagram et TikTok la musique s'ajoute dans
 * l'application, et une piste importée serait de toute façon remplacée.
 *
 *   php artisan mja:montages
 *   php artisan mja:montages --force --seulement=teaser
 */
class GenererMontagesVideo extends Command
{
    protected $signature = 'mja:montages
        {--force : Refait les montages déjà présents}
        {--seulement= : Ne rend qu\'une ébauche, par son identifiant}
        {--musique= : Fichier de public/videos/musiques/ à poser sous le montage}
        {--musique-depart=0 : Seconde du morceau où commencer — le refrain est rarement au début}';

    protected $description = 'Assemble les ébauches de /kit-video en réels 1080x1920 prêts à publier';

    private const L = 1080;
    private const H = 1920;
    private const IPS = 30;

    /** Durée du fondu entre deux plans, en secondes. */
    private const FONDU = 0.4;

    /** Fonds proposés pour un carton : haut, bas, couleur du texte. */
    private const NUANCES = [
        'navy'  => [[20, 48, 110],  [42, 85, 180],   [255, 255, 255]],
        'bleu'  => [[26, 61, 138],  [61, 174, 245],  [255, 255, 255]],
        'encre' => [[11, 30, 69],   [32, 72, 164],   [255, 255, 255]],
        'jaune' => [[245, 166, 35], [255, 205, 120], [11, 30, 69]],
        'rouge' => [[176, 6, 30],   [208, 2, 27],    [255, 255, 255]],
        'blanc' => [[255, 255, 255], [232, 240, 252], [11, 30, 69]],
    ];

    /** Fondu de sortie du son : une coupure nette en fin de réel s'entend. */
    private const FONDU_SON = 1.2;

    private string $ffmpeg;
    private string $ffprobe;
    private string $travail;

    public function handle(): int
    {
        if (! $this->outils()) {
            return self::FAILURE;
        }

        $fiche = json_decode((string) @file_get_contents(public_path('videos/kit/montage.json')), true);

        if (! is_array($fiche) || empty($fiche['ebauches'])) {
            $this->error('Aucune ébauche lisible dans public/videos/kit/montage.json.');

            return self::FAILURE;
        }

        $sortie = public_path('videos/montages');
        $this->travail = storage_path('app/montages-tmp');

        foreach ([$sortie, $this->travail] as $dossier) {
            if (! is_dir($dossier)) {
                mkdir($dossier, 0755, true);
            }
        }

        $seulement = $this->option('seulement');
        $faits = 0;

        foreach ($fiche['ebauches'] as $ebauche) {
            if ($seulement && $ebauche['id'] !== $seulement) {
                continue;
            }

            $destination = $sortie . '/' . $ebauche['id'] . '.mp4';

            if (is_file($destination) && ! $this->option('force')) {
                $this->line("  {$ebauche['id']}.mp4 déjà présent — --force pour le refaire");
                continue;
            }

            $this->info("▸ {$ebauche['nom']}");

            if ($this->rendre($ebauche, $fiche['plans'], $destination)) {
                $faits++;
            }
        }

        $this->nettoyer();

        $this->newLine();
        $this->info($faits . ' montage(s) écrit(s) dans public/videos/montages/.');

        return self::SUCCESS;
    }

    /** Localise ffmpeg et ffprobe, sans quoi rien n'est possible. */
    private function outils(): bool
    {
        $candidats = [
            'ffmpeg',
            'C:/Users/' . (getenv('USERNAME') ?: '') . '/AppData/Local/Microsoft/WinGet/Packages/Gyan.FFmpeg_Microsoft.Winget.Source_8wekyb3d8bbwe/ffmpeg-8.1.1-full_build/bin/ffmpeg.exe',
            '/usr/bin/ffmpeg',
            '/usr/local/bin/ffmpeg',
        ];

        foreach ($candidats as $chemin) {
            $test = new Process([$chemin, '-version']);
            $test->run();

            if ($test->isSuccessful()) {
                $this->ffmpeg = $chemin;
                $this->ffprobe = str_replace('ffmpeg', 'ffprobe', $chemin);

                return true;
            }
        }

        $this->error('ffmpeg est introuvable — impossible de rendre les montages.');
        $this->line('Installez-le (winget install Gyan.FFmpeg, ou apt install ffmpeg) puis relancez.');

        return false;
    }

    /** Assemble une ébauche : cartons, plans normalisés, fondus, export. */
    private function rendre(array $ebauche, array $plans, string $destination): bool
    {
        $segments = [];

        // ── Carton d'ouverture ───────────────────────────────────────
        // « aucune » veut dire aucune : certains montages ouvrent volontairement
        // sur une image forte, un carton de titre les ferait défiler.
        $intro = ($ebauche['intro'] ?? 'titre') === 'aucune'
            ? null
            : $this->carton($ebauche['id'] . '-intro', $ebauche['accroche'] ?? '', $ebauche['sous'] ?? '', 'intro');

        if ($intro) {
            $segments[] = ['image' => $intro, 'duree' => 2.4, 'carton' => true];
        }

        // ── Les plans ────────────────────────────────────────────────
        $rangCarton = 0;

        foreach ($ebauche['plans'] as $entree) {
            // Un carton n'a pas de fichier : c'est une phrase sur aplat,
            // intercalée entre deux plans pour rythmer le propos.
            if (is_array($entree) && ! empty($entree['carton'])) {
                $duree = (float) ($entree['duree'] ?? 1.4);
                $rang = $rangCarton++;

                // Mot après mot : la phrase se lit au rythme où elle s'écrit,
                // ce qui retient l'œil bien mieux qu'un bloc posé d'un coup.
                if (! empty($entree['mots'])) {
                    $anime = $this->carteAnimee($entree['carton'], $rang, $entree['emoji'] ?? null,
                                                $duree, $entree['couleur'] ?? null);

                    if ($anime) {
                        $segments[] = ['video' => $anime, 'depart' => 0, 'duree' => $duree, 'carton' => true];
                        continue;
                    }
                }

                $image = $this->carteTexte($entree['carton'], $rang, $entree['emoji'] ?? null,
                                           null, $entree['couleur'] ?? null);

                if ($image) {
                    $segments[] = [
                        'image' => $image,
                        'duree' => $duree,
                        'carton' => true,
                        // Un aplat parfaitement immobile ressemble à une panne :
                        // une poussée lente suffit à le rendre vivant.
                        'zoom' => true,
                    ];
                }

                continue;
            }

            // Un plan se cite par son nom, ou par un objet qui resserre
            // l'extrait — les montages à coupes rapides en ont besoin.
            $nom = is_array($entree) ? ($entree['fichier'] ?? '') : $entree;
            $source = public_path('videos/kit/' . $nom);

            if ($nom === '' || ! is_file($source)) {
                $this->warn("    plan absent, ignoré : {$nom}");
                continue;
            }

            $meta = $plans[$nom] ?? [];
            $duree = (float) (is_array($entree) ? ($entree['duree'] ?? $meta['duree'] ?? 3) : ($meta['duree'] ?? 3));
            $depart = (float) (is_array($entree) ? ($entree['depart'] ?? $meta['depart'] ?? 0) : ($meta['depart'] ?? 0));

            $legende = is_array($entree) ? ($entree['texte'] ?? null) : null;

            // Une poussée d'objectif redonne du mouvement à un plan fixe, et
            // donne l'impression d'avancer vers ce qu'on regarde.
            $poussee = is_array($entree)
                ? (bool) ($entree['poussee'] ?? ($ebauche['poussee'] ?? false))
                : (bool) ($ebauche['poussee'] ?? false);

            $vitesse = is_array($entree) ? (float) ($entree['vitesse'] ?? 1) : 1;

            $segments[] = str_ends_with(strtolower($nom), '.mp4')
                ? ['video' => $source, 'depart' => $depart, 'duree' => $duree,
                   'texte' => $legende, 'poussee' => $poussee, 'vitesse' => $vitesse]
                : ['image' => $source, 'duree' => $duree, 'zoom' => true, 'texte' => $legende];
        }

        // ── Carton de fermeture ──────────────────────────────────────
        // Le sous-titre de l'intro ne convient pas au carton de fin : « ÇA
        // COMMENCE » sous « J'ADHÈRE » n'a aucun sens. On y met la saison.
        $outro = ($ebauche['outro'] ?? 'appel') === 'aucune'
            ? null
            : $this->carton($ebauche['id'] . '-outro', "J'ADHÈRE",
                            $ebauche['saison'] ?? 'SAISON 2026-2027', 'outro');

        if ($outro) {
            $segments[] = ['image' => $outro, 'duree' => 2.4, 'carton' => true];
        }

        if (count($segments) < 2) {
            $this->warn('    pas assez de plans exploitables.');

            return false;
        }

        // Chaque segment est d'abord ramené au même format : c'est la seule
        // façon fiable d'enchaîner des sources de tailles et de cadences
        // différentes sans que ffmpeg refuse le raccord.
        $normalises = [];

        foreach ($segments as $i => $segment) {
            $this->output->write('.');
            $fichier = $this->travail . '/' . $ebauche['id'] . '-' . str_pad((string) $i, 2, '0', STR_PAD_LEFT) . '.mp4';

            if (! $this->normaliser($segment, $fichier)) {
                $this->warn('    segment ' . $i . ' illisible, ignoré.');
                continue;
            }

            // La durée réelle prime : un carton animé ou un ralenti tombe
            // rarement au centième près, et l'écart décale tous les raccords.
            $reelle = (float) str_replace(',', '.', $this->duree($fichier));

            $normalises[] = [
                'fichier' => $fichier,
                'duree' => $reelle > 0.2 ? $reelle : $segment['duree'],
            ];
        }

        $this->output->write(' assemblage… ');

        $ok = ($ebauche['transition'] ?? 'fondu') === 'coupe'
            ? $this->assemblerCoupe($normalises, $destination)
            : $this->assemblerFondu($normalises, $destination, $ebauche['transition'] ?? 'fondu');

        if (! $ok) {
            $this->error('échec');

            return false;
        }

        if ($this->option('musique') && ! $this->poserMusique($destination)) {
            $this->warn('    musique non ajoutée.');
        }

        $this->affiche($destination);
        $duree = $this->duree($destination);
        $poids = round(filesize($destination) / 1048576, 1);
        $this->line("terminé — {$duree} s, {$poids} Mo");

        return true;
    }

    /**
     * Ramène un segment au format cible : 1080x1920, 30 ips, sans son.
     *
     * Le recadrage est « couvrant » (l'image remplit sans se déformer), les
     * filets tricolores et le logo sont incrustés ici pour que tous les plans
     * reçoivent le même habillage.
     */
    private function normaliser(array $segment, string $destination): bool
    {
        $cmd = [$this->ffmpeg, '-y', '-v', 'error'];

        if (isset($segment['video'])) {
            $vitesse = (float) ($segment['vitesse'] ?? 1);
            $cmd[] = '-ss';
            $cmd[] = (string) $segment['depart'];
            // Au ralenti, il faut prélever moins de matière pour remplir la
            // même durée : sans cela le plan se coupe avant la fin.
            $cmd[] = '-t';
            $cmd[] = (string) ($segment['duree'] * $vitesse);
            $cmd[] = '-i';
            $cmd[] = $segment['video'];
        } else {
            $cmd[] = '-loop';
            $cmd[] = '1';
            $cmd[] = '-t';
            $cmd[] = (string) $segment['duree'];
            $cmd[] = '-i';
            $cmd[] = $segment['image'];
        }

        // Les cartons portent déjà le logo en grand : pas de badge en coin.
        $badge = empty($segment['carton']);

        if ($badge) {
            $cmd[] = '-i';
            $cmd[] = $this->badgeLogo();
        }

        // La légende est composée en image : GD gère les accents et la
        // typographie de la charte mieux que le filtre texte de ffmpeg.
        $legende = empty($segment['texte']) ? null : $this->legende($segment['texte']);

        if ($legende) {
            $cmd[] = '-i';
            $cmd[] = $legende;
        }

        // « flags=lanczos » : la plupart des rushes sont en 360 x 640 et
        // doivent tripler de taille. Le rééchantillonnage par défaut les rend
        // pâteux ; lanczos garde les contours nets.
        $habillage = '';

        if (($segment['vitesse'] ?? 1) != 1) {
            // setpts étire le temps ; l'interpolation d'images comble les
            // trous, sinon un ralenti à 60 % devient saccadé.
            $habillage .= 'setpts=' . round(1 / (float) $segment['vitesse'], 4) . '*PTS,'
                . 'minterpolate=fps=' . self::IPS . ":mi_mode=blend,";
        }

        $habillage .= 'scale=' . self::L . ':' . self::H . ':force_original_aspect_ratio=increase:flags=lanczos'
            . ',crop=' . self::L . ':' . self::H
            . ',fps=' . self::IPS . ',setsar=1';

        if (! empty($segment['poussee'])) {
            // Recadrage progressif : la fenêtre se resserre image après image,
            // ce qui donne une poussée régulière sans à-coup.
            $images = max(1, (int) round($segment['duree'] * self::IPS));
            $habillage .= ",crop=w='iw/(1+0.07*(n/{$images}))':h='ih/(1+0.07*(n/{$images}))':x='(iw-ow)/2':y='(ih-oh)/2'"
                . ',scale=' . self::L . ':' . self::H . ':flags=lanczos';
        }

        $habillage .= ',format=yuv420p';

        // Un plan fixe respire mieux avec un léger zoom.
        if (! empty($segment['zoom'])) {
            $images = (int) round($segment['duree'] * self::IPS);
            $habillage = 'scale=' . (self::L * 2) . ':' . (self::H * 2)
                . ':force_original_aspect_ratio=increase:flags=lanczos,crop=' . (self::L * 2) . ':' . (self::H * 2)
                . ',zoompan=z=\'min(zoom+0.0009,1.12)\':d=' . $images
                . ':x=\'iw/2-(iw/zoom/2)\':y=\'ih/2-(ih/zoom/2)\':s=' . self::L . 'x' . self::H
                . ',fps=' . self::IPS . ',setsar=1,format=yuv420p';
        }

        $tiers = (int) (self::L / 3);
        $barres = '';
        foreach ([[0, '0x3DAEF5'], [$tiers, '0xF5A623'], [2 * $tiers, '0xD0021B']] as [$x, $couleur]) {
            $largeur = $x === 2 * $tiers ? self::L - 2 * $tiers : $tiers;
            $barres .= ",drawbox=x={$x}:y=0:w={$largeur}:h=10:color={$couleur}@1:t=fill";
            $barres .= ',drawbox=x=' . $x . ':y=' . (self::H - 10) . ":w={$largeur}:h=10:color={$couleur}@1:t=fill";
        }

        $etape = 0;
        $filtre = '[0:v]' . $habillage . $barres . '[v' . $etape . '];';
        $entree = 1;

        if ($badge) {
            $filtre .= '[v' . $etape . '][' . $entree . ':v]overlay=x=' . (self::L - 150) . ':y=44'
                . '[v' . (++$etape) . '];';
            $entree++;
        }

        if ($legende) {
            // Ancrée en bas, au-dessus du filet : c'est là que l'œil revient
            // entre deux plans, et la zone reste libre sur les réseaux.
            $filtre .= '[v' . $etape . '][' . $entree . ':v]overlay=x=0:y=H-h-140'
                . '[v' . (++$etape) . '];';
        }

        $filtre = rtrim($filtre, ';');
        $filtre = preg_replace('/\[v' . $etape . '\]$/', '[out]', $filtre);

        $cmd = array_merge($cmd, [
            '-filter_complex', $filtre,
            '-map', '[out]',
            '-an',
            '-c:v', 'libx264', '-preset', 'veryfast', '-crf', '14',
            '-pix_fmt', 'yuv420p',
            '-t', (string) $segment['duree'],
            $destination,
        ]);

        return $this->lancer($cmd);
    }

    /** Enchaînement par coupes franches, via le démultiplexeur concat. */
    private function assemblerCoupe(array $segments, string $destination): bool
    {
        $liste = $this->travail . '/liste-' . basename($destination, '.mp4') . '.txt';
        $lignes = array_map(
            fn ($s) => "file '" . str_replace('\\', '/', $s['fichier']) . "'",
            $segments
        );
        file_put_contents($liste, implode("\n", $lignes) . "\n");

        // Les intermédiaires sont encodés très finement : les recopier tels
        // quels conserve toute leur qualité, sans seconde compression.
        return $this->lancer([
            $this->ffmpeg, '-y', '-v', 'error',
            '-f', 'concat', '-safe', '0', '-i', $liste,
            '-c', 'copy', '-movflags', '+faststart', $destination,
        ]);
    }

    /**
     * Enchaînement par fondus (xfade), en une seule passe.
     *
     * Chaque fondu empiète sur le plan précédent : l'instant de bascule se
     * calcule donc sur les durées déjà cumulées, moins les fondus consommés.
     */
    private function assemblerFondu(array $segments, string $destination, string $type = 'fondu'): bool
    {
        if (count($segments) < 2) {
            return false;
        }

        // Noms parlants côté fiche, noms de ffmpeg côté rendu.
        $effets = [
            'fondu'      => 'fade',
            'fondublanc' => 'fadewhite',
            'glisse'     => 'slideleft',
            'balayage'   => 'wiperight',
            'cercle'     => 'circleopen',
            'volet'      => 'smoothup',
        ];
        $effet = $effets[$type] ?? 'fade';

        $cmd = [$this->ffmpeg, '-y', '-v', 'error'];

        foreach ($segments as $s) {
            $cmd[] = '-i';
            $cmd[] = $s['fichier'];
        }

        $filtre = '';
        $courant = '[0:v]';
        $ecoule = $segments[0]['duree'];

        for ($i = 1; $i < count($segments); $i++) {
            $bascule = round($ecoule - self::FONDU, 3);
            $sortie = $i === count($segments) - 1 ? '[out]' : "[x{$i}]";
            $filtre .= $courant . "[{$i}:v]xfade=transition={$effet}:duration=" . self::FONDU
                . ":offset={$bascule}{$sortie};";
            $courant = $sortie;
            $ecoule += $segments[$i]['duree'] - self::FONDU;
        }

        $cmd = array_merge($cmd, [
            '-filter_complex', rtrim($filtre, ';'),
            '-map', '[out]',
            '-an',
            '-c:v', 'libx264', '-preset', 'slow', '-crf', '18',
            '-profile:v', 'high', '-level', '4.2',
            '-pix_fmt', 'yuv420p', '-movflags', '+faststart',
            $destination,
        ]);

        return $this->lancer($cmd);
    }

    /**
     * Pose une musique sous le montage, avec fondus d'entrée et de sortie.
     *
     * À réserver aux vidéos qui ne passeront pas par Instagram ou TikTok :
     * ces plateformes fournissent leur propre bibliothèque, sous licence, et
     * une piste importée peut faire retirer la publication.
     */
    private function poserMusique(string $video): bool
    {
        $piste = public_path('videos/musiques/' . $this->option('musique'));

        if (! is_file($piste)) {
            $this->warn('    musique introuvable : ' . $piste);

            return false;
        }

        $duree = (float) str_replace(',', '.', $this->duree($video));
        $sonorise = preg_replace('/\.mp4$/', '-son.mp4', $video);

        // Le filtre doit désigner explicitement la piste d'entrée et sa
        // sortie : sans étiquettes, ffmpeg ne sait pas à quoi l'appliquer.
        $filtre = '[1:a]afade=t=in:st=0:d=0.8'
            . ',afade=t=out:st=' . round(max(0, $duree - self::FONDU_SON), 2) . ':d=' . self::FONDU_SON
            . ',volume=0.8[son]';

        // Le point de départ se pose avant l'entrée : ffmpeg décode alors
        // directement à partir de là, sans lire tout ce qui précède.
        $depart = max(0, (float) $this->option('musique-depart'));

        $ok = $this->lancer([
            $this->ffmpeg, '-y', '-v', 'error',
            '-i', $video,
            '-ss', (string) $depart,
            '-i', $piste,
            '-filter_complex', $filtre,
            '-map', '0:v', '-map', '[son]',
            '-c:v', 'copy', '-c:a', 'aac', '-b:a', '160k',
            '-shortest', '-movflags', '+faststart',
            $sonorise,
        ]);

        if ($ok) {
            @unlink($video);
            rename($sonorise, $video);
        } else {
            @unlink($sonorise);
        }

        return $ok;
    }

    /** Carton d'ouverture ou de fermeture, composé au pixel avec GD. */
    private function carton(string $nom, string $titre, string $sous, string $genre): ?string
    {
        if (! extension_loaded('gd')) {
            return null;
        }

        $police = $this->police();
        $toile = imagecreatetruecolor(self::L, self::H);

        // Dégradé vertical navy → bleu, dans l'esprit de la charte.
        for ($y = 0; $y < self::H; $y++) {
            $t = $y / self::H;
            $couleur = imagecolorallocate($toile,
                (int) (20 + 30 * $t), (int) (48 + 40 * $t), (int) (110 + 70 * $t));
            imagefilledrectangle($toile, 0, $y, self::L, $y, $couleur);
        }

        $blanc = imagecolorallocate($toile, 255, 255, 255);
        $jaune = imagecolorallocate($toile, 245, 166, 35);
        $bleuClair = imagecolorallocate($toile, 189, 212, 245);

        // Filets tricolores.
        $tiers = (int) (self::L / 3);
        foreach ([[0, [61, 174, 245]], [$tiers, [245, 166, 35]], [2 * $tiers, [208, 2, 27]]] as [$x, $rvb]) {
            $c = imagecolorallocate($toile, ...$rvb);
            $fin = $x === 2 * $tiers ? self::L : $x + $tiers;
            imagefilledrectangle($toile, $x, 0, $fin, 10, $c);
            imagefilledrectangle($toile, $x, self::H - 10, $fin, self::H, $c);
        }

        // Logo, au centre haut.
        $logo = @imagecreatefromjpeg(public_path('images/logo.jpg'));
        if ($logo) {
            $cote = 280;
            $x = (int) ((self::L - $cote) / 2);
            $this->pastille($toile, $x, 520, $cote, $cote, 46, $blanc);
            imagecopyresampled($toile, $logo, $x + 22, 542, 0, 0, $cote - 44, $cote - 44,
                imagesx($logo), imagesy($logo));
            imagedestroy($logo);
        }

        if ($police) {
            $this->centre($toile, $titre, 900, 96, $blanc, $police['gras']);
            imagefilledrectangle($toile, (int) (self::L / 2) - 70, 960, (int) (self::L / 2) + 70, 968, $jaune);
            $this->centre($toile, $sous, 1050, 44, $bleuClair, $police['normale']);

            if ($genre === 'outro') {
                $this->centre($toile, 'mja-martinique.com', 1320, 46, $blanc, $police['gras']);
                $this->centre($toile, '@madin_jeunes_ambition', 1400, 40, $bleuClair, $police['normale']);
            } else {
                $this->centre($toile, "MADIN' JEUNES AMBITION", 1320, 44, $blanc, $police['gras']);
                $this->centre($toile, 'Relève tous les défis !', 1390, 38, $bleuClair, $police['italique']);
            }
        }

        $chemin = $this->travail . '/' . $nom . '.png';
        imagepng($toile, $chemin);
        imagedestroy($toile);

        return $chemin;
    }

    /**
     * Compose une légende en image transparente.
     *
     * Un voile sombre derrière le texte : sans lui, une phrase blanche posée
     * sur un ciel ou un tee-shirt clair devient illisible, et c'est
     * précisément là que se trouvent la plupart des plans.
     */
    private function legende(string $texte): ?string
    {
        $police = $this->police();

        if (! extension_loaded('gd') || ! $police) {
            return null;
        }

        $marge = 90;
        $largeurTexte = self::L - 2 * $marge;
        $taille = 54;
        $lignes = $this->decouperTexte($texte, $taille, $largeurTexte, $police['gras']);

        while (count($lignes) > 3 && $taille > 34) {
            $taille -= 3;
            $lignes = $this->decouperTexte($texte, $taille, $largeurTexte, $police['gras']);
        }

        $interligne = (int) round($taille * 1.32);
        $hauteur = count($lignes) * $interligne + 96;

        $toile = imagecreatetruecolor(self::L, $hauteur);
        imagesavealpha($toile, true);
        imagealphablending($toile, true);
        imagefill($toile, 0, 0, imagecolorallocatealpha($toile, 0, 0, 0, 127));

        // Voile en dégradé, opaque au centre du texte, effacé sur les bords.
        for ($y = 0; $y < $hauteur; $y++) {
            $part = min($y, $hauteur - $y) / ($hauteur / 2);
            $alpha = (int) round(127 - 85 * min(1, $part));
            imagefilledrectangle($toile, 0, $y, self::L, $y,
                imagecolorallocatealpha($toile, 11, 30, 69, $alpha));
        }

        $blanc = imagecolorallocate($toile, 255, 255, 255);
        $ombre = imagecolorallocatealpha($toile, 0, 0, 0, 40);
        $jaune = imagecolorallocate($toile, 245, 166, 35);

        $y = 48 + $taille;

        foreach ($lignes as $ligne) {
            $boite = imagettfbbox($taille, 0, $police['gras'], $ligne);
            $x = (int) ((self::L - ($boite[2] - $boite[0])) / 2);
            imagettftext($toile, $taille, 0, $x + 3, $y + 3, $ombre, $police['gras'], $ligne);
            imagettftext($toile, $taille, 0, $x, $y, $blanc, $police['gras'], $ligne);
            $y += $interligne;
        }

        imagefilledrectangle($toile, (int) (self::L / 2) - 46, $hauteur - 34,
                             (int) (self::L / 2) + 46, $hauteur - 28, $jaune);

        $chemin = $this->travail . '/legende-' . substr(md5($texte), 0, 10) . '.png';
        imagepng($toile, $chemin);
        imagedestroy($toile);

        return $chemin;
    }

    /** @return string[] */
    private function decouperTexte(string $texte, int $taille, int $largeurMax, string $police): array
    {
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

    /**
     * Carton de texte plein écran, intercalé entre deux plans.
     *
     * Les fonds alternent d'un carton à l'autre : deux aplats identiques qui
     * se suivent donnent l'impression d'une vidéo figée, l'alternance marque
     * au contraire chaque respiration.
     */
    private function carteTexte(string $phrase, int $rang, ?string $emoji = null,
                                ?int $motsVisibles = null, ?string $couleur = null): ?string
    {
        $police = $this->police();

        if (! extension_loaded('gd') || ! $police) {
            return null;
        }

        // Nuances nommées : la fiche d'une ébauche désigne une couleur, le
        // monteur propose la même liste, le rendu reste identique aux deux.
        $nuances = self::NUANCES;
        $ordre = ['navy', 'bleu', 'encre'];
        $choix = $nuances[$couleur] ?? $nuances[$ordre[$rang % count($ordre)]];
        [$haut, $bas, $encreTexte] = $choix;

        $toile = imagecreatetruecolor(self::L, self::H);

        for ($y = 0; $y < self::H; $y++) {
            $t = $y / self::H;
            imagefilledrectangle($toile, 0, $y, self::L, $y, imagecolorallocate($toile,
                (int) ($haut[0] + ($bas[0] - $haut[0]) * $t),
                (int) ($haut[1] + ($bas[1] - $haut[1]) * $t),
                (int) ($haut[2] + ($bas[2] - $haut[2]) * $t),
            ));
        }

        // Filets de la charte, comme sur les plans.
        $tiers = (int) (self::L / 3);
        foreach ([[0, [61, 174, 245]], [$tiers, [245, 166, 35]], [2 * $tiers, [208, 2, 27]]] as [$x, $rvb]) {
            $c = imagecolorallocate($toile, ...$rvb);
            $fin = $x === 2 * $tiers ? self::L : $x + $tiers;
            imagefilledrectangle($toile, $x, 0, $fin, 10, $c);
            imagefilledrectangle($toile, $x, self::H - 10, $fin, self::H, $c);
        }

        // Sur un fond clair, le texte blanc disparaît : chaque nuance porte
        // sa propre couleur d'encre.
        $blanc = imagecolorallocate($toile, ...$encreTexte);
        $jaune = $encreTexte === [255, 255, 255]
            ? imagecolorallocate($toile, 245, 166, 35)
            : imagecolorallocate($toile, 255, 255, 255);

        $largeurTexte = self::L - 200;
        $taille = 86;
        $lignes = $this->decouperTexte($phrase, $taille, $largeurTexte, $police['gras']);

        while (count($lignes) > 4 && $taille > 46) {
            $taille -= 4;
            $lignes = $this->decouperTexte($phrase, $taille, $largeurTexte, $police['gras']);
        }

        $interligne = (int) round($taille * 1.28);
        $y = (int) ((self::H - count($lignes) * $interligne) / 2) + $taille;

        // Trait jaune au-dessus du bloc de texte.
        imagefilledrectangle($toile, (int) (self::L / 2) - 60, $y - $taille - 70,
                             (int) (self::L / 2) + 60, $y - $taille - 60, $jaune);

        // Les mots pas encore dits sont tracés en transparence : la phrase
        // garde sa place, seule sa lisibilité progresse.
        $pale = imagecolorallocatealpha($toile, 255, 255, 255, 108);
        $rendu = 0;

        foreach ($lignes as $ligne) {
            $boite = imagettfbbox($taille, 0, $police['gras'], $ligne);
            $x = (int) ((self::L - ($boite[2] - $boite[0])) / 2);

            if ($motsVisibles === null) {
                imagettftext($toile, $taille, 0, $x, $y, $blanc, $police['gras'], $ligne);
            } else {
                foreach (preg_split('/(\s+)/u', $ligne, -1, PREG_SPLIT_DELIM_CAPTURE) as $bout) {
                    if (trim($bout) === '') {
                        $x += imagettfbbox($taille, 0, $police['gras'], 'M')[2] * 0.4;
                        continue;
                    }

                    $couleur = $rendu < $motsVisibles ? $blanc : $pale;
                    imagettftext($toile, $taille, 0, (int) $x, $y, $couleur, $police['gras'], $bout);
                    $cadre = imagettfbbox($taille, 0, $police['gras'], $bout);
                    $x += $cadre[2] - $cadre[0];
                    $rendu++;
                }
            }

            $y += $interligne;
        }

        if ($emoji === 'cool') {
            $this->smiley($toile, (int) (self::L / 2), $y + 40, 78);
        }

        $chemin = $this->travail . '/carton-' . substr(md5($phrase . $emoji . $couleur), 0, 10)
            . ($motsVisibles === null ? '' : '-' . $motsVisibles) . '.png';
        imagepng($toile, $chemin);
        imagedestroy($toile);

        return $chemin;
    }

    /**
     * Carton dont les mots apparaissent l'un après l'autre.
     *
     * Chaque état est une image ; l'ensemble est monté en vidéo courte. Les
     * mots pas encore dits restent tracés en transparence : la phrase ne
     * saute pas d'une ligne à l'autre pendant qu'elle s'écrit.
     */
    private function carteAnimee(string $phrase, int $rang, ?string $emoji, float $duree,
                                 ?string $couleur = null): ?string
    {
        $mots = preg_split('/\s+/u', trim($phrase)) ?: [];

        if (count($mots) < 2) {
            return null;
        }

        $etapes = [];

        for ($i = 1; $i <= count($mots); $i++) {
            $image = $this->carteTexte($phrase, $rang, $i === count($mots) ? $emoji : null, $i, $couleur);

            if (! $image) {
                return null;
            }

            $etapes[] = $image;
        }

        // L'écriture occupe les deux tiers du carton, le dernier état tient
        // le reste : on lit la phrase entière avant de passer à l'image.
        $parMot = ($duree * 0.62) / count($mots);
        $liste = $this->travail . '/mots-' . substr(md5($phrase), 0, 10) . '.txt';
        $lignes = [];

        foreach ($etapes as $n => $image) {
            $lignes[] = "file '" . str_replace('\\', '/', $image) . "'";
            $lignes[] = 'duration ' . round($n === count($etapes) - 1 ? $duree - $parMot * $n : $parMot, 3);
        }

        // Le démultiplexeur concat ignore la durée du dernier fichier : on le
        // répète pour que la tenue finale soit respectée.
        $lignes[] = "file '" . str_replace('\\', '/', end($etapes)) . "'";
        file_put_contents($liste, implode("\n", $lignes) . "\n");

        $sortie = $this->travail . '/carton-anime-' . substr(md5($phrase), 0, 10) . '.mp4';

        $ok = $this->lancer([
            $this->ffmpeg, '-y', '-v', 'error',
            '-f', 'concat', '-safe', '0', '-i', $liste,
            '-vf', 'fps=' . self::IPS . ',format=yuv420p',
            '-c:v', 'libx264', '-preset', 'veryfast', '-crf', '14',
            '-t', (string) $duree,
            $sortie,
        ]);

        return $ok ? $sortie : null;
    }

    /**
     * Smiley à lunettes noires, dessiné au trait.
     *
     * Les polices installées sur un serveur ne rendent pas les émojis en
     * couleur : le caractère sortirait en carré vide. On le dessine donc.
     */
    private function smiley($toile, int $cx, int $cy, int $rayon): void
    {
        $jaune = imagecolorallocate($toile, 255, 204, 77);
        $noir = imagecolorallocate($toile, 20, 22, 28);

        imagefilledellipse($toile, $cx, $cy, $rayon * 2, $rayon * 2, $jaune);

        // Lunettes : deux verres arrondis reliés par un pont.
        $l = (int) ($rayon * 0.62);
        $h = (int) ($rayon * 0.40);
        $y = $cy - (int) ($rayon * 0.24);

        foreach ([-1, 1] as $cote) {
            $x = $cx + $cote * (int) ($rayon * 0.34) - (int) ($l / 2);
            $this->pastille($toile, $x, $y - (int) ($h / 2), $l, $h, (int) ($h * 0.42), $noir);
        }

        imagefilledrectangle($toile, $cx - (int) ($rayon * 0.12), $y - (int) ($h * 0.16),
                             $cx + (int) ($rayon * 0.12), $y + (int) ($h * 0.10), $noir);

        // Sourire : un arc épais, obtenu par deux arcs superposés.
        imagesetthickness($toile, max(3, (int) ($rayon * 0.10)));
        imagearc($toile, $cx, $cy + (int) ($rayon * 0.10), (int) ($rayon * 1.05),
                 (int) ($rayon * 0.95), 25, 155, $noir);
        imagesetthickness($toile, 1);
    }

    /** Badge blanc arrondi portant le logo, incrusté sur chaque plan. */
    private function badgeLogo(): string
    {
        $chemin = $this->travail . '/badge.png';

        if (is_file($chemin)) {
            return $chemin;
        }

        $cote = 106;
        $toile = imagecreatetruecolor($cote, $cote);
        imagesavealpha($toile, true);
        imagefill($toile, 0, 0, imagecolorallocatealpha($toile, 0, 0, 0, 127));

        $this->pastille($toile, 0, 0, $cote - 1, $cote - 1, 22, imagecolorallocate($toile, 255, 255, 255));

        $logo = @imagecreatefromjpeg(public_path('images/logo.jpg'));
        if ($logo) {
            imagecopyresampled($toile, $logo, 9, 9, 0, 0, $cote - 18, $cote - 18,
                imagesx($logo), imagesy($logo));
            imagedestroy($logo);
        }

        imagepng($toile, $chemin);
        imagedestroy($toile);

        return $chemin;
    }

    /** Vignette d'aperçu, prise au premier plan de contenu. */
    private function affiche(string $video): void
    {
        $this->lancer([
            $this->ffmpeg, '-y', '-v', 'error',
            '-ss', '3', '-i', $video, '-frames:v', '1',
            '-vf', 'scale=540:-1',
            preg_replace('/\.mp4$/', '.jpg', $video),
        ]);
    }

    private function duree(string $fichier): string
    {
        $p = new Process([$this->ffprobe, '-v', 'error', '-show_entries', 'format=duration',
                          '-of', 'csv=p=0', $fichier]);
        $p->run();

        return number_format((float) trim($p->getOutput()), 1, ',', '');
    }

    private function lancer(array $cmd): bool
    {
        $p = new Process($cmd, null, null, null, 600);
        $p->run();

        if (! $p->isSuccessful()) {
            $erreur = trim($p->getErrorOutput() ?: $p->getOutput());
            if ($erreur !== '') {
                $this->newLine();
                $this->line(substr($erreur, 0, 900));
            }
        }

        return $p->isSuccessful();
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

    /** Écrit un texte centré, en réduisant la taille jusqu'à ce qu'il tienne. */
    private function centre($toile, string $texte, int $y, int $taille, int $couleur, string $police): void
    {
        if ($texte === '') {
            return;
        }

        $marge = 120;

        do {
            $boite = imagettfbbox($taille, 0, $police, $texte);
            $largeur = $boite[2] - $boite[0];
            if ($largeur <= self::L - $marge || $taille <= 22) {
                break;
            }
            $taille -= 3;
        } while (true);

        imagettftext($toile, $taille, 0, (int) ((self::L - $largeur) / 2), $y, $couleur, $police, $texte);
    }

    /** @return array{gras: string, normale: string, italique: string}|null */
    private function police(): ?array
    {
        $jeux = [
            'gras' => ['C:/Windows/Fonts/arialbd.ttf', '/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf',
                       '/usr/share/fonts/truetype/liberation/LiberationSans-Bold.ttf'],
            'normale' => ['C:/Windows/Fonts/arial.ttf', '/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf',
                          '/usr/share/fonts/truetype/liberation/LiberationSans-Regular.ttf'],
            'italique' => ['C:/Windows/Fonts/ariali.ttf', '/usr/share/fonts/truetype/dejavu/DejaVuSans-Oblique.ttf',
                           '/usr/share/fonts/truetype/liberation/LiberationSans-Italic.ttf'],
        ];

        $trouve = [];

        foreach ($jeux as $style => $chemins) {
            foreach ($chemins as $chemin) {
                if (is_file($chemin)) {
                    $trouve[$style] = $chemin;
                    break;
                }
            }
        }

        return count($trouve) === 3 ? $trouve : null;
    }

    /** Les intermédiaires ne servent qu'au temps du rendu. */
    private function nettoyer(): void
    {
        foreach (glob($this->travail . '/*') ?: [] as $fichier) {
            @unlink($fichier);
        }
    }
}
