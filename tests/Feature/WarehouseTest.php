<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Warehouse;
use App\Constants\Messages;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WarehouseTest extends TestCase
{
    use RefreshDatabase; // Mengosongkan database setiap kali test dijalankan

    /** @test */
    public function it_can_delete_a_warehouse_via_api()
    {
        // 1. Persiapan: Buat data warehouse di database
        $warehouse = Warehouse::factory()->create();

        // 2. Aksi: Panggil route/fungsi delete (asumsikan menggunakan API)
        $response = $this->deleteJson("/api/warehouses/{$warehouse->id}");

        // 3. Verifikasi: Cek apakah statusnya 200/302 dan data hilang dari DB
        $response->assertStatus(200); 
        $this->assertDatabaseMissing('warehouses', ['id' => $warehouse->id]);
    }

    /** @test */
    public function it_returns_404_when_warehouse_not_found()
    {
        // Aksi: Hapus ID yang tidak ada (misal: ID 999)
        $response = $this->deleteJson("/api/warehouses/999");

        // Verifikasi: Cek response JSON sesuai dengan baris 189-192 di kode Anda
        $response->assertStatus(404)
                 ->assertJson([
                     'success' => false,
                     'message' => Messages::WAREHOUSE_NOT_FOUND
                 ]);
    }
}