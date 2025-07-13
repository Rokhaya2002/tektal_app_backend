<?php

// Script de test pour vérifier les routes admin
echo "=== TEST DES ROUTES ADMIN ===\n\n";

// Configuration
$baseUrl = 'http://localhost:8000/api';
$adminEmail = 'admin@tektall.com';
$adminPassword = 'admin123';

// 1. Test de connexion admin
echo "1. Test de connexion admin...\n";
$loginData = [
    'email' => $adminEmail,
    'password' => $adminPassword
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/admin/login');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($loginData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

$startTime = microtime(true);
$response = curl_exec($ch);
$endTime = microtime(true);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

echo "Temps de réponse: " . round(($endTime - $startTime) * 1000, 2) . "ms\n";
echo "Code HTTP: $httpCode\n";

if ($httpCode === 200) {
    $loginResult = json_decode($response, true);
    if (isset($loginResult['token'])) {
        $token = $loginResult['token'];
        echo "✅ Connexion réussie\n\n";

        // 2. Test des statistiques utilisateurs
        echo "2. Test des statistiques utilisateurs...\n";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $baseUrl . '/admin/users/stats');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json'
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $startTime = microtime(true);
        $response = curl_exec($ch);
        $endTime = microtime(true);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        echo "Temps de réponse: " . round(($endTime - $startTime) * 1000, 2) . "ms\n";
        echo "Code HTTP: $httpCode\n";

        if ($httpCode === 200) {
            $stats = json_decode($response, true);
            echo "✅ Statistiques récupérées:\n";
            echo "   - Total utilisateurs: " . ($stats['total_users'] ?? 'N/A') . "\n";
            echo "   - Admins: " . ($stats['total_admins'] ?? 'N/A') . "\n";
            echo "   - Utilisateurs réguliers: " . ($stats['total_regular_users'] ?? 'N/A') . "\n";
            echo "   - Utilisateurs actifs: " . ($stats['active_users'] ?? 'N/A') . "\n";
            echo "   - Aujourd'hui: " . ($stats['today_users'] ?? 'N/A') . "\n";
            echo "   - Cette semaine: " . ($stats['week_users'] ?? 'N/A') . "\n";
            echo "   - Ce mois: " . ($stats['month_users'] ?? 'N/A') . "\n\n";
        } else {
            echo "❌ Erreur lors de la récupération des statistiques\n";
            echo "Réponse: $response\n\n";
        }

        // 3. Test de la liste des utilisateurs
        echo "3. Test de la liste des utilisateurs...\n";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $baseUrl . '/admin/users?page=1&per_page=5');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json'
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $startTime = microtime(true);
        $response = curl_exec($ch);
        $endTime = microtime(true);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        echo "Temps de réponse: " . round(($endTime - $startTime) * 1000, 2) . "ms\n";
        echo "Code HTTP: $httpCode\n";

        if ($httpCode === 200) {
            $users = json_decode($response, true);
            echo "✅ Liste des utilisateurs récupérée:\n";
            echo "   - Nombre d'utilisateurs: " . count($users['data'] ?? []) . "\n";
            echo "   - Total: " . ($users['pagination']['total'] ?? 'N/A') . "\n";
            echo "   - Page actuelle: " . ($users['pagination']['current_page'] ?? 'N/A') . "\n\n";
        } else {
            echo "❌ Erreur lors de la récupération des utilisateurs\n";
            echo "Réponse: $response\n\n";
        }

        // 4. Test de l'activité récente
        echo "4. Test de l'activité récente...\n";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $baseUrl . '/admin/users/activity');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json'
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $startTime = microtime(true);
        $response = curl_exec($ch);
        $endTime = microtime(true);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        echo "Temps de réponse: " . round(($endTime - $startTime) * 1000, 2) . "ms\n";
        echo "Code HTTP: $httpCode\n";

        if ($httpCode === 200) {
            $activity = json_decode($response, true);
            echo "✅ Activité récente récupérée\n\n";
        } else {
            echo "❌ Erreur lors de la récupération de l'activité\n";
            echo "Réponse: $response\n\n";
        }

    } else {
        echo "❌ Token non trouvé dans la réponse\n";
        echo "Réponse: $response\n\n";
    }
} else {
    echo "❌ Échec de la connexion\n";
    echo "Réponse: $response\n\n";
}

curl_close($ch);

echo "=== FIN DES TESTS ===\n";
