<?php

/**
 * Version modifiée de public/index.php pour hébergement mutualisé.
 *
 * Structure sur le serveur :
 *   /home/USER/mja/          ← tout le projet Laravel
 *   /home/USER/public_html/  ← web root (ce fichier va ici, renommé index.php)
 *
 * Remplace le contenu de public_html/index.php par ce fichier.
 */

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Chemin vers le dossier racine Laravel (en dehors de public_html)
// Ajuste selon ta structure réelle sur le serveur
$laravelRoot = __DIR__ . '/../mja';

// Si le projet est directement dans public_html/../laravel :
// $laravelRoot = __DIR__ . '/../laravel';

if (file_exists($laravelRoot . '/vendor/autoload.php')) {
    require $laravelRoot . '/vendor/autoload.php';
} else {
    die('Vendor autoload not found. Check $laravelRoot path.');
}

/** @var Application $app */
$app = require_once $laravelRoot . '/bootstrap/app.php';

$app->usePublicPath(__DIR__);

$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
