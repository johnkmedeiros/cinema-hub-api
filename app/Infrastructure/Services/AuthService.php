<?php

namespace App\Infrastructure\Services;

use App\Domain\Entities\User;
use App\Infrastructure\Persistence\Models\UserEloquentModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function generateToken(User $user): string
    {
        $userModel = UserEloquentModel::where('email', $user->getEmail())->firstOrFail();

        return $userModel->createToken('auth_token')->plainTextToken;
    }

    public function validatePasswordAndGetToken(?User $user, string $password)
    {
        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
        
        $userModel = UserEloquentModel::where('email', $user->getEmail())->first();

        if (!$userModel || !Hash::check($password, $userModel->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $userModel->createToken('api_token')->plainTextToken;

        return $token;
    }
}
