<?php

namespace App\Application\Resources\Movies;

use Illuminate\Http\Resources\Json\JsonResource;

class GenericMessageResource extends JsonResource
{
    private string $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }

    public function toArray($request)
    {
        return [
            'message' => $this->message,
        ];
    }
}
