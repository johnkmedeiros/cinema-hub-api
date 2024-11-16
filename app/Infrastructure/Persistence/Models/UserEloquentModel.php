<?php

namespace App\Infrastructure\Persistence\Models;

use App\Infrastructure\Persistence\Factories\UserEloquentModelFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class UserEloquentModel extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected static function newFactory()
    {
        return UserEloquentModelFactory::new();
    }


    /* ╔═════════════════════════ Relationships ═════════════════════╗ */


    public function favorites()
    {
        return $this->hasMany(FavoriteMovieEloquentModel::class, 'movie_id');
    }
}
