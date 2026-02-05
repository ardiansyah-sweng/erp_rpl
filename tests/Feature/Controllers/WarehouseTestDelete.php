<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\Warehouse; // Sesuaikan dengan path model Anda
use Illuminate\Foundation\Testing\RefreshDatabase;

class WarehouseTestDelete extends TestCase
{
    // Gunakan trait ini agar data testing tidak mengotori database asli
    use RefreshDatabase;

    /** @test */
    public function it_can_delete_a_warehouse()
    {
        // 1. Persiapan: Buat data dummy di database
        $warehouse = Warehouse::factory()->create();

        // 2. Aksi: Panggil route delete
        // Menggunakan API route /api/warehouses/{id}
        $response = $this->deleteJson("/api/warehouses/{$warehouse->id}");

        // 3. Verifikasi: Pastikan data sudah hilang dari database
        $this->assertDatabaseMissing('warehouses', [
            'id' => $warehouse->id
        ]);

        // 4. Verifikasi: Cek apakah respon sukses (API returns 200/204)
        $response->assertStatus(200); // API biasanya return 200 untuk success
    }
}