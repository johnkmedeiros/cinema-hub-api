<?php

namespace App\Application\Resources\Movies;

use Illuminate\Http\Resources\Json\JsonResource;

class FavoriteMovieResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'external_id' => $this->getExternalId(),
            'title' => $this->getTitle(),
            'overview' => $this->getOverview(),
            'release_date' => $this->getReleaseDate(),
            'poster_path' => $this->getPosterPath(),
        ];
    }
}
