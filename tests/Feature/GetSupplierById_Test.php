<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Supplier;

class GetSupplierById_Test extends TestCase
{
    /**
     * Test getSupplierById dari model.
     */
    public function test_model_getSupplierById(): void
    {
        // Ambil salah satu supplier secara acak
        $supplier = Supplier::inRandomOrder()->first();
        
        // Pastikan ada data
        $this->assertNotNull($supplier);

        // Panggil method getSupplierById
        $foundSupplier = (new Supplier())->getSupplierById($supplier->supplier_id);

        // Cek apakah data yang ditemukan sesuai
        $this->assertNotNull($foundSupplier);
        $this->assertEquals($supplier->supplier_id, $foundSupplier->supplier_id);
    }

    /**
     * Test getSupplierById melalui route controller.
     */
    public function test_controller_getSupplierById(): void
    {
        // Ambil salah satu supplier secara acak
        $supplier = Supplier::inRandomOrder()->first();

        // Pastikan data tidak kosong
        $this->assertNotNull($supplier);

        // Kirim request ke route yang sesuai
        $response = $this->get('/supplier/detail/' . $supplier->supplier_id);

        // Cek response OK
        $response->assertStatus(200);

        // Opsional: pastikan view berisi data tertentu
        $response->assertViewIs('Supplier.detail');
        $response->assertViewHas('sup', function ($sup) use ($supplier) {
            return $sup->supplier_id === $supplier->supplier_id;
        });
    }
}