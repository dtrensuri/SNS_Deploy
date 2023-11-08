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
        if (env('APP_ENV') == 'production') {
            if (env('APP_ENV') == 'production') {
                if ($this->app->environment('production')) {
                    \URL::forceScheme('https');
                }
            }
        } else {

        }
    }
}
