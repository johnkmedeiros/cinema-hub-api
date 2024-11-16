<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\Entities\Movie;
use App\Domain\Entities\MovieFavorite;
use App\Domain\Interfaces\Repositories\MovieRepositoryInterface;
use App\Infrastructure\Persistence\Models\MovieEloquentModel;
use App\Infrastructure\Persistence\Models\MovieFavoriteEloquentModel;

class EloquentMovieRepository implements MovieRepositoryInterface
{
    public function findById(int $id): ?Movie
    {
        $movie = MovieEloquentModel::find($id);

        if (!$movie) {
            return null;
        }

        return new Movie(
            $movie->themoviedb_id,
            $movie->title,
            $movie->overview,
            $movie->release_date,
            $movie->poster_path,
            $movie->id,
        );
    }

    public function findByTheMovieDbId(int $theMovieDbId): ?Movie
    {
        $movie = MovieEloquentModel::where('themoviedb_id', $theMovieDbId)->first();

        if (!$movie) {
            return null;
        }

        return new Movie(
            $movie->themoviedb_id,
            $movie->title,
            $movie->overview,
            $movie->release_date,
            $movie->poster_path,
            $movie->id,
        );
    }

    public function create(Movie $movie): Movie
    {
        $model = new MovieEloquentModel([
            'themoviedb_id' => $movie->getTheMovieDbId(),
            'title' => $movie->getTitle(),
            'overview' => $movie->getOverview(),
            'release_date' => $movie->getReleaseDate(),
            'poster_path' => $movie->getPosterPath()
        ]);

        $model->save();

        $movie->setId($model->id);

        return $movie;
    }

    public function deleteById(int $id): void
    {
        MovieEloquentModel::where('id', $id)->delete();
    }


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
}
