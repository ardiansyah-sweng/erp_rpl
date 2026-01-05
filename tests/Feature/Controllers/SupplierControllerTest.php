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

   /**
     * Test method deleteSupplierByID berhasil menghapus supplier
     */
    public function test_deleteSupplierByID_can_delete_existing_supplier()
    {
        // Arrange: Buat data supplier
        // FIX: supplier_id maksimal 6 karakter (SUP001)
        $supplier = Supplier::factory()->create([
            'supplier_id' => 'SUP001', 
            'company_name' => 'PT Test Delete',
            'address' => 'Jl. Delete No. 123',
            'telephone' => '081234567890',
            'bank_account' => '1234567890'
        ]);

        // Act: Hapus supplier
        $response = $this->deleteJson("/supplier/delete/{$supplier->supplier_id}");

        // Assert: Response berhasil
        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'success',
                     'message' => 'Supplier berhasil dihapus.'
                 ]);

        // Assert: Data terhapus dari database
        $this->assertDatabaseMissing('suppliers', [
            'supplier_id' => 'SUP001'
        ]);
    }

    /**
     * Test method deleteSupplierByID gagal jika ID tidak ditemukan
     */
    public function test_deleteSupplierByID_returns_404_when_supplier_not_found()
    {
        // Act: Coba hapus supplier yang tidak ada (Gunakan ID 6 digit acak)
        $response = $this->deleteJson("/supplier/delete/ERR404");

        // Assert: Response 404 Not Found
        $response->assertStatus(404)
                 ->assertJson([
                     'status' => 'error',
                     'message' => 'Supplier tidak ditemukan.'
                 ]);
    }

    /**
     * Test method deleteSupplierByID isolasi data (tidak menghapus data lain)
     */
    public function test_deleteSupplierByID_only_deletes_target_supplier()
    {
        // Arrange: Buat 3 supplier
        // FIX: supplier_id disesuaikan jadi 6 karakter
        $supplier1 = Supplier::factory()->create(['supplier_id' => 'ISO001']);
        $supplier2 = Supplier::factory()->create(['supplier_id' => 'ISO002']);
        $supplier3 = Supplier::factory()->create(['supplier_id' => 'ISO003']);

        // Act: Hapus supplier kedua
        $response = $this->deleteJson("/supplier/delete/ISO002");

        // Assert: Response berhasil
        $response->assertStatus(200);

        // Assert: Hanya supplier kedua yang terhapus
        $this->assertDatabaseMissing('suppliers', ['supplier_id' => 'ISO002']);
        $this->assertDatabaseHas('suppliers', ['supplier_id' => 'ISO001']);
        $this->assertDatabaseHas('suppliers', ['supplier_id' => 'ISO003']);
    }

    /**
     * Test method deleteSupplierByID struktur JSON response
     */
    public function test_deleteSupplierByID_returns_correct_json_structure()
    {
        // Arrange: Buat supplier (Biarkan Factory yang generate ID 6 digit otomatis)
        $supplier = Supplier::factory()->create();

        // Act: Hapus supplier
        $response = $this->deleteJson("/supplier/delete/{$supplier->supplier_id}");

        // Assert: Response memiliki struktur yang benar
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'message'
                 ]);
    }
}