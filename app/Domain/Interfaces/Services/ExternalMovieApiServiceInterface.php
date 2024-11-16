<?php

namespace App\Domain\Interfaces\Services;

interface ExternalMovieApiServiceInterface
{
    public function searchMovies(string $query, int $page = 1): ?array;
    public function getMovie(int $movieId): ?array;
}
