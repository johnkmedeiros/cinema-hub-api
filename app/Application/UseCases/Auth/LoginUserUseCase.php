<?php

namespace App\Application\UseCases\Auth;

use App\Application\DTOs\Auth\LoginUserDTO;
use App\Application\Resources\Auth\AuthTokenResource;
use App\Domain\Interfaces\Repositories\UserRepositoryInterface;
use App\Infrastructure\Services\AuthService;
use Illuminate\Http\Resources\Json\JsonResource;

class LoginUserUseCase
{
    private UserRepositoryInterface $userRepository;
    private AuthService $authService;

    public function __construct(UserRepositoryInterface $userRepository, AuthService $authService)
    {
        $this->userRepository = $userRepository;
        $this->authService = $authService;
    }

    public function execute(LoginUserDTO $dto): JsonResource
    {
        $user = $this->userRepository->findByEmail($dto->email);

        $token = $this->authService->validatePasswordAndGetToken($user, $dto->password);

        $responseResource = (new AuthTokenResource($token));

        return $responseResource;
    }
}
