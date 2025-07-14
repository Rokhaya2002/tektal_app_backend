<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Line;
use App\Models\Stop;
use App\Models\SearchHistory;

class LineController extends Controller
{
    public function show($id)
    {
        $line = Line::find($id);

        if (!$line) {
            return response()->json(['message' => 'Ligne non trouvée'], 404);
        }

        $stops = $line->stops;

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

        // Enregistrer la recherche dans l'historique
        $this->saveSearchHistory($from, $to, $request);

        $lines = Line::with('stops')->get();
        $results = [];

        foreach ($lines as $line) {
            $stopNames = $line->stops->pluck('name');
            // Recherche insensible à la casse
            $fromIndex = $stopNames->search(function ($stopName) use ($from) {
                return strtolower($stopName) === strtolower($from);
            });
            $toIndex = $stopNames->search(function ($stopName) use ($to) {
                return strtolower($stopName) === strtolower($to);
            });

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

    /**
     * Enregistrer une recherche dans l'historique
     */
    private function saveSearchHistory($from, $to, $request)
    {
        try {
            // Récupérer l'utilisateur connecté
            $user = $request->user();

            if (!$user) {
                // Si pas d'utilisateur connecté, on ne sauvegarde pas l'historique
                return;
            }

            $searchHistory = SearchHistory::where('from', $from)
                ->where('to', $to)
                ->where('user_id', $user->id)
                ->first();

            if ($searchHistory) {
                $searchHistory->incrementSearch();
            } else {
                SearchHistory::create([
                    'from' => $from,
                    'to' => $to,
                    'user_id' => $user->id,
                    'count' => 1,
                    'last_searched_at' => now()
                ]);
            }
        } catch (\Exception $e) {
            // Log l'erreur mais ne pas faire échouer la recherche
            Log::error('Erreur lors de la sauvegarde de l\'historique: ' . $e->getMessage());
        }
    }

    public function all()
    {
        $lines = Line::withCount('stops')->get();
        return response()->json($lines);
    }

    // Nouvelle méthode pour l'autocomplétion
    public function autocomplete(Request $request)
    {
        $query = $request->query('q');

        if (!$query || strlen($query) < 2) {
            return response()->json([]);
        }

        $lowerQuery = strtolower($query);

        // Recherche dans les noms d'arrêts (insensible à la casse)
        $stops = Stop::whereRaw('LOWER(name) LIKE ?', ['%' . $lowerQuery . '%'])
            ->select('name')
            ->distinct()
            ->limit(10)
            ->get()
            ->pluck('name');

        // Recherche dans les noms de lignes (insensible à la casse)
        $lines = Line::whereRaw('LOWER(name) LIKE ?', ['%' . $lowerQuery . '%'])
            ->select('name', 'departure', 'destination')
            ->limit(5)
            ->get();

        // Recherche dans les points de départ (insensible à la casse)
        $departures = Line::whereRaw('LOWER(departure) LIKE ?', ['%' . $lowerQuery . '%'])
            ->select('departure')
            ->distinct()
            ->limit(5)
            ->get()
            ->pluck('departure');

        // Recherche dans les destinations (insensible à la casse)
        $destinations = Line::whereRaw('LOWER(destination) LIKE ?', ['%' . $lowerQuery . '%'])
            ->select('destination')
            ->distinct()
            ->limit(5)
            ->get()
            ->pluck('destination');

        // Combiner et dédupliquer les résultats
        $allResults = collect()
            ->merge($stops)
            ->merge($departures)
            ->merge($destinations)
            ->unique()
            ->values()
            ->take(15);

        return response()->json($allResults);
    }

    // Méthode pour obtenir les suggestions de lignes basées sur deux arrêts
    public function suggestLines(Request $request)
    {
        $from = $request->query('from');
        $to = $request->query('to');

        if (!$from || !$to) {
            return response()->json([]);
        }

        $lines = Line::with('stops')->get();
        $suggestions = [];

        foreach ($lines as $line) {
            $stopNames = $line->stops->pluck('name');
            // Recherche insensible à la casse
            $fromIndex = $stopNames->search(function ($stopName) use ($from) {
                return strtolower($stopName) === strtolower($from);
            });
            $toIndex = $stopNames->search(function ($stopName) use ($to) {
                return strtolower($stopName) === strtolower($to);
            });

            if ($fromIndex !== false && $toIndex !== false && $fromIndex !== $toIndex) {
                $suggestions[] = [
                    'id' => $line->id,
                    'name' => $line->name,
                    'departure' => $line->departure,
                    'destination' => $line->destination,
                    'from' => $from,
                    'to' => $to,
                    'direction' => $fromIndex < $toIndex ? 'Aller' : 'Retour',
                    'stops_count' => abs($toIndex - $fromIndex) + 1
                ];
            }
        }

        return response()->json($suggestions);
    }

    // Nouvelle méthode pour obtenir toutes les destinations distinctes
    public function allDestinations()
    {
        $destinations = Line::select('destination')
            ->distinct()
            ->orderBy('destination')
            ->pluck('destination');
        return response()->json($destinations);
    }
}
