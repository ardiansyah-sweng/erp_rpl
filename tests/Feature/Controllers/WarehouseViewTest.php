<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\User; // Jika halaman membutuhkan login
use Illuminate\Foundation\Testing\RefreshDatabase;

class WarehouseViewTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test untuk memastikan halaman 'Add Warehouse' bisa terbuka.
     */
    public function test_warehouse_add_view_can_be_rendered(): void
    {
        // 1. Jika aplikasi Anda mewajibkan login, buat user dummy
        $user = User::factory()->create();

        // 2. Aksi: Buka halaman form tambah gudang
        $response = $this->actingAs($user)->get('/warehouses/create');

        // 3. Verifikasi status sukses
        $response->assertStatus(200);
    }
}