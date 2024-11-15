<?php

namespace App\Application\Resources\Movies;

use Illuminate\Http\Resources\Json\JsonResource;

class SearchMoviesResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'themoviedb_id' => $this->getTheMovieDbId(),
            'title' => $this->getTitle(),
            'overview' => $this->getOverview(),
            'release_date' => $this->getReleaseDate(),
            'poster_path' => $this->getPosterPath(),
        ];
    }
}
