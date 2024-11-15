<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\Entities\MovieFavorite;
use App\Domain\Interfaces\Repositories\FavoriteRepositoryInterface;
use App\Infrastructure\Persistence\Models\MovieFavoriteEloquentModel;

class EloquentMovieFavoriteRepository implements FavoriteRepositoryInterface
{
    public function addFavorite(int $userId, int $movieId): MovieFavorite
    {
        $favoriteModel = MovieFavoriteEloquentModel::firstOrNew([
            'user_id' => $userId,
            'movie_id' => $movieId
        ]);

        $favoriteModel->save();

        return new MovieFavorite($favoriteModel->user_id, $favoriteModel->movie_id, $favoriteModel->id);
    }

    public function removeFavorite(int $userId, int $movieId): void
    {
        $favoriteModel = MovieFavoriteEloquentModel::where('user_id', $userId)
            ->where('movie_id', $movieId)
            ->firstOrFail();

        $favoriteModel->delete();
    }

    public function getFavoritesByUserId(int $userId): array
    {
        $favoriteModels = MovieFavoriteEloquentModel::where('user_id', $userId)->get();

        return $favoriteModels->map(function ($favoriteModel) {
            return new MovieFavorite($favoriteModel->user_id, $favoriteModel->movie_id, $favoriteModel->id);
        })->toArray();
    }

    public function isFavorite(int $userId, int $movieId): bool
    {
        return MovieFavoriteEloquentModel::where('user_id', $userId)
            ->where('movie_id', $movieId)
            ->exists();
    }

    public function deleteFavoritesByUserId(int $userId): void
    {
        MovieFavoriteEloquentModel::where('user_id', $userId)->delete();
    }
}
