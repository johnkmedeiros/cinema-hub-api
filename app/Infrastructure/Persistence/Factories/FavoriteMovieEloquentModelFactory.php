<?php

namespace App\Infrastructure\Persistence\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Infrastructure\Persistence\Models\FavoriteMovieEloquentModel>
 */
class FavoriteMovieEloquentModelFactory extends Factory
{
    public function definition(): array
    {
        return [
        ];
    }

    public function modelName(): string
    {
        return \App\Infrastructure\Persistence\Models\FavoriteMovieEloquentModel::class;
    }
}
