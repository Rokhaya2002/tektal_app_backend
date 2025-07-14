<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminUserController extends Controller
{
    /**
     * Obtenir la liste des utilisateurs avec pagination
     */
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 15);
        $search = $request->query('search');
        $role = $request->query('role'); // 'all', 'admin', 'user'

        $query = User::query();

        // Filtre par recherche
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'ilike', '%' . $search . '%')
                    ->orWhere('email', 'ilike', '%' . $search . '%');
            });
        }

        // Filtre par rôle
        if ($role && $role !== 'all') {
            if ($role === 'admin') {
                $query->where('is_admin', true);
            } elseif ($role === 'user') {
                $query->where('is_admin', false);
            }
        }

        $users = $query->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return response()->json([
            'data' => $users->items(),
            'pagination' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
                'from' => $users->firstItem(),
                'to' => $users->lastItem(),
            ]
        ]);
    }

    /**
     * Obtenir les statistiques des utilisateurs
     */
    public function stats()
    {
        $totalUsers = User::count();
        $totalAdmins = User::where('is_admin', true)->count();
        $totalRegularUsers = User::where('is_admin', false)->count();

        // Utilisateurs créés aujourd'hui
        $todayUsers = User::whereDate('created_at', Carbon::today())->count();

        // Utilisateurs créés cette semaine
        $weekUsers = User::where('created_at', '>=', Carbon::now()->startOfWeek())->count();

        // Utilisateurs créés ce mois
        $monthUsers = User::where('created_at', '>=', Carbon::now()->startOfMonth())->count();

        // Utilisateurs actifs (connectés dans les 30 derniers jours)
        $activeUsers = User::where('last_login_at', '>=', Carbon::now()->subDays(30))->count();

        // Évolution des inscriptions (7 derniers jours)
        $registrationTrend = User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top 10 des utilisateurs les plus actifs (basé sur les recherches)
        $mostActiveUsers = User::select('users.id', 'users.name', 'users.email', DB::raw('COUNT(search_history.id) as search_count'))
            ->leftJoin('search_history', 'users.id', '=', 'search_history.user_id')
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderByDesc('search_count')
            ->limit(10)
            ->get();

        return response()->json([
            'total_users' => $totalUsers,
            'total_admins' => $totalAdmins,
            'total_regular_users' => $totalRegularUsers,
            'today_users' => $todayUsers,
            'week_users' => $weekUsers,
            'month_users' => $monthUsers,
            'active_users' => $activeUsers,
            'registration_trend' => $registrationTrend,
            'most_active_users' => $mostActiveUsers
        ]);
    }

    /**
     * Obtenir les détails d'un utilisateur
     */
    public function show($id)
    {
        $user = User::with(['searchHistory' => function ($query) {
            $query->orderBy('last_searched_at', 'desc')->limit(10);
        }])->find($id);

        if (!$user) {
            return response()->json(['error' => 'Utilisateur non trouvé'], 404);
        }

        // Statistiques de l'utilisateur
        $userStats = [
            'total_searches' => $user->searchHistory()->sum('count'),
            'unique_searches' => $user->searchHistory()->count(),
            'last_search' => $user->searchHistory()->max('last_searched_at'),
            'account_age' => $user->created_at->diffForHumans(),
            'last_login' => $user->last_login_at ? Carbon::parse($user->last_login_at)->diffForHumans() : 'Jamais'
        ];

        return response()->json([
            'user' => $user,
            'stats' => $userStats
        ]);
    }

    /**
     * Créer un nouvel utilisateur
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'is_admin' => 'boolean'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => $request->is_admin ?? false
        ]);

        return response()->json([
            'message' => 'Utilisateur créé avec succès',
            'user' => $user
        ], 201);
    }

    /**
     * Mettre à jour un utilisateur
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'Utilisateur non trouvé'], 404);
        }

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'sometimes|required|string|min:8',
            'is_admin' => 'sometimes|boolean'
        ]);

        $updateData = $request->only(['name', 'email', 'is_admin']);

        if ($request->has('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        return response()->json([
            'message' => 'Utilisateur mis à jour avec succès',
            'user' => $user
        ]);
    }

    /**
     * Supprimer un utilisateur
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'Utilisateur non trouvé'], 404);
        }

        // Empêcher la suppression de son propre compte
        if ($user->id === auth()->id()) {
            return response()->json(['error' => 'Vous ne pouvez pas supprimer votre propre compte'], 400);
        }

        $user->delete();

        return response()->json([
            'message' => 'Utilisateur supprimé avec succès'
        ]);
    }

    /**
     * Désactiver/Réactiver un utilisateur
     */
    public function toggleStatus($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'Utilisateur non trouvé'], 404);
        }

        $user->update(['is_active' => !$user->is_active]);

        return response()->json([
            'message' => 'Statut de l\'utilisateur modifié avec succès',
            'user' => $user
        ]);
    }

    /**
     * Obtenir l'activité récente des utilisateurs
     */
    public function recentActivity()
    {
        $recentSearches = DB::table('search_history')
            ->join('users', 'search_history.user_id', '=', 'users.id')
            ->select('users.name', 'search_history.from', 'search_history.to', 'search_history.last_searched_at')
            ->orderBy('search_history.last_searched_at', 'desc')
            ->limit(20)
            ->get();

        $recentRegistrations = User::select('name', 'email', 'created_at')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'recent_searches' => $recentSearches,
            'recent_registrations' => $recentRegistrations
        ]);
    }
}
