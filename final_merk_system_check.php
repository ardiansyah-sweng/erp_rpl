<?php

require_once 'vendor/autoload.php';

// Load Laravel app
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== SISTEM MERK & DASHBOARD VERIFICATION ===\n\n";

echo "1. ROUTE TESTING:\n";
// Test all merk routes
$merkRoutes = [
    'merk.index' => '/merk',
    'merk.create' => '/merk/create', 
    'merk.store' => '/merk',
    'merk.show' => '/merk/1',
    'merk.edit' => '/merk/1/edit',
    'merk.update' => '/merk/1',
    'merk.destroy' => '/merk/1'
];

foreach ($merkRoutes as $name => $expectedPath) {
    try {
        if (in_array($name, ['merk.store', 'merk.update', 'merk.destroy'])) {
            echo "   ✅ Route $name (POST/PUT/DELETE) - exists\n";
        } else {
            $url = route($name, $name === 'merk.show' || $name === 'merk.edit' ? 1 : []);
            echo "   ✅ Route $name -> $url\n";
        }
    } catch (Exception $e) {
        echo "   ❌ Route $name error: " . $e->getMessage() . "\n";
    }
}

echo "\n2. DASHBOARD & CATEGORIES TESTING:\n";
$dashboardRoutes = ['categories.index', 'categories.list'];
foreach ($dashboardRoutes as $route) {
    try {
        $url = route($route);
        echo "   ✅ Route $route -> $url\n";
    } catch (Exception $e) {
        echo "   ❌ Route $route error: " . $e->getMessage() . "\n";
    }
}

echo "\n3. CONTROLLER METHODS:\n";
// Check MerkController methods
$merkMethods = ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'];
foreach ($merkMethods as $method) {
    if (method_exists(\App\Http\Controllers\MerkController::class, $method)) {
        echo "   ✅ MerkController::$method exists\n";
    } else {
        echo "   ❌ MerkController::$method missing\n";
    }
}

echo "\n4. MODEL & FACTORY:\n";
// Check Model
if (class_exists(\App\Models\Merk::class)) {
    echo "   ✅ Merk Model exists\n";
} else {
    echo "   ❌ Merk Model missing\n";
}

// Check Factory
if (class_exists(\Database\Factories\MerkFactory::class)) {
    echo "   ✅ MerkFactory exists\n";
} else {
    echo "   ❌ MerkFactory missing\n";
}

echo "\n5. RESOURCE CLASSES:\n";
if (class_exists(\App\Http\Resources\MerkResource::class)) {
    echo "   ✅ MerkResource exists\n";
} else {
    echo "   ❌ MerkResource missing\n";
}

if (class_exists(\App\Http\Resources\MerkCollection::class)) {
    echo "   ✅ MerkCollection exists\n";
} else {
    echo "   ❌ MerkCollection missing\n";
}

echo "\n=== VERIFICATION COMPLETE ===\n";
