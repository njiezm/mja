<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class PurgeDeletedMembers extends Command
{
    protected $signature = 'mja:purge-members';

    protected $description = 'Supprime définitivement les comptes membres effacés depuis plus de 30 jours';

    public function handle(): int
    {
        // Seuls les comptes adhérents sont concernés : un compte purement
        // administrateur ne se supprime pas depuis l'espace adhérent.
        $members = User::onlyTrashed()
            ->whereNotNull('adhesion_id')
            ->where('deleted_at', '<', now()->subDays(30))
            ->get();

        foreach ($members as $member) {
            $member->forceDelete();
        }

        $this->info($members->count() . ' compte(s) définitivement supprimé(s).');

        return self::SUCCESS;
    }
}
