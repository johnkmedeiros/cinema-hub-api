<?php

namespace App\Infrastructure\Http\Controllers\Movies;

use App\Application\DTOs\Movies\AddMovieToFavoritesDTO;
use App\Application\DTOs\Movies\SearchMoviesDTO;
use App\Application\UseCases\Movies\AddMovieToFavoritesUseCase;
use App\Application\UseCases\Movies\SearchMoviesUseCase;
use App\Infrastructure\RequestValidators\Movies\AddMovieToFavoritesRequest;
use App\Infrastructure\RequestValidators\Movies\SearchMoviesRequest;

class MovieController
{
    public function __construct(
        private SearchMoviesUseCase $searchMoviesUseCase,
        private AddMovieToFavoritesUseCase $addMovieToFavoritesUseCase
    ) {}

    public function search(SearchMoviesRequest $request)
    {
        $dto = SearchMoviesDTO::makeFromRequest($request);
        $response = $this->searchMoviesUseCase->execute($dto);

        return $response->response()->setStatusCode(200);
    }

    public function addFavorite(AddMovieToFavoritesRequest $request)
    {
        $dto = AddMovieToFavoritesDTO::makeFromRequest($request);
        $response = $this->addMovieToFavoritesUseCase->execute($dto);

        return $response->response()->setStatusCode(200);
    }
}
