<?php

namespace App\Domain\Interfaces\Repositories;

use App\Domain\Entities\MovieFavorite;

interface FavoriteRepositoryInterface
{
    public function addFavorite(int $userId, int $movieId): MovieFavorite;

    public function removeFavorite(int $userId, int $movieId): void;

    public function getFavoritesByUserId(int $userId): array;

    public function isFavorite(int $userId, int $movieId): bool;

    public function deleteFavoritesByUserId(int $userId): void;
}
