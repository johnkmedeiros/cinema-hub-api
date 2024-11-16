<?php

namespace App\Infrastructure\Services\Auth;

use App\Domain\Entities\User;
use App\Domain\Interfaces\Services\AuthServiceInterface;
use App\Infrastructure\Persistence\Models\UserEloquentModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService implements AuthServiceInterface
{
    public function generateToken(User $user): string
    {
        $userModel = UserEloquentModel::where('email', $user->getEmail())->firstOrFail();

        return $userModel->createToken('auth_token')->plainTextToken;
    }

    public function validatePasswordAndGetToken(?User $user, string $password): string
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

    public function getAuthenticatedUser(): User
    {
        $userModel = auth()->user();

        return new User($userModel->name, $userModel->email, $userModel->password, $userModel->id);
    }
}
