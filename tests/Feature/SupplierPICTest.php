<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\SupplierPICModel;

class SupplierPICTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_update_supplier_pic_detail()
    {
        // 1. Buat data awal
        $pic = SupplierPICModel::factory()->create([
            'supplier_id' => 'SUP001',
            'name' => 'Nama Lama',
            'phone_number' => '081111111',
            'email' => 'lama@example.com',
            'assigned_date' => '2025-01-01'
        ]);

        // 2. Data baru
        $updateData = [
            'supplier_id' => 'SUP001',
            'name' => 'Nama Baru Update',
            'phone_number' => '082222222',
            'email' => 'baru@example.com',
            'assigned_date' => '2026-01-01'
        ];

        // 3. Jalankan aksi (Method PUT sesuai web.php)
        $response = $this->put("/supplier-pic/update/{$pic->id}", $updateData);

        // 4. Verifikasi status 200 (karena Controller return JSON)
        $response->assertStatus(200);

        // 5. Verifikasi database
        $this->assertDatabaseHas('supplier_pics', [
            'id' => $pic->id,
            'name' => 'Nama Baru Update'
        ]);
    }
}