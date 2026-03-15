<?php
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/dashboard/uv-history', function () {
    return view('dashboard');
})->name('uv-history');

Route::get('/dashboard/recent-readings', function () {
    return view('dashboard');
})->name('recent-readings');
