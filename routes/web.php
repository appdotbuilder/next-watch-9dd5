<?php

use App\Http\Controllers\MovieController;
use App\Http\Controllers\PreferenceController;
use App\Http\Controllers\WatchedController;
use App\Http\Controllers\WatchListController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/health-check', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
    ]);
})->name('health-check');

// Main recommendations page (home) - show recommendations or welcome page
Route::get('/', function () {
    // If user is authenticated and we have movies, show recommendations
    if (auth()->check() && \App\Models\Movie::count() > 0) {
        return app(\App\Http\Controllers\MovieController::class)->index();
    }
    
    // Otherwise show welcome page
    return \Inertia\Inertia::render('welcome');
})->name('home');

// Recommendations
Route::get('/recommendations', [MovieController::class, 'index'])->name('movies.recommendations');

// Preferences (user ratings)
Route::post('/preferences', [PreferenceController::class, 'store'])->name('movies.preference')->middleware('auth');

// Watch list
Route::resource('watchlist', WatchListController::class)->only(['index', 'store', 'destroy'])->middleware('auth');
Route::delete('/watchlist', [WatchListController::class, 'destroy'])->name('movies.watchlist.destroy')->middleware('auth');

// Watched movies
Route::get('/watched', [WatchedController::class, 'index'])->name('movies.watched')->middleware('auth');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
