<?php

namespace Tests\Feature;

use App\Models\SupplierMaterial;
use App\Models\SupplierProduct;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
// use Faker\Factory as Faker;

class CountSupplierMaterialFoundByKeywordTest extends TestCase
{
    use RefreshDatabase;

    public function test_countSupplierMaterialFoundByKeyword(): void
    {
        for ($i = 0; $i < 10; $i++){

            DB::table('supplier_product')->insert([
                'supplier_id'   => 'SUP01' . $i, 
                'company_name'  => 'PT Maju' . $i,
                'product_id'    => 'P0012' . $i,
                'product_name'  => 'Medium 01' . $i,
                'base_price'    => '1000' . $i,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }

        $count = DB::table('supplier_product')->count();
        dump("Jumlah Supplier Material:", $count);
        
        $this->assertEquals(10, $count);
    }
}
