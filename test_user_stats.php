<?php

require_once 'vendor/autoload.php';

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

// Simuler l'environnement Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Test des statistiques utilisateurs ===\n\n";

try {
    // Test 1: Compter tous les utilisateurs
    $totalUsers = User::count();
    echo "✅ Total utilisateurs: $totalUsers\n";

    // Test 2: Compter les admins
    $totalAdmins = User::where('is_admin', true)->count();
    echo "✅ Total admins: $totalAdmins\n";

    // Test 3: Compter les utilisateurs normaux
    $totalRegularUsers = User::where('is_admin', false)->count();
    echo "✅ Total utilisateurs normaux: $totalRegularUsers\n";

    // Test 4: Utilisateurs créés aujourd'hui
    $todayUsers = User::whereDate('created_at', Carbon::today())->count();
    echo "✅ Utilisateurs créés aujourd'hui: $todayUsers\n";

    // Test 5: Utilisateurs créés cette semaine
    $weekUsers = User::where('created_at', '>=', Carbon::now()->startOfWeek())->count();
    echo "✅ Utilisateurs créés cette semaine: $weekUsers\n";

    // Test 6: Utilisateurs créés ce mois
    $monthUsers = User::where('created_at', '>=', Carbon::now()->startOfMonth())->count();
    echo "✅ Utilisateurs créés ce mois: $monthUsers\n";

    // Test 7: Utilisateurs actifs (connectés dans les 30 derniers jours)
    $activeUsers = User::where('last_login_at', '>=', Carbon::now()->subDays(30))->count();
    echo "✅ Utilisateurs actifs (30 jours): $activeUsers\n";

    // Test 8: Évolution des inscriptions (7 derniers jours)
    $registrationTrend = User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
        ->where('created_at', '>=', Carbon::now()->subDays(7))
        ->groupBy('date')
        ->orderBy('date')
        ->get();

    echo "✅ Évolution des inscriptions (7 jours):\n";
    foreach ($registrationTrend as $trend) {
        echo "   - {$trend->date}: {$trend->count} utilisateurs\n";
    }

    // Test 9: Top 5 des utilisateurs les plus actifs
    $mostActiveUsers = User::select('users.id', 'users.name', 'users.email', DB::raw('COUNT(search_history.id) as search_count'))
        ->leftJoin('search_history', 'users.id', '=', 'search_history.user_id')
        ->groupBy('users.id', 'users.name', 'users.email')
        ->orderByDesc('search_count')
        ->limit(5)
        ->get();

    echo "✅ Top 5 utilisateurs les plus actifs:\n";
    foreach ($mostActiveUsers as $user) {
        echo "   - {$user->name} ({$user->email}): {$user->search_count} recherches\n";
    }

    // Test 10: Derniers utilisateurs créés
    $recentUsers = User::select('name', 'email', 'created_at', 'is_admin')
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();

    echo "✅ 5 derniers utilisateurs créés:\n";
    foreach ($recentUsers as $user) {
        $role = $user->is_admin ? 'Admin' : 'Utilisateur';
        echo "   - {$user->name} ({$user->email}) - {$role} - {$user->created_at}\n";
    }

    echo "\n=== Test réussi ! ===\n";

} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "Fichier: " . $e->getFile() . "\n";
    echo "Ligne: " . $e->getLine() . "\n";
}
