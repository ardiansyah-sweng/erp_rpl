<?php
// Test Laravel controller directly without Dusk
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Set environment to dusk.local
putenv('APP_ENV=dusk.local');
$app->loadEnvironmentFrom('.env.dusk.local');

echo "Testing Laravel Branch controller...\n";

try {
    // Create request
    $request = new \Illuminate\Http\Request();
    $request->setMethod('POST');
    $request->request->add([
        'branch_name' => 'Controller Test Branch',
        'branch_address' => 'Controller Test Address',
        'branch_telephone' => '08111111111',
        '_token' => csrf_token()
    ]);
    
    // Create controller
    $controller = new \App\Http\Controllers\BranchController();
    
    // Create validated request
    $validatedRequest = new \App\Http\Requests\StoreBranchRequest();
    $validatedRequest->setMethod('POST');
    $validatedRequest->request->add([
        'branch_name' => 'Controller Test Branch',
        'branch_address' => 'Controller Test Address', 
        'branch_telephone' => '08111111111'
    ]);
    
    echo "Calling controller store method...\n";
    $response = $controller->store($validatedRequest);
    
    echo "Response type: " . get_class($response) . "\n";
    
    if ($response instanceof \Illuminate\Http\RedirectResponse) {
        echo "Redirect to: " . $response->getTargetUrl() . "\n";
    }
    
    // Check database
    $branch = \App\Models\Branch::where('branch_name', 'Controller Test Branch')->first();
    if ($branch) {
        echo "SUCCESS: Branch created in database!\n";
        // Clean up
        $branch->delete();
        echo "Cleaned up test data.\n";
    } else {
        echo "FAILED: Branch not found in database.\n";
    }
    
} catch(Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
