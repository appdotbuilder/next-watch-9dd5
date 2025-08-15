<?php

namespace App\Services;

class ConfigService
{
    /**
     * Get TMDB API key from configuration.
     *
     * @return string
     */
    public static function getTmdbApiKey(): string
    {
        // Use environment variables directly for testing
        // In production, these would be set via config/services.php
        $key = $_ENV['TMDB_API_KEY'] ?? '';
        return $key ?: '';
    }

    /**
     * Get Groq API key from configuration.
     *
     * @return string
     */
    public static function getGroqApiKey(): string
    {
        // Use environment variables directly for testing
        // In production, these would be set via config/services.php
        $key = $_ENV['GROQ_API_KEY'] ?? '';
        return $key ?: '';
    }
}