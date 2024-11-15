<?php

namespace App\Application\UseCases\Auth;

use App\Application\DTOs\Auth\LoginUserDTO;
use App\Application\Resources\Auth\AuthTokenResource;
use App\Domain\Interfaces\Repositories\UserRepositoryInterface;
use App\Infrastructure\Services\AuthService;

class LoginUserUseCase
{
    private UserRepositoryInterface $userRepository;
    private AuthService $authService;

    public function __construct(UserRepositoryInterface $userRepository, AuthService $authService)
    {
        $this->userRepository = $userRepository;
        $this->authService = $authService;
    }

    public function execute(LoginUserDTO $dto): array
    {
        $user = $this->userRepository->findByEmail($dto->email);

        $token = $this->authService->validatePasswordAndGetToken($user, $dto->password);

        $responseResource = (new AuthTokenResource($token))->resolve();

        return $responseResource;
    }
}
