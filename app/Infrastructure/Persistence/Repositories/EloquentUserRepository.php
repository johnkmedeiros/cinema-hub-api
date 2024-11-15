<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\Entities\User;
use App\Domain\Interfaces\Repositories\UserRepositoryInterface;
use App\Infrastructure\Persistence\Models\UserEloquentModel;

class EloquentUserRepository implements UserRepositoryInterface
{
    public function create(User $user): void
    {
        UserEloquentModel::create([
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
        ]);
    }

    public function findByEmail(string $email): ?User
    {
        $user = UserEloquentModel::where('email', $email)->first();

        if (!$user) {
            return null;
        }

        return new User(
            $user->name,
            $user->email,
            $user->password
        );
    }
}
