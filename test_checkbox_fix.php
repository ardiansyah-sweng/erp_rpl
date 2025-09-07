<?php
require 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Warehouse;

echo "=== Testing Checkbox Update Fix ===\n";

try {
    // Create test warehouse
    $warehouse = Warehouse::create([
        'warehouse_name' => 'Test Fix ' . time(),
        'warehouse_address' => 'Test Address',
        'warehouse_phone' => '123456789',
        'is_active' => true,
        'is_rm_warehouse' => false,
        'is_fg_warehouse' => false
    ]);
    
    echo "✅ Created warehouse: {$warehouse->warehouse_name}\n";
    echo "Initial: RM={$warehouse->is_rm_warehouse}, FG={$warehouse->is_fg_warehouse}, Active={$warehouse->is_active}\n";
    
    // Test update directly
    $warehouse->update([
        'is_rm_warehouse' => true,
        'is_fg_warehouse' => true,
        'is_active' => false
    ]);
    
    $warehouse->refresh();
    echo "✅ Direct update successful!\n";
    echo "Updated: RM={$warehouse->is_rm_warehouse}, FG={$warehouse->is_fg_warehouse}, Active={$warehouse->is_active}\n";
    
    // Clean up
    $warehouse->delete();
    echo "✅ Test warehouse deleted\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}

echo "=== Test Complete ===\n";
