<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
<<<<<<< HEAD
        //
=======
        // Register Dusk in non-production environments only if package exists
        if ($this->app->environment('local', 'testing') && class_exists(\Laravel\Dusk\DuskServiceProvider::class)) {
            $this->app->register(\Laravel\Dusk\DuskServiceProvider::class);
        }
>>>>>>> db38f69b140aa6aa9d8a9ee64e5cc0227e4ca099
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
