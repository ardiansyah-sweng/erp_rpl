<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class CreateDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new database based on the .env configuration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Ambil nama database dari .env
        $dbName = env('DB_DATABASE');

        // Validasi nama database
        if (empty($dbName)) {
            $this->error('Database name is not defined in .env file!');
            return;
        }

        // Ubah koneksi database sementara untuk membuat database
        Config::set('database.connections.mysql.database', null);

        try {
            // Hapus database jika sudah ada
            $this->info("Checking if database '$dbName' exists...");
            DB::statement("DROP DATABASE IF EXISTS `$dbName`");
            $this->info("Database '$dbName' has been dropped.");
                        
            // Buat database
            DB::statement("CREATE DATABASE IF NOT EXISTS `$dbName`");
            $this->info("Database '$dbName' created successfully!");
        } catch (\Exception $e) {
            $this->error("Failed to create database '$dbName': " . $e->getMessage());
        } finally {
            // Kembalikan konfigurasi koneksi database ke nilai awal
            Config::set('database.connections.mysql.database', $dbName);
        }
    }
}
