<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Warehouse;
use App\Http\Controllers\WarehouseController;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WarehouseAddControllerTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function berhasil_menambahkan_warehouse_dengan_data_valid()
    {

        $data = [
            'warehouse_name' => 'Gudang Coba Aja',
            'warehouse_address' => 'Jl. Baru',
            'warehouse_telephone' => '0822',
            'is_rm_whouse' => false,
            'is_fg_whouse' => true,
            'is_active' => true,
        ];

        $response = $this->postJson('/warehouse/add', $data);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Warehouse berhasil ditambahkan.',
        ]);

        $this->assertDatabaseHas('warehouse', [
            'warehouse_name' => $data['warehouse_name'],
        ]);
    }

    /** @test */
    public function gagal_menambahkan_warehouse_dengan_nama_kurang_dari_3_karakter()
    {

        $data = [
            'warehouse_name' => 'AB',
            'warehouse_address' => 'Jl. Test',
            'warehouse_telephone' => '0811',
            'is_rm_whouse' => true,
            'is_fg_whouse' => false,
            'is_active' => true,
        ];

        $response = $this->postJson('/warehouse/add', $data);
        $response->assertStatus(200);
        $response->assertJsonValidationErrors(['warehouse_name']);
    }

    /** @test */
    public function gagal_menambahkan_warehouse_dengan_nama_yang_sama()
    {
        $data = [
            'warehouse_name' => 'Gudang A',
            'warehouse_address' => 'Alamat',
            'warehouse_telephone' => '0812',
            'is_rm_whouse' => true,
            'is_fg_whouse' => false,
            'is_active' => true,
        ];

        $response = $this->postJson('/warehouse/add', $data);

        $response->assertStatus(200);
        $response->assertJsonValidationErrors(['warehouse_name']);
    }

    
}
