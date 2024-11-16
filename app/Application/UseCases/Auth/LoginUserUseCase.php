<?php

namespace App\Application\UseCases\Auth;

use App\Application\DTOs\Auth\LoginUserDTO;
use App\Application\Resources\Auth\AuthTokenResource;
use App\Domain\Interfaces\Repositories\UserRepositoryInterface;
use App\Domain\Interfaces\Services\AuthServiceInterface;
use Illuminate\Http\Resources\Json\JsonResource;

class LoginUserUseCase
{
    private UserRepositoryInterface $userRepository;
    private AuthServiceInterface $authService;

    public function __construct(UserRepositoryInterface $userRepository, AuthServiceInterface $authService)
    {
        $this->userRepository = $userRepository;
        $this->authService = $authService;
    }

    public function execute(LoginUserDTO $dto): JsonResource
    {
        $user = $this->userRepository->findByEmail($dto->email);

        $token = $this->authService->validatePasswordAndGetToken($user, $dto->password);

        return new AuthTokenResource($token);
    }
}
