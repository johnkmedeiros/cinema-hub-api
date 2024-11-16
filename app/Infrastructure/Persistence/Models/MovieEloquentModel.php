<?php

namespace App\Infrastructure\Persistence\Models;

use App\Infrastructure\Persistence\Factories\MovieEloquentModelFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovieEloquentModel extends Model
{
    use HasFactory;

    protected $table = 'movies';

    protected $fillable = [
        'themoviedb_id',
        'title',
        'overview',
        'release_date',
        'poster_path',
    ];

    protected $casts = [
        'release_date' => 'datetime',
    ];

    protected static function newFactory()
    {
        return MovieEloquentModelFactory::new();
    }

    /* ╔═════════════════════════ Relationships ═════════════════════╗ */


    public function favorites()
    {
        return $this->hasMany(MovieFavoriteEloquentModel::class, 'movie_id');
    }
}
