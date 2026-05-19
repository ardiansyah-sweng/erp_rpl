<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\SupplierMaterial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class SupplierMaterialControllerTest extends TestCase
{
    // Menggunakan RefreshDatabase sesuai file asli Anda
    // Ini akan mereset database setiap kali test jalan, jadi aman.
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // run migrations for test DB
        $this->artisan('migrate');
    }

    // ==========================================
    // EXISTING TESTS (ADD MATERIAL)
    // ==========================================

    // Test Case 1: Add supplier material with valid data redirects with success
    public function test_addSupplierMaterial_with_valid_data_redirects_with_success()
    {
        // Arrange
        $data = [
            'supplier_id' => 'SUP001',
            'company_name' => 'PT ABC Suppliers',
            'product_id' => 'P001',
            'product_name' => 'Product A',
            'base_price' => 10000,
        ];

        // Act
        $response = $this->post('/supplier/material/add', $data);

        // Assert
        $response->assertRedirect()
                 ->assertSessionHas('success', 'Data supplier product berhasil divalidasi!');
        
        $this->assertDatabaseHas('supplier_product', [
            'supplier_id' => 'SUP001',
            'product_id' => 'P001',
            'base_price' => 10000,
        ]);
    }

    // Test Case 2: Add supplier material with missing required field fails validation
    public function test_addSupplierMaterial_with_missing_supplier_id_fails_validation()
    {
        // Arrange
        $data = [
            'company_name' => 'PT ABC Suppliers',
            'product_id' => 'P001',
            'product_name' => 'Product A',
            'base_price' => 10000,
            // supplier_id missing
        ];

        // Act
        $response = $this->post('/supplier/material/add', $data);

        // Assert
        $response->assertRedirect()
                 ->assertSessionHasErrors('supplier_id');
        
        $this->assertDatabaseMissing('supplier_product', [
            'product_id' => 'P001',
        ]);
    }

    // Test Case 3: Add supplier material with invalid base_price fails validation
    public function test_addSupplierMaterial_with_negative_base_price_fails_validation()
    {
        // Arrange
        $data = [
            'supplier_id' => 'SUP001',
            'company_name' => 'PT ABC Suppliers',
            'product_id' => 'P001',
            'product_name' => 'Product A',
            'base_price' => -5000, // Invalid negative price
        ];

        // Act
        $response = $this->post('/supplier/material/add', $data);

        // Assert
        $response->assertRedirect()
                 ->assertSessionHasErrors('base_price');
        
        $this->assertDatabaseMissing('supplier_product', [
            'supplier_id' => 'SUP001',
            'product_id' => 'P001',
        ]);
    }

    // ==========================================
    // NEW TESTS (SEARCH FUNCTION)
    // ==========================================

    /**
     * Test 4: Cek Pencarian Berhasil (Happy Path)
     * Memastikan data muncul jika keyword cocok.
     */
    public function test_search_function_returns_data_when_keyword_matches()
    {
        // ARRANGE: Buat data dummy manual
        SupplierMaterial::create([
            'supplier_id'   => 'SUP001',
            'company_name'  => 'PT Mencari Cinta',
            'product_id'    => 'PROD-01',
            'product_name'  => 'Semen Gresik',
            'base_price'    => 50000
        ]);

        // ACT: Search keyword 'Semen'
        // Pastikan route 'supplier.material.search' sudah ada di web.php
        $response = $this->get(route('supplier.material.search', ['keyword' => 'Semen']));

        // ASSERT:
        $response->assertStatus(200);
        $response->assertViewHas('materials');
        
        // Pastikan datanya ada 1 dan isinya benar
        $materials = $response->viewData('materials');
        $this->assertNotEmpty($materials);
        $this->assertEquals('Semen Gresik', $materials->first()->product_name);
    }

    /**
     * Test 5: Cek Logika Empty (Sad Path - Sesuai Dosen)
     * Memastikan muncul pesan error jika data tidak ditemukan.
     */
    public function test_search_shows_error_message_when_data_is_empty()
    {
        // ARRANGE: Biarkan database kosong atau isi data yang tidak relevan
        SupplierMaterial::create([
            'supplier_id'   => 'SUP002',
            'company_name'  => 'PT Lain',
            'product_id'    => 'PROD-02',
            'product_name'  => 'Batu Bata',
            'base_price'    => 1000
        ]);

        // ACT: Search keyword 'Emas' (Tidak ada di database)
        $response = $this->get(route('supplier.material.search', ['keyword' => 'Emas']));

        // ASSERT:
        $response->assertStatus(200);
        
        // 1. Pastikan materials kosong
        $materials = $response->viewData('materials');
        $this->assertTrue($materials->isEmpty());

        // 2. Pastikan Session Flash Error muncul (PENTING: Ini yang diminta dosen)
        $response->assertSessionHas('error', 'Data tidak ditemukan atau tidak ada hasil.');
    }

    // ==========================================
    // TESTS (COUNT BY CATEGORY)
    // ==========================================

    public function test_countSupplierMaterialByCategory_returns_correct_total()
    {
        DB::table('categories')->insert(['id' => 1, 'category' => 'Bahan Baku', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('products')->insert(['product_id' => 'P001', 'name' => 'Tepung', 'type' => 'RM', 'category' => 1, 'created_at' => now(), 'updated_at' => now()]);
        DB::table('item')->insert(['sku' => 'P001-01', 'item_name' => 'Tepung Terigu', 'product_id' => 'P001', 'measurement_unit' => 'kg', 'stock_unit' => 'kg', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('supplier_product')->insert(['supplier_id' => 'SUP001', 'company_name' => 'PT A', 'product_id' => 'P001-01', 'product_name' => 'Tepung Terigu', 'base_price' => 10000, 'created_at' => now(), 'updated_at' => now()]);

        $response = $this->get('/supplier-material/category/1/SUP001');

        $response->assertStatus(200);
        $response->assertJson(['category' => '1', 'supplier' => 'SUP001', 'total' => 1]);
    }

    public function test_countSupplierMaterialByCategory_returns_zero_when_not_found()
    {
        $response = $this->get('/supplier-material/category/99/SUP999');

        $response->assertStatus(200);
        $response->assertJson(['total' => 0]);
    }
}