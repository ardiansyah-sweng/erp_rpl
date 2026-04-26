<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class SyncTestDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:sync-test {--fresh : Drop and recreate test database} {--data-only : Only sync data, not structure}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync production database to test database for testing purposes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Starting database synchronization...');
        
        // Get database configurations
        $prodDb = Config::get('database.connections.mysql.database');
        $testDb = $prodDb . '_test';
        $host = Config::get('database.connections.mysql.host');
        $username = Config::get('database.connections.mysql.username');
        $password = Config::get('database.connections.mysql.password');
        
        $this->info("📊 Source: {$prodDb}");
        $this->info("🎯 Target: {$testDb}");
        
        try {
            // Step 1: Create test database if not exists
            $this->createTestDatabase($testDb);
            
            // Step 2: Sync based on options
            if ($this->option('fresh')) {
                $this->syncFreshDatabase($prodDb, $testDb);
            } elseif ($this->option('data-only')) {
                $this->syncDataOnly($prodDb, $testDb);
            } else {
                $this->syncFullDatabase($prodDb, $testDb);
            }
            
            $this->newLine();
            $this->info('✅ Database synchronization completed successfully!');
            $this->info("🎯 Test database '{$testDb}' is ready for testing.");
            
        } catch (\Exception $e) {
            $this->error('❌ Synchronization failed: ' . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
    
    /**
     * Create test database if not exists
     */
    private function createTestDatabase($testDb)
    {
        $this->info("🔨 Creating test database '{$testDb}' if not exists...");
        
        DB::statement("CREATE DATABASE IF NOT EXISTS `{$testDb}`");
        
        $this->info("✅ Test database created/verified.");
    }
    
    /**
     * Sync full database (structure + data)
     */
    private function syncFullDatabase($prodDb, $testDb)
    {
        $this->info("📋 Syncing full database (structure + data)...");
        
        // Get all tables from production
        $tables = DB::select("SHOW TABLES FROM `{$prodDb}`");
        $tableKey = "Tables_in_{$prodDb}";
        
        $this->info("📊 Found " . count($tables) . " tables to sync.");
        
        $bar = $this->output->createProgressBar(count($tables));
        $bar->start();
        
        foreach ($tables as $table) {
            $tableName = $table->$tableKey;
            
            // Drop table in test db if exists
            DB::statement("DROP TABLE IF EXISTS `{$testDb}`.`{$tableName}`");
            
            // Create table structure
            $createTable = DB::select("SHOW CREATE TABLE `{$prodDb}`.`{$tableName}`")[0];
            $createSql = str_replace(
                "CREATE TABLE `{$tableName}`",
                "CREATE TABLE `{$testDb}`.`{$tableName}`",
                $createTable->{'Create Table'}
            );
            DB::statement($createSql);
            
            // Copy data
            DB::statement("INSERT INTO `{$testDb}`.`{$tableName}` SELECT * FROM `{$prodDb}`.`{$tableName}`");
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine();
    }
    
    /**
     * Sync fresh database (drop and recreate)
     */
    private function syncFreshDatabase($prodDb, $testDb)
    {
        $this->warn("⚠️  Fresh sync will DROP existing test database!");
        
        if (!$this->confirm('Are you sure you want to continue?')) {
            $this->info('❌ Operation cancelled.');
            return;
        }
        
        $this->info("🗑️  Dropping test database...");
        DB::statement("DROP DATABASE IF EXISTS `{$testDb}`");
        
        $this->info("🔨 Creating fresh test database...");
        DB::statement("CREATE DATABASE `{$testDb}`");
        
        $this->syncFullDatabase($prodDb, $testDb);
    }
    
    /**
     * Sync data only (keep existing structure)
     */
    private function syncDataOnly($prodDb, $testDb)
    {
        $this->info("📊 Syncing data only (preserving structure)...");
        
        // Get all tables from production
        $tables = DB::select("SHOW TABLES FROM `{$prodDb}`");
        $tableKey = "Tables_in_{$prodDb}";
        
        $bar = $this->output->createProgressBar(count($tables));
        $bar->start();
        
        foreach ($tables as $table) {
            $tableName = $table->$tableKey;
            
            // Check if table exists in test db
            $testTableExists = DB::select("SHOW TABLES FROM `{$testDb}` LIKE '{$tableName}'");
            
            if (empty($testTableExists)) {
                $this->warn("⚠️  Table '{$tableName}' not found in test database, skipping...");
                continue;
            }
            
            // Clear existing data
            DB::statement("DELETE FROM `{$testDb}`.`{$tableName}`");
            
            // Copy data
            DB::statement("INSERT INTO `{$testDb}`.`{$tableName}` SELECT * FROM `{$prodDb}`.`{$tableName}`");
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine();
    }
}
