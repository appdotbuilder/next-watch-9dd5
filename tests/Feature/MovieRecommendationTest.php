<?php

namespace Tests\Feature;

use App\Models\Movie;
use App\Models\User;
use App\Models\UserPreference;
use App\Models\WatchList;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MovieRecommendationTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_shows_welcome_for_guest(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => 
            $page->component('welcome')
        );
    }

    public function test_recommendations_page_shows_recommendations(): void
    {
        // Create some movies
        Movie::factory()->count(10)->create();

        $response = $this->get('/recommendations');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => 
            $page->component('recommendations')
                 ->has('recommendations')
        );
    }

    public function test_authenticated_user_can_add_preference(): void
    {
        $user = User::factory()->create();
        $movie = Movie::factory()->create();

        $response = $this->actingAs($user)
            ->postJson('/preferences', [
                'movie_id' => $movie->id,
                'rating' => 'liked',
            ]);

        $response->assertStatus(200);
        
        $this->assertDatabaseHas('user_preferences', [
            'user_id' => $user->id,
            'movie_id' => $movie->id,
            'rating' => 'liked',
            'watched' => true,
        ]);
    }

    public function test_guest_cannot_add_preference(): void
    {
        $movie = Movie::factory()->create();

        $response = $this->postJson('/preferences', [
            'movie_id' => $movie->id,
            'rating' => 'liked',
        ]);

        $response->assertStatus(401);
    }

    public function test_user_can_add_to_watch_list(): void
    {
        $user = User::factory()->create();
        $movie = Movie::factory()->create();

        $response = $this->actingAs($user)
            ->postJson(route('watchlist.store'), [
                'movie_id' => $movie->id,
            ]);

        $response->assertStatus(200);
        
        $this->assertDatabaseHas('watch_lists', [
            'user_id' => $user->id,
            'movie_id' => $movie->id,
        ]);
    }

    public function test_user_can_remove_from_watch_list(): void
    {
        $user = User::factory()->create();
        $movie = Movie::factory()->create();
        
        // Add to watch list first
        WatchList::factory()->create([
            'user_id' => $user->id,
            'movie_id' => $movie->id,
        ]);

        $response = $this->actingAs($user)
            ->deleteJson('/watchlist', [
                'movie_id' => $movie->id,
            ]);

        $response->assertStatus(200);
        
        $this->assertDatabaseMissing('watch_lists', [
            'user_id' => $user->id,
            'movie_id' => $movie->id,
        ]);
    }

    public function test_user_can_view_watch_list(): void
    {
        $user = User::factory()->create();
        $movies = Movie::factory()->count(3)->create();
        
        foreach ($movies as $movie) {
            WatchList::factory()->create([
                'user_id' => $user->id,
                'movie_id' => $movie->id,
            ]);
        }

        $response = $this->actingAs($user)->get(route('watchlist.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => 
            $page->component('watch-list')
                 ->has('watchList', 3)
        );
    }

    public function test_user_can_view_watched_movies(): void
    {
        $user = User::factory()->create();
        $movies = Movie::factory()->count(3)->create();
        
        foreach ($movies as $index => $movie) {
            UserPreference::factory()->create([
                'user_id' => $user->id,
                'movie_id' => $movie->id,
                'rating' => $index % 2 === 0 ? 'liked' : 'disliked',
            ]);
        }

        $response = $this->actingAs($user)->get('/watched');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => 
            $page->component('watched')
                 ->has('watchedMovies', 3)
        );
    }


}