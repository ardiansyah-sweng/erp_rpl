<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Warehouse;
use App\Http\Controllers\WarehouseController;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WarehouseAddControllerTest extends TestCase
{

    /** @test */
    public function gagal_menambahkan_warehouse_dengan_nama_kurang_dari_3_karakter()
    {
        $controller = new WarehouseController();

        $data = [
            'warehouse_name' => 'AB',
            'warehouse_address' => 'Jl. Test',
            'warehouse_telephone' => '0811',
            'is_rm_whouse' => true,
            'is_fg_whouse' => false,
            'is_active' => true,
        ];

        $response = $controller->addWarehouse($data);

        $this->assertFalse($response['success']);
        $this->assertArrayHasKey('warehouse_name', $response['errors']->toArray());
    }

    /** @test */
    public function gagal_menambahkan_warehouse_dengan_nama_yang_sama()
    {
        $controller = new WarehouseController();

        $data = [
            'warehouse_name' => 'Gudang A',
            'warehouse_address' => 'Alamat',
            'warehouse_telephone' => '0812',
            'is_rm_whouse' => true,
            'is_fg_whouse' => false,
            'is_active' => true,
        ];

        $controller->addWarehouse($data);
        $response = $controller->addWarehouse($data); 

        $this->assertFalse($response['success']);
        $this->assertArrayHasKey('warehouse_name', $response['errors']->toArray());
    }

    /** @test */
    public function berhasil_menambahkan_warehouse_dengan_data_valid()
    {
        $controller = new WarehouseController();

        $data = [
            'warehouse_name' => 'Gudang Coba Unik 123',
            'warehouse_address' => 'Jl. Baru',
            'warehouse_telephone' => '0822',
            'is_rm_whouse' => false,
            'is_fg_whouse' => true,
            'is_active' => true,
        ];

        $response = $controller->addWarehouse($data);

        $this->assertTrue($response['success']);
        $this->assertEquals('Warehouse berhasil ditambahkan.', $response['message']);

        $this->assertDatabaseHas('warehouse', [
            'warehouse_name' => 'Gudang Baru',
        ]);
    }
}
