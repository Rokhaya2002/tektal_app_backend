<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StopController;
use App\Http\Controllers\LineController;
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

Route::get('/stops', [StopController::class, 'index']);

Route::get('/lines/{id}', [LineController::class, 'show']);

Route::get('/search', [LineController::class, 'search']);

Route::get('/all-lines', [LineController::class, 'all']);

// Nouvelles routes pour l'autocomplétion
Route::get('/autocomplete', [LineController::class, 'autocomplete']);
Route::get('/suggest-lines', [LineController::class, 'suggestLines']);
Route::get('/all-destinations', [LineController::class, 'allDestinations']);

// Routes pour l'historique de recherche
Route::post('/search-history', [SearchHistoryController::class, 'store']);
Route::get('/search-history', [SearchHistoryController::class, 'index']);
Route::get('/search-history/suggestions', [SearchHistoryController::class, 'suggestions']);
Route::get('/search-history/stats', [SearchHistoryController::class, 'stats']);
Route::get('/search-history/chart-stats', [SearchHistoryController::class, 'chartStats']);
Route::get('/search-history/search', [SearchHistoryController::class, 'search']);
Route::delete('/search-history', [SearchHistoryController::class, 'clear']);
Route::delete('/search-history/{id}', [SearchHistoryController::class, 'destroy']);

// Auth admin
Route::post('/admin/register', [\App\Http\Controllers\AdminAuthController::class, 'register']);
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

    // Statistiques de recherche admin
    Route::get('/search-stats', [SearchHistoryController::class, 'stats']);
    Route::get('/search-chart-stats', [SearchHistoryController::class, 'chartStats']);
});
