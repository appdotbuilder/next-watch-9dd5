<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\RecommendationService;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class MovieController extends Controller
{
    /**
     * The recommendation service instance.
     *
     * @var RecommendationService
     */
    private RecommendationService $recommendationService;

    /**
     * Create a new controller instance.
     */
    public function __construct(RecommendationService $recommendationService)
    {
        $this->recommendationService = $recommendationService;
    }

    /**
     * Display the main recommendations page.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get recommendations
        $recommendations = $this->recommendationService->getRecommendations($user, 20);
        
        // Add recommendation reasons
        $recommendations = $recommendations->map(function ($movie) use ($user) {
            $movie->recommendation_reason = $this->recommendationService->generateRecommendationReason($movie, $user);
            return $movie;
        });

        // Get user's watch list and preferences for the frontend
        $watchListIds = [];
        $preferenceIds = [];
        
        if ($user) {
            $watchListIds = $user->watchList()->pluck('movie_id')->toArray();
            $preferenceIds = $user->preferences()->pluck('movie_id')->toArray();
        }

        return Inertia::render('recommendations', [
            'recommendations' => $recommendations,
            'watchListIds' => $watchListIds,
            'preferenceIds' => $preferenceIds,
            'user' => $user,
        ]);
    }






}