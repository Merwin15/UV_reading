<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Public landing page
Route::get('/', function () {
    return view('welcome');
});

// Authenticated user dashboard (non-admin)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Non-admin UV history + statistics (using same dashboard shell)
Route::get('/uv-history', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('uv-history');

Route::get('/recent-readings', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('recent-readings');

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get(
        '/admin/dashboard',
        [App\Http\Controllers\Admin\AdminController::class, 'dashboard']
    )->name('admin.dashboard');

    Route::get(
        '/admin/dashboard/uv-history',
        [App\Http\Controllers\Admin\AdminController::class, 'dashboard']
    )->name('admin.uv-history');

    Route::get(
        '/admin/dashboard/recent-readings',
        [App\Http\Controllers\Admin\AdminController::class, 'dashboard']
    )->name('admin.recent-readings');

    Route::get(
        '/admin/dashboard/live-reading',
        [App\Http\Controllers\Admin\AdminController::class, 'dashboard']
    )->name('admin.live-reading');

    Route::get(
        '/admin/dashboard/data-log',
        [App\Http\Controllers\Admin\AdminController::class, 'dashboard']
    )->name('admin.data-log');

    Route::delete(
        '/admin/sensor-readings',
        [App\Http\Controllers\Admin\AdminController::class, 'clearSensorReadings']
    )->name('admin.sensor-readings.clear');
});

// Farmer routes
Route::middleware(['auth', 'role:farmer'])->group(function () {
    Route::get(
        '/farmer/dashboard',
        [App\Http\Controllers\Farmer\FarmerController::class, 'dashboard']
    )->name('farmer.dashboard');
});

require __DIR__.'/auth.php';