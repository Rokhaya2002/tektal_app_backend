<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Line;
use App\Models\Stop;

class LinesAndStopsSeeder extends Seeder
{
    public function run()
    {
        DB::table('line_stop')->truncate();
        DB::table('stops')->truncate();
        DB::table('lines')->truncate();

        $linesData = [
            [
        'name' => 'DDD 1',
        'departure' => 'Parcelles Assainies',
                'destination' => 'Place LECLERC',
                'stops' => [
                    'Sapeur Pompiers',
                    'Unités 09 10 15',
                    'Ecole Dior',
                    'Terrain Acapes',
                    'Unités 22 24',
                    'Marché Grand Médine',
                    'Rondpoint 26',
                    'VDN-Foire',
                    'Cité Keur Gorgui',
                    'Ecole Normale',
                    'UCAD',
                    'Rond-point SHAM',
                    'Marché Tilène',
                    'Poste Médina',
                    'Difoncé',
                    'Sandaga',
                    'Avenue George Pompidou',
                    'Place de l\'Indépendance',
                    'Gare TER',
                    'Embarcadère',
                    'Terminus Leclerc',
                ],
            ],
            [
        'name' => 'DDD 4',
                'departure' => 'Liberté 5',
                'destination' => 'Place LECLERC',
                'stops' => [
                    'Cité Derklé',
                    'Rond-point Liberté 6',
                    'Khar Yalla',
                    'Cité Marine',
                    'Cem Ousmane Socé Diop Dieuppeul 3',
                    'Eglise Martyrs de l\'Ouganda',
                    'SDE',
                    'Collège Sacré Cœur',
                    'Sicap Karack',
                    'Point E',
                    'Université Hampate Ba',
                    'Canal 4',
                    'Marché Fass',
                    'Gueule Tapée',
                    'Rond-Point SHAM',
                    'Marché Tilène',
                    'Poste Médina',
                    'Avenue Blaise Diagne',
                    'Difoncé',
                    'Sandaga',
                    'ASECNA',
                    'Boulevard de la République',
                    'Avenue Léopold Sédar Senghor',
                    'Place de l\'Indépendance',
                    'Gare TER',
                    'Embarcadère',
                    'Terminus Leclerc',
                ],
            ],
            [
        'name' => 'TATA 1',
                'departure' => 'HLM Grand Yoff',
                'destination' => 'Lat Dior',
                'stops' => [
                    'Terminus HLM Grand Yoff',
                    'Scat Urban',
                    'Liberté 6',
                    'Derkle',
                    'Liberté 4',
                    'Rond-point Jet d\'Eau',
                    'Bourguiba',
                    'Grand Dakar',
                    'Fass Médina',
                    'Sham',
                    'Avenue Blaise Diagne',
                    'Terminus Lat Dior',
                ],
            ],
            [
        'name' => 'TATA 2',
                'departure' => 'Parcelles Assainies',
                'destination' => 'Petersen',
                'stops' => [
                    'Terminus des Parcelles Assainies',
                    'Croisement 22',
                    'Pont Aliou Sow',
                    'Rond-Point Liberté 6',
                    'Castor',
                    'HLM',
                    'Colobane',
                    'Tiilène',
                    'Avenue Blaise Diagne',
                    'Terminus Petersen',
                ],
            ],
        ];

        // Création des arrêts uniques (partagés entre lignes si besoin)
        $allStops = [];
        foreach ($linesData as $lineData) {
            foreach ($lineData['stops'] as $stopName) {
                if (!isset($allStops[$stopName])) {
                    $allStops[$stopName] = Stop::create([
                        'name' => $stopName,
                    ]);
                }
            }
    }

        // Création des lignes et associations arrêts/ligne avec ordre
        foreach ($linesData as $lineData) {
            $line = Line::create([
                'name' => $lineData['name'],
                'departure' => $lineData['departure'],
                'destination' => $lineData['destination'],
            ]);
            foreach ($lineData['stops'] as $order => $stopName) {
                $line->stops()->attach($allStops[$stopName]->id, ['order' => $order + 1]);
    }
}
    }
}
