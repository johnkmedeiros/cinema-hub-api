<?php

namespace App\Infrastructure\Http\Controllers\Movies;

use App\Application\DTOs\Movies\AddMovieToFavoritesDTO;
use App\Application\DTOs\Movies\ListFavoriteMoviesDTO;
use App\Application\DTOs\Movies\SearchMoviesDTO;
use App\Application\UseCases\Movies\AddMovieToFavoritesUseCase;
use App\Application\UseCases\Movies\ListFavoriteMoviesUseCase;
use App\Application\UseCases\Movies\SearchMoviesUseCase;
use App\Application\UseCases\Movies\RemoveMovieFromFavoritesUseCase;
use App\Infrastructure\RequestValidators\Movies\AddMovieToFavoritesRequest;
use App\Infrastructure\RequestValidators\Movies\ListFavoriteMoviesRequest;
use App\Infrastructure\RequestValidators\Movies\SearchMoviesRequest;
use Illuminate\Http\Request;

class MovieController
{
    public function __construct(
        private SearchMoviesUseCase $searchMoviesUseCase,
        private ListFavoriteMoviesUseCase $listFavoriteMoviesUseCase,
        private AddMovieToFavoritesUseCase $addMovieToFavoritesUseCase,
        private RemoveMovieFromFavoritesUseCase $removeMovieFromFavoritesUseCase
    ) {}

    public function search(SearchMoviesRequest $request)
    {
        $dto = SearchMoviesDTO::makeFromRequest($request);
        $response = $this->searchMoviesUseCase->execute($dto);

        return $response->response()->setStatusCode(200);
    }

    public function listFavorites(ListFavoriteMoviesRequest $request)
    {
        $dto = ListFavoriteMoviesDTO::makeFromRequest($request);
        $response = $this->listFavoriteMoviesUseCase->execute($dto);

        return $response->response()->setStatusCode(200);
    }

    public function addFavorite(AddMovieToFavoritesRequest $request)
    {
        $dto = AddMovieToFavoritesDTO::makeFromRequest($request);
        $response = $this->addMovieToFavoritesUseCase->execute($dto);

        return $response->response()->setStatusCode(200);
    }

    public function removeFavorite(Request $request, int $movieExternalId)
    {
        $response = $this->removeMovieFromFavoritesUseCase->execute($movieExternalId);

        return $response->response()->setStatusCode(200);
    }
}
