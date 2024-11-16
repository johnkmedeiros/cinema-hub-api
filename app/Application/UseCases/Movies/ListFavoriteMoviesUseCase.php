<?php

namespace App\Application\UseCases\Movies;

use App\Application\DTOs\Movies\ListFavoriteMoviesDTO;
use App\Application\Resources\Movies\FavoriteMovieResource;
use App\Domain\Interfaces\Repositories\MovieRepositoryInterface;
use App\Domain\Interfaces\Services\AuthServiceInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ListFavoriteMoviesUseCase
{
    private MovieRepositoryInterface $movieRepository;
    private AuthServiceInterface $authService;

    public function __construct(MovieRepositoryInterface $movieRepository, AuthServiceInterface $authService)
    {
        $this->movieRepository = $movieRepository;
        $this->authService = $authService;
    }

    public function execute(ListFavoriteMoviesDTO $dto): AnonymousResourceCollection
    {
        $user = $this->authService->getAuthenticatedUser();
        $response = $this->movieRepository->getFavoritesByUserId($user->getId(), $dto->page);

        return FavoriteMovieResource::collection($response->movies)->additional([
            'meta' => [
                'current_page' => $response->currentPage ?? $dto->page,
                'total_pages' => $response->totalPages ?? 1,
                'total_results' => $response->totalResults ?? 0,
            ]
        ]);
    }
}
