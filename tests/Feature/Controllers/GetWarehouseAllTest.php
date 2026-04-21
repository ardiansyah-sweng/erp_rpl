<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetWarehouseAllTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test sukses mendapatkan semua data warehouse.
     */
    public function test_get_warehouse_all_success(): void
    {
        // 1. Buat data dummy di database dengan field yang sesuai
        Warehouse::create([
            'warehouse_name'    => 'Gudang Jakarta',
            'warehouse_address' => 'Jakarta Utara',
            'warehouse_phone'   => '021111'
        ]);

        Warehouse::create([
            'warehouse_name'    => 'Gudang Surabaya',
            'warehouse_address' => 'Surabaya Timur',
            'warehouse_phone'   => '031222'
        ]);

        // 2. Aksi: Panggil endpoint API
        $response = $this->getJson('/api/warehouses');

        // 3. Verifikasi
        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data'); // Cek jumlah data di dalam 'data' array
        $response->assertJsonFragment(['warehouse_name' => 'Gudang Jakarta']);
        $response->assertJsonFragment(['warehouse_name' => 'Gudang Surabaya']);
    }
}