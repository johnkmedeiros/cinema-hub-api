<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Application\DTOs\Movies\Responses\PaginatedFavoriteMoviesRepositoryResponse;
use App\Domain\Entities\Movie;
use App\Domain\Entities\FavoriteMovie;
use App\Domain\Interfaces\Repositories\MovieRepositoryInterface;
use App\Infrastructure\Persistence\Models\MovieEloquentModel;
use App\Infrastructure\Persistence\Models\FavoriteMovieEloquentModel;

class EloquentMovieRepository implements MovieRepositoryInterface
{
    public function findById(int $id): ?Movie
    {
        $movie = MovieEloquentModel::find($id);

        if (!$movie) {
            return null;
        }

        return new Movie(
            $movie->external_id,
            $movie->title,
            $movie->overview,
            $movie->release_date,
            $movie->poster_path,
            $movie->id,
        );
    }

    public function findByExternalId(int $externalId): ?Movie
    {
        $movie = MovieEloquentModel::where('external_id', $externalId)->first();

        if (!$movie) {
            return null;
        }

        return new Movie(
            $movie->external_id,
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
            'external_id' => $movie->getExternalId(),
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


    public function addFavorite(int $userId, int $movieId): FavoriteMovie
    {
        $favoriteModel = FavoriteMovieEloquentModel::firstOrNew([
            'user_id' => $userId,
            'movie_id' => $movieId
        ]);

        $favoriteModel->save();

        return new FavoriteMovie($favoriteModel->user_id, $favoriteModel->movie_id, $favoriteModel->id);
    }

    public function removeFavorite(int $userId, int $movieId): void
    {
        $favoriteModel = FavoriteMovieEloquentModel::where('user_id', $userId)
            ->where('movie_id', $movieId)
            ->firstOrFail();

        $favoriteModel->delete();
    }


    public function getFavoritesByUserId(int $userId, int $page = 1): PaginatedFavoriteMoviesRepositoryResponse
    {
        $favoriteModels = FavoriteMovieEloquentModel::where('user_id', $userId)
            ->with('movie')
            ->paginate(30, ['*'], 'page', $page);

        $movies = $favoriteModels->map(function ($favorite) {
            $movie = $favorite->movie;

            return new Movie(
                $movie->external_id,
                $movie->title,
                $movie->overview,
                $movie->release_date,
                $movie->poster_path,
                $movie->id,
            );
        })->toArray();

        return new PaginatedFavoriteMoviesRepositoryResponse(
            $movies,
            $favoriteModels->currentPage(),
            $favoriteModels->lastPage(),
            $favoriteModels->total()
        );
    }

    public function isFavorited(int $userId, int $movieId): bool
    {
        return FavoriteMovieEloquentModel::where('user_id', $userId)
            ->where('movie_id', $movieId)
            ->exists();
    }
}
