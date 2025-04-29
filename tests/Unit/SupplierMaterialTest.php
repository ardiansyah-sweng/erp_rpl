<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\SupplierMaterial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class SupplierMaterialTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_update_supplier_material()
    {
        // Create test data
        $testData = [
            'supplier_id' => 'SUP001',
            'company_name' => 'Test Company',
            'product_id' => 'PRD001',
            'product_name' => 'Test Product',
            'base_price' => 100000,
            'created_at' => now(),
            'updated_at' => now()
        ];

        // Insert initial data
        DB::table('supplier_product')->insert($testData);

        // Update data
        $updateData = [
            'company_name' => 'Updated Company',
            'product_name' => 'Updated Product',
            'base_price' => 150000,
            'updated_at' => now()
        ];

        $result = SupplierMaterial::updateSupplierMaterial(
            $testData['supplier_id'],
            $testData['product_id'],
            $updateData
        );

        // Assert update was successful
        $this->assertTrue($result > 0);

        // Verify updated data
        $updated = DB::table('supplier_product')
            ->where('supplier_id', $testData['supplier_id'])
            ->where('product_id', $testData['product_id'])
            ->first();
            
        $this->assertEquals('Updated Company', $updated->company_name);
        $this->assertEquals('Updated Product', $updated->product_name);
        $this->assertEquals(150000, $updated->base_price);
    }
}