<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchHistoryController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->get('limit', 20);

        $searchHistory = DB::table('search_history')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();

        return response()->json($searchHistory);
    }

    public function store(Request $request)
    {
        $request->validate([
            'query' => 'required|string|max:255',
            'results_count' => 'integer',
            'user_id' => 'integer|nullable',
        ]);

        $id = DB::table('search_history')->insertGetId([
            'query' => $request->query,
            'results_count' => $request->results_count ?? 0,
            'user_id' => $request->user_id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $searchHistory = DB::table('search_history')->where('id', $id)->first();

        return response()->json([
            'message' => 'Recherche enregistrée',
            'search_history' => $searchHistory
        ], 201);
    }

    public function autocomplete(Request $request)
    {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $results = collect();

        // Recherche dans les lignes
        $lines = DB::table('lines')
            ->where('name', 'LIKE', "%{$query}%")
            ->orWhere('departure', 'LIKE', "%{$query}%")
            ->orWhere('destination', 'LIKE', "%{$query}%")
            ->limit(5)
            ->get()
            ->map(function ($line) {
                return [
                    'type' => 'line',
                    'id' => $line->id,
                    'name' => $line->name,
                    'description' => "De {$line->departure} à {$line->destination}",
                    'display' => "Ligne {$line->name} - De {$line->departure} à {$line->destination}"
                ];
            });

        // Recherche dans les arrêts
        $stops = DB::table('stops')
            ->join('lines', 'stops.line_id', '=', 'lines.id')
            ->where('stops.name', 'LIKE', "%{$query}%")
            ->limit(5)
            ->get(['stops.id', 'stops.name', 'lines.name as line_name'])
            ->map(function ($stop) {
                return [
                    'type' => 'stop',
                    'id' => $stop->id,
                    'name' => $stop->name,
                    'line_name' => $stop->line_name,
                    'display' => "Arrêt {$stop->name} - Ligne {$stop->line_name}"
                ];
            });

        $results = $lines->concat($stops)->take(10);

        return response()->json($results);
    }

    public function getStats()
    {
        $stats = [
            'total_searches' => DB::table('search_history')->count(),
            'today_searches' => DB::table('search_history')
                ->whereDate('created_at', today())
                ->count(),
            'popular_searches' => DB::table('search_history')
                ->select('query', DB::raw('count(*) as count'))
                ->groupBy('query')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get(),
            'recent_searches' => DB::table('search_history')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get(['query', 'created_at'])
        ];

        return response()->json($stats);
    }
}
