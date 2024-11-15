<?php

namespace App\Domain\Interfaces\Repositories;

use App\Domain\Entities\Movie;

interface MovieRepositoryInterface
{
    public function findById(int $id): ?Movie;

    public function findByTheMovieDbId(int $theMovieDbId): ?Movie;

    public function create(Movie $movie): Movie;

    public function deleteById(int $id): void;
}
