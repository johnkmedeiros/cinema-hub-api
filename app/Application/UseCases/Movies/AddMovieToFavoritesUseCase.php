<?php

namespace App\Application\UseCases\Movies;

use App\Application\DTOs\Movies\AddMovieToFavoritesDTO;
use App\Application\Enums\ErrorCodeEnum;
use App\Application\Exceptions\BusinessException;
use App\Application\Resources\Movies\GenericMessageResource;
use App\Domain\Entities\Movie;
use App\Domain\Interfaces\Repositories\MovieRepositoryInterface;
use App\Domain\Interfaces\Services\AuthServiceInterface;
use App\Domain\Interfaces\Services\ExternalMovieApiServiceInterface;
use Illuminate\Http\Resources\Json\JsonResource;

class AddMovieToFavoritesUseCase
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

    public function execute(AddMovieToFavoritesDTO $dto): JsonResource
    {
        $movie = $this->getMovie($dto);
        $this->addToFavorites($movie);

        return new GenericMessageResource("Movie #{$movie->getExternalId()} added to your favorite list.");
    }

    private function getMovie(AddMovieToFavoritesDTO $dto): Movie
    {
        $movie = $this->movieRepository->findByExternalId($dto->externalId);

        if (!$movie) {
            $movie = $this->externalMovieApiService->getMovie($dto->externalId);

            if (!$movie) {
                throw new BusinessException("Movie not found.", 404, ErrorCodeEnum::MOVIE_NOT_FOUND->value);
            }

            $movie = $this->movieRepository->create($movie);
        }

        return $movie;
    }

    private function addToFavorites(Movie $movie): void
    {
        $user = $this->authService->getAuthenticatedUser();

        if ($this->movieRepository->isFavorited($user->getId(), $movie->getId())) {
            throw new BusinessException("Movie is already favorited.", 422, ErrorCodeEnum::MOVIE_ALREADY_FAVORITED->value);
        }

        $this->movieRepository->addFavorite($user->getId(), $movie->getId());
    }
}
