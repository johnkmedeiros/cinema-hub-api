<?php

namespace App\Domain\Entities;

class Movie
{
    private ?int $id;
    private int $theMovieDbId;
    private string $title;
    private ?string $overview;
    private string $releaseDate;
    private ?string $posterPath;

    public function __construct(
        int $theMovieDbId,
        string $title,
        ?string $overview,
        string $releaseDate,
        ?string $posterPath,
        ?int $id = null,
    ) {
        $this->id = $id;
        $this->theMovieDbId = $theMovieDbId;
        $this->title = $title;
        $this->overview = $overview;
        $this->releaseDate = $releaseDate;
        $this->posterPath = $posterPath;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getTheMovieDbId(): int
    {
        return $this->theMovieDbId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getOverview(): ?string
    {
        return $this->overview;
    }

    public function getReleaseDate(): string
    {
        return $this->releaseDate;
    }

    public function getPosterPath(): ?string
    {
        return $this->posterPath;
    }
}
