<?php

namespace App\Application\Enums;

enum ErrorCodeEnum: string
{
    case MOVIE_NOT_FOUND = 'MOVIE_NOT_FOUND';
    case MOVIE_ALREADY_FAVORITED = 'MOVIE_ALREADY_FAVORITED';
    case MOVIE_NOT_FAVORITED = 'MOVIE_NOT_FAVORITED';
}
