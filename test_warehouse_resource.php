<?php
require 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Warehouse;
use App\Http\Resources\WarehouseResource;

echo "=== Testing WarehouseResource in Update Method ===\n";

try {
    // Create test warehouse
    $warehouse = Warehouse::create([
        'warehouse_name' => 'Test Resource ' . time(),
        'warehouse_address' => 'Test Address for Resource',
        'warehouse_phone' => '123456789',
        'is_active' => true,
        'is_rm_warehouse' => false,
        'is_fg_warehouse' => true
    ]);
    
    echo "✅ Created warehouse: {$warehouse->warehouse_name} (ID: {$warehouse->id})\n";
    
    // Test WarehouseResource output
    $resource = new WarehouseResource($warehouse);
    $resourceArray = $resource->toArray(request());
    
    echo "✅ WarehouseResource generated successfully!\n";
    echo "Resource data structure:\n";
    echo "- ID: " . $resourceArray['id'] . "\n";
    echo "- Name: " . $resourceArray['warehouse_name'] . "\n";
    echo "- Status: " . $resourceArray['status'] . "\n";
    echo "- Type: " . $resourceArray['warehouse_type'] . "\n";
    echo "- Display Name: " . $resourceArray['display_name'] . "\n";
    
    // Update warehouse
    $warehouse->update([
        'is_rm_warehouse' => true,
        'is_fg_warehouse' => false,
        'is_active' => false
    ]);
    
    $warehouse->refresh();
    
    // Test updated resource
    $updatedResource = new WarehouseResource($warehouse);
    $updatedResourceArray = $updatedResource->toArray(request());
    
    echo "\n✅ After Update:\n";
    echo "- Status: " . $updatedResourceArray['status'] . "\n";
    echo "- Type: " . $updatedResourceArray['warehouse_type'] . "\n";
    echo "- Display Name: " . $updatedResourceArray['display_name'] . "\n";
    
    // Clean up
    $warehouse->delete();
    echo "\n✅ Test warehouse deleted\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}

echo "\n=== Test Complete ===\n";
