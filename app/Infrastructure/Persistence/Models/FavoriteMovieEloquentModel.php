<?php

namespace App\Infrastructure\Persistence\Models;

use App\Infrastructure\Persistence\Factories\FavoriteMovieEloquentModelFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavoriteMovieEloquentModel extends Model
{
    use HasFactory;

    protected $table = 'user_favorite_movies';

    protected $fillable = [
        'user_id',
        'movie_id',
    ];

    protected static function newFactory()
    {
        return FavoriteMovieEloquentModelFactory::new();
    }

    /* ╔═════════════════════════ Relationships ═════════════════════╗ */


    public function user()
    {
        return $this->belongsTo(UserEloquentModel::class, 'user_id');
    }

    public function movie()
    {
        return $this->belongsTo(MovieEloquentModel::class, 'movie_id');
    }
}
