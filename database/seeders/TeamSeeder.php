<?php

namespace Database\Seeders;

use App\Models\TeamMember;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    public function run(): void
    {
        TeamMember::truncate();

        $members = [
            // ── Bureau réel ─────────────────────────────────────────────────────
            [
                'prenom' => 'Kévin',
                'nom'    => 'FLORIAN',
                'role'   => 'Président',
                'bio'    => 'Fondateur et président de Madin\'Jeunes Ambition depuis 2011, Kévin porte la vision de l\'association avec passion. Sous sa direction, MJA est devenue une référence pour les jeunes de Martinique et au-delà.',
                'actif'  => true,
                'ordre'  => 1,
            ],
            [
                'prenom' => 'Chloé',
                'nom'    => 'FLORIAN',
                'role'   => 'Secrétaire',
                'bio'    => 'Secrétaire générale de l\'association, Chloé assure la coordination administrative et la communication interne. Elle joue un rôle clé dans l\'organisation des événements MJA.',
                'actif'  => true,
                'ordre'  => 2,
            ],
            [
                'prenom' => 'Sarah',
                'nom'    => '',
                'role'   => 'Secrétaire adjointe',
                'bio'    => 'Secrétaire adjointe de MJA, Sarah participe activement à l\'organisation des événements et au suivi des adhérents.',
                'actif'  => true,
                'ordre'  => 3,
            ],

            // ── Membres du bureau (postes à confirmer) ──────────────────────────
            [
                'prenom' => 'Marie-Line',
                'nom'    => 'ROSETTE',
                'role'   => 'Trésorière',
                'bio'    => 'Marie-Line gère les finances de l\'association et assure la bonne gestion des ressources et des partenariats financiers.',
                'actif'  => true,
                'ordre'  => 4,
            ],
            [
                'prenom' => 'Fabrice',
                'nom'    => 'MONTOUT',
                'role'   => 'Chargé des événements',
                'bio'    => 'Fabrice organise et coordonne les événements portés par MJA, du MJA Sport Day aux Trophées Lumina.',
                'actif'  => true,
                'ordre'  => 5,
            ],
            [
                'prenom' => 'Laetitia',
                'nom'    => 'PIERRE-LOUIS',
                'role'   => 'Chargée de communication',
                'bio'    => 'Laetitia pilote la présence de MJA sur les réseaux sociaux et dans les médias. Elle coordonne la communication du réseau Fwi Ti Dèj.',
                'actif'  => true,
                'ordre'  => 6,
            ],
        ];

        foreach ($members as $member) {
            TeamMember::create($member);
        }
    }
}
