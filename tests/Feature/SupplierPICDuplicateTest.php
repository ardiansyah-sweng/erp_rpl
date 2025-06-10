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

    /** @test */
    public function testTidakDuplikat()
    {
        $response = $this->post('/supplier/SUP001/add-pic', [
            'supplier_id' => 'SUP001',
            'name' => 'Eki Saputra',
            'email' => 'saputra.eki@gmail.com',
            'phone_number' => '+62 877-000-0001',
            'assigned_date' => now()->format('d/m/Y'),
            'supplier_name' => 'Quantum Selera Tbk',
        ]);

        $response->assertSessionDoesntHaveErrors();
        $response->assertSessionHas('success', 'PIC berhasil ditambahkan!');
        $this->assertDatabaseHas('supplier_pic', [
            'supplier_id' => 'SUP001',
            'name' => 'Eki Saputra',
            'email' => 'saputra.eki@gmail.com',
            'phone_number' => '+62 877-000-0001',
        ]);
    }

}
