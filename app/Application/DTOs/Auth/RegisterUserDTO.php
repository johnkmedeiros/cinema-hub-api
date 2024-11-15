<?php

namespace App\Application\DTOs\Auth;

use App\Application\DTOs\BaseDTO;
use Illuminate\Http\Request;

class RegisterUserDTO extends BaseDTO
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
    ) {
    }

    public static function makeFromRequest(Request $request): self
    {
        return new self(
            name: $request->name,
            email: $request->email,
            password: $request->password
        );
    }
}
