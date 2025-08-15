<?php

namespace Database\Factories;

use App\Models\Movie;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserPreference>
 */
class UserPreferenceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'movie_id' => Movie::factory(),
            'rating' => $this->faker->randomElement(['liked', 'disliked']),
            'watched' => true,
        ];
    }

    /**
     * Indicate that the preference is liked.
     */
    public function liked(): static
    {
        return $this->state(fn (array $attributes) => [
            'rating' => 'liked',
        ]);
    }

    /**
     * Indicate that the preference is disliked.
     */
    public function disliked(): static
    {
        return $this->state(fn (array $attributes) => [
            'rating' => 'disliked',
        ]);
    }
}