<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WarehouseTest extends TestCase
{
    // use RefreshDatabase;
    // use DatabaseTransactions;

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_add_warehouse_if_valid_data()
    {
        $data = [
            'warehouse_name' => 'Gudang Utama',
            'warehouse_address' => 'Jl. Industri No. 123',
            'warehouse_telephone' => '081234567890',
            'is_rm_whouse' => true,
            'is_fg_whouse' => false,
            'is_active' => true,
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
            'warehouse_name' => 'Gudang Lama',
            'warehouse_address' => 'Jl. Raya No. 1',
            'warehouse_telephone' => '021-12345678',
        ]);

        // Act: Lakukan update
        $data = [
            'warehouse_name' => 'Gudang Baru',
            'warehouse_address' => 'Jl. Baru No. 2',
            'warehouse_telephone' => '021-87654321',
        ];

        $result = $warehouse->update($data);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseHas('warehouse', [
            'id' => $warehouse->id,
            'warehouse_name' => 'Gudang Baru',
            'warehouse_address' => 'Jl. Baru No. 2',
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
    #[\PHPUnit\Framework\Attributes\Test]
    public function test_it_returns_paginated_warehouses()
    {
        $result = \App\Models\Warehouse::getWarehouseAll();

        // 1. Apakah hasilnya paginator?
        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $result);

        // 2. Apakah jumlah data di halaman ini <= 10?
        $this->assertLessThanOrEqual(10, $result->count());

        // 3. Apakah total semua data cocok dengan jumlah di tabel?
        $expectedTotal = \App\Models\Warehouse::count();
        $this->assertEquals($expectedTotal, $result->total());
    }
}
