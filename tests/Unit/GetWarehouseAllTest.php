<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Warehouse;

class GetWarehouseAllTest extends TestCase
{
    /** @test */
    public function it_returns_paginated_warehouses()
    {
        // Hitung jumlah data sebelum test
        $beforeCount = Warehouse::count();

        // Tambah 15 data baru untuk kebutuhan test in
        for ($i = 1; $i <= 15; $i++) {
            Warehouse::create([
                'warehouse_name' => 'GudangBarang ' . $i,
                'warehouse_address' => 'Alamat ' . $i,
                'warehouse_telephone' => '0812345678' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'is_rm_whouse' => 0,
                'is_fg_whouse' => 1,
                'is_active' => 1,
            ]);
        }

        // Ambil data hasil dari function yang sedang diuji
        $result = Warehouse::getWarehouseAll();

        // Cek: halaman pertama hanya berisi 10 data
        $this->assertEquals(10, $result->count());

        // Hitung total data sekarang
        $totalNow = $beforeCount + 15;
        $expectedLastPage = ceil($totalNow / 10);

        // Cek: jumlah halaman sesuai
        $this->assertEquals($expectedLastPage, $result->lastPage());
    }
}
