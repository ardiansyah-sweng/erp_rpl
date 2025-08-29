<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Testing route registration...\n";

try {
    $router = app('router');
    $routes = $router->getRoutes();
    
    echo "Total routes loaded: " . count($routes) . "\n";
    
    $branchRoutes = [];
    foreach ($routes as $route) {
        if (str_contains($route->uri(), 'branches')) {
            $branchRoutes[] = $route->uri() . ' [' . implode(',', $route->methods()) . ']';
        }
    }
    
    if (empty($branchRoutes)) {
        echo "No branch routes found!\n";
        echo "Checking api.php file...\n";
        
        if (file_exists('routes/api.php')) {
            echo "api.php exists\n";
            $content = file_get_contents('routes/api.php');
            if (str_contains($content, 'BranchApiController')) {
                echo "BranchApiController found in api.php\n";
            } else {
                echo "BranchApiController NOT found in api.php\n";
            }
        } else {
            echo "api.php does not exist\n";
        }
    } else {
        echo "Branch routes found:\n";
        foreach ($branchRoutes as $route) {
            echo "  - " . $route . "\n";
        }
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
