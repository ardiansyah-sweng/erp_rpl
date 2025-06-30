<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\SupplierMaterialModel;

class GetSupplierMaterialByProductTypeTest extends TestCase
{
    /**
     * Test getSupplierMaterialByProductType using real data.
     */
   public function test_get_supplier_material_by_product_type_example(): void
    {
    $supplierId = 'SUP001';  // SUP001 menyuplai produk P001-maiores
    $productType = 'RM';     // P001 di tabel products bertipe RM

    $result = SupplierMaterialModel::getSupplierMaterialByProductType($supplierId, $productType);

    $this->assertNotEmpty($result);

    foreach ($result as $item) {
        $this->assertEquals($supplierId, $item->supplier_id);
        $this->assertEquals($productType, $item->product_type);
    }
    }

}
