<?php

require_once 'vendor/autoload.php';

// Load Laravel app
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== ADDING TEST CATEGORIES ===\n\n";

try {
    // Add test categories
    $categories = [
        ['category' => 'Electronics', 'parent_id' => null, 'is_active' => 1],
        ['category' => 'Books', 'parent_id' => null, 'is_active' => 1],
        ['category' => 'Smartphones', 'parent_id' => 1, 'is_active' => 1],
    ];
    
    foreach ($categories as $categoryData) {
        $category = \App\Models\Category::create($categoryData);
        echo "✅ Created category: " . $category->category . " (ID: " . $category->id . ")\n";
    }
    
    echo "\n✅ Total categories in database: " . \App\Models\Category::count() . "\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "   File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "\n=== TESTING COMPLETE ===\n";
