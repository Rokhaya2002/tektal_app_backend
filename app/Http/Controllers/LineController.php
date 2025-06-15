<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LineController extends Controller
{
    public function show($id)
    {
        $line = DB::table('lines')->where('id', $id)->first();

        if (!$line) {
            return response()->json(['message' => 'Ligne non trouvée'], 404);
        }

        $stops = DB::table('stops')
            ->where('line_id', $id)
            ->orderBy('stop_order')
            ->get(['id', 'name', 'stop_order']);

        return response()->json([
            'id' => $line->id,
            'name' => $line->name,
            'departure' => $line->departure,
            'destination' => $line->destination,
            'stops' => $stops
        ]);
    }

    public function search(Request $request)
    {
        $from = $request->query('from');
        $to = $request->query('to');

        if (!$from || !$to) {
            return response()->json(['error' => 'Paramètres manquants'], 400);
        }

        $lines = DB::table('lines')->get();
        $results = [];

        foreach ($lines as $line) {
            $stops = DB::table('stops')
                ->where('line_id', $line->id)
                ->orderBy('stop_order')
                ->pluck('name');

            $fromIndex = $stops->search($from);
            $toIndex = $stops->search($to);

            if ($fromIndex !== false && $toIndex !== false && $fromIndex !== $toIndex) {
                $results[] = [
                    'id' => $line->id,
                    'name' => $line->name,
                    'departure' => $from,        
                    'destination' => $to,         
                    'reversed' => $fromIndex > $toIndex
                ];
            }
        }

        return response()->json($results);
    }

    public function all()
    {
        $lines = DB::table('lines')->get();
        return response()->json($lines);
    }
}
