<?php

namespace App\Infrastructure\Persistence\Factories;

use App\Domain\Entities\User;
use App\Infrastructure\Persistence\Models\UserEloquentModel;

class UserFactory
{
    public static function create(array $data): User
    {
        $createdUser = UserEloquentModel::factory()->create($data);

        return new User(
            $createdUser->name,
            $createdUser->email,
            $createdUser->password,
            $createdUser->id,
        );
    }
}
