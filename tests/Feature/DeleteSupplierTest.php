<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Supplier;

class DeleteSupplierTest extends TestCase
{
    public function test_can_delete_supplier_by_id()
    {
        $supplierId = 'SUP002';

        $supplier = Supplier::where('supplier_id', $supplierId)->first();
        $this->assertNotNull($supplier, 'Supplier tidak ditemukan di database.');

        $result = Supplier::deleteSupplier($supplierId);

        $this->assertTrue($result['success']);
        $this->assertEquals('Supplier berhasil dihapus.', $result['message']);

        $this->assertDatabaseMissing('supplier', [
            'supplier_id' => $supplierId
        ]);
    }


}
