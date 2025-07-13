<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StopController;
use App\Http\Controllers\LineController;
use App\Http\Controllers\SearchHistoryController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Routes d'authentification utilisateur
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Routes protégées utilisateur
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Route de recherche (protégée)
    Route::get('/search', [LineController::class, 'search']);

    // Routes pour l'historique de recherche (protégées)
    Route::post('/search-history', [SearchHistoryController::class, 'store']);
    Route::get('/search-history', [SearchHistoryController::class, 'index']);
    Route::get('/search-history/suggestions', [SearchHistoryController::class, 'suggestions']);
    Route::get('/search-history/search', [SearchHistoryController::class, 'search']);
    Route::delete('/search-history', [SearchHistoryController::class, 'clear']);
    Route::delete('/search-history/{id}', [SearchHistoryController::class, 'destroy']);
});

Route::get('/stops', [StopController::class, 'index']);

Route::get('/lines/{id}', [LineController::class, 'show']);

Route::get('/all-lines', [LineController::class, 'all']);

// Nouvelles routes pour l'autocomplétion
Route::get('/autocomplete', [LineController::class, 'autocomplete']);
Route::get('/suggest-lines', [LineController::class, 'suggestLines']);
Route::get('/all-destinations', [LineController::class, 'allDestinations']);

// Auth admin (connexion uniquement)
Route::post('/admin/login', [\App\Http\Controllers\AdminAuthController::class, 'login']);

// Routes admin protégées
Route::middleware('auth:sanctum')->prefix('admin')->group(function () {
    // Gestion des lignes admin
    Route::get('/lines', [\App\Http\Controllers\AdminLineController::class, 'index']);
    Route::post('/lines', [\App\Http\Controllers\AdminLineController::class, 'store']);
    Route::get('/lines/{id}', [\App\Http\Controllers\AdminLineController::class, 'show']);
    Route::put('/lines/{id}', [\App\Http\Controllers\AdminLineController::class, 'update']);
    Route::delete('/lines/{id}', [\App\Http\Controllers\AdminLineController::class, 'destroy']);

    // Gestion des arrêts admin
    Route::get('/stops', [\App\Http\Controllers\AdminStopController::class, 'index']);
    Route::post('/stops', [\App\Http\Controllers\AdminStopController::class, 'store']);
    Route::get('/stops/{id}', [\App\Http\Controllers\AdminStopController::class, 'show']);
    Route::put('/stops/{id}', [\App\Http\Controllers\AdminStopController::class, 'update']);
    Route::delete('/stops/{id}', [\App\Http\Controllers\AdminStopController::class, 'destroy']);

    // Gestion des utilisateurs admin
    Route::get('/users', [\App\Http\Controllers\AdminUserController::class, 'index']);
    Route::get('/users/stats', [\App\Http\Controllers\AdminUserController::class, 'stats']);
    Route::get('/users/activity', [\App\Http\Controllers\AdminUserController::class, 'recentActivity']);
    Route::get('/users/{id}', [\App\Http\Controllers\AdminUserController::class, 'show']);
    Route::post('/users', [\App\Http\Controllers\AdminUserController::class, 'store']);
    Route::put('/users/{id}', [\App\Http\Controllers\AdminUserController::class, 'update']);
    Route::delete('/users/{id}', [\App\Http\Controllers\AdminUserController::class, 'destroy']);
    Route::patch('/users/{id}/toggle-status', [\App\Http\Controllers\AdminUserController::class, 'toggleStatus']);

    // Statistiques de recherche admin
    Route::get('/search-stats', [SearchHistoryController::class, 'stats']);
    Route::get('/search-chart-stats', [SearchHistoryController::class, 'chartStats']);
});
