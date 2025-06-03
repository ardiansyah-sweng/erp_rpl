<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\SupplierPic;

class SupplierPICDuplicateTest extends TestCase
{
    /** @test */
    public function testDuplikat()
    {
        // Ambil data secara acak dari tabel supplier_pic
        $pic = SupplierPic::inRandomOrder()->first();

        if (!$pic) {
            $this->markTestSkipped('Data supplier_pic tidak tersedia.');
            return;
        }

        // Kirim request dengan data yang sama
        $response = $this->post("/supplier/{$pic->supplier_id}/add-pic", [
            'supplier_id' => $pic->supplier_id,
            'name' => $pic->name,
            'email' => $pic->email,
            'phone_number' => $pic->phone_number,
            'assigned_date' => now()->format('d/m/Y'),
            'supplier_name' => $pic->supplier_name ?? 'Nama Supplier',
        ]);

        // Test berhasil jika muncul error validasi 'duplicate'
        $response->assertSessionHasErrors('duplicate');
    }
}
