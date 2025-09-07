<?php
require 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Warehouse;

echo "=== Testing Warehouse Destroy Method ===\n";

try {
    // Test 1: Check if warehouse exists
    $warehouse = Warehouse::first();
    if (!$warehouse) {
        echo "No warehouses found in database.\n";
        exit(1);
    }
    
    echo "Found warehouse: {$warehouse->name} (ID: {$warehouse->id})\n";
    
    // Test 2: Check static deleteWarehouse method
    echo "Testing static deleteWarehouse method...\n";
    
    // Create a test warehouse first
    $testWarehouse = new Warehouse();
    $testWarehouse->name = 'Test Warehouse ' . time();
    $testWarehouse->location = 'Test Location';
    $testWarehouse->capacity = 1000;
    $testWarehouse->manager = 'Test Manager';
    $testWarehouse->phone = '123456789';
    $testWarehouse->email = 'test@example.com';
    $testWarehouse->is_active = true;
    $testWarehouse->save();
    
    echo "Created test warehouse: {$testWarehouse->name} (ID: {$testWarehouse->id})\n";
    
    // Test the delete method
    $result = Warehouse::deleteWarehouse($testWarehouse->id);
    
    if ($result['success']) {
        echo "✅ Delete successful: {$result['message']}\n";
    } else {
        echo "❌ Delete failed: {$result['message']}\n";
    }
    
    // Verify deletion
    $deletedWarehouse = Warehouse::find($testWarehouse->id);
    if (!$deletedWarehouse) {
        echo "✅ Warehouse successfully deleted from database\n";
    } else {
        echo "❌ Warehouse still exists in database\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "=== Test Complete ===\n";
