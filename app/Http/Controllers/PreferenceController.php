<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\UserPreference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PreferenceController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'movie_id' => 'required|exists:movies,id',
            'rating' => 'required|in:liked,disliked',
        ]);

        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'Authentication required'], 401);
        }

        UserPreference::updateOrCreate(
            [
                'user_id' => $user->id,
                'movie_id' => $request->movie_id,
            ],
            [
                'rating' => $request->rating,
                'watched' => true,
            ]
        );

        return response()->json(['success' => true]);
    }
}