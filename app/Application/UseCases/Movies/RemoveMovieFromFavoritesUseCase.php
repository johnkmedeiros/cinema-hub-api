<?php

namespace App\Application\UseCases\Movies;

use App\Application\Enums\ErrorCodeEnum;
use App\Application\Exceptions\BusinessException;
use App\Application\Resources\Movies\GenericMessageResource;
use App\Domain\Entities\Movie;
use App\Domain\Interfaces\Repositories\MovieRepositoryInterface;
use App\Domain\Interfaces\Services\AuthServiceInterface;
use App\Domain\Interfaces\Services\ExternalMovieApiServiceInterface;
use Illuminate\Http\Resources\Json\JsonResource;

class RemoveMovieFromFavoritesUseCase
{
    private ExternalMovieApiServiceInterface $externalMovieApiService;
    private MovieRepositoryInterface $movieRepository;
    private AuthServiceInterface $authService;

    public function __construct(ExternalMovieApiServiceInterface $externalMovieApiService, MovieRepositoryInterface $movieRepository, AuthServiceInterface $authService)
    {
        $this->externalMovieApiService = $externalMovieApiService;
        $this->movieRepository = $movieRepository;
        $this->authService = $authService;
    }

    public function execute(int $movieExternalId): JsonResource
    {
        $movie = $this->getMovie($movieExternalId);

        $this->removeFromFavorites($movie);

        return new GenericMessageResource("Movie #{$movie->getExternalId()} removed from your favorite list.");
    }

    private function getMovie(int $movieExternalId): Movie
    {
        $movie = $this->movieRepository->findByExternalId($movieExternalId);

        if (!$movie) {
            throw new BusinessException("Movie is not favorited.", 422, ErrorCodeEnum::MOVIE_NOT_FAVORITED->value);
        }

        return $movie;
    }

    private function removeFromFavorites(Movie $movie): void
    {
        $user = $this->authService->getAuthenticatedUser();

        if (!$this->movieRepository->isFavorited($user->getId(), $movie->getId())) {
            throw new BusinessException("Movie is not favorited.", 422, ErrorCodeEnum::MOVIE_NOT_FAVORITED->value);
        }

        $this->movieRepository->removeFavorite($user->getId(), $movie->getId());
    }
}
