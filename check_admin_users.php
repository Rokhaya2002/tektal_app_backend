<?php

require_once 'vendor/autoload.php';

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

echo "=== VÉRIFICATION DES UTILISATEURS ADMIN ===\n\n";

// Compter les utilisateurs admin
$adminCount = User::where('is_admin', true)->count();
echo "Nombre d'utilisateurs admin: $adminCount\n\n";

if ($adminCount > 0) {
    echo "Liste des utilisateurs admin:\n";
    $admins = User::where('is_admin', true)->get(['id', 'name', 'email', 'created_at']);

    foreach ($admins as $admin) {
        echo "- ID: {$admin->id}, Nom: {$admin->name}, Email: {$admin->email}, Créé: {$admin->created_at}\n";
    }
} else {
    echo "Aucun utilisateur admin trouvé.\n";
    echo "Création d'un utilisateur admin par défaut...\n";

    $admin = User::create([
        'name' => 'Administrateur',
        'email' => 'admin@tektall.com',
        'password' => bcrypt('admin123'),
        'is_admin' => true
    ]);

    echo "✅ Utilisateur admin créé:\n";
    echo "- ID: {$admin->id}, Nom: {$admin->name}, Email: {$admin->email}\n";
    echo "- Mot de passe: admin123\n\n";
}

echo "\n=== VÉRIFICATION COMPLÈTE ===\n";
