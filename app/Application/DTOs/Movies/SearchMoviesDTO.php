<?php

namespace App\Application\DTOs\Movies;

use App\Application\DTOs\BaseDTO;
use Illuminate\Http\Request;

class SearchMoviesDTO extends BaseDTO
{
    public function __construct(
        public string $query,
        public int $page = 1,
    ) {
    }

    public static function makeFromRequest(Request $request): self
    {
        return new self(
            query: $request->input('query'),
            page: $request->input('page', 1),
        );
    }
}
