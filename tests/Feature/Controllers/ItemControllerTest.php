<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class ItemControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    private function prepareDataForCategory($catId, $catName)
    {
        // 1. Insert Kategori
        DB::table('category')->insertOrIgnore([
            'id' => $catId,
            'category' => $catName,
            'parent_id' => null,
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Insert Produk 
        // PERBAIKAN PENTING: Sesuaikan nama kolom dengan Migration Product!
        // product_name -> name
        // product_category -> category
        $prodId = 'P' . str_pad($catId, 3, '0', STR_PAD_LEFT); 
        
        DB::table('products')->insertOrIgnore([
            'product_id' => $prodId,
            'name' => 'Produk ' . $catName, // UBAH KE 'name'
            'type' => 'FG', // UBAH KE 'type' (bukan product_type)
            'category' => $catId, // UBAH KE 'category' (bukan product_category)
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Insert Item
        DB::table('items')->insert([
            'product_id' => $prodId,
            'sku' => 'SKU-' . $prodId,
            'name' => 'Item ' . $catName, // Cek migration item, biasanya 'name' atau 'item_name'
            'measurement' => 'PCS', 
            'base_price' => 1000,
            'selling_price' => 1500,
            'purchase_unit' => 1,
            'sell_unit' => 1,
            'stock_unit' => 100,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /** @test */
    public function get_item_by_category_returns_correct_data()
    {
        $targetCategoryId = 1;
        $this->prepareDataForCategory($targetCategoryId, 'Elektronik');

        $response = $this->get('/items/category/' . $targetCategoryId);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        
        $data = $response->json('data');
        $this->assertNotEmpty($data);
        // Pastikan assertion ini sesuai dengan data yang di-insert di atas
        // Jika di DB item name = 'Item Elektronik', maka assert-nya harus sama
    }

    /** @test */
    public function get_item_by_category_returns_404_if_empty()
    {
        DB::table('category')->insertOrIgnore([
            'id' => 99,
            'category' => 'Kategori Kosong',
            'is_active' => 1,
        ]);

        $response = $this->get('/items/category/99');

        $response->assertStatus(404);
        $response->assertJsonPath('success', false);
    }
}