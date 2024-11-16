<?php

namespace App\Application\UseCases\Movies;

use App\Application\DTOs\Movies\SearchMoviesDTO;
use App\Application\Resources\Movies\SearchMoviesResource;
use App\Domain\Interfaces\Services\ExternalMovieApiServiceInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SearchMoviesUseCase
{
    private ExternalMovieApiServiceInterface $externalMovieApiService;

    public function __construct(ExternalMovieApiServiceInterface $externalMovieApiService)
    {
        $this->externalMovieApiService = $externalMovieApiService;
    }

    public function execute(SearchMoviesDTO $dto): AnonymousResourceCollection
    {
        $response = $this->externalMovieApiService->searchMovies($dto->query, $dto->page);

        return SearchMoviesResource::collection($response->movies)->additional([
            'meta' => [
                'current_page' => $response->currentPage ?? $dto->page,
                'total_pages' => $response->totalPages ?? 1,
                'total_results' => $response->totalResults ?? 0,
            ]
        ]);
    }
}
