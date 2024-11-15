<?php

namespace App\Application\UseCases\Movies;

use App\Application\DTOs\Movies\SearchMoviesDTO;
use App\Application\Resources\Movies\SearchMoviesResource;
use App\Domain\Entities\Movie;
use App\Infrastructure\Services\TheMovieDbService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SearchMoviesUseCase
{
    private TheMovieDbService $theMovieDbService;

    public function __construct(TheMovieDbService $theMovieDbService)
    {
        $this->theMovieDbService = $theMovieDbService;
    }

    public function execute(SearchMoviesDTO $dto): AnonymousResourceCollection
    {
        $response = $this->theMovieDbService->searchMovies($dto->query, $dto->page);
        $moviesData = $response['results'] ?? [];

        $movies = [];

        foreach ($moviesData as $movieData) {
            $movie = new Movie(
                theMovieDbId: $movieData['id'],
                title: $movieData['title'],
                overview: $movieData['overview'] ?? null,
                releaseDate: $movieData['release_date'],
                posterPath: $movieData['poster_path'] ?? null,
            );

            $movies[] = $movie;
        }

        return SearchMoviesResource::collection($movies)->additional([
            'meta' => [
                'current_page' => $response['page'] ?? $dto->page,
                'total_pages' => $response['total_pages'] ?? 0,
                'total_results' => $response['total_results'] ?? 0,
            ]
        ]);
    }
}
