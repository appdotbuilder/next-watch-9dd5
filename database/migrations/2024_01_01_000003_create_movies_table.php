<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->integer('tmdb_id')->unique()->comment('TMDB movie/show ID');
            $table->string('type')->comment('movie or tv');
            $table->string('title')->comment('Movie or show title');
            $table->text('overview')->nullable()->comment('Plot synopsis');
            $table->string('poster_path')->nullable()->comment('TMDB poster image path');
            $table->string('backdrop_path')->nullable()->comment('TMDB backdrop image path');
            $table->json('genres')->nullable()->comment('Array of genre names');
            $table->decimal('vote_average', 3, 1)->nullable()->comment('TMDB rating');
            $table->integer('vote_count')->nullable()->comment('Number of votes');
            $table->date('release_date')->nullable()->comment('Release date');
            $table->integer('runtime')->nullable()->comment('Runtime in minutes');
            $table->string('status')->nullable()->comment('Movie status');
            $table->timestamps();
            
            // Indexes for performance
            $table->index('tmdb_id');
            $table->index('type');
            $table->index('vote_average');
            $table->index('release_date');
            $table->index(['type', 'vote_average']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};