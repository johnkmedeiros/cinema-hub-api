<?php

namespace App\Infrastructure\RequestValidators\Movies;

use Illuminate\Foundation\Http\FormRequest;

class SearchMoviesRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'query' => 'required|string',
            'page' => 'integer|min:1|nullable'
        ];
    }
}
