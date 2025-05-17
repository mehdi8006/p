<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\DivisionController;
use App\Http\Controllers\API\DocumentpathController;
use App\Http\Controllers\API\HistoriqueController;
use App\Http\Controllers\API\StatusController;
use App\Http\Controllers\API\TaskController;

// Public routes (no auth required)
Route::post('/login', [AuthController::class, 'login']);

// API v1 Routes
Route::prefix('v1')->group(function () {
    // Auth routes
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/rehash-passwords', [AuthController::class, 'rehashPasswords']);
    
    // Admin routes
    Route::apiResource('admins', AdminController::class);
    
    // Division routes
    Route::apiResource('divisions', DivisionController::class);
    
    // Task routes
    Route::apiResource('tasks', TaskController::class);
    
    // Status routes
    Route::apiResource('statuses', StatusController::class);
    
    // Document routes
    Route::apiResource('documentpaths', DocumentpathController::class);
    
    // History routes
    Route::apiResource('historiques', HistoriqueController::class);
});

// Protected routes
Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});