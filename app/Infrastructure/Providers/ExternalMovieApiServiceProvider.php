<?php

namespace App\Infrastructure\Providers;

use App\Domain\Interfaces\Services\ExternalMovieApiServiceInterface;
use App\Infrastructure\Services\Movies\TheMovieDbService;
use Illuminate\Support\ServiceProvider;

class ExternalMovieApiServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(ExternalMovieApiServiceInterface::class, TheMovieDbService::class);
    }

    public function boot()
    {
    }
}
