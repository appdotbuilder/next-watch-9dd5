<?php

namespace Database\Seeders;

use App\Models\Movie;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MovieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create popular movies
        Movie::factory()->popular()->count(20)->create();
        
        // Create popular TV shows
        Movie::factory()->tvShow()->popular()->count(15)->create();
        
        // Create regular movies and shows
        Movie::factory()->count(30)->create();
        Movie::factory()->tvShow()->count(20)->create();
    }
}