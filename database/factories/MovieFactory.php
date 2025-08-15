<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Movie>
 */
class MovieFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $genres = ['Action', 'Adventure', 'Animation', 'Comedy', 'Crime', 'Documentary', 'Drama', 'Family', 'Fantasy', 'History', 'Horror', 'Music', 'Mystery', 'Romance', 'Science Fiction', 'Thriller', 'War', 'Western'];
        $selectedGenres = $this->faker->randomElements($genres, random_int(1, 3));

        return [
            'tmdb_id' => $this->faker->unique()->numberBetween(1, 999999),
            'type' => $this->faker->randomElement(['movie', 'tv']),
            'title' => $this->faker->sentence(random_int(1, 4), false),
            'overview' => $this->faker->paragraph(3),
            'poster_path' => '/poster' . $this->faker->numberBetween(1, 10) . '.jpg',
            'backdrop_path' => '/backdrop' . $this->faker->numberBetween(1, 10) . '.jpg',
            'genres' => $selectedGenres,
            'vote_average' => $this->faker->randomFloat(1, 1, 10),
            'vote_count' => $this->faker->numberBetween(10, 10000),
            'release_date' => $this->faker->date(),
            'runtime' => $this->faker->numberBetween(60, 180),
            'status' => $this->faker->randomElement(['Released', 'Post Production', 'In Production']),
        ];
    }

    /**
     * Indicate that the movie is a highly rated movie.
     */
    public function popular(): static
    {
        return $this->state(fn (array $attributes) => [
            'vote_average' => $this->faker->randomFloat(1, 7, 9.5),
            'vote_count' => $this->faker->numberBetween(1000, 50000),
        ]);
    }

    /**
     * Indicate that the movie is a TV show.
     */
    public function tvShow(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'tv',
            'runtime' => null,
        ]);
    }
}