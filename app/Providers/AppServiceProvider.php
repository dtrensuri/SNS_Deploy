<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Auth\Guard\CustomAuthGuard;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
