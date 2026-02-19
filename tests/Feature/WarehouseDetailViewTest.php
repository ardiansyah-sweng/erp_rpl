<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Warehouse;

class WarehouseDetailViewTest extends TestCase
{
    public function test_warehouse_detail_view_shows_information()
    {
        $warehouse = Warehouse::factory()->create([
            'warehouse_name' => 'Gudang Utama',
            'warehouse_address' => 'Jl. Test 123',
            'warehouse_phone' => '08123456789',
            'is_rm_warehouse' => true,
            'is_fg_warehouse' => false,
            'is_active' => true,
        ]);

        $response = $this->get(route('warehouse.detail', $warehouse->id));

        $response->assertStatus(200);
        $response->assertSee('Detail Warehouse');
        $response->assertSee('Gudang Utama');
        $response->assertSee('Jl. Test 123');
        $response->assertSee('08123456789');
        $response->assertSee('Ya');
        $response->assertSee('Tidak');
        $response->assertSee('Aktif');
    }
}
