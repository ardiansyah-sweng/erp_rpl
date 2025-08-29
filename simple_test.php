<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SimpleRouteTest extends TestCase
{
    use RefreshDatabase;

    public function test_routes_are_working()
    {
        // Test 1: Cek apakah ada response dari /api/branches
        echo "Testing GET /api/branches...\n";
        $response = $this->get('/api/branches');
        echo "Status: " . $response->status() . "\n";
        echo "Content: " . $response->getContent() . "\n\n";
        
        // Test 2: Cek POST /api/branches
        echo "Testing POST /api/branches...\n";
        $data = [
            'branch_name' => 'Test Branch',
            'branch_address' => 'Test Address',
            'branch_telephone' => '081234567890',
            'is_active' => true
        ];
        
        $response = $this->postJson('/api/branches', $data);
        echo "Status: " . $response->status() . "\n";
        echo "Content: " . $response->getContent() . "\n";
    }
}

// Jalankan test manual
$test = new SimpleRouteTest();
$test->setUp();
$test->test_routes_are_working();
