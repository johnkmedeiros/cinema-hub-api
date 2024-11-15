<?php

namespace App\Domain\Entities;

class MovieFavorite
{
    private int $userId;
    private int $movieId;
    private ?int $id;

    public function __construct(int $userId, int $movieId, ?int $id = null)
    {
        $this->userId = $userId;
        $this->movieId = $movieId;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getMovieId(): int
    {
        return $this->movieId;
    }
}
