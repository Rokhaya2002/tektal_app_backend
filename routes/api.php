<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StopController;
use App\Http\Controllers\LineController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminLineController;
use App\Http\Controllers\AdminStopController;
use App\Http\Controllers\SearchHistoryController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Routes publiques
Route::get('/stops', [StopController::class, 'index']);
Route::get('/lines/{id}', [LineController::class, 'show']);
Route::get('/search', [LineController::class, 'search']);
Route::get('/all-lines', [LineController::class, 'all']);

// Routes d'authentification générale (utilisateurs normaux)
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Routes d'authentification admin
Route::post('/admin/login', [AdminAuthController::class, 'login']);

// Routes de recherche et historique
Route::get('/search-history', [SearchHistoryController::class, 'index']);
Route::post('/search-history', [SearchHistoryController::class, 'store']);
Route::get('/autocomplete', [SearchHistoryController::class, 'autocomplete']);

// Routes protégées (utilisateurs connectés)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'getCurrentUser']);
});

// Routes admin protégées
Route::middleware('auth:sanctum')->prefix('admin')->group(function () {
    Route::post('/logout', [AdminAuthController::class, 'logout']);
    Route::get('/user', [AdminAuthController::class, 'getCurrentUser']);

    // Gestion des lignes
    Route::get('/lines', [AdminLineController::class, 'index']);
    Route::post('/lines', [AdminLineController::class, 'store']);
    Route::get('/lines/{id}', [AdminLineController::class, 'show']);
    Route::put('/lines/{id}', [AdminLineController::class, 'update']);
    Route::delete('/lines/{id}', [AdminLineController::class, 'destroy']);

    // Gestion des arrêts
    Route::get('/stops', [AdminStopController::class, 'index']);
    Route::post('/stops', [AdminStopController::class, 'store']);
    Route::get('/stops/{id}', [AdminStopController::class, 'show']);
    Route::put('/stops/{id}', [AdminStopController::class, 'update']);
    Route::delete('/stops/{id}', [AdminStopController::class, 'destroy']);

    // Statistiques
    Route::get('/search-stats', [SearchHistoryController::class, 'getStats']);

    Route::get('/users', function () {
        return response()->json([]);
    });

    Route::get('/users/stats', function () {
        return response()->json(['total' => 0, 'message' => 'Statistiques utilisateurs à implémenter']);
    });
});
