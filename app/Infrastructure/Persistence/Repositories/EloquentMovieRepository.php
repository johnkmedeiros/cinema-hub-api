<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\Entities\Movie;
use App\Domain\Interfaces\Repositories\MovieRepositoryInterface;
use App\Infrastructure\Persistence\Models\MovieEloquentModel;

class EloquentMovieRepository implements MovieRepositoryInterface
{
    public function findById(int $id): ?Movie
    {
        $movie = MovieEloquentModel::find($id);

        if (!$movie) {
            return null;
        }

        return new Movie(
            $movie->id,
            $movie->themoviedb_id,
            $movie->title,
            $movie->overview,
            $movie->release_date,
            $movie->poster_path
        );
    }

    public function findByTheMovieDbId(int $theMovieDbId): ?Movie
    {
        $movie = MovieEloquentModel::where('themoviedb_id', $theMovieDbId)->first();

        if (!$movie) {
            return null;
        }

        return new Movie(
            $movie->id,
            $movie->themoviedb_id,
            $movie->title,
            $movie->overview,
            $movie->release_date,
            $movie->poster_path
        );
    }

    public function create(Movie $movie): Movie
    {
        $model = new MovieEloquentModel([
            'themoviedb_id' => $movie->getThemoviedbId(),
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
}
