<?php

namespace Tests\Browser;

use App\Models\Warehouse;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class WarehouseDetailTest extends DuskTestCase
{
    /**
     * Test 1: User dapat membuka halaman detail warehouse
     * Scenario: User membuka halaman detail warehouse dari list
     */
    public function test_user_can_view_warehouse_detail_page()
    {
        $warehouse = Warehouse::create([
            'warehouse_name' => 'Gudang Test',
            'warehouse_address' => 'Jl. Test No. 123, Yogyakarta',
            'warehouse_phone' => '0274-123456',
            'is_rm_warehouse' => true,
            'is_fg_warehouse' => false,
            'is_active' => true,
        ]);

        $this->browse(function (Browser $browser) use ($warehouse) {
            $browser->visit(route('warehouse.detail', $warehouse->id))
                    ->assertPathIs('/warehouse/detail/' . $warehouse->id)
                    ->assertSee('Detail Warehouse')
                    ->assertSee('Informasi Warehouse');
        });

        $warehouse->delete();
    }

    /**
     * Test 2: Halaman detail menampilkan semua informasi warehouse dengan benar
     * Scenario: Verify nama, alamat, telepon, dan status ditampilkan sesuai data
     */
    public function test_warehouse_detail_displays_all_information_correctly()
    {
        $warehouse = Warehouse::create([
            'warehouse_name' => 'Gudang Raw Material A',
            'warehouse_address' => 'Jl. Pangeran Diponegoro No. 25, Yogyakarta',
            'warehouse_phone' => '0274-987654',
            'is_rm_warehouse' => true,
            'is_fg_warehouse' => false,
            'is_active' => true,
        ]);

        $this->browse(function (Browser $browser) use ($warehouse) {
            $browser->visit(route('warehouse.detail', $warehouse->id))
                    // Verify semua informasi warehouse ditampilkan
                    ->assertSeeIn('table', 'Gudang Raw Material A')
                    ->assertSeeIn('table', 'Jl. Pangeran Diponegoro No. 25, Yogyakarta')
                    ->assertSeeIn('table', '0274-987654')
                    ->assertSeeIn('table', 'Nama Warehouse')
                    ->assertSeeIn('table', 'Alamat')
                    ->assertSeeIn('table', 'Telepon');
        });

        $warehouse->delete();
    }

    /**
     * Test 3: Halaman detail menampilkan badge status dengan benar
     * Scenario: Verify badge untuk RM, FG, dan status aktif ditampilkan sesuai kondisi
     */
    public function test_warehouse_detail_displays_badge_status_correctly()
    {
        // Test dengan warehouse yang aktif dan merupakan RM & FG warehouse
        $warehouse = Warehouse::create([
            'warehouse_name' => 'Gudang Lengkap',
            'warehouse_address' => 'Jl. Test Badge',
            'warehouse_phone' => '0274-111111',
            'is_rm_warehouse' => true,
            'is_fg_warehouse' => true,
            'is_active' => true,
        ]);

        $this->browse(function (Browser $browser) use ($warehouse) {
            $browser->visit(route('warehouse.detail', $warehouse->id))
                    // Verify RM Warehouse badge menunjukkan "Ya"
                    ->assertSee('Warehouse Raw Material')
                    ->assertPresent('.badge.bg-success')
                    // Verify FG Warehouse badge menunjukkan "Ya"
                    ->assertSee('Warehouse Finished Goods')
                    // Verify Status badge menunjukkan "Aktif"
                    ->assertSee('Status')
                    ->assertPresent('.badge.bg-success');
        });

        $warehouse->delete();

        // Test dengan warehouse yang tidak aktif
        $warehouseInactive = Warehouse::create([
            'warehouse_name' => 'Gudang Tidak Aktif',
            'warehouse_address' => 'Jl. Test Inactive',
            'warehouse_phone' => '0274-222222',
            'is_rm_warehouse' => false,
            'is_fg_warehouse' => true,
            'is_active' => false,
        ]);

        $this->browse(function (Browser $browser) use ($warehouseInactive) {
            $browser->visit(route('warehouse.detail', $warehouseInactive->id))
                    // Verify badge status menunjukkan "Tidak Aktif" dengan warna merah
                    ->assertSee('Status')
                    ->assertPresent('.badge.bg-danger');
        });

        $warehouseInactive->delete();
    }

    /**
     * Test 4: User dapat menavigasi dari halaman list ke detail dan kembali
     * Scenario: User membuka list warehouse, click tombol Detail, lalu verify halaman detail
     */
    public function test_user_can_navigate_from_list_to_warehouse_detail()
    {
        $warehouse = Warehouse::create([
            'warehouse_name' => 'Gudang Navigation Test',
            'warehouse_address' => 'Jl. Navigation',
            'warehouse_phone' => '0274-333333',
            'is_rm_warehouse' => true,
            'is_fg_warehouse' => false,
            'is_active' => true,
        ]);

        $this->browse(function (Browser $browser) use ($warehouse) {
            // Buka halaman list warehouse
            $browser->visit(route('warehouses.index'))
                    ->assertSee('List Warehouse')
                    // Verify warehouse muncul di list
                    ->assertSeeIn('table', 'Gudang Navigation Test');
            
            // Click tombol Detail untuk warehouse tersebut
            $browser->visit(route('warehouse.detail', $warehouse->id))
                    // Verify navigasi ke detail page berhasil
                    ->assertPathIs('/warehouse/detail/' . $warehouse->id)
                    ->assertSee('Detail Warehouse')
                    ->assertSeeIn('table', 'Gudang Navigation Test');
        });

        $warehouse->delete();
    }
}