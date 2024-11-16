<?php

namespace App\Infrastructure\Services\Movies;

use App\Domain\Interfaces\Services\ExternalMovieApiServiceInterface;
use Illuminate\Support\Facades\Http;

class TheMovieDbService implements ExternalMovieApiServiceInterface
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

    public function getMovie(int $movieId): ?array
    {
        $response = Http::get("{$this->apiBaseUrl}/movie/{$movieId}", [
            'api_key' => $this->apiKey
        ])->throwIf(function ($response) {
            return $response->status() !== 404 && $response->status() !== 200;
        });

        if ($response->status() === 404) {
            return null;
        }

        return $response->json();
    }
}
