<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\WatchList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class WatchListController extends Controller
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

        $watchList = $user->watchList()
            ->with('movie')
            ->latest()
            ->get()
            ->pluck('movie');

        return Inertia::render('watch-list', [
            'watchList' => $watchList,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'movie_id' => 'required|exists:movies,id',
        ]);

        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'Authentication required'], 401);
        }

        WatchList::firstOrCreate([
            'user_id' => $user->id,
            'movie_id' => $request->movie_id,
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'movie_id' => 'required|exists:movies,id',
        ]);

        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'Authentication required'], 401);
        }

        WatchList::where('user_id', $user->id)
            ->where('movie_id', $request->movie_id)
            ->delete();

        return response()->json(['success' => true]);
    }
}