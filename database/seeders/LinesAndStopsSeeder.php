<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LinesAndStopsSeeder extends Seeder
{
    public function run(): void
{
    // 🔄 DDD 1
    $ddd1 = DB::table('lines')->insertGetId([
        'name' => 'DDD 1',
        'departure' => 'Parcelles Assainies',
        'destination' => 'Gare Leclerc',
        'created_at' => now(), 'updated_at' => now(),
    ]);
    $stopsDdd1 = ['Parcelles Assainies', 'HLM Grand Médine', 'Colobane', 'Dakar Plateau', 'Gare Leclerc'];
    foreach ($stopsDdd1 as $i => $stop) {
        DB::table('stops')->insert([
            'name' => $stop, 'line_id' => $ddd1, 'stop_order' => $i + 1,
            'created_at' => now(), 'updated_at' => now()
        ]);
    }

    // 🔄 DDD 2
    $ddd2 = DB::table('lines')->insertGetId([
        'name' => 'DDD 2',
        'departure' => 'Guédiawaye',
        'destination' => 'Palais de Justice',
        'created_at' => now(), 'updated_at' => now(),
    ]);
    $stopsDdd2 = ['Guédiawaye', 'Hann', 'Patte d\'Oie', 'Colobane', 'Palais de Justice'];
    foreach ($stopsDdd2 as $i => $stop) {
        DB::table('stops')->insert([
            'name' => $stop, 'line_id' => $ddd2, 'stop_order' => $i + 1,
            'created_at' => now(), 'updated_at' => now()
        ]);
    }

    // 🔄 DDD 3
    $ddd3 = DB::table('lines')->insertGetId([
        'name' => 'DDD 3',
        'departure' => 'Yoff',
        'destination' => 'Liberté 6',
        'created_at' => now(), 'updated_at' => now(),
    ]);
    $stopsDdd3 = ['Yoff', 'Cité Aviation', 'Mermoz', 'Sacré-Cœur', 'Liberté 6'];
    foreach ($stopsDdd3 as $i => $stop) {
        DB::table('stops')->insert([
            'name' => $stop, 'line_id' => $ddd3, 'stop_order' => $i + 1,
            'created_at' => now(), 'updated_at' => now()
        ]);
    }

    // 🔄 DDD 4
    $ddd4 = DB::table('lines')->insertGetId([
        'name' => 'DDD 4',
        'departure' => 'Grand Yoff',
        'destination' => 'Sandaga',
        'created_at' => now(), 'updated_at' => now(),
    ]);
    $stopsDdd4 = ['Grand Yoff', 'Dieuppeul', 'HLM', 'Colobane', 'Sandaga'];
    foreach ($stopsDdd4 as $i => $stop) {
        DB::table('stops')->insert([
            'name' => $stop, 'line_id' => $ddd4, 'stop_order' => $i + 1,
            'created_at' => now(), 'updated_at' => now()
        ]);
    }

    // 🔄 DDD 5
    $ddd5 = DB::table('lines')->insertGetId([
        'name' => 'DDD 5',
        'departure' => 'Rufisque',
        'destination' => 'Gare routière Pompiers',
        'created_at' => now(), 'updated_at' => now(),
    ]);
    $stopsDdd5 = ['Rufisque', 'Thiaroye', 'Yeumbeul', 'Colobane', 'Gare Pompiers'];
    foreach ($stopsDdd5 as $i => $stop) {
        DB::table('stops')->insert([
            'name' => $stop, 'line_id' => $ddd5, 'stop_order' => $i + 1,
            'created_at' => now(), 'updated_at' => now()
        ]);
    }

    // 🟢 TATA 1
    $tata1 = DB::table('lines')->insertGetId([
        'name' => 'TATA 1',
        'departure' => 'Grand Dakar',
        'destination' => 'Dakar Plateau',
        'created_at' => now(), 'updated_at' => now(),
    ]);
    $stopsTata1 = ['Grand Dakar', 'Colobane', 'HLM', 'Sandaga', 'Dakar Plateau'];
    foreach ($stopsTata1 as $i => $stop) {
        DB::table('stops')->insert([
            'name' => $stop, 'line_id' => $tata1, 'stop_order' => $i + 1,
            'created_at' => now(), 'updated_at' => now()
        ]);
    }

    // 🟢 TATA 2
    $tata2 = DB::table('lines')->insertGetId([
        'name' => 'TATA 2',
        'departure' => 'Thiaroye',
        'destination' => 'Liberté 5',
        'created_at' => now(), 'updated_at' => now(),
    ]);
    $stopsTata2 = ['Thiaroye', 'Yeumbeul', 'Colobane', 'Sacré-Cœur', 'Liberté 5'];
    foreach ($stopsTata2 as $i => $stop) {
        DB::table('stops')->insert([
            'name' => $stop, 'line_id' => $tata2, 'stop_order' => $i + 1,
            'created_at' => now(), 'updated_at' => now()
        ]);
    }

    // 🟢 TATA 3
    $tata3 = DB::table('lines')->insertGetId([
        'name' => 'TATA 3',
        'departure' => 'Pikine',
        'destination' => 'Plateau',
        'created_at' => now(), 'updated_at' => now(),
    ]);
    $stopsTata3 = ['Pikine', 'Guédiawaye', 'Patte d\'Oie', 'Colobane', 'Plateau'];
    foreach ($stopsTata3 as $i => $stop) {
        DB::table('stops')->insert([
            'name' => $stop, 'line_id' => $tata3, 'stop_order' => $i + 1,
            'created_at' => now(), 'updated_at' => now()
        ]);
    }

    // 🟢 TATA 4
    $tata4 = DB::table('lines')->insertGetId([
        'name' => 'TATA 4',
        'departure' => 'Keur Massar',
        'destination' => 'Colobane',
        'created_at' => now(), 'updated_at' => now(),
    ]);
    $stopsTata4 = ['Keur Massar', 'Malika', 'Yeumbeul', 'Thiaroye', 'Colobane'];
    foreach ($stopsTata4 as $i => $stop) {
        DB::table('stops')->insert([
            'name' => $stop, 'line_id' => $tata4, 'stop_order' => $i + 1,
            'created_at' => now(), 'updated_at' => now()
        ]);
    }

    // 🟢 TATA 5
    $tata5 = DB::table('lines')->insertGetId([
        'name' => 'TATA 5',
        'departure' => 'Dalifort',
        'destination' => 'UCAD',
        'created_at' => now(), 'updated_at' => now(),
    ]);
    $stopsTata5 = ['Dalifort', 'Colobane', 'Bourguiba', 'Fann', 'UCAD'];
    foreach ($stopsTata5 as $i => $stop) {
        DB::table('stops')->insert([
            'name' => $stop, 'line_id' => $tata5, 'stop_order' => $i + 1,
            'created_at' => now(), 'updated_at' => now()
        ]);
    }
}

}
