<?php

namespace App\Infrastructure\Services;

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
        ]);

        return $response->json();
    }
}
