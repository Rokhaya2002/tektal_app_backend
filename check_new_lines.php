<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Line;

echo "=== Vérification des nouvelles lignes ===\n\n";

$newLines = Line::whereIn('name', ['DDD 10', 'DDD 20', 'DDD 18', 'TATA 46', 'TATA 30', 'TATA 85', 'TATA 34'])->get();

foreach ($newLines as $line) {
    echo "Ligne: {$line->name}\n";
    echo "Départ: {$line->departure}\n";
    echo "Destination: {$line->destination}\n";

    $stops = $line->stops()->orderBy('line_stop.stop_order')->get();
    echo "Arrêts (" . $stops->count() . "): ";
    foreach ($stops as $stop) {
        echo $stop->name . " (ordre: {$stop->pivot->stop_order}), ";
    }
    echo "\n\n";
}

echo "Total nouvelles lignes: " . $newLines->count() . "\n";
