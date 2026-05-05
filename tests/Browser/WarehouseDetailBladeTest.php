<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\Warehouse;

class WarehouseDetailBladeTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Test Case: Verifikasi Halaman Detail Warehouse menampilkan data yang benar.
     */
    public function test_detail_page_shows_correct_data()
    {
        $this->browse(function (Browser $browser) {
            // 1. Setup Data Dummy
            $warehouse = Warehouse::create([
                'warehouse_name'    => 'Gudang Spesifik Test',
                'warehouse_address' => 'Jl. Detail Raya No. 99',
                'warehouse_phone'   => '0812-9999-8888',
                'is_active'         => true,
                'is_rm_warehouse'   => true,
                'is_fg_warehouse'   => false,
            ]);

            // 2. Kunjungi Halaman Detail
            $browser->visit('/warehouse/detail/' . $warehouse->id)
                    
                    // 3. Validasi Komponen Utama
                    ->assertSee('Detail Warehouse')
                    
                    // 4. Validasi Data Teks
                    ->assertSee('Gudang Spesifik Test')
                    ->assertSee('Jl. Detail Raya No. 99')
                    ->assertSee('0812-9999-8888')

                    // 5. Validasi Status (Badge)
                    ->assertSee('Aktif')
                    ->assertPresent('.badge.bg-success') 

                    // 6. Validasi Tabel
                    ->assertSee('Ya')   // Untuk RM
                    ->assertSee('Tidak'); // Untuk FG
        });
    }
}