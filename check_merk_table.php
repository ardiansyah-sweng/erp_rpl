<?php

// Test untuk mengecek struktur tabel merk
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    echo "=== CHECKING MERK TABLE STRUCTURE ===\n\n";
    
    // Check if table exists
    $tableName = config('db_tables.merk');
    echo "Table name: {$tableName}\n";
    
    // Get columns
    $columns = DB::select("SHOW COLUMNS FROM {$tableName}");
    
    echo "Current columns:\n";
    foreach ($columns as $column) {
        echo "- {$column->Field} ({$column->Type}) " . 
             ($column->Null == 'YES' ? 'NULL' : 'NOT NULL') . 
             ($column->Default ? " DEFAULT {$column->Default}" : '') . "\n";
    }
    
    echo "\n=== TESTING MODEL ===\n";
    
    // Try to create a simple test record
    $testData = [
        'merk_name' => 'Test Merk ' . time(),
        'merk_description' => 'Test description',
        'is_active' => true
    ];
    
    echo "Attempting to create record with data:\n";
    print_r($testData);
    
    $id = DB::table($tableName)->insertGetId($testData);
    echo "Success! Created record with ID: {$id}\n";
    
    // Clean up
    DB::table($tableName)->where('id', $id)->delete();
    echo "Test record deleted.\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    
    // If column doesn't exist, we might need to add it
    if (strpos($e->getMessage(), 'merk_description') !== false) {
        echo "\nColumn 'merk_description' doesn't exist. Adding it...\n";
        
        try {
            DB::statement("ALTER TABLE {$tableName} ADD COLUMN merk_description TEXT NULL AFTER merk_name");
            echo "Column added successfully!\n";
        } catch (Exception $e2) {
            echo "Failed to add column: " . $e2->getMessage() . "\n";
        }
    }
    
    if (strpos($e->getMessage(), 'merk_name') !== false) {
        echo "\nColumn 'merk_name' doesn't exist. The table might have 'merk' instead.\n";
        echo "Let's check what columns actually exist:\n";
        
        try {
            $columns = DB::select("SHOW COLUMNS FROM {$tableName}");
            foreach ($columns as $column) {
                echo "- {$column->Field}\n";
            }
        } catch (Exception $e3) {
            echo "Cannot show columns: " . $e3->getMessage() . "\n";
        }
    }
}
