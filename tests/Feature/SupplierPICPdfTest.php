<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class SupplierPICPdfTest extends TestCase
{
    public function test_generate_pdf_for_valid_supplier_id()
    {
        // Ambil 1 data supplier_pic dari database
        $supplierPic = DB::table('supplier_pic')->first();

        $this->assertNotNull($supplierPic, 'Tidak ada data di tabel supplier_pic.');

        // Panggil endpoint PDF
        $response = $this->get(route('supplier.pic.pdf', [
            'supplier_id' => $supplierPic->supplier_id,
        ]));

        // Assert sukses
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
        $this->assertStringContainsString('%PDF', $response->getContent());
    }

    public function test_returns_404_when_supplier_id_not_found()
    {
        // Panggil endpoint dengan supplier_id yang tidak ada
        $response = $this->get(route('supplier.pic.pdf', [
            'supplier_id' => 'SUP999_DOES_NOT_EXIST',
        ]));

        // Karena kamu return JSON error 404 jika kosong
        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Data not found',
        ]);
    }
}