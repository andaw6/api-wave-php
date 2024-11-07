<?php

namespace App\Providers;

use App\Services\AuthentificationJwtService;
use App\Services\AuthentificationSanctumSerivce;
use Illuminate\Support\ServiceProvider;
use App\Services\interface\AuthentificationServiceInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->bind(AuthentificationServiceInterface::class, AuthentificationJwtService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
