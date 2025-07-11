<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StopController;
use App\Http\Controllers\LineController;





Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/stops', [StopController::class, 'index']);
Route::get('/lines/{id}', [LineController::class, 'show']);
Route::get('/search', [LineController::class, 'search']);
Route::get('/all-lines', [LineController::class, 'all']);
Route::get('/stops/search', [StopController::class, 'search']);


