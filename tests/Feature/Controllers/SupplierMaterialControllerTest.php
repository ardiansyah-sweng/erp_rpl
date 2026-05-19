<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class SupplierMaterialControllerTest extends TestCase
{
    use RefreshDatabase;

    private function seedData()
    {
        DB::table('categories')->insert([
            ['id' => 1, 'category' => 'Bahan Baku', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('products')->insert([
            ['product_id' => 'P001', 'name' => 'Tepung', 'type' => 'RM', 'category' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('item')->insert([
            ['sku' => 'P001-01', 'item_name' => 'Tepung Terigu', 'product_id' => 'P001', 'measurement_unit' => 'kg', 'stock_unit' => 'kg', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('supplier_product')->insert([
            ['supplier_id' => 'SUP001', 'company_name' => 'PT A', 'product_id' => 'P001-01', 'product_name' => 'Tepung Terigu', 'base_price' => 10000, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function test_returns_correct_count_for_valid_category_and_supplier()
    {
        $this->seedData();

        $response = $this->get('/supplier-material/category/1/SUP001');

        $response->assertStatus(200);
        $response->assertJson([
            'category' => '1',
            'supplier' => 'SUP001',
            'total'    => 1,
        ]);
    }

    public function test_returns_zero_when_supplier_has_no_material_in_category()
    {
        $this->seedData();

        $response = $this->get('/supplier-material/category/1/SUP999');

        $response->assertStatus(200);
        $response->assertJson([
            'category' => '1',
            'supplier' => 'SUP999',
            'total'    => 0,
        ]);
    }

    public function test_returns_zero_for_nonexistent_category()
    {
        $this->seedData();

        $response = $this->get('/supplier-material/category/99/SUP001');

        $response->assertStatus(200);
        $response->assertJson([
            'category' => '99',
            'supplier' => 'SUP001',
            'total'    => 0,
        ]);
    }
}
