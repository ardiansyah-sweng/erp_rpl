<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Product; 
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;

class ProductModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_update_product_berhasil()
    {
        Schema::disableForeignKeyConstraints();

        $product = Product::forceCreate([
            'product_name'        => 'Tes Awal',
            'product_id'          => 'A1', // ID Pendek (Aman)
            'product_type'        => 'RM', 
            'product_category'    => 1,
            
            'product_description' => 'Deskripsi dummy biar gak error' 
        ]);

        $dataUpdate = [
            'product_name' => 'Tes Sukses'
        ];

        $hasil = Product::updateProduct($product->id, $dataUpdate);

        $this->assertNotNull($hasil);
        
        $cekDb = Product::find($product->id);
        $this->assertEquals('Tes Sukses', $cekDb->product_name);
    }
}