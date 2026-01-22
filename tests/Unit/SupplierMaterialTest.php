<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\SupplierMaterial; 
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SupplierMaterialTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_get_supplier_material_by_product_type_berhasil()
    {
        // 1. Matikan aturan Foreign Key
        Schema::disableForeignKeyConstraints();

        $now = Carbon::now();

        // 2. Data Dummy Produk
        DB::table('products')->insert([
            'product_id'          => 'P1', 
            'product_name'        => 'Sepatu Sekolah',
            'product_description' => 'Deskripsi sepatu',
            'product_type'        => 'FG', 
            'product_category'    => 1,
            'created_at'          => $now,
            'updated_at'          => $now
        ]);

        // 3. Data Dummy Item
        DB::table('item')->insert([
            'sku'              => 'K1',
            'item_name'        => 'Sepatu Hitam Polos',
            'product_id'       => 'P1', 
            'measurement_unit' => 'Pasang',
            'stock_unit'       => 50,
            'created_at'       => $now,
            'updated_at'       => $now
        ]);

        // 4. Data Dummy Supplier
        DB::table('supplier_product')->insert([
            'supplier_id'  => 'S1',
            'company_name' => 'Toko Sepatu Bata',
            'product_id'   => 'K1', 
            'product_name' => 'Sepatu Hitam Polos', // <--- INI KUNCINYA
            'base_price'   => 150000,
            'created_at'   => $now,
            'updated_at'   => $now
        ]);

        // 5. Test Fungsi
        $hasil = SupplierMaterial::getSupplierMaterialByProductType('S1', 'FG');

        // 6. Cek Hasil
        $this->assertNotEmpty($hasil, 'Data tidak ditemukan! Cek query join-nya.');
        $this->assertEquals(1, $hasil->count(), 'Harusnya ada 1 sepatu.');
        $this->assertEquals('Sepatu Hitam Polos', $hasil->first()->item_name);
    }
}