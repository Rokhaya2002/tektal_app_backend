<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LinesAndStopsSeeder extends Seeder
{
    public function run(): void
    {
       
        $this->createLine('DDD 1', 'Parcelles Assainies', 'Place LECLERC', [
            'Sapeur Pompiers', 'Unités 09 10 15', 'Ecole Dior', 'Terrain Acapes', 'Unités 22 24',
            'Marché Grand Médine', 'Rondpoint 26', 'VDN-Foire', 'Cité Keur Gorgui', 'Ecole Normale',
            'UCAD', 'Rond-point SHAM', 'Marché Tilène', 'Poste Médina', 'Difoncé', 'Sandaga',
            'Avenue George Pompidou', 'Place de l\'Indépendance', 'Gare TER', 'Embarcadère', 'Terminus Leclerc'
        ]);

        $this->createLine('DDD 4', 'Liberté 5', 'Place LECLERC', [
            'Cité Derklé', 'Rond-point Liberté 6', 'Khar Yalla', 'Cité Marine', 'Cem Ousmane Socé Diop Dieuppeul 3',
            'Eglise Martyrs de l\'Ouganda', 'SDE', 'Collège Sacré Cœur', 'Sicap Karack', 'Point E',
            'Université Hampate Ba', 'Canal 4', 'Marché Fass', 'Gueule Tapée', 'Rond-Point SHAM', 'Marché Tilène',
            'Poste Médina', 'Avenue Blaise Diagne', 'Difoncé', 'Sandaga', 'ASECNA', 'Boulevard de la République',
            'Avenue Léopold Sédar Senghor', 'Place de l\'Indépendance', 'Gare TER', 'Embarcadère', 'Terminus Leclerc'
        ]);

        $this->createLine('DDD 5', 'Daroukhane', 'Palais 1', [
            'Police Wakhinane', 'Marché Manne Diarra', 'Station Touré', 'Police Guédiawaye', 'Pikine',
            'Rue 10', 'Cité Lobatt Fall', 'Péage', 'Pompier', 'Avenue Lamine Gueye', 'Rond-point Sandaga',
            'Avenue Peytavin', 'ASECNA', 'Bid de la République', 'Avenue Léopold Sédar Senghor', 
            'Hôpital Principale', 'Rond-point Assemblée Nationale', 'Hôpital Le Dantec', 'Institut Pasteur', 'Terminus Palais 1'
        ]);

        $this->createLine('DDD 6', 'Guédiawaye', 'Palais 1', [
            'Terminus Guédiawaye', 'Hôpital Roi Bedoin', 'Marché jeudi', 'Hôpital Dalal Diam', 'Camberene', 
            'Case Ba', 'Police Parcelles', 'Marché Grand Médine', 'Rond-point 26', 'Foire', 'Sipres', 
            'Deux voies Liberté 6', 'Rond-point Liberté 6', 'JVC', 'Terminus Liberté 5', 'Allées Khalifa Ababacar Sy', 
            'Jet d\'Eau', 'Niary Tally', 'Eglise Sainte Thérèse- ENA', 'Lycée Kennedy', 'Boulevard Général De Gaule', 
            'Centenaire', 'RTS', 'Allées Pape Gueye Fall', 'Petersen', 'Avenue Faidherbe', 'Avenue lamine Gueye', 
            'Avenue André Peytavin', 'Avenue Jean Jaurès', 'ASECNA', 'Bid de la République', 
            'Avenue Léopold Sédar Senghor', 'Hôpital Principale', 'Rond-point Assemblée Nationale', 
            'Hôpital Le Dantec', 'Institut Pasteur', 'Terminus Palais 1'
        ]);

        $this->createLine('DDD 7', 'OUAKAM', 'Palais 2', [
            'Citée Assemblée', 'Cité Comico (Case des Tout Petits) Sortie', 'Ouakam', 'Hôpital IHO', 
            'Fenêtre Mermoz', 'Mermoz', 'ENEA', 'Ecole Normale', 'Avenue Cheikh Anta Diop', 
            'École Manguier', 'Police 4eme', 'Rond Point Sham', 'Marché Tilène', 'Rond-point Poste Médina', 
            'Avenue Blaise Diagne', 'Difoncé', 'Sandaga', 'Avenue George Pompidou', 'Place de l\'Indépendance', 
            'Avenue LSS', 'Hôpital Principale', 'Rond-point Assemblée Nationale', 'Hôpital Le Dantec', 
            'Institut Pasteur', 'Terminus Palais 2'
        ]);

        $this->createLine('DDD 8', 'Aéroport LSS', 'Palais 2', [
            'Cité Ascena', 'Yoff Tonghor', 'Hôpital Philippe Maguilène Senghor', 'Pond Foire', 'Stade LSS',
            'Patte d\'Oie', 'Grand Yoff', 'Hôpital Général Idrissa Pouye (Ex CTO)', 'Fourrière', 'Cité des Eaux',
            'Station Castor', 'Avenue Bourguiba', 'Jet d\'Eau', 'Stade Demba Diop', 'Dial Diop (Ex-Rue 10)', 'Zone B',
            'Point E', 'Avenue Cheikh Anta Diop', 'Ecole Manguier', 'Police 4eme', 'Rond Point Sham', 'Marché Tilène',
            'Rond-point Poste Médina', 'Avenue Blaise Diagne', 'Difoncé', 'Sandaga', 'Ascena', 'Bid de la République',
            'Avenue LSS', 'Hôpital Principale', 'Rond-point Assemblée Nationale', 'Hôpital Le Dantec', 'Institut Pasteur', 'Terminus Palais 2'
        ]);

        $this->createLine('DDD 9', 'Liberté 6', 'Palais 1', [
            'Terminus Liberté 6', 'Rond-Point JVC', 'Sacré cœur', 'IPG', 'Terminus Dieuppeul', 'Allées Ababacar Sy',
            'Rond point Jet d\'Eau', 'Niary Tally', 'Allée Cheikh Sidate', 'Eglise Sainte Thérèse', 'Lycée Blaise Diagne',
            'Lycée Kennedy', 'Obélisque', 'Centenaire', 'Rond-point RTS', 'Rond Point Poste Médina', 'Avenue Blaise Diagne',
            'Difoncé', 'Sandaga', 'Avenue George Pompidou', 'Place de l\'Indépendance', 'Avenue LSS', 'Hôpital Principale',
            'Rond-point Assemblée Nationale', 'Hôpital Le Dantec', 'Institut Pasteur', 'Terminus Palais 1'
        ]);

        $this->createLine('DDD 10', 'Liberté 5', 'Palais 2', [
            'Cité Derklé', 'Rond-point Liberté 6', 'Khar Yalla', 'Cité Marine', 'Cem Ousmane Socé Diop',
            'Eglise Martyrs de l\'Ouganda', 'SDE', 'Dakar Sacré Cœur', 'Stade Demba Diop', 'Avenue Bourguiba',
            'Ecole Normale', 'Poste Fann', 'Rue Aimé Césaire', 'Ministère de la Santé', 'Corniche Ouest', 'IFAN',
            'Hôtel Terroubi', 'Tunnel de Soumbédioune', 'Village Artisanal de Soumbédioune', 'Prison Reubeus',
            'Turbinal de Dakar', 'Boulevard de la République', 'Sorano', 'Avenue LSS', 'Hôpital Principale',
            'Rond-point Assemblée Nationale', 'Hôpital Le Dantec', 'Institut Pasteur', 'Terminus Palais 2'
        ]);

        $this->createLine('DDD 16A', 'Malika', 'Palais 1', [
            'Terminus Malika', 'Malika Plage', 'Terminus 221(Gadaye)', 'Hamo', 'Terminus Guédiawaye', 'Marché Sahm',
            'Ecole Canada-Icotaf', 'Cité Lobatt Fall', 'Rond-Point Lobatt Fall', 'Autoroute', 'Colobane',
            'Rond-point Colobane', 'Place Bakou', 'Cfao Technologies', 'Cymos', 'Arsenal', 'Place de l\'Indépendance', 'Palais 1'
        ]);

        $this->createLine('DDD 121', 'SCAT URBAM', 'LECLERC', [
            'Mairie Grand Yoff', 'Cité CSE', 'Pentola', 'Les deux voies de Sipres', 'Camp Pénal', 
            'Les deux voies de Liberté 6', 'Rond-Point JVC', 'Boulangerie Jaune', 'Collège Sacré Cœur', 
            'Scor Liberté', 'Sicap Rue 10', 'Lycée Blaise Diagne', 'Place ONU', 'Canal 4', 'HLM Fass', 
            'Travaux Communaux', 'Rue 22 prolongée', 'RTS', 'Allées Papa Gueye Fall', 'Petersen', 
            'Avenue Faidherbe', 'Place de l\'Indépendance', 'Rue Mouhamed 5 ex Albert Sarraut', 'BCEAO', 'Terminus Leclerc'
        ]);

        // ===== (TATA) =====
        $this->createLine('TATA 1', 'HLM Grand Yoff', 'Lat Dior', [
            'Terminus HLM Grand Yoff', 'Scat Urban', 'Liberté 6', 'Derkle', 'Liberté 4', 'Rond-point Jet d\'Eau',
            'Bourguiba', 'Grand Dakar', 'Fass Médina', 'Sham', 'Avenue Blaise Diagne', 'Terminus Lat Dior'
        ]);

        $this->createLine('TATA 2', 'Parcelles Assainies', 'Petersen', [
            'Terminus des Parcelles Assainies', 'Croisement 22', 'Pont Aliou Sow', 'Rond-Point Liberté 6', 'Castor',
            'HLM', 'Colobane', 'Tiilène', 'Avenue Blaise Diagne', 'Terminus Petersen'
        ]);

        $this->createLine('TATA 3', 'Yoff', 'Petersen', [
            'Yoff', 'Aéroport LSS', 'Route de Ngor', 'Almadies', 'Mamelles', 'Ouakam', 'Mermoz', 'Hôpital Fann',
            'UCAD', 'Sham', 'Avenue Blaise Diagne', 'Petersen'
        ]);

        $this->createLine('TATA 24', 'Guédiawaye', 'UCAD', [
            'Guédiawaye', 'Sonatel', 'Ecole Lansar', 'Tally bou Bess', 'Bountou Pikine', 'Cité des Eaux', 'Castor',
            'Bourguiba', 'Ecole Normale', 'Hôpital Fann', 'UCAD'
        ]);

        $this->createLine('TATA 27', 'Marché Boubess', 'Petersen', [
            'Marché Boubess', 'Lycée Limamoulaye', 'Hôpital Roi Bedoin', 'Sonatel', 'Marché Peund Lansar',
            'Rue 10', 'Cité Lobatt Fall', 'Colobane', 'Grande Mosquée de Dakar', 'Petersen'
        ]);

        $this->createLine('TATA 34', 'Noir Foire', 'Lat Dior', [
            'Nord Foire', 'Cité Sipres', 'Scat Urbam', 'Camp Pénal', 'Liberté 6', 'Score Liberté', 'Sicap Rue 10',
            'Zone B', 'Lycée Blaise Diagne', 'UCAD', 'Sham', 'Lat Dior'
        ]);

        $this->createLine('TATA 37', 'APIX', 'UCAD', [
            'APIX', 'Croisement Tivaouane Peulh', 'Cité Sotrac', 'Station Keur Massar', 'MTOA', 'Malika Montagne',
            'Malika Terminus', 'VDN', 'Rond Point Croisement Gadaye', 'Croisement Wakhinane', 'Hamo 6', 
            'Rond-point Dial Mbaye', 'Police Guédiawaye', 'Pikine Rue 10', 'Technopole', 'Croisement Cambérène',
            'Maristes', 'Pharmacie Abdourahmane', 'Ecole Japonaise', 'Colobane', 'Fass', 'Gueule Tapée', 'Sham', 'UCAD'
        ]);

        $this->createLine('TATA 44', 'Petit Mbao', 'Ouakam', [
            'Petit Mbao', 'Fass Mbao', 'Diamaguene', 'Poste Thiaroye', 'Bountou Pikine', 'Patte d\'Oies', 'Foire', 'VDN', 'Ouakam'
        ]);

        $this->createLine('TATA 51', 'Beaux Maraîchers', 'Jaxaay', [
            'Beaux Maraîchers', 'Icotaf', 'Texaco', 'Police Thiaroye', 'Tally Diallo', 'Yeumbeul', 'Route de Boune',
            'Hôpital Keur Massar', 'Deux voies Keur Massar', 'Jaxaay'
        ]);

        $this->createLine('TATA 58', 'Cité Comico', 'Sham', [
            'Cité Comico', 'Mbed Fass', 'Fass Mbao'
        ]);
    }

    private function createLine($name, $departure, $destination, $stops)
    {
        $lineId = DB::table('lines')->insertGetId([
            'name' => $name,
            'departure' => $departure,
            'destination' => $destination,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        foreach ($stops as $i => $stop) {
            DB::table('stops')->insert([
                'name' => $stop,
                'line_id' => $lineId,
                'stop_order' => $i + 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}

