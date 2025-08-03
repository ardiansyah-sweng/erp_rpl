<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Supplier;

class GetSupplierTest extends TestCase
{
    /** @test */
    public function it_loads_the_supplier_list_view_with_data()
    {
        // Ambil data supplier dari database
        $supplier = Supplier::first();
        if (!$supplier) {
            $this->markTestSkipped('Tidak ada data supplier tersedia di database.');
        }

        // Akses halaman
        $response = $this->get('/supplier/list');

        // Pastikan status OK
        $response->assertStatus(200);

        // Pastikan view yang digunakan benar
        $response->assertViewIs('supplier.list');

        // Pastikan variabel suppliers tersedia di view
        $response->assertViewHas('suppliers');

        // Ambil data view untuk pengecekan lanjut
        $viewSuppliers = $response->viewData('suppliers');

        // Pastikan data yang dikembalikan tidak kosong
        $this->assertNotEmpty($viewSuppliers);

        // Pastikan salah satu data sesuai
        $this->assertEquals($supplier->supplier_id, $viewSuppliers->first()->supplier_id);
    }

}
