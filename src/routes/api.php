<?php

use App\Http\Controllers\Api\UnitController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Get units with caching (default)
Route::get('/units', [UnitController::class, 'index']);

// Get fresh units (bypass cache)
Route::get('/units/fresh', [UnitController::class, 'fresh']);

// Clear units cache (protected)
Route::post('/units/clear-cache', [UnitController::class, 'clearCache'])->middleware('auth:api');
