<?php

namespace App\Infrastructure\Persistence\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Infrastructure\Persistence\Models\UserEloquentModel>
 */
class UserEloquentModelFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password')
        ];
    }

    public function modelName(): string
    {
        return \App\Infrastructure\Persistence\Models\UserEloquentModel::class;
    }
}
