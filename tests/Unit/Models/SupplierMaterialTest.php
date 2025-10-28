<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Product;
use App\Models\SupplierMaterial;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SupplierMaterialTest extends TestCase
{
    use RefreshDatabase;

    // ========================================================================
    // TEST UNTUK FUNGSI countSupplierMaterial()
    // ========================================================================

    /** @test */
    public function it_counts_supplier_materials_correctly()
    {
        // Arrange: buat 2 RM, 1 FG
        $productRM1 = Product::factory()->create(['product_id' => 'RM01', 'type' => 'RM']);
        $productRM2 = Product::factory()->create(['product_id' => 'RM02', 'type' => 'RM']);
        $productFG  = Product::factory()->create(['product_id' => 'FG01', 'type' => 'FG']);

        SupplierMaterial::factory()->create(['product_id' => $productRM1->product_id . '-01']);
        SupplierMaterial::factory()->create(['product_id' => $productRM2->product_id . '-01']);
        SupplierMaterial::factory()->create(['product_id' => $productFG->product_id . '-01']);

        // Act
        $count = SupplierMaterial::countSupplierMaterial();

        // Assert
        $this->assertEquals(2, $count, 'Jumlah supplier material untuk produk RM harus 2');
    }

    /** @test */
    public function it_returns_zero_when_no_rm_products_exist()
    {
        // Arrange: hanya buat produk FG
        $productFG = Product::factory()->create(['product_id' => 'FG01', 'type' => 'FG']);
        SupplierMaterial::factory()->create(['product_id' => $productFG->product_id . '-01']);

        // Act
        $count = SupplierMaterial::countSupplierMaterial();

        // Assert
        $this->assertEquals(0, $count);
    }

    /** @test */
    public function it_counts_unique_rm_products_only()
    {
        // Arrange: buat 1 RM
        $productRM = Product::factory()->create(['product_id' => 'RM01', 'type' => 'RM']);

        // Buat 2 SupplierMaterial untuk produk RM yang sama
        SupplierMaterial::factory()->create(['product_id' => $productRM->product_id . '-01']);
        SupplierMaterial::factory()->create(['product_id' => $productRM->product_id . '-02']);

        // Act
        $count = SupplierMaterial::countSupplierMaterial();

        // Assert: tetap 1 karena produk unik
        $this->assertEquals(1, $count);
    }

    /** @test */
    public function it_counts_only_rm_products_among_mixed_types()
    {
        // Arrange: 1 RM, 1 FG
        $productRM = Product::factory()->create(['product_id' => 'RM01', 'type' => 'RM']);
        $productFG = Product::factory()->create(['product_id' => 'FG01', 'type' => 'FG']);

        SupplierMaterial::factory()->create(['product_id' => $productRM->product_id . '-01']);
        SupplierMaterial::factory()->create(['product_id' => $productFG->product_id . '-01']);

        // Act
        $count = SupplierMaterial::countSupplierMaterial();

        // Assert
        $this->assertEquals(1, $count);
    }

    /** @test */
    public function it_returns_zero_when_supplier_product_table_is_empty()
    {
        // Act
        $count = SupplierMaterial::countSupplierMaterial();

        // Assert
        $this->assertEquals(0, $count);
    }

    /** @test */
    public function it_handles_multiple_suppliers_with_same_rm_product()
    {
        // Arrange: 1 RM product
        $productRM = Product::factory()->create(['product_id' => 'RM01', 'type' => 'RM']);

        // 2 supplier berbeda dengan RM yang sama
        SupplierMaterial::factory()->create(['product_id' => $productRM->product_id . '-01']);
        SupplierMaterial::factory()->create(['product_id' => $productRM->product_id . '-01', 'supplier_id' => 'SUP002']);

        // Act
        $count = SupplierMaterial::countSupplierMaterial();

        // Assert: tetap 1 karena produk unik
        $this->assertEquals(1, $count);
    }

    // ========================================================================
    // TEST UNTUK FUNGSI countSupplierMaterialFoundByKeyword() 
    // ========================================================================

    /**
     * Test pencarian berdasarkan supplier_id
     * @test
     */
    public function it_counts_supplier_material_by_supplier_id()
    {
        // Arrange
        SupplierMaterial::factory()->create([
            'supplier_id' => 'SUP001',
            'company_name' => 'PT Supplier Jaya',
            'product_id' => 'PROD001',
            'product_name' => 'Material A',
            'base_price' => 10000,
        ]);
        
        SupplierMaterial::factory()->create([
            'supplier_id' => 'SUP002',
            'company_name' => 'CV Makmur Sejahtera',
            'product_id' => 'PROD002',
            'product_name' => 'Material B',
            'base_price' => 20000,
        ]);

        // Act
        $count = SupplierMaterial::countSupplierMaterialFoundByKeyword('SUP001');
        
        // Assert
        $this->assertEquals(1, $count, 'Should find exactly 1 record with supplier_id SUP001');
    }

    /**
     * Test pencarian berdasarkan company_name
     * @test
     */
    public function it_counts_supplier_material_by_company_name()
    {
        // Arrange
        SupplierMaterial::factory()->create([
            'supplier_id' => 'SUP001',
            'company_name' => 'PT Supplier Jaya',
            'product_id' => 'PROD001',
            'product_name' => 'Material A',
            'base_price' => 10000,
        ]);
        
        SupplierMaterial::factory()->create([
            'supplier_id' => 'SUP002',
            'company_name' => 'CV Makmur Sejahtera',
            'product_id' => 'PROD002',
            'product_name' => 'Material B',
            'base_price' => 20000,
        ]);

        // Act
        $count = SupplierMaterial::countSupplierMaterialFoundByKeyword('Makmur');
        
        // Assert
        $this->assertEquals(1, $count, 'Should find exactly 1 record with company_name containing Makmur');
    }

    /**
     * Test pencarian berdasarkan product_id
     * @test
     */
    public function it_counts_supplier_material_by_product_id()
    {
        // Arrange
        SupplierMaterial::factory()->create([
            'supplier_id' => 'SUP001',
            'company_name' => 'PT Supplier Jaya',
            'product_id' => 'PROD001',
            'product_name' => 'Material A',
            'base_price' => 10000,
        ]);
        
        SupplierMaterial::factory()->create([
            'supplier_id' => 'SUP002',
            'company_name' => 'CV Makmur Sejahtera',
            'product_id' => 'PROD002',
            'product_name' => 'Material B',
            'base_price' => 20000,
        ]);

        // Act
        $count = SupplierMaterial::countSupplierMaterialFoundByKeyword('PROD002');
        
        // Assert
        $this->assertEquals(1, $count, 'Should find exactly 1 record with product_id PROD002');
    }

    /**
     * Test pencarian berdasarkan product_name
     * @test
     */
    public function it_counts_supplier_material_by_product_name()
    {
        // Arrange
        SupplierMaterial::factory()->create([
            'supplier_id' => 'SUP001',
            'company_name' => 'PT Supplier Jaya',
            'product_id' => 'PROD001',
            'product_name' => 'Material A',
            'base_price' => 10000,
        ]);
        
        SupplierMaterial::factory()->create([
            'supplier_id' => 'SUP002',
            'company_name' => 'CV Makmur Sejahtera',
            'product_id' => 'PROD002',
            'product_name' => 'Material B',
            'base_price' => 20000,
        ]);
        
        SupplierMaterial::factory()->create([
            'supplier_id' => 'SUP003',
            'company_name' => 'PT Global Trading',
            'product_id' => 'PROD003',
            'product_name' => 'Material C',
            'base_price' => 30000,
        ]);

        // Act
        $count = SupplierMaterial::countSupplierMaterialFoundByKeyword('Material');
        
        // Assert
        $this->assertEquals(3, $count, 'Should find all 3 records with product_name containing Material');
    }

    /**
     * Test pencarian dengan keyword yang tidak ditemukan
     * @test
     */
    public function it_counts_supplier_material_with_no_results()
    {
        // Arrange
        SupplierMaterial::factory()->create([
            'supplier_id' => 'SUP001',
            'company_name' => 'PT Supplier Jaya',
            'product_id' => 'PROD001',
            'product_name' => 'Material A',
            'base_price' => 10000,
        ]);

        // Act
        $count = SupplierMaterial::countSupplierMaterialFoundByKeyword('TIDAKADA');
        
        // Assert
        $this->assertEquals(0, $count, 'Should return 0 when keyword is not found');
    }

    /**
     * Test pencarian dengan keyword kosong
     * @test
     */
    public function it_counts_supplier_material_with_empty_keyword()
    {
        // Arrange
        SupplierMaterial::factory()->create([
            'supplier_id' => 'SUP001',
            'company_name' => 'PT Supplier Jaya',
            'product_id' => 'PROD001',
            'product_name' => 'Material A',
            'base_price' => 10000,
        ]);
        
        SupplierMaterial::factory()->create([
            'supplier_id' => 'SUP002',
            'company_name' => 'CV Makmur Sejahtera',
            'product_id' => 'PROD002',
            'product_name' => 'Material B',
            'base_price' => 20000,
        ]);
        
        SupplierMaterial::factory()->create([
            'supplier_id' => 'SUP003',
            'company_name' => 'PT Global Trading',
            'product_id' => 'PROD003',
            'product_name' => 'Material C',
            'base_price' => 30000,
        ]);

        // Act
        $count = SupplierMaterial::countSupplierMaterialFoundByKeyword('');
        
        // Assert
        $this->assertEquals(3, $count, 'Should return all records when keyword is empty');
    }

    /**
     * Test pencarian dengan keyword partial match
     * @test
     */
    public function it_counts_supplier_material_with_partial_keyword()
    {
        // Arrange
        SupplierMaterial::factory()->create([
            'supplier_id' => 'SUP001',
            'company_name' => 'PT Supplier Jaya',
            'product_id' => 'PROD001',
            'product_name' => 'Material A',
            'base_price' => 10000,
        ]);
        
        SupplierMaterial::factory()->create([
            'supplier_id' => 'SUP002',
            'company_name' => 'CV Makmur Sejahtera',
            'product_id' => 'PROD002',
            'product_name' => 'Material B',
            'base_price' => 20000,
        ]);
        
        SupplierMaterial::factory()->create([
            'supplier_id' => 'SUP003',
            'company_name' => 'PT Global Trading',
            'product_id' => 'PROD003',
            'product_name' => 'Material C',
            'base_price' => 30000,
        ]);

        // Act
        $count = SupplierMaterial::countSupplierMaterialFoundByKeyword('PT');
        
        // Assert
        $this->assertEquals(2, $count, 'Should find 2 records with company_name starting with PT');
    }

    /**
     * Test pencarian case insensitive
     * @test
     */
    public function it_counts_supplier_material_case_insensitive()
    {
        // Arrange
        SupplierMaterial::factory()->create([
            'supplier_id' => 'SUP001',
            'company_name' => 'PT Supplier Jaya',
            'product_id' => 'PROD001',
            'product_name' => 'Material A',
            'base_price' => 10000,
        ]);

        // Act
        $count = SupplierMaterial::countSupplierMaterialFoundByKeyword('jaya');
        
        // Assert
        $this->assertEquals(1, $count, 'Should find exactly 1 record with "Jaya" (case insensitive)');
    }

    /**
     * Test dengan angka dalam keyword
     * @test
     */
    public function it_counts_supplier_material_with_number_in_keyword()
    {
        // Arrange - buat 3 record, 2 mengandung "001"
        SupplierMaterial::factory()->create([
            'supplier_id' => 'SUP001',
            'company_name' => 'PT Supplier Jaya',
            'product_id' => 'PROD100',
            'product_name' => 'Material A',
            'base_price' => 10000,
        ]);
        
        SupplierMaterial::factory()->create([
            'supplier_id' => 'SUP002',
            'company_name' => 'CV Makmur Sejahtera',
            'product_id' => 'PROD001',
            'product_name' => 'Material B',
            'base_price' => 20000,
        ]);
        
        SupplierMaterial::factory()->create([
            'supplier_id' => 'SUP003',
            'company_name' => 'PT Global Trading',
            'product_id' => 'PROD999',
            'product_name' => 'Material C',
            'base_price' => 30000,
        ]);

        // Act
        $count = SupplierMaterial::countSupplierMaterialFoundByKeyword('001');
        
        // Assert
        $this->assertEquals(2, $count, 'Should find 2 records with "001" in supplier_id or product_id');
    }
}