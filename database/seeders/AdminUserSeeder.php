<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer un admin principal
        User::create([
            'name' => 'Administrateur Principal',
            'email' => 'admin@tektal.com',
            'password' => Hash::make('admin123'),
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        // Créer un admin secondaire
        User::create([
            'name' => 'Admin Support',
            'email' => 'support@tektal.com',
            'password' => Hash::make('support123'),
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        $this->command->info('Utilisateurs admin créés avec succès!');
        $this->command->info('Email: admin@tektal.com, Mot de passe: admin123');
        $this->command->info('Email: support@tektal.com, Mot de passe: support123');
    }
}
