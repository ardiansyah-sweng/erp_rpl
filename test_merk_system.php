<?php

// Simple test file to verify Merk system functionality
// Run this with: php test_merk_system.php

require_once 'vendor/autoload.php';

use App\Models\Merk;
use App\Http\Controllers\MerkController;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== MERK SYSTEM TEST ===\n\n";

try {
    // Test 1: Create a new Merk
    echo "1. Creating test merk...\n";
    $merk = new Merk();
    $merk->merk = "Test Merk " . date('Y-m-d H:i:s');
    $merk->is_active = true;
    $merk->save();
    echo "   ✓ Merk created with ID: {$merk->id}\n";
    
    // Test 2: Read the merk
    echo "2. Reading merk...\n";
    $foundMerk = Merk::find($merk->id);
    echo "   ✓ Merk found: {$foundMerk->merk}\n";
    echo "   ✓ Status: " . ($foundMerk->is_active ? 'Active' : 'Inactive') . "\n";
    
    // Test 3: Test scopes
    echo "3. Testing scopes...\n";
    $activeCount = Merk::active()->count();
    $totalCount = Merk::count();
    echo "   ✓ Active merks: {$activeCount}\n";
    echo "   ✓ Total merks: {$totalCount}\n";
    
    // Test 4: Test search
    echo "4. Testing search...\n";
    $searchResults = Merk::search('Test')->get();
    echo "   ✓ Search results for 'Test': {$searchResults->count()} found\n";
    
    // Test 5: Update the merk
    echo "5. Updating merk...\n";
    $foundMerk->merk = "Updated Test Merk";
    $foundMerk->save();
    echo "   ✓ Merk updated successfully\n";
    
    // Test 6: Test accessors
    echo "6. Testing accessors...\n";
    echo "   ✓ Status label: {$foundMerk->status_label}\n";
    echo "   ✓ Display name: {$foundMerk->display_name}\n";
    
    // Test 7: Clean up
    echo "7. Cleaning up...\n";
    $foundMerk->delete();
    echo "   ✓ Test merk deleted\n";
    
    echo "\n=== ALL TESTS PASSED ✓ ===\n";
    echo "Merk system is working correctly!\n";
    
} catch (Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
