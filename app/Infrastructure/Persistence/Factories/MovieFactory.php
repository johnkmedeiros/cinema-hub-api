<?php

namespace App\Infrastructure\Persistence\Factories;

use App\Domain\Entities\Movie;
use App\Domain\Entities\FavoriteMovie;
use App\Infrastructure\Persistence\Models\MovieEloquentModel;
use App\Infrastructure\Persistence\Models\FavoriteMovieEloquentModel;

class MovieFactory
{
    public static function create(array $data): Movie
    {
        $createdMovie = MovieEloquentModel::factory()->create($data);

        return new Movie(
            $createdMovie->external_id,
            $createdMovie->title,
            $createdMovie->overview,
            $createdMovie->release_date,
            $createdMovie->poster_path,
            $createdMovie->id,
        );
    }

    public static function createFavorite(array $data): FavoriteMovie
    {
        $createdFavoriteMovie = FavoriteMovieEloquentModel::factory()->create($data);

        return new FavoriteMovie(
            $createdFavoriteMovie->user_id,
            $createdFavoriteMovie->movie_id,
            $createdFavoriteMovie->id,
        );
    }
}
