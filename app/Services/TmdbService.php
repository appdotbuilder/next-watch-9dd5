<?php

namespace App\Services;

use App\Models\Movie;
use App\Services\ConfigService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TmdbService
{
    /**
     * The TMDB API base URL.
     *
     * @var string
     */
    private string $baseUrl = 'https://api.themoviedb.org/3';

    /**
     * The TMDB API key.
     *
     * @var string
     */
    private string $apiKey;

    /**
     * Create a new service instance.
     */
    public function __construct()
    {
        $this->apiKey = ConfigService::getTmdbApiKey();
    }

    /**
     * Get popular movies from TMDB.
     *
     * @param int $page
     * @return array
     */
    public function getPopularMovies(int $page = 1): array
    {
        try {
            $response = Http::get("{$this->baseUrl}/movie/popular", [
                'api_key' => $this->apiKey,
                'page' => $page,
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('TMDB API error for popular movies', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return [];
        } catch (\Exception $e) {
            Log::error('TMDB API exception for popular movies', [
                'message' => $e->getMessage(),
            ]);

            return [];
        }
    }

    /**
     * Get popular TV shows from TMDB.
     *
     * @param int $page
     * @return array
     */
    public function getPopularTvShows(int $page = 1): array
    {
        try {
            $response = Http::get("{$this->baseUrl}/tv/popular", [
                'api_key' => $this->apiKey,
                'page' => $page,
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('TMDB API error for popular TV shows', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return [];
        } catch (\Exception $e) {
            Log::error('TMDB API exception for popular TV shows', [
                'message' => $e->getMessage(),
            ]);

            return [];
        }
    }

    /**
     * Search for movies and TV shows.
     *
     * @param string $query
     * @param int $page
     * @return array
     */
    public function search(string $query, int $page = 1): array
    {
        try {
            $response = Http::get("{$this->baseUrl}/search/multi", [
                'api_key' => $this->apiKey,
                'query' => $query,
                'page' => $page,
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('TMDB API error for search', [
                'query' => $query,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return [];
        } catch (\Exception $e) {
            Log::error('TMDB API exception for search', [
                'query' => $query,
                'message' => $e->getMessage(),
            ]);

            return [];
        }
    }

    /**
     * Get movie details by TMDB ID.
     *
     * @param int $tmdbId
     * @return array|null
     */
    public function getMovieDetails(int $tmdbId): ?array
    {
        try {
            $response = Http::get("{$this->baseUrl}/movie/{$tmdbId}", [
                'api_key' => $this->apiKey,
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            Log::error('TMDB API exception for movie details', [
                'tmdb_id' => $tmdbId,
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Get TV show details by TMDB ID.
     *
     * @param int $tmdbId
     * @return array|null
     */
    public function getTvDetails(int $tmdbId): ?array
    {
        try {
            $response = Http::get("{$this->baseUrl}/tv/{$tmdbId}", [
                'api_key' => $this->apiKey,
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            Log::error('TMDB API exception for TV details', [
                'tmdb_id' => $tmdbId,
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Store or update movie data from TMDB.
     *
     * @param array $tmdbData
     * @param string $type
     * @return Movie
     */
    public function storeMovieFromTmdb(array $tmdbData, string $type = 'movie'): Movie
    {
        $genres = collect($tmdbData['genres'] ?? [])->pluck('name')->toArray();
        
        $releaseDate = null;
        if (isset($tmdbData['release_date']) && $tmdbData['release_date']) {
            $releaseDate = $tmdbData['release_date'];
        } elseif (isset($tmdbData['first_air_date']) && $tmdbData['first_air_date']) {
            $releaseDate = $tmdbData['first_air_date'];
        }

        $title = $tmdbData['title'] ?? $tmdbData['name'] ?? '';

        return Movie::updateOrCreate(
            ['tmdb_id' => $tmdbData['id']],
            [
                'type' => $type,
                'title' => $title,
                'overview' => $tmdbData['overview'] ?? '',
                'poster_path' => $tmdbData['poster_path'],
                'backdrop_path' => $tmdbData['backdrop_path'] ?? null,
                'genres' => $genres,
                'vote_average' => $tmdbData['vote_average'] ?? null,
                'vote_count' => $tmdbData['vote_count'] ?? null,
                'release_date' => $releaseDate,
                'runtime' => $tmdbData['runtime'] ?? null,
                'status' => $tmdbData['status'] ?? null,
            ]
        );
    }
}