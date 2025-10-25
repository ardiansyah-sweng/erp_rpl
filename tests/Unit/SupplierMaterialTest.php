<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\SupplierMaterial;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SupplierMaterialTest extends TestCase
{
    /**
     * Setup test database dengan data dummy
     * Tidak menggunakan RefreshDatabase untuk menghindari migration error
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        // Drop tabel jika sudah ada
        Schema::dropIfExists('supplier_product');
        
        // Buat tabel supplier_product manual (tanpa migration)
        Schema::create('supplier_product', function ($table) {
            $table->id();
            $table->string('supplier_id');
            $table->string('company_name');
            $table->string('product_id');
            $table->string('product_name');
            $table->decimal('base_price', 15, 2);
            $table->timestamps();
        });
        
        // Insert data dummy untuk testing
        DB::table('supplier_product')->insert([
            [
                'supplier_id' => 'SUP001',
                'company_name' => 'PT Supplier Jaya',
                'product_id' => 'PROD001',
                'product_name' => 'Material A',
                'base_price' => 10000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'supplier_id' => 'SUP002',
                'company_name' => 'CV Makmur Sejahtera',
                'product_id' => 'PROD002',
                'product_name' => 'Material B',
                'base_price' => 20000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'supplier_id' => 'SUP003',
                'company_name' => 'PT Global Trading',
                'product_id' => 'PROD003',
                'product_name' => 'Material C',
                'base_price' => 30000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Cleanup setelah test
     */
    protected function tearDown(): void
    {
        Schema::dropIfExists('supplier_product');
        parent::tearDown();
    }

    /**
     * Test pencarian berdasarkan supplier_id
     */
    public function test_count_supplier_material_by_supplier_id()
    {
        $count = SupplierMaterial::countSupplierMaterialFoundByKeyword('SUP001');
        
        $this->assertEquals(1, $count, 'Should find exactly 1 record with supplier_id SUP001');
    }

    /**
     * Test pencarian berdasarkan company_name
     */
    public function test_count_supplier_material_by_company_name()
    {
        $count = SupplierMaterial::countSupplierMaterialFoundByKeyword('Makmur');
        
        $this->assertEquals(1, $count, 'Should find exactly 1 record with company_name containing Makmur');
    }

    /**
     * Test pencarian berdasarkan product_id
     */
    public function test_count_supplier_material_by_product_id()
    {
        $count = SupplierMaterial::countSupplierMaterialFoundByKeyword('PROD002');
        
        $this->assertEquals(1, $count, 'Should find exactly 1 record with product_id PROD002');
    }

    /**
     * Test pencarian berdasarkan product_name
     */
    public function test_count_supplier_material_by_product_name()
    {
        $count = SupplierMaterial::countSupplierMaterialFoundByKeyword('Material');
        
        $this->assertEquals(3, $count, 'Should find all 3 records with product_name containing Material');
    }

    /**
     * Test pencarian dengan keyword yang tidak ditemukan
     */
    public function test_count_supplier_material_with_no_results()
    {
        $count = SupplierMaterial::countSupplierMaterialFoundByKeyword('TIDAKADA');
        
        $this->assertEquals(0, $count, 'Should return 0 when keyword is not found');
    }

    /**
     * Test pencarian dengan keyword kosong
     */
    public function test_count_supplier_material_with_empty_keyword()
    {
        $count = SupplierMaterial::countSupplierMaterialFoundByKeyword('');
        
        $this->assertEquals(3, $count, 'Should return all records when keyword is empty');
    }

    /**
     * Test pencarian dengan keyword partial match
     */
    public function test_count_supplier_material_with_partial_keyword()
    {
        $count = SupplierMaterial::countSupplierMaterialFoundByKeyword('PT');
        
        $this->assertEquals(2, $count, 'Should find 2 records with company_name starting with PT');
    }

    /**
     * Test pencarian case insensitive
     */
    public function test_count_supplier_material_case_insensitive()
    {
        $count = SupplierMaterial::countSupplierMaterialFoundByKeyword('jaya');
        
        $this->assertGreaterThanOrEqual(1, $count, 'Should find at least 1 record (case insensitive)');
    }

    /**
     * Test dengan multiple keywords
     */
    public function test_count_supplier_material_with_number_in_keyword()
    {
        $count = SupplierMaterial::countSupplierMaterialFoundByKeyword('001');
        
        $this->assertGreaterThanOrEqual(1, $count, 'Should find records with 001 in any field');
    }
}