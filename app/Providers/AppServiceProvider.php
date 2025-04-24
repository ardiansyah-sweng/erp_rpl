<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Aktifkan log query untuk debug
        DB::enableQueryLog();

        // Contoh: dump semua query yang dijalankan saat request selesai
        app()->terminating(function () {
            foreach (DB::getQueryLog() as $query) {
                \Log::info('SQL', $query);
            }
            
        });foreach (DB::getQueryLog() as $query) {
    \Log::info('SQL', $query);
}

    }
}
