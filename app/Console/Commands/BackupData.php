<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;
use ZipArchive;

class BackupData extends Command
{
    protected $signature = 'mja:backup {--keep=7 : Nombre de sauvegardes à conserver}';

    protected $description = 'Sauvegarde la base de données + les fichiers déposés (photos d\'adhésion) dans storage/app/backups';

    public function handle(): int
    {
        $dir = storage_path('app/backups');
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $stamp = now()->format('Ymd-His');
        $zipPath = $dir . DIRECTORY_SEPARATOR . "mja-backup-{$stamp}.zip";

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            $this->error('Impossible de créer l\'archive.');
            return self::FAILURE;
        }

        // 1) Dump base de données (best effort selon le driver)
        $sql = $this->dumpDatabase();
        if ($sql !== null) {
            $zip->addFromString('database.sql', $sql);
            $this->info('Base de données ajoutée (' . strlen($sql) . ' octets).');
        } else {
            $this->warn('Dump base de données ignoré (outil indisponible) — pense au backup DB côté hébergeur.');
        }

        // 2) Fichiers déposés (photos d'adhésion, ressources, etc.)
        $storage = storage_path('app/public');
        $count = 0;
        if (is_dir($storage)) {
            $it = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($storage, \FilesystemIterator::SKIP_DOTS));
            foreach ($it as $file) {
                if ($file->isFile()) {
                    $local = 'files/' . ltrim(str_replace($storage, '', $file->getPathname()), '\\/');
                    $zip->addFile($file->getPathname(), str_replace('\\', '/', $local));
                    $count++;
                }
            }
        }
        $zip->close();
        $this->info("{$count} fichier(s) ajouté(s).");
        $this->info('Sauvegarde : ' . $zipPath);

        $this->rotate($dir, (int) $this->option('keep'));

        return self::SUCCESS;
    }

    /** Renvoie le dump SQL, ou null si l'outil n'est pas disponible. */
    private function dumpDatabase(): ?string
    {
        $conn = config('database.default');
        $c = config("database.connections.$conn");

        try {
            if ($conn === 'mysql' || ($c['driver'] ?? null) === 'mysql') {
                $result = Process::env(['MYSQL_PWD' => (string) ($c['password'] ?? '')])
                    ->timeout(300)
                    ->run(['mysqldump', '--host=' . $c['host'], '--port=' . $c['port'], '--user=' . $c['username'],
                        '--single-transaction', '--no-tablespaces', '--skip-lock-tables', $c['database']]);
                return $result->successful() ? $result->output() : null;
            }

            if (($c['driver'] ?? null) === 'pgsql') {
                $result = Process::env(['PGPASSWORD' => (string) ($c['password'] ?? '')])
                    ->timeout(300)
                    ->run(['pg_dump', '-h', (string) $c['host'], '-p', (string) $c['port'], '-U', (string) $c['username'], $c['database']]);
                return $result->successful() ? $result->output() : null;
            }

            if (($c['driver'] ?? null) === 'sqlite' && ! empty($c['database']) && is_file($c['database'])) {
                return file_get_contents($c['database']) ?: null;
            }
        } catch (\Throwable $e) {
            $this->warn('Dump DB : ' . $e->getMessage());
        }

        return null;
    }

    /** Ne conserve que les $keep archives les plus récentes. */
    private function rotate(string $dir, int $keep): void
    {
        if ($keep < 1) {
            return;
        }
        $files = glob($dir . DIRECTORY_SEPARATOR . 'mja-backup-*.zip') ?: [];
        rsort($files); // plus récent d'abord (nom horodaté)
        foreach (array_slice($files, $keep) as $old) {
            @unlink($old);
        }
    }
}
