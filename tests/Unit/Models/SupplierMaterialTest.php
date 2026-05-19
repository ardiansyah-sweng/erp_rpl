<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\SupplierMaterial;
use Illuminate\Support\Facades\DB;

class SupplierMaterialTest extends TestCase
{
    use RefreshDatabase;

    private function seedData()
    {
        DB::table('categories')->insert([
            ['id' => 1, 'category' => 'Bahan Baku', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'category' => 'Kemasan',    'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('products')->insert([
            ['product_id' => 'P001', 'name' => 'Tepung', 'type' => 'RM', 'category' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['product_id' => 'P002', 'name' => 'Gula',   'type' => 'RM', 'category' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('item')->insert([
            ['sku' => 'P001-01', 'item_name' => 'Tepung Terigu', 'product_id' => 'P001', 'measurement_unit' => 'kg', 'stock_unit' => 'kg', 'created_at' => now(), 'updated_at' => now()],
            ['sku' => 'P002-01', 'item_name' => 'Gula Pasir',    'product_id' => 'P002', 'measurement_unit' => 'kg', 'stock_unit' => 'kg', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('supplier_product')->insert([
            ['supplier_id' => 'SUP001', 'company_name' => 'PT A', 'product_id' => 'P001-01', 'product_name' => 'Tepung Terigu', 'base_price' => 10000, 'created_at' => now(), 'updated_at' => now()],
            ['supplier_id' => 'SUP001', 'company_name' => 'PT A', 'product_id' => 'P002-01', 'product_name' => 'Gula Pasir',    'base_price' => 15000, 'created_at' => now(), 'updated_at' => now()],
            ['supplier_id' => 'SUP002', 'company_name' => 'PT B', 'product_id' => 'P001-01', 'product_name' => 'Tepung Terigu', 'base_price' => 11000, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /** @test */
    public function it_counts_material_by_category_and_supplier_correctly()
    {
        $this->seedData();

        $count = SupplierMaterial::countSupplierMaterialByCategory(1, 'SUP001');

        $this->assertEquals(1, $count);
    }

    /** @test */
    public function it_returns_zero_when_supplier_has_no_material_in_category()
    {
        $this->seedData();

        $count = SupplierMaterial::countSupplierMaterialByCategory(2, 'SUP002');

        $this->assertEquals(0, $count);
    }

    /** @test */
    public function it_returns_zero_for_nonexistent_category()
    {
        $this->seedData();

        $count = SupplierMaterial::countSupplierMaterialByCategory(99, 'SUP001');

        $this->assertEquals(0, $count);
    }

    /** @test */
    public function it_returns_zero_for_nonexistent_supplier()
    {
        $this->seedData();

        $count = SupplierMaterial::countSupplierMaterialByCategory(1, 'SUP999');

        $this->assertEquals(0, $count);
    }

    /** @test */
    public function it_counts_correctly_when_multiple_suppliers_share_same_category()
    {
        $this->seedData();

        $countSup1 = SupplierMaterial::countSupplierMaterialByCategory(1, 'SUP001');
        $countSup2 = SupplierMaterial::countSupplierMaterialByCategory(1, 'SUP002');

        $this->assertEquals(1, $countSup1);
        $this->assertEquals(1, $countSup2);
    }
}
