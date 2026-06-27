<?php
/**
 * Script de migration à usage UNIQUE via navigateur.
 * ⚠️  SUPPRIMER CE FICHIER IMMÉDIATEMENT APRÈS UTILISATION.
 *
 * Accès : https://votre-domaine.com/deploy-run.php?secret=REMPLACER_ICI
 */

$secret = 'REMPLACER_ICI'; // ← Mets un mot de passe unique ici avant upload

if (!isset($_GET['secret']) || $_GET['secret'] !== $secret) {
    http_response_code(403);
    die('<h1>403 Forbidden</h1>');
}

chdir(__DIR__ . '/..');

require __DIR__ . '/../vendor/autoload.php';

$app    = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

echo '<html><head><meta charset="UTF-8"><style>
    body { font-family: monospace; background: #0f1b33; color: #4ade80; padding: 2rem; }
    .error { color: #f87171; }
    .ok { color: #4ade80; }
    h2 { color: #f5a623; }
    pre { background: #1a2744; padding: 1rem; border-radius: 8px; overflow-x: auto; }
</style></head><body>';

echo '<h2>🚀 MJA — Script de déploiement</h2>';

$commands = [
    ['migrate', '--force' => true],
    ['config:cache'],
    ['route:cache'],
    ['view:cache'],
    ['storage:link'],
];

foreach ($commands as $cmd) {
    $command = array_shift($cmd);
    $args    = $cmd;
    echo "<h3>> php artisan $command</h3><pre>";
    ob_start();
    try {
        $status = $kernel->call($command, $args);
        $output = ob_get_clean();
        echo htmlspecialchars($kernel->output());
        if ($status === 0) {
            echo "\n<span class='ok'>✅ OK</span>";
        } else {
            echo "\n<span class='error'>⚠️ Status: $status</span>";
        }
    } catch (\Exception $e) {
        ob_end_clean();
        echo "<span class='error'>❌ Erreur : " . htmlspecialchars($e->getMessage()) . "</span>";
    }
    echo '</pre>';
    flush();
}

echo '<hr style="border-color:#1a2744;margin:2rem 0">';
echo '<p style="color:#f87171;font-weight:bold;">⚠️ IMPORTANT : Supprime ce fichier maintenant via FTP !</p>';
echo '<p>Chemin : <code>public/deploy-run.php</code></p>';
echo '</body></html>';
