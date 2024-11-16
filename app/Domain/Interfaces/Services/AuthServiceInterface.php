<?php

namespace App\Domain\Interfaces\Services;

use App\Domain\Entities\User;

interface AuthServiceInterface
{
    public function generateToken(User $user): string;
    public function validatePasswordAndGetToken(?User $user, string $password): string;
    public function getAuthenticatedUser(): User;
}
