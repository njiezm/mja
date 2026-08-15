<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Fusionne les comptes « espace adhérent » (table members) et les comptes
 * back-office (table users) en une seule identité : un email, un mot de passe.
 *
 * Un compte peut désormais être adhérent, administrateur, ou les deux. Les
 * administrateurs qui sont aussi adhérents apparaissent au trombinoscope sans
 * avoir à gérer deux jeux d'identifiants.
 *
 * La table `members` n'est pas supprimée : elle reste comme filet de sécurité
 * le temps de valider la bascule en production. Une migration ultérieure
 * pourra la retirer.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Adhésion en cours du titulaire du compte (null = compte purement admin).
            $table->foreignId('adhesion_id')->nullable()->after('email')
                ->constrained('adhesions')->nullOnDelete();
            $table->boolean('show_in_directory')->default(true)->after('is_active');
            $table->string('restore_token', 64)->nullable()->index()->after('remember_token');
            $table->softDeletes();
        });

        Schema::table('adhesions', function (Blueprint $table) {
            // Historique : toutes les adhésions successives d'une même personne.
            $table->foreignId('user_id')->nullable()->after('id')
                ->constrained('users')->nullOnDelete();
        });

        $this->migrerLesComptesAdherents();
        $this->rattacherLesAdhesionsParEmail();
    }

    /**
     * Reprend chaque compte de `members` : soit il rejoint le compte admin qui
     * porte le même email, soit un nouveau compte de rôle « membre » est créé.
     */
    private function migrerLesComptesAdherents(): void
    {
        if (! Schema::hasTable('members')) {
            return;
        }

        foreach (DB::table('members')->orderBy('id')->cursor() as $membre) {
            $email = mb_strtolower(trim($membre->email));

            $user = DB::table('users')->whereRaw('LOWER(email) = ?', [$email])->first();

            if ($user) {
                // Compte admin existant : on lui greffe la partie « adhérent ».
                // Le mot de passe du back-office est conservé — c'est celui que
                // la personne utilise déjà pour la partie la plus sensible.
                DB::table('users')->where('id', $user->id)->update([
                    'adhesion_id'       => $membre->adhesion_id,
                    'show_in_directory' => $membre->show_in_directory,
                    'restore_token'     => $membre->restore_token,
                    'deleted_at'        => $membre->deleted_at,
                ]);

                $userId = $user->id;
            } else {
                $adhesion = DB::table('adhesions')->where('id', $membre->adhesion_id)->first();

                $nom = $adhesion
                    ? trim(($adhesion->prenom ?? '') . ' ' . ($adhesion->nom ?? ''))
                    : $membre->email;

                $userId = DB::table('users')->insertGetId([
                    'name'               => $nom !== '' ? $nom : $membre->email,
                    'email'              => $membre->email,
                    'adhesion_id'        => $membre->adhesion_id,
                    'role'               => 'membre',
                    'is_active'          => true,
                    'show_in_directory'  => $membre->show_in_directory,
                    'password'           => $membre->password,
                    'password_encrypted' => $membre->password_encrypted ?? null,
                    'remember_token'     => $membre->remember_token,
                    'restore_token'      => $membre->restore_token,
                    'deleted_at'         => $membre->deleted_at,
                    'created_at'         => $membre->created_at,
                    'updated_at'         => $membre->updated_at,
                ]);
            }

            DB::table('adhesions')->where('id', $membre->adhesion_id)->update(['user_id' => $userId]);
        }
    }

    /**
     * Rattache les adhésions restantes au compte qui porte le même email.
     * C'est ce qui fait apparaître au trombinoscope les administrateurs déjà
     * adhérents mais qui n'avaient jamais créé de compte « espace membre ».
     */
    private function rattacherLesAdhesionsParEmail(): void
    {
        $adhesions = DB::table('adhesions')->whereNull('user_id')->get(['id', 'email']);

        foreach ($adhesions as $adhesion) {
            $email = mb_strtolower(trim((string) $adhesion->email));

            if ($email === '') {
                continue;
            }

            $user = DB::table('users')->whereRaw('LOWER(email) = ?', [$email])->first();

            if (! $user) {
                continue;
            }

            DB::table('adhesions')->where('id', $adhesion->id)->update(['user_id' => $user->id]);

            // Le compte n'a pas encore d'adhésion courante : on lui rattache celle-ci.
            if ($user->adhesion_id === null) {
                DB::table('users')->where('id', $user->id)->update(['adhesion_id' => $adhesion->id]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('adhesions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });

        Schema::table('users', function (Blueprint $table) {
            // L'index part avant sa colonne : SQLite reconstruit la table à
            // chaque suppression de colonne et refuse un index orphelin.
            $table->dropIndex(['restore_token']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('adhesion_id');
            $table->dropColumn(['show_in_directory', 'restore_token']);
            $table->dropSoftDeletes();
        });
    }
};
