<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Product;
use App\Models\SupplierMaterial;

class SupplierMaterialTest extends TestCase
{
    use RefreshDatabase;

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
}
