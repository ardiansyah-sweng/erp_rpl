<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Warehouse;
use App\Models\User; // Asumsi model User untuk otentikasi

class WarehouseControllerTest extends TestCase
{
    // Pastikan database bersih sebelum setiap tes
    use RefreshDatabase; 

    // ----------------------------------------------------------------------
    // TEST CASE 1: Berhasil Menghapus Warehouse
    // ----------------------------------------------------------------------

    /** @test */
    public function it_can_delete_a_warehouse_successfully()
    {
        // 1. ARRANGE: Siapkan user dan data warehouse yang akan dihapus
        $user = User::factory()->create(); 
        $warehouse = Warehouse::factory()->create(); 

        // 2. ACT: Lakukan HTTP request DELETE, bertindak sebagai user yang terotentikasi
        $response = $this->actingAs($user) 
                         // Menggunakan route name dan ID warehouse
                         ->delete(route('warehouses.destroy', $warehouse->id));

        // 3. ASSERT: Verifikasi hasil

        // a. Pastikan status response adalah sukses (200 OK)
        $response->assertStatus(200); 

        // b. Verifikasi data sudah TIDAK ADA di database (Paling Penting!)
        $this->assertDatabaseMissing('warehouses', [
            'id' => $warehouse->id,
        ]);
        
        // c. (Opsional) Verifikasi struktur JSON response
        $response->assertJsonStructure(['data', 'message']);
    }

    // ----------------------------------------------------------------------
    // TEST CASE 2: Gagal (ID Tidak Ditemukan)
    // ----------------------------------------------------------------------

    /** @test */
    public function it_returns_404_when_deleting_a_non_existent_warehouse()
    {
        // 1. ARRANGE: Siapkan user dan ID fiktif
        $user = User::factory()->create();
        $nonExistentId = 999; // ID yang pasti tidak ada

        // 2. ACT: Lakukan request DELETE ke ID fiktif
        $response = $this->actingAs($user)
                         ->delete(route('warehouses.destroy', $nonExistentId));

        // 3. ASSERT: Verifikasi status 404 Not Found
        // (Ini sesuai dengan logic `if (!$warehouse)` di controller Anda)
        $response->assertStatus(404) 
                 ->assertJson([
                    // Sesuaikan pesan 404/not found yang sebenarnya
                    'message' => 'Resource not found' 
                 ]); 
    }
}