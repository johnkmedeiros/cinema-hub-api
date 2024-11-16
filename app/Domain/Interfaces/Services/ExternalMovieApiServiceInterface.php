<?php

namespace App\Domain\Interfaces\Services;

use App\Application\DTOs\Movies\Responses\SearchMoviesExternalApiResponse;
use App\Domain\Entities\Movie;

interface ExternalMovieApiServiceInterface
{
    public function searchMovies(string $query, int $page = 1): SearchMoviesExternalApiResponse;
    public function getMovie(int $movieId): ?Movie;
}
