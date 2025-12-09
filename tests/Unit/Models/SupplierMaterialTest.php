<?php 

namespace Tests\Unit\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Product;
use App\Models\SupplierMaterial;
use App\Models\Supplier;
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
    public function it_counts_supplier_material_found_by_supplier_id()
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
    public function it_counts_supplier_material_found_by_company_name()
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
    public function it_counts_supplier_material_found_by_product_id()
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
    public function it_counts_supplier_material_found_by_product_name()
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
    public function it_counts_supplier_material_found_with_no_results()
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
    public function it_counts_supplier_material_found_with_empty_keyword()
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
    public function it_counts_supplier_material_found_with_partial_keyword()
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
    public function it_counts_supplier_material_found_case_insensitive()
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
    public function it_counts_supplier_material_found_with_number_in_keyword()
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

    // ========================================================================
    // TEST UNTUK FUNGSI getSupplierMaterialByKeyword()
    // ========================================================================

    /**
     * Test: Pencarian berdasarkan supplier_id
     * @test
     */
    public function getSupplierMaterialByKeyword_can_search_by_supplier_id()
    {
        // Arrange
        $supplier1 = Supplier::factory()->create(['supplier_id' => 'SUP001']);
        $supplier2 = Supplier::factory()->create(['supplier_id' => 'SUP002']);
        
        SupplierMaterial::factory()->create([
            'supplier_id' => $supplier1->supplier_id,
            'company_name' => $supplier1->company_name,
        ]);
        
        SupplierMaterial::factory()->create([
            'supplier_id' => $supplier2->supplier_id,
            'company_name' => $supplier2->company_name,
        ]);

        // Act
        $result = SupplierMaterial::getSupplierMaterialByKeyword('SUP001');

        // Assert
        $this->assertCount(1, $result);
        $this->assertEquals('SUP001', $result->first()->supplier_id);
    }

    /**
     * Test: Pencarian berdasarkan company_name
     * @test
     */
    public function getSupplierMaterialByKeyword_can_search_by_company_name()
    {
        // Arrange
        $supplier1 = Supplier::factory()->create(['company_name' => 'PT Maju Jaya']);
        $supplier2 = Supplier::factory()->create(['company_name' => 'PT Mundur Terus']);
        
        SupplierMaterial::factory()->create([
            'supplier_id' => $supplier1->supplier_id,
            'company_name' => 'PT Maju Jaya',
        ]);
        
        SupplierMaterial::factory()->create([
            'supplier_id' => $supplier2->supplier_id,
            'company_name' => 'PT Mundur Terus',
        ]);

        // Act
        $result = SupplierMaterial::getSupplierMaterialByKeyword('Maju');

        // Assert
        $this->assertCount(1, $result);
        $this->assertStringContainsString('Maju', $result->first()->company_name);
    }

    /**
     * Test: Pencarian berdasarkan product_id
     * @test
     */
    public function getSupplierMaterialByKeyword_can_search_by_product_id()
    {
        // Arrange - DIPERBAIKI: product_id maksimal 4 karakter
        $product1 = Product::factory()->create(['product_id' => 'P001']);
        $product2 = Product::factory()->create(['product_id' => 'P002']);
        
        SupplierMaterial::factory()->create([
            'product_id' => $product1->product_id . '-01',
        ]);
        
        SupplierMaterial::factory()->create([
            'product_id' => $product2->product_id . '-01',
        ]);

        // Act - DIPERBAIKI: keyword disesuaikan
        $result = SupplierMaterial::getSupplierMaterialByKeyword('P001');

        // Assert
        $this->assertCount(1, $result);
        $this->assertStringContainsString('P001', $result->first()->product_id);
    }

    /**
     * Test: Pencarian berdasarkan product_name
     * @test
     */
    public function getSupplierMaterialByKeyword_can_search_by_product_name()
    {
        // Arrange
        $product1 = Product::factory()->create(['name' => 'Bahan Baku A']);
        $product2 = Product::factory()->create(['name' => 'Bahan Baku B']);
        
        SupplierMaterial::factory()->create([
            'product_name' => 'Bahan Baku A',
        ]);
        
        SupplierMaterial::factory()->create([
            'product_name' => 'Bahan Baku B',
        ]);

        // Act
        $result = SupplierMaterial::getSupplierMaterialByKeyword('Bahan Baku A');

        // Assert
        $this->assertCount(1, $result);
        $this->assertEquals('Bahan Baku A', $result->first()->product_name);
    }

    /**
     * Test: Pencarian dengan keyword sebagian (partial match)
     * @test
     */
    public function getSupplierMaterialByKeyword_can_search_with_partial_keyword()
    {
        // Arrange
        SupplierMaterial::factory()->create([
            'company_name' => 'PT Teknologi Maju',
        ]);
        
        SupplierMaterial::factory()->create([
            'company_name' => 'CV Berkah Abadi',
        ]);

        // Act
        $result = SupplierMaterial::getSupplierMaterialByKeyword('Tekno');

        // Assert
        $this->assertCount(1, $result);
        $this->assertStringContainsString('Teknologi', $result->first()->company_name);
    }

    /**
     * Test: Mengembalikan multiple results jika keyword cocok dengan beberapa record
     * @test
     */
    public function getSupplierMaterialByKeyword_returns_multiple_results_when_keyword_matches_multiple_records()
    {
        // Arrange
        SupplierMaterial::factory()->create([
            'company_name' => 'PT ABC Indonesia',
        ]);
        
        SupplierMaterial::factory()->create([
            'product_name' => 'ABC Material',
        ]);
        
        SupplierMaterial::factory()->create([
            'company_name' => 'PT XYZ Corp',
        ]);

        // Act
        $result = SupplierMaterial::getSupplierMaterialByKeyword('ABC');

        // Assert
        $this->assertCount(2, $result);
    }

    /**
     * Test: Mengembalikan empty collection jika tidak ada yang cocok
     * @test
     */
    public function getSupplierMaterialByKeyword_returns_empty_collection_when_no_match_found()
    {
        // Arrange
        SupplierMaterial::factory()->create([
            'company_name' => 'PT Maju Jaya',
        ]);

        // Act
        $result = SupplierMaterial::getSupplierMaterialByKeyword('TIDAK_ADA');

        // Assert
        $this->assertCount(0, $result);
        $this->assertTrue($result->isEmpty());
    }

    /**
     * Test: Pencarian case insensitive
     * @test
     */
    public function getSupplierMaterialByKeyword_is_case_insensitive()
    {
        // Arrange
        SupplierMaterial::factory()->create([
            'company_name' => 'PT MAJU JAYA',
        ]);

        // Act
        $result = SupplierMaterial::getSupplierMaterialByKeyword('maju jaya');

        // Assert
        $this->assertCount(1, $result);
    }

    /**
     * Test: Pencarian dengan keyword kosong mengembalikan semua data
     * @test
     */
    public function getSupplierMaterialByKeyword_returns_all_data_with_empty_keyword()
    {
        // Arrange
        SupplierMaterial::factory()->count(3)->create();

        // Act
        $result = SupplierMaterial::getSupplierMaterialByKeyword('');

        // Assert
        $this->assertCount(3, $result);
    }

    /**
     * Test: Pencarian dengan OR condition di semua kolom
     * @test
     */
    public function getSupplierMaterialByKeyword_searches_across_all_columns_with_or_condition()
    {
        // Arrange - DIPERBAIKI: product_id maksimal 4 karakter
        $supplier = Supplier::factory()->create(['supplier_id' => 'SUP123']);
        $product = Product::factory()->create(['product_id' => 'P456']);
        
        SupplierMaterial::factory()->create([
            'supplier_id' => $supplier->supplier_id,
            'company_name' => 'PT Different Name',
            'product_id' => 'OTHER-01',
            'product_name' => 'Other Product',
        ]);

        // Act - Search by supplier_id
        $result1 = SupplierMaterial::getSupplierMaterialByKeyword('SUP123');
        
        // Act - Search by product_id
        SupplierMaterial::factory()->create([
            'product_id' => $product->product_id . '-01',
        ]);
        $result2 = SupplierMaterial::getSupplierMaterialByKeyword('P456');

        // Assert
        $this->assertCount(1, $result1);
        $this->assertCount(1, $result2);
    }

    /**
     * Test: Mengembalikan semua kolom dengan lengkap
     * @test
     */
    public function getSupplierMaterialByKeyword_returns_all_columns_in_result()
    {
        // Arrange
        $supplierMaterial = SupplierMaterial::factory()->create([
            'supplier_id' => 'SUP001',
            'company_name' => 'PT Test Company',
            'product_id' => 'PROD-01',
            'product_name' => 'Test Product',
            'base_price' => 50000,
        ]);

        // Act
        $result = SupplierMaterial::getSupplierMaterialByKeyword('SUP001');

        // Assert
        $this->assertNotNull($result->first()->id);
        $this->assertEquals('SUP001', $result->first()->supplier_id);
        $this->assertEquals('PT Test Company', $result->first()->company_name);
        $this->assertEquals('PROD-01', $result->first()->product_id);
        $this->assertEquals('Test Product', $result->first()->product_name);
        $this->assertEquals(50000, $result->first()->base_price);
        $this->assertNotNull($result->first()->created_at);
        $this->assertNotNull($result->first()->updated_at);
    }

    /**
     * Test: Handle special characters dalam pencarian
     * @test
     */
    public function getSupplierMaterialByKeyword_handles_special_characters_in_search()
    {
        // Arrange
        SupplierMaterial::factory()->create([
            'company_name' => 'PT A&B Corporation',
        ]);

        // Act
        $result = SupplierMaterial::getSupplierMaterialByKeyword('A&B');

        // Assert
        $this->assertCount(1, $result);
    }

    /**
     * Test: Return type adalah Collection
     * @test
     */
    public function getSupplierMaterialByKeyword_returns_collection_instance()
    {
        // Arrange
        SupplierMaterial::factory()->create();

        // Act
        $result = SupplierMaterial::getSupplierMaterialByKeyword('');

        // Assert
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $result);
    }
}