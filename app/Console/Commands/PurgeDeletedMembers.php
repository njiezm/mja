<?php

namespace App\Console\Commands;

use App\Models\Member;
use Illuminate\Console\Command;

class PurgeDeletedMembers extends Command
{
    protected $signature = 'mja:purge-members';

    protected $description = 'Supprime définitivement les comptes membres effacés depuis plus de 30 jours';

    public function handle(): int
    {
        $members = Member::onlyTrashed()
            ->where('deleted_at', '<', now()->subDays(30))
            ->get();

        foreach ($members as $member) {
            $member->forceDelete();
        }

        $this->info($members->count() . ' compte(s) définitivement supprimé(s).');

        return self::SUCCESS;
    }
}
