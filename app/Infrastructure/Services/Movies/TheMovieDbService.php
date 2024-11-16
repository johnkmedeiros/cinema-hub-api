<?php

namespace App\Infrastructure\Services\Movies;

use App\Application\DTOs\Movies\Responses\SearchMoviesExternalApiResponse;
use App\Domain\Entities\Movie;
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

    public function searchMovies(string $query, int $page = 1): SearchMoviesExternalApiResponse
    {
        $response = Http::get("{$this->apiBaseUrl}/search/movie", [
            'api_key' => $this->apiKey,
            'query' => $query,
            'page' => $page
        ])->throwUnlessStatus(200);

        return new SearchMoviesExternalApiResponse(
            currentPage: $response['page'],
            totalPages: $response['total_pages'],
            totalResults: $response['total_results'],
            movies: collect($response['results'])->map(function ($movieData) {
                return new Movie(
                    externalId: $movieData['id'],
                    title: $movieData['title'],
                    overview: $movieData['overview'] ?? null,
                    releaseDate: $movieData['release_date'],
                    posterPath: $movieData['poster_path'] ?? null
                );
            })->toArray()
        );
    }

    public function getMovie(int $movieId): ?Movie
    {
        $response = Http::get("{$this->apiBaseUrl}/movie/{$movieId}", [
            'api_key' => $this->apiKey
        ])->throwIf(function ($response) {
            return $response->status() !== 404 && $response->status() !== 200;
        });

        if ($response->status() === 404) {
            return null;
        }

        $response = $response->json();

        return new Movie(
            externalId: $response['id'],
            title: $response['title'],
            overview: $response['overview'],
            releaseDate: $response['release_date'],
            posterPath: $response['poster_path']
        );
    }
}
