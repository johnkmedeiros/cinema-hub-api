<?php

namespace App\Application\UseCases\Movies;

use App\Application\DTOs\Movies\AddMovieToFavoritesDTO;
use App\Application\Resources\Movies\GenericMessageResource;
use App\Domain\Entities\Movie;
use App\Domain\Interfaces\Repositories\MovieRepositoryInterface;
use App\Infrastructure\Services\Auth\AuthService;
use App\Infrastructure\Services\Movies\TheMovieDbService;
use Illuminate\Http\Resources\Json\JsonResource;

class AddMovieToFavoritesUseCase
{
    private TheMovieDbService $theMovieDbService;
    private MovieRepositoryInterface $movieRepository;
    private AuthService $authService;

    public function __construct(TheMovieDbService $theMovieDbService, MovieRepositoryInterface $movieRepository, AuthService $authService)
    {
        $this->theMovieDbService = $theMovieDbService;
        $this->movieRepository = $movieRepository;
        $this->authService = $authService;
    }

    public function execute(AddMovieToFavoritesDTO $dto): JsonResource
    {
        $movie = $this->getMovie($dto);
        $this->addToFavorites($movie);

        return new GenericMessageResource("All right, movie #{$movie->getTheMovieDbId()} is in your favorites list.");
    }

    private function getMovie(AddMovieToFavoritesDTO $dto): Movie
    {
        $movie = $this->movieRepository->findByTheMovieDbId($dto->theMovieDbId);

        if (!$movie) {
            $response = $this->theMovieDbService->getMovie($dto->theMovieDbId);

            $movie = new Movie(
                theMovieDbId: $response['id'],
                title: $response['title'],
                overview: $response['overview'],
                releaseDate: $response['release_date'],
                posterPath: $response['poster_path']
            );

            $movie = $this->movieRepository->create($movie);
        }

        return $movie;
    }

    private function addToFavorites(Movie $movie): void
    {
        $user = $this->authService->getAuthenticatedUser();

        if ($this->movieRepository->isFavorite($user->getId(), $movie->getId())) {
            return;
        }

        $this->movieRepository->addFavorite($user->getId(), $movie->getId());
    }
}
