<?php

namespace App\Infrastructure\RequestValidators\Movies;

use Illuminate\Foundation\Http\FormRequest;

class ListFavoriteMoviesRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'page' => 'integer|min:1|nullable'
        ];
    }
}
