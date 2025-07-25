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
        // Supprimer les données dans le bon ordre pour éviter les erreurs FK
        DB::table('line_stop')->delete();
        DB::table('stops')->delete();
        DB::table('lines')->delete();

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
                'name' => 'DDD 4B',
                'departure' => 'Guédiawaye',
                'destination' => 'Université UCAD',
                'stops' => [
                    'Guédiawaye',
                    'Patte d’Oie',
                    'Avenue Blaise Diagne',
                    'Université UCAD',
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
            [
                'name' => 'DDD 10',
                'departure' => 'Liberté 6',
                'destination' => 'Sandaga',
                'stops' => [
                    'Liberté 6',
                    'HLM Grand-Yoff',
                    'Colobane',
                    'Sandaga',
                ],
            ],
            [
                'name' => 'DDD 20',
                'departure' => 'Ouakam',
                'destination' => 'Petersen',
                'stops' => [
                    'Ouakam',
                    'Fann Résidence',
                    'Place de l’Indépendance',
                    'Petersen',
                ],
            ],
            [
                'name' => 'DDD 18',
                'departure' => 'Parcelles U22',
                'destination' => 'Marché Kermel',
                'stops' => [
                    'Parcelles U22',
                    'Grand Médine',
                    'Liberté 5',
                    'Marché Kermel',
                ],
            ],
            [
                'name' => 'DDD 4',
                'departure' => 'Guédiawaye',
                'destination' => 'Université UCAD',
                'stops' => [
                    'Guédiawaye',
                    'Patte d’Oie',
                    'Avenue Blaise Diagne',
                    'Université UCAD',
                ],
            ],
            [
                'name' => 'TATA 46',
                'departure' => 'Keur Massar',
                'destination' => 'Yoff Virage',
                'stops' => [
                    'Keur Massar',
                    'Rufisque',
                    'Pikine',
                    'Yoff Virage',
                ],
            ],
            [
                'name' => 'TATA 30',
                'departure' => 'Thiaroye',
                'destination' => 'Médina',
                'stops' => [
                    'Thiaroye',
                    'Dalifort',
                    'Grand Dakar',
                    'Médina',
                ],
            ],
            [
                'name' => 'TATA 85',
                'departure' => 'Malika',
                'destination' => 'Colobane',
                'stops' => [
                    'Malika',
                    'Diamaguène',
                    'Yeumbeul',
                    'Colobane',
                ],
            ],
            [
                'name' => 'TATA 34',
                'departure' => 'Cambérène',
                'destination' => 'Point E',
                'stops' => [
                    'Cambérène',
                    'Niary Tally',
                    'Fass',
                    'Point E',
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
                $line->stops()->attach($allStops[$stopName]->id, ['stop_order' => $order + 1]);
            }
        }
    }
}
