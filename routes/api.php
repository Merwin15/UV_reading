<?php

use App\Http\Controllers\Api\SensorController;
use Illuminate\Support\Facades\Route;

// Public endpoint for ESP32 to send data
// Remove the /api prefix since we're in routes/api.php
Route::post('/sensor-data', [SensorController::class, 'store']);
Route::get('/dashboard-data', [SensorController::class, 'getDashboardData']);


// Protected endpoint for dashboard data (requires authentication)
Route::middleware('auth:sanctum')->get('/dashboard-data', [SensorController::class, 'getDashboardData']);

// OR if you want it public (no auth required):
Route::get('/dashboard-data', [SensorController::class, 'getDashboardData']);