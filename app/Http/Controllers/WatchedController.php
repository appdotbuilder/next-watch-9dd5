<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\UserPreference;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class WatchedController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        $preferences = $user->preferences()
            ->with('movie')
            ->latest()
            ->get();

        $watchedMovies = $preferences->map(function ($preference) {
            /** @var UserPreference $preference */
            $movie = $preference->movie;
            $movie->setAttribute('user_rating', $preference->rating);
            return $movie;
        });

        return Inertia::render('watched', [
            'watchedMovies' => $watchedMovies,
        ]);
    }
}