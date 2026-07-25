<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeSuperAdmin extends Command
{
    protected $signature = 'mja:super-admin
                            {email : Adresse email du super administrateur}
                            {--name= : Nom affiché}
                            {--password= : Mot de passe (généré si absent pour un nouveau compte)}';

    protected $description = 'Crée ou promeut un compte super administrateur';

    public function handle(): int
    {
        $email = strtolower(trim($this->argument('email')));
        $user  = User::where('email', $email)->first();
        $isNew = ! $user;

        if ($isNew) {
            $user = new User();
            $user->email = $email;
        }

        $user->name = $this->option('name') ?: ($user->name ?: Str::of($email)->before('@')->headline());
        $user->role = User::ROLE_SUPER_ADMIN;
        $user->is_active = true;

        $plain = $this->option('password');
        if ($isNew && ! $plain) {
            $plain = Str::password(14, symbols: false);
        }
        if ($plain) {
            $user->setPasswordAndCopy($plain);
        }

        $user->save();

        $this->info(($isNew ? 'Compte super admin créé' : 'Compte promu super admin') . " : {$user->email}");
        if ($plain) {
            $this->line("Mot de passe : <fg=yellow>{$plain}</>");
        } else {
            $this->line('Mot de passe inchangé.');
        }

        return self::SUCCESS;
    }
}
