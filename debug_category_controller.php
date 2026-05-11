<?php

require_once 'vendor/autoload.php';

// Load Laravel app
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== CATEGORY CONTROLLER DEBUG ===\n\n";

try {
    // Test getAllCategory method directly
    echo "1. Testing getAllCategory method:\n";
    $categories = \App\Models\Category::getAllCategory();
    echo "   ✅ getAllCategory returned: " . get_class($categories) . "\n";
    echo "   ✅ Total items: " . $categories->total() . "\n";
    echo "   ✅ Current page items: " . $categories->count() . "\n";
    
    // Test search parameter
    echo "\n2. Testing getAllCategory with search:\n";
    $searchCategories = \App\Models\Category::getAllCategory('test');
    echo "   ✅ Search returned: " . get_class($searchCategories) . "\n";
    echo "   ✅ Search items: " . $searchCategories->count() . "\n";
    
    // Simulate controller logic
    echo "\n3. Simulating CategoryController index:\n";
    $search = null;
    $categories = \App\Models\Category::getAllCategory($search);
    
    if ($categories->count() > 0) {
        echo "   ✅ Categories available for view\n";
        echo "   ✅ First category: " . $categories->first()->category . "\n";
    } else {
        echo "   ⚠️ No categories found\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "   File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "   Trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== DEBUG COMPLETE ===\n";
