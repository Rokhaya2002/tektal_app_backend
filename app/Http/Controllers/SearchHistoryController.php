<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SearchHistory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SearchHistoryController extends Controller
{
    /**
     * Enregistrer une nouvelle recherche dans l'historique
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from' => 'required|string|max:255',
            'to' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Données invalides',
                'details' => $validator->errors()
            ], 400);
        }

        $user = $request->user();
        if (!$user) {
            return response()->json([
                'error' => 'Utilisateur non connecté'
            ], 401);
        }

        $from = $request->input('from');
        $to = $request->input('to');

        // Chercher si cette recherche existe déjà pour cet utilisateur
        $searchHistory = SearchHistory::where('from', $from)
            ->where('to', $to)
            ->where('user_id', $user->id)
            ->first();

        if ($searchHistory) {
            // Incrémenter le compteur et mettre à jour la date
            $searchHistory->incrementSearch();
        } else {
            // Créer une nouvelle entrée
            $searchHistory = SearchHistory::create([
                'from' => $from,
                'to' => $to,
                'user_id' => $user->id,
                'count' => 1,
                'last_searched_at' => now()
            ]);
        }

        return response()->json([
            'message' => 'Recherche enregistrée',
            'data' => $searchHistory
        ], 201);
    }

    /**
     * Obtenir l'historique des recherches
     */
    public function index(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'error' => 'Utilisateur non connecté'
            ], 401);
        }

        $limit = $request->query('limit', 20);
        $type = $request->query('type', 'recent'); // 'recent' ou 'popular'

        if ($type === 'popular') {
            $history = SearchHistory::where('user_id', $user->id)->popular($limit)->get();
        } else {
            $history = SearchHistory::where('user_id', $user->id)->recent($limit)->get();
        }

        return response()->json([
            'data' => $history,
            'total' => $history->count()
        ]);
    }

    /**
     * Obtenir les suggestions populaires
     */
    public function suggestions(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'error' => 'Utilisateur non connecté'
            ], 401);
        }

        $limit = $request->query('limit', 10);
        $suggestions = SearchHistory::where('user_id', $user->id)->getPopularSuggestions($limit);

        return response()->json([
            'suggestions' => $suggestions,
            'total' => count($suggestions)
        ]);
    }

    /**
     * Obtenir les statistiques de recherche détaillées pour l'admin
     */
    public function stats()
    {
        $totalSearches = SearchHistory::sum('count');
        $uniqueSearches = SearchHistory::count();
        $mostPopular = SearchHistory::popular(10)->get();
        $recentSearches = SearchHistory::recent(5)->get();

        // Statistiques par jour (7 derniers jours)
        $dailyStats = SearchHistory::selectRaw('TO_CHAR(last_searched_at, \'YYYY-MM-DD\') as date, SUM(count) as total_searches')
            ->where('last_searched_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top 5 des destinations les plus recherchées
        $topDestinations = SearchHistory::selectRaw('"to" as destination, SUM(count) as total_searches')
            ->groupBy('to')
            ->orderByDesc('total_searches')
            ->limit(5)
            ->get();

        // Top 5 des points de départ les plus recherchés
        $topDepartures = SearchHistory::selectRaw('"from" as departure, SUM(count) as total_searches')
            ->groupBy('from')
            ->orderByDesc('total_searches')
            ->limit(5)
            ->get();

        // Statistiques par heure (24 dernières heures)
        $hourlyStats = SearchHistory::selectRaw('EXTRACT(HOUR FROM last_searched_at) as hour, SUM(count) as total_searches')
            ->where('last_searched_at', '>=', Carbon::now()->subDay())
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        // Recherches aujourd'hui
        $todaySearches = SearchHistory::whereDate('last_searched_at', Carbon::today())->sum('count');

        // Recherches cette semaine
        $weekSearches = SearchHistory::where('last_searched_at', '>=', Carbon::now()->startOfWeek())->sum('count');

        // Recherches ce mois
        $monthSearches = SearchHistory::where('last_searched_at', '>=', Carbon::now()->startOfMonth())->sum('count');

        return response()->json([
            'stats' => [
                'total_searches' => $totalSearches,
                'unique_searches' => $uniqueSearches,
                'today_searches' => $todaySearches,
                'week_searches' => $weekSearches,
                'month_searches' => $monthSearches,
                'most_popular' => $mostPopular,
                'recent_searches' => $recentSearches,
                'daily_stats' => $dailyStats,
                'hourly_stats' => $hourlyStats,
                'top_destinations' => $topDestinations,
                'top_departures' => $topDepartures
            ]
        ]);
    }

    /**
     * Obtenir les statistiques pour les graphiques (format optimisé)
     */
    public function chartStats(Request $request)
    {
        $period = $request->query('period', 'week'); // 'day', 'week', 'month'

        switch ($period) {
            case 'day':
                $startDate = Carbon::now()->subDay();
                $groupBy = 'EXTRACT(HOUR FROM last_searched_at)';
                $dateFormat = 'H:i';
                break;
            case 'week':
                $startDate = Carbon::now()->subWeek();
                $groupBy = 'TO_CHAR(last_searched_at, \'YYYY-MM-DD\')';
                $dateFormat = 'd/m';
                break;
            case 'month':
                $startDate = Carbon::now()->subMonth();
                $groupBy = 'TO_CHAR(last_searched_at, \'YYYY-MM-DD\')';
                $dateFormat = 'd/m';
                break;
            default:
                $startDate = Carbon::now()->subWeek();
                $groupBy = 'TO_CHAR(last_searched_at, \'YYYY-MM-DD\')';
                $dateFormat = 'd/m';
        }

        $chartData = SearchHistory::selectRaw("$groupBy as date, SUM(count) as total_searches")
            ->where('last_searched_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function ($item) use ($dateFormat) {
                return [
                    'date' => $item->date,
                    'searches' => $item->total_searches
                ];
            });

        return response()->json([
            'chart_data' => $chartData,
            'period' => $period
        ]);
    }

    /**
     * Supprimer tout l'historique de l'utilisateur connecté
     */
    public function clear(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'error' => 'Utilisateur non connecté'
            ], 401);
        }

        SearchHistory::where('user_id', $user->id)->delete();

        return response()->json([
            'message' => 'Historique supprimé avec succès'
        ]);
    }

    /**
     * Supprimer une recherche spécifique de l'utilisateur connecté
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'error' => 'Utilisateur non connecté'
            ], 401);
        }

        $searchHistory = SearchHistory::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$searchHistory) {
            return response()->json([
                'error' => 'Recherche non trouvée'
            ], 404);
        }

        $searchHistory->delete();

        return response()->json([
            'message' => 'Recherche supprimée avec succès'
        ]);
    }

    /**
     * Rechercher dans l'historique de l'utilisateur connecté
     */
    public function search(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'error' => 'Utilisateur non connecté'
            ], 401);
        }

        $query = $request->query('q');

        if (!$query) {
            return response()->json([
                'error' => 'Terme de recherche requis'
            ], 400);
        }

        $lowerQuery = strtolower($query);

        $results = SearchHistory::where('user_id', $user->id)
            ->where(function ($q) use ($lowerQuery) {
                $q->whereRaw('LOWER(`from`) LIKE ?', ['%' . $lowerQuery . '%'])
                    ->orWhereRaw('LOWER(`to`) LIKE ?', ['%' . $lowerQuery . '%']);
            })
            ->orderBy('last_searched_at', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'data' => $results,
            'total' => $results->count()
        ]);
    }
}
