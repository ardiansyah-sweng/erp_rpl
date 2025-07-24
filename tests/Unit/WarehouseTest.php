<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WarehouseTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_add_warehouse_if_valid_data()
    {
        $data = [
            'warehouse_name'      => 'Gudang Utama',
            'warehouse_address'   => 'Jl. Industri No. 123',
            'warehouse_telephone' => '081234567890',
            'is_rm_whouse'        => true,
            'is_fg_whouse'        => false,
            'is_active'           => true,
        ];

        $warehouse = Warehouse::addWarehouse($data);

        $this->assertInstanceOf(Warehouse::class, $warehouse);
        $this->assertEquals('Gudang Utama', $warehouse->warehouse_name);
        $this->assertDatabaseHas('warehouse', $data);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_add_warehouse_if_empty_data()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Data tidak boleh kosong.');

        Warehouse::addWarehouse([]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_update_existing_warehouse()
    {
        // Arrange: Buat warehouse awal
        $warehouse = Warehouse::create([
            'warehouse_name'     => 'Gudang Lama',
            'warehouse_address'  => 'Jl. Raya No. 1',
            'warehouse_telephone' => '021-12345678',
        ]);

        // Act: Lakukan update
        $data = [
            'warehouse_name'     => 'Gudang Baru',
            'warehouse_address'  => 'Jl. Baru No. 2',
            'warehouse_telephone' => '021-87654321',
        ];

        $result = $warehouse->update($data);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseHas('warehouse', [
            'id'                 => $warehouse->id,
            'warehouse_name'     => 'Gudang Baru',
            'warehouse_address'  => 'Jl. Baru No. 2',
            'warehouse_telephone' => '021-87654321',
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_returns_false_when_updating_non_existing_warehouse()
    {
        // Act: Cari warehouse yang tidak ada
        $warehouse = Warehouse::find(9999);

        // Assert
        $this->assertNull($warehouse);
    }
      public function it_returns_paginated_warehouses()
    {
        for ($i = 1; $i <= 15; $i++) {
            Warehouse::create([
                'warehouse_name'      => 'GudangBarang ' . $i,
                'warehouse_address'   => 'Alamat ' . $i,
                'warehouse_telephone' => '0812345678' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'is_rm_whouse'        => 0,
                'is_fg_whouse'        => 1,
                'is_active'           => 1,
            ]);
        }

        $result = Warehouse::getWarehouseAll();

        $this->assertEquals(10, $result->count()); // Halaman pertama: 10 data
        $this->assertEquals(2, $result->lastPage()); // Total halaman: 2
    }
}
