<?php

namespace App\Domain\Interfaces\Repositories;

use App\Domain\Entities\Movie;
use App\Domain\Entities\MovieFavorite;

interface MovieRepositoryInterface
{
    public function findById(int $id): ?Movie;

    public function findByTheMovieDbId(int $theMovieDbId): ?Movie;

    public function create(Movie $movie): Movie;

    public function deleteById(int $id): void;

    public function addFavorite(int $userId, int $movieId): MovieFavorite;

    public function removeFavorite(int $userId, int $movieId): void;

    public function getFavoritesByUserId(int $userId): array;

    public function isFavorited(int $userId, int $movieId): bool;
}
