<?php

namespace App\Domain\Interfaces\Repositories;

use App\Application\DTOs\Movies\Responses\PaginatedFavoriteMoviesRepositoryResponse;
use App\Domain\Entities\FavoriteMovie;
use App\Domain\Entities\Movie;

interface MovieRepositoryInterface
{
    public function findById(int $id): ?Movie;

    public function findByExternalId(int $externalId): ?Movie;

    public function create(Movie $movie): Movie;

    public function deleteById(int $id): void;

    public function addFavorite(int $userId, int $movieId): FavoriteMovie;

    public function removeFavorite(int $userId, int $movieId): void;

    public function getFavoritesByUserId(int $userId, int $page = 1): PaginatedFavoriteMoviesRepositoryResponse;

    public function isFavorited(int $userId, int $movieId): bool;
}
