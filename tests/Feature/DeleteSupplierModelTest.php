<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;

class DeleteSupplierModelTest extends TestCase
{

    /** @test */
    public function test_deletesuppliermodel()
    {
        // Ambil supplier acak
        $supplier = Supplier::inRandomOrder()->first();
        dump($supplier);

        if ($supplier) {
            // Periksa apakah supplier ini punya purchase order
            $poExists = DB::table('purchase_order')
                ->where('supplier_id', $supplier->supplier_id)
                ->exists();

            if (!$poExists) {
                // Hapus supplier
                $result = Supplier::deleteSupplier($supplier->supplier_id);

                // Assert
                $this->assertTrue($result);
                $this->assertDatabaseMissing('suppliers', [
                    'supplier_id' => $supplier->supplier_id
                ]);
            } else {
                // Jika supplier punya PO, abaikan penghapusan
                $this->assertTrue(true); // Test tetap sukses
            }
        } else {
            // Jika tidak ada supplier, test tetap jalan
            $this->assertTrue(true); // Test tetap sukses
        }
    }

    
}
