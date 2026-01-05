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
        // Hapus atau beri komentar pada baris di bawah ini:
        /* if ($this->app->environment('local', 'testing')) {
            $this->app->register(\Laravel\Dusk\DuskServiceProvider::class);
        }
        */
=======
        // Register Dusk in non-production environments only if package exists
        if ($this->app->environment('local', 'testing') && class_exists(\Laravel\Dusk\DuskServiceProvider::class)) {
            $this->app->register(\Laravel\Dusk\DuskServiceProvider::class);
        }
>>>>>>> b0a6f6dd058f0e9f6847c11eb652e8aa2532deb4
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
