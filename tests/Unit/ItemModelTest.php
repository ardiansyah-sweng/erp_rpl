<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Item; 
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;

class ItemModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_get_item_by_id_berhasil()
    {
        // 1. Matikan aturan database (Biar gak error Foreign Key)
        Schema::disableForeignKeyConstraints();

        // 2. Buat Data Dummy
        // pakai forceCreate agar data pasti masuk
        $item = Item::forceCreate([
            'id' => 777,
            'item_name' => 'Sepatu',
            'product_id' => 'P-99', 
            'sku' => 'SKU-777',     
            'measurement_unit' => 1 
        ]);

        // 3. Panggil Fungsi getItemByID
        $hasil = Item::getItemByID(777);

        // 4. Cek Hasil
        $this->assertNotNull($hasil, 'Data tidak ditemukan (null)');
        $this->assertEquals(777, $hasil->id);
        $this->assertEquals('Barang Tes Ikhya', $hasil->item_name);
    }
}
