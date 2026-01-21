<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SupplierControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // run migrations for test DB
        $this->artisan('migrate');
    }

    /**
     * Arrange: create suppliers
     * Act: call /suppliers/search with exact company_name
     * Assert: returns the matching supplier in JSON
     */
    public function test_search_suppliers_returns_exact_match()
    {
        // Arrange
        Supplier::create([
            'supplier_id' => 'SUP001',
            'company_name' => 'PT ABC Suppliers',
            'address' => 'Jl. Test 1',
            'telephone' => '021-1111111',
            'bank_account' => '111-222-333',
        ]);

        Supplier::create([
            'supplier_id' => 'SUP002',
            'company_name' => 'PT DEF Trading',
            'address' => 'Jl. Test 2',
            'telephone' => '021-2222222',
            'bank_account' => '444-555-666',
        ]);

        // Act
        $response = $this->getJson('/suppliers/search?keywords=PT%20ABC%20Suppliers');

        // Assert
        $response->assertStatus(200)
                 ->assertJson(['status' => 'success'])
                 ->assertJsonCount(1, 'data')
                 ->assertJsonPath('data.0.company_name', 'PT ABC Suppliers');
    }

    /**
     * Arrange: create suppliers with shared token
     * Act: call search with partial keyword
     * Assert: returns all partial matches
     */
    public function test_search_suppliers_returns_partial_matches()
    {
        // Arrange
        Supplier::create([
            'supplier_id' => 'SUP010',
            'company_name' => 'Alpha Supplies',
            'address' => 'Jl. Alpha',
            'telephone' => '021-3333333',
            'bank_account' => '101-202-303',
        ]);

        Supplier::create([
            'supplier_id' => 'SUP011',
            'company_name' => 'Alpha Trading Co.',
            'address' => 'Jl. Beta',
            'telephone' => '021-4444444',
            'bank_account' => '404-505-606',
        ]);

        Supplier::create([
            'supplier_id' => 'SUP012',
            'company_name' => 'Beta Supplies',
            'address' => 'Jl. Gamma',
            'telephone' => '021-5555555',
            'bank_account' => '707-808-909',
        ]);

        // Act - search for 'Alpha' should match two suppliers
        $response = $this->getJson('/suppliers/search?keywords=Alpha');

        // Assert
        $response->assertStatus(200)
                 ->assertJson(['status' => 'success'])
                 ->assertJsonCount(2, 'data');
    }

    /**
     * Arrange: no supplier matches
     * Act: call search with keyword that doesn't exist
     * Assert: returns empty data array
     */
    public function test_search_suppliers_returns_empty_when_no_match()
    {
        // Arrange - create one supplier
        Supplier::create([
            'supplier_id' => 'SUP100',
            'company_name' => 'Gamma Supplies',
            'address' => 'Jl. Gamma',
            'telephone' => '021-6666666',
            'bank_account' => '111-000-111',
        ]);

        // Act
        $response = $this->getJson('/suppliers/search?keywords=NonExistingKeyword');

        // Assert
        $response->assertStatus(200)
                 ->assertJson(['status' => 'success'])
                 ->assertJsonCount(0, 'data');
    }
}
