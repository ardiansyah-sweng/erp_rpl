<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddWarehouseTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test sukses menambahkan data Warehouse baru dengan field yang benar.
     */
    public function test_add_warehouse_success(): void
    {
        // 1. Siapkan data payload dengan key yang sesuai dengan validasi di Controller
        $payload = [
            'warehouse_name'    => 'Gudang Utama Jakarta',
            'warehouse_address' => 'Jakarta Utara',
            'warehouse_phone'   => '021-12345678'
        ];

        // 2. Aksi: Kirim request POST ke endpoint
        $response = $this->postJson('/api/warehouses', $payload);

        // 3. Verifikasi response status (201 Created)
        $response->assertStatus(201);

        // 4. Verifikasi data benar-benar masuk ke database
        $this->assertDatabaseHas('warehouses', [
            'warehouse_name' => 'Gudang Utama Jakarta'
        ]);
    }

    /**
     * Test validasi gagal jika field wajib tidak diisi.
     */
    public function test_add_warehouse_validation_error(): void
    {
        // Kirim data kosong untuk memicu error validasi
        $response = $this->postJson('/api/warehouses', []);

        // Berharap status 422 (Unprocessable Entity)
        $response->assertStatus(422);
        
        // Verifikasi pesan error spesifik muncul
        $response->assertJsonValidationErrors(['warehouse_name', 'warehouse_address', 'warehouse_phone']);
    }
}