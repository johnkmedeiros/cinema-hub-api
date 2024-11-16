<?php

namespace App\Application\UseCases\Auth;

use App\Application\DTOs\Auth\RegisterUserDTO;
use App\Application\Resources\Auth\AuthTokenResource;
use App\Domain\Entities\User;
use App\Domain\Interfaces\Repositories\UserRepositoryInterface;
use App\Infrastructure\Services\Auth\AuthService;
use Illuminate\Http\Resources\Json\JsonResource;

class RegisterUserUseCase
{
    private UserRepositoryInterface $userRepository;
    private AuthService $authService;

    public function __construct(UserRepositoryInterface $userRepository, AuthService $authService)
    {
        $this->userRepository = $userRepository;
        $this->authService = $authService;
    }

    public function execute(RegisterUserDTO $dto): JsonResource
    {
        $user = new User($dto->name, $dto->email, bcrypt($dto->password));

        $this->userRepository->create($user);

        $token = $this->authService->generateToken($user);

        return new AuthTokenResource($token);
    }
}
