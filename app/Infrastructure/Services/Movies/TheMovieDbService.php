<?php

namespace App\Infrastructure\Services\Movies;

use Illuminate\Support\Facades\Http;

class TheMovieDbService
{
    protected string $apiBaseUrl;
    protected string $apiKey;

    public function __construct()
    {
        $this->apiBaseUrl = config('themoviedb.base_url');
        $this->apiKey = config('themoviedb.api_key');
    }

    public function searchMovies(string $query, int $page = 1): ?array
    {
        $response = Http::get("{$this->apiBaseUrl}/search/movie", [
            'api_key' => $this->apiKey,
            'query' => $query,
            'page' => $page
        ])->throwUnlessStatus(200);

        return $response->json();
    }

    public function getMovie(int $theMovieDbId): ?array
    {
        $response = Http::get("{$this->apiBaseUrl}/movie/{$theMovieDbId}", [
            'api_key' => $this->apiKey
        ])->throwUnlessStatus(200);

        return $response->json();
    }
}
