<?php

namespace App\Application\DTOs\Movies;

use App\Application\DTOs\BaseDTO;
use Illuminate\Http\Request;

class ListFavoriteMoviesDTO extends BaseDTO
{
    public function __construct(
        public int $page = 1,
    ) {
    }

    public static function makeFromRequest(Request $request): self
    {
        return new self(
            page: $request->input('page', 1),
        );
    }
}
