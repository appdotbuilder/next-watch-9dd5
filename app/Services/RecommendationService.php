<?php

namespace App\Services;

use App\Models\Movie;
use App\Models\User;
use App\Models\UserPreference;
use App\Services\ConfigService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RecommendationService
{
    /**
     * The Groq API base URL.
     *
     * @var string
     */
    private string $groqUrl = 'https://api.groq.com/openai/v1/chat/completions';

    /**
     * The Groq API key.
     *
     * @var string
     */
    private string $groqApiKey;

    /**
     * Create a new service instance.
     */
    public function __construct()
    {
        $this->groqApiKey = ConfigService::getGroqApiKey();
    }

    /**
     * Get personalized recommendations for a user.
     *
     * @param User|null $user
     * @param int $limit
     * @return Collection
     */
    public function getRecommendations(?User $user = null, int $limit = 20): Collection
    {
        if (!$user) {
            // For guests, return popular movies/shows
            return $this->getPopularContent($limit);
        }

        // Get user preferences
        $likedMovies = $user->preferences()->where('rating', 'liked')->with('movie')->get()->pluck('movie');
        $dislikedMovies = $user->preferences()->where('rating', 'disliked')->with('movie')->get()->pluck('movie');
        $watchedMovieIds = $user->preferences()->pluck('movie_id');

        // Get unseen movies
        $unseenMovies = Movie::whereNotIn('id', $watchedMovieIds)
            ->orderBy('vote_average', 'desc')
            ->orderBy('vote_count', 'desc')
            ->take($limit * 2) // Get more to filter through
            ->get();

        if ($likedMovies->isEmpty()) {
            // No preferences yet, return popular content
            return $unseenMovies->take($limit);
        }

        // Get recommendations based on liked content
        $recommendations = $this->getContentBasedRecommendations($likedMovies, $dislikedMovies, $unseenMovies, $limit);

        return $recommendations;
    }

    /**
     * Get popular content for new users.
     *
     * @param int $limit
     * @return Collection
     */
    protected function getPopularContent(int $limit): Collection
    {
        return Movie::orderBy('vote_average', 'desc')
            ->orderBy('vote_count', 'desc')
            ->where('vote_count', '>', 100) // Filter out movies with too few votes
            ->take($limit)
            ->get();
    }

    /**
     * Get content-based recommendations.
     *
     * @param Collection $likedMovies
     * @param Collection $dislikedMovies
     * @param Collection $unseenMovies
     * @param int $limit
     * @return Collection
     */
    protected function getContentBasedRecommendations(
        Collection $likedMovies,
        Collection $dislikedMovies,
        Collection $unseenMovies,
        int $limit
    ): Collection {
        // Extract preferred genres from liked movies
        $likedGenres = $likedMovies->flatMap(fn($movie) => $movie->genres ?? [])->countBy()->sortDesc();
        $dislikedGenres = $dislikedMovies->flatMap(fn($movie) => $movie->genres ?? [])->countBy();

        // Score each unseen movie
        $scoredMovies = $unseenMovies->map(function ($movie) use ($likedGenres, $dislikedGenres) {
            $score = $movie->vote_average ?? 0;

            // Boost score for preferred genres
            foreach ($movie->genres ?? [] as $genre) {
                if ($likedGenres->has($genre)) {
                    $score += $likedGenres[$genre] * 0.5; // Genre boost
                }
                if ($dislikedGenres->has($genre)) {
                    $score -= $dislikedGenres[$genre] * 0.3; // Genre penalty
                }
            }

            $movie->recommendation_score = $score;
            return $movie;
        });

        return $scoredMovies->sortByDesc('recommendation_score')->take($limit);
    }

    /**
     * Generate AI-powered recommendation reason.
     *
     * @param Movie $movie
     * @param User|null $user
     * @return string
     */
    public function generateRecommendationReason(Movie $movie, ?User $user = null): string
    {
        if (!$user || !$this->groqApiKey) {
            return $this->getDefaultReason($movie);
        }

        try {
            // Get user's liked movies for context
            $likedMovies = $user->preferences()->where('rating', 'liked')->with('movie')->get()->pluck('movie');
            
            if ($likedMovies->isEmpty()) {
                return $this->getDefaultReason($movie);
            }

            $likedTitles = $likedMovies->pluck('title')->take(3)->join(', ');
            $movieGenres = collect($movie->genres)->join(', ');

            $prompt = "Based on the user liking {$likedTitles}, generate a short (10-15 words) recommendation reason for '{$movie->title}' ({$movieGenres}). Start with 'Because you liked...' or 'Since you enjoyed...'";

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->groqApiKey,
                'Content-Type' => 'application/json',
            ])->post($this->groqUrl, [
                'model' => 'llama3-8b-8192',
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $prompt,
                    ],
                ],
                'max_tokens' => 50,
                'temperature' => 0.7,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $reason = trim($data['choices'][0]['message']['content'] ?? '');
                
                if ($reason) {
                    return $reason;
                }
            }
        } catch (\Exception $e) {
            Log::error('Groq API error for recommendation reason', [
                'movie_id' => $movie->id,
                'message' => $e->getMessage(),
            ]);
        }

        return $this->getDefaultReason($movie);
    }

    /**
     * Get a default recommendation reason.
     *
     * @param Movie $movie
     * @return string
     */
    protected function getDefaultReason(Movie $movie): string
    {
        $rating = $movie->vote_average;
        $genres = collect($movie->genres)->take(2)->join(' & ');

        if ($rating >= 8) {
            return "Highly rated {$genres} with {$rating}★ rating";
        }

        if ($rating >= 7) {
            return "Popular {$genres} with great reviews";
        }

        if ($genres) {
            return "Trending {$genres} you might enjoy";
        }

        return "Popular choice among viewers";
    }
}