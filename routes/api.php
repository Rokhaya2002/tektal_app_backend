<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StopController;
use App\Http\Controllers\LineController;

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

