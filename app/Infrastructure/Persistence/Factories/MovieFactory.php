<?php

namespace App\Infrastructure\Persistence\Factories;

use App\Domain\Entities\Movie;
use App\Domain\Entities\MovieFavorite;
use App\Infrastructure\Persistence\Models\MovieEloquentModel;
use App\Infrastructure\Persistence\Models\MovieFavoriteEloquentModel;

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

    public static function createFavorite(array $data): MovieFavorite
    {
        $createdMovieFavorite = MovieFavoriteEloquentModel::factory()->create($data);

        return new MovieFavorite(
            $createdMovieFavorite->user_id,
            $createdMovieFavorite->movie_id,
            $createdMovieFavorite->id,
        );
    }
}
