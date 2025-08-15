<?php

namespace Database\Seeders;

use App\Models\Movie;
use App\Models\User;
use App\Models\UserPreference;
use App\Models\WatchList;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create test user
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Seed movies
        $this->call(MovieSeeder::class);

        // Get some movies for preferences and watch list
        $movies = Movie::take(15)->get();
        
        // Create some preferences for the test user
        foreach ($movies->take(8) as $index => $movie) {
            UserPreference::factory()->create([
                'user_id' => $user->id,
                'movie_id' => $movie->id,
                'rating' => $index % 3 === 0 ? 'disliked' : 'liked', // Mix of likes and dislikes
            ]);
        }

        // Add some movies to watch list
        foreach ($movies->skip(8)->take(5) as $movie) {
            WatchList::factory()->create([
                'user_id' => $user->id,
                'movie_id' => $movie->id,
            ]);
        }

        // Create additional users for testing
        User::factory(5)->create();
    }
}
