<?php

namespace App\Infrastructure\Providers;

use App\Domain\Interfaces\Services\AuthServiceInterface;
use App\Infrastructure\Services\Auth\AuthService;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(AuthServiceInterface::class, AuthService::class);
    }

    public function boot()
    {
    }
}
