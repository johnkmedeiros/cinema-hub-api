<?php

namespace App\Application\DTOs\Movies;

use App\Application\DTOs\BaseDTO;
use Illuminate\Http\Request;

class AddMovieToFavoritesDTO extends BaseDTO
{
    public function __construct(
        public int $theMovieDbId,
    ) {
    }

    public static function makeFromRequest(Request $request): self
    {
        return new self(
            theMovieDbId: $request->input('themoviedb_id'),
        );
    }
}
