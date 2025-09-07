<?php
require 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Warehouse;
use App\Http\Resources\WarehouseCollection;

echo "=== Testing Warehouse API ===\n";

try {
    // Test 1: Check if we have warehouses
    $warehouseCount = Warehouse::count();
    echo "Total warehouses in database: {$warehouseCount}\n";
    
    if ($warehouseCount === 0) {
        echo "No warehouses found. Creating a test warehouse...\n";
        
        $testWarehouse = new Warehouse();
        $testWarehouse->warehouse_name = 'Test Warehouse API';
        $testWarehouse->warehouse_address = 'Test Address API';
        $testWarehouse->warehouse_phone = '123456789';
        $testWarehouse->is_rm_warehouse = true;
        $testWarehouse->is_fg_warehouse = false;
        $testWarehouse->is_active = true;
        $testWarehouse->save();
        
        echo "Test warehouse created with ID: {$testWarehouse->id}\n";
    }
    
    // Test 2: Test searchWithFilters method
    echo "\nTesting searchWithFilters method...\n";
    $query = Warehouse::searchWithFilters([]);
    $warehouses = $query->paginate(10);
    echo "Query executed successfully. Found {$warehouses->count()} warehouses.\n";
    
    // Test 3: Test WarehouseCollection
    echo "\nTesting WarehouseCollection...\n";
    $collection = new WarehouseCollection($warehouses);
    $result = $collection->toArray(request());
    
    echo "WarehouseCollection created successfully.\n";
    echo "Data count: " . count($result['data']) . "\n";
    echo "Meta info: Active count = " . $result['meta']['active_count'] . "\n";
    
    echo "\n✅ All tests passed!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
}

echo "=== Test Complete ===\n";
