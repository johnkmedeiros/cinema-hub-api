<?php

namespace App\Domain\Interfaces\Repositories;

use App\Domain\Entities\User;

interface UserRepositoryInterface
{
    public function create(User $user): void;
    public function findByEmail(string $email): ?User;
}
