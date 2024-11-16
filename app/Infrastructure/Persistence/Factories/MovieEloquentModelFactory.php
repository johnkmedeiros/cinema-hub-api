<?php

namespace App\Infrastructure\Persistence\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Infrastructure\Persistence\Models\MovieEloquentModel>
 */
class MovieEloquentModelFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->name(),
            'overview' => fake()->sentence(),
            'release_date' => fake()->date(),
            'poster_path' => fake()->imageUrl(),
        ];
    }

    public function modelName(): string
    {
        return \App\Infrastructure\Persistence\Models\MovieEloquentModel::class;
    }
}
