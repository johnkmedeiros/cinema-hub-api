<?php

namespace App\Infrastructure\Http\Controllers\Auth;

use App\Application\DTOs\Auth\LoginUserDTO;
use App\Application\DTOs\Auth\RegisterUserDTO;
use App\Application\UseCases\Auth\LoginUserUseCase;
use App\Application\UseCases\Auth\RegisterUserUseCase;
use App\Infrastructure\RequestValidators\Auth\LoginUserRequest;
use App\Infrastructure\RequestValidators\Auth\RegisterUserRequest;

class AuthController
{
    public function __construct(private RegisterUserUseCase $registerUserUseCase, private LoginUserUseCase $loginUserUseCase)
    {
    }

    public function register(RegisterUserRequest $request)
    {
        $userRegisterDTO = RegisterUserDTO::makeFromRequest($request);
        $response = $this->registerUserUseCase->execute($userRegisterDTO);

        return response()->json($response, 201);
    }

    public function login(LoginUserRequest $request)
    {
        $userLoginDTO = LoginUserDTO::makeFromRequest($request);
        $response = $this->loginUserUseCase->execute($userLoginDTO);

        return response()->json($response, 200);
    }
}
