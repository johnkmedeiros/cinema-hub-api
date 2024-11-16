<?php

namespace App\Application\DTOs\Movies\Responses;

use App\Domain\Entities\Movie;

class SearchMoviesExternalApiResponse
{
    public function __construct(
        public array $movies = [],
        public int $currentPage = 1,
        public int $totalPages = 1,
        public int $totalResults = 0
    ) {
        $this->movies = array_map(function ($movie) {
            if (!($movie instanceof Movie)) {
                throw new \InvalidArgumentException("Invalid movie object.", 500);
            }
            return $movie;
        }, $movies);
    }
}
