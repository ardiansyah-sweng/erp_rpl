<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\SupplierMaterialModel;

class GetSupplierMaterialByProductTypeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test getSupplierMaterialByProductType returns correct data.
     */
    public function test_get_supplier_material_by_product_type(): void
    {
        // Insert dummy product
        \DB::table('products')->insert([
            'product_id' => 'P001',
            'product_name' => 'Dummy Product',
            'product_type' => 'chemical',
            'product_category' => 1,
            'product_description' => 'Sample Chemical',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Insert dummy supplier_product
        \DB::table('supplier_product')->insert([
            'supplier_id' => 'SUP001',
            'company_name' => 'Supplier Test Co.',
            'product_id' => 'P001',
            'product_name' => 'Dummy Product',
            'base_price' => 10000,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $result = SupplierMaterialModel::getSupplierMaterialByProductType('SUP001', 'chemical');

        $this->assertNotEmpty($result);
        $this->assertEquals('P001', $result[0]->product_id);
        $this->assertEquals('chemical', $result[0]->product_type);
        $this->assertEquals('Sample Chemical', $result[0]->product_description);
    }
}
