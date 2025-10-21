<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CountWarehouse_Test extends TestCase
{
    use RefreshDatabase;
    
    public function test_count_warehouse()
    {
        for ($i = 0; $i < 65; $i++) {
            Warehouse::create([
                'warehouse_name' => 'Gudang ' . $i,
                'warehouse_address' => 'Jl. Contoh No. ' . $i,
                'warehouse_telephone' => '08123456789',
                'is_rm_whouse' => 0,
                'is_fg_whouse' => 1,
                'is_active' => 1,
            ]);
        }
        
        $count = Warehouse::count();
        dump("Jumlah warehouse:", $count);
        
        $this->assertEquals(65, $count);
    }

    // Jumlah Warehouse yang aktif
    public function test_count_active_warehouse()
    {
        // Arrange: Buat data warehouse
        Warehouse::factory()->count(10)->create(['is_active' => 1]);
        Warehouse::factory()->count(5)->create(['is_active' => 0]);

        // Act: Hitung warehouse aktif
        $activeCount = Warehouse::countActiveWarehouse();

        // Assert: Periksa jumlah warehouse aktif
        $this->assertEquals(10, $activeCount);
    }

    // Jumlah Warehouse tidak aktif
    public function test_count_inactive_warehouse()
    {
        // Arrange: Buat data warehouse
        Warehouse::factory()->count(10)->create(['is_active' => 1]);
        Warehouse::factory()->count(5)->create(['is_active' => 0]);

        // Act: Hitung warehouse nonaktif
        $inactiveCount = Warehouse::countInactiveWarehouse();

        // Assert: Periksa jumlah warehouse nonaktif
        $this->assertEquals(5, $inactiveCount);
    }
}
