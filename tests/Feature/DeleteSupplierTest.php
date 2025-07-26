<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Supplier;

class DeleteSupplierTest extends TestCase
{
    public function test_cannot_delete_supplier_by_id()
    {
        $supplierId = 'SUP003';

        $supplier = Supplier::where('supplier_id', $supplierId)->first();
        $this->assertNotNull($supplier, 'Supplier tidak ditemukan di database.');

        $result = Supplier::deleteSupplier($supplierId);

        $this->assertFalse($result['success']);
        $this->assertEquals('Supplier tidak dapat dihapus karena sudah memiliki PO.', $result['message']);
    }



}
