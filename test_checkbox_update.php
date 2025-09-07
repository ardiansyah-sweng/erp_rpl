<?php
require 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Warehouse;

echo "=== Testing Warehouse Checkbox Update ===\n";

try {
    // Create test warehouse
    $warehouse = new Warehouse();
    $warehouse->warehouse_name = 'Test Checkbox Update ' . time();
    $warehouse->warehouse_address = 'Test Address';
    $warehouse->warehouse_phone = '123456789';
    $warehouse->is_active = true;
    $warehouse->is_rm_warehouse = false;
    $warehouse->is_fg_warehouse = false;
    $warehouse->save();
    
    echo "✅ Created test warehouse: {$warehouse->warehouse_name} (ID: {$warehouse->id})\n";
    echo "Initial values: RM={$warehouse->is_rm_warehouse}, FG={$warehouse->is_fg_warehouse}, Active={$warehouse->is_active}\n";
    
    // Test update with boolean values
    $updated = $warehouse->update([
        'is_rm_warehouse' => true,
        'is_fg_warehouse' => true,
        'is_active' => false
    ]);
    
    if ($updated) {
        // Refresh from database
        $warehouse->refresh();
        echo "✅ Update successful!\n";
        echo "Updated values: RM={$warehouse->is_rm_warehouse}, FG={$warehouse->is_fg_warehouse}, Active={$warehouse->is_active}\n";
        
        // Verify boolean conversion
        echo "Type check: RM=" . gettype($warehouse->is_rm_warehouse) . ", FG=" . gettype($warehouse->is_fg_warehouse) . "\n";
    } else {
        echo "❌ Update failed\n";
    }
    
    // Clean up
    $warehouse->delete();
    echo "✅ Test warehouse deleted\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "=== Test Complete ===\n";
