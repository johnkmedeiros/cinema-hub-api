<?php

namespace App\Infrastructure\Http\Controllers\Movies;

use App\Application\DTOs\Movies\SearchMoviesDTO;
use App\Application\UseCases\Movies\SearchMoviesUseCase;
use App\Infrastructure\RequestValidators\Movies\SearchMoviesRequest;

class MovieController
{
    public function __construct(private SearchMoviesUseCase $searchMoviesUseCase)
    {
    }

    public function search(SearchMoviesRequest $request)
    {
        $dto = SearchMoviesDTO::makeFromRequest($request);
        $response = $this->searchMoviesUseCase->execute($dto);

        return $response;
    }
}
