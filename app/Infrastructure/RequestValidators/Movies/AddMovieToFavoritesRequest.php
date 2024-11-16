<?php

namespace App\Infrastructure\RequestValidators\Movies;

use Illuminate\Foundation\Http\FormRequest;

class AddMovieToFavoritesRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'themoviedb_id' => 'integer|required'
        ];
    }
}
