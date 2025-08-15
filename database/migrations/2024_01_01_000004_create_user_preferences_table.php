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
        Schema::create('user_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('movie_id')->constrained()->onDelete('cascade');
            $table->enum('rating', ['liked', 'disliked'])->comment('User rating for the movie');
            $table->boolean('watched')->default(true)->comment('Whether user has watched this');
            $table->timestamps();
            
            // Ensure one preference per user per movie
            $table->unique(['user_id', 'movie_id']);
            
            // Indexes for performance
            $table->index('user_id');
            $table->index('rating');
            $table->index(['user_id', 'rating']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_preferences');
    }
};