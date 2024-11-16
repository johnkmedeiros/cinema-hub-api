<?php

namespace App\Domain\Entities;

class Movie
{
    private ?int $id;
    private int $externalId;
    private string $title;
    private ?string $overview;
    private string $releaseDate;
    private ?string $posterPath;

    public function __construct(
        int $externalId,
        string $title,
        ?string $overview,
        string $releaseDate,
        ?string $posterPath,
        ?int $id = null,
    ) {
        $this->id = $id;
        $this->externalId = $externalId;
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

    public function getExternalId(): int
    {
        return $this->externalId;
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
