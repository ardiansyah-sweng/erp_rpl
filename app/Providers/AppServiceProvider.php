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
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
