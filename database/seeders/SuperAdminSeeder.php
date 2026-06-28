<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'contact@njiezm.fr'],
            [
                'name'     => 'Super Admin MJA',
                'password' => Hash::make('njiezm190964-'),
                'role'     => 'super_admin',
            ]
        );

        $this->command->info('Super admin OK : contact@njiezm.fr');
    }
}
