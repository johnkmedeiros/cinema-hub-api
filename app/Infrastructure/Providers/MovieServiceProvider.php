<?php

namespace App\Infrastructure\Providers;

use App\Domain\Interfaces\Repositories\MovieRepositoryInterface;
use App\Infrastructure\Persistence\Repositories\EloquentMovieRepository;
use Illuminate\Support\ServiceProvider;

class MovieServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(MovieRepositoryInterface::class, EloquentMovieRepository::class);
    }

    public function boot()
    {
    }
}
