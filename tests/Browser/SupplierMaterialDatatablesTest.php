<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Support\Facades\DB;

class SupplierMaterialDatatablesTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * TEST CASE 1: Verifikasi halaman bisa diakses dan data ditampilkan dengan benar
     * - Mengecek title halaman
     * - Mengecek struktur table headers
     * - Mengecek data supplier material muncul di table
     */
    public function test_user_can_access_page()
    {
        // Buat dummy data menggunakan DB::table (tanpa factory)
        DB::table('supplier_product')->insert([
            'supplier_id'   => 'SUP001',
            'company_name'  => 'PT Baja Indonesia Jaya',
            'product_id'    => 'PROD-01',
            'product_name'  => 'Besi Beton Ulir 10mm',
            'base_price'    => 50000,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/supplier/material/list')
                    ->pause(1000)
                    // Assert 1: Judul halaman benar
                    ->assertSee('Supplier Material')
                    ->assertSee('List Table')
                    // Assert 2: Header table lengkap
                    ->assertSee('supplier_id')
                    ->assertSee('company_name')
                    ->assertSee('product_name')
                    ->assertSee('base_price')
                    // Assert 3: Data supplier material muncul
                    ->assertSee('SUP001')
                    ->assertSee('PT Baja Indonesia Jaya')
                    ->assertSee('Besi Beton Ulir 10mm')
                    ->assertSee('50000');
        });
    }

    /**
     * TEST CASE 2: Verifikasi multiple data dan action buttons berfungsi
     * - Memastikan multiple rows ditampilkan dengan benar
     * - Memastikan action buttons (Edit, Delete, Detail, Cetak PDF) ada
     */
    public function test_table_shows_data()
    {
        // Buat multiple data menggunakan DB::table
        DB::table('supplier_product')->insert([
            [
                'supplier_id'   => 'SUP002',
                'company_name'  => 'PT Semen Gresik',
                'product_id'    => 'PROD-02',
                'product_name'  => 'Semen Portland 50kg',
                'base_price'    => 75000,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'supplier_id'   => 'SUP003',
                'company_name'  => 'PT Bata Merah',
                'product_id'    => 'PROD-03',
                'product_name'  => 'Bata Merah Premium',
                'base_price'    => 2500,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
        ]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/supplier/material/list')
                    ->pause(1500)
                    // Assert 1: Verifikasi data pertama muncul
                    ->assertSee('PT Semen Gresik')
                    ->assertSee('Semen Portland 50kg')
                    ->assertSee('75000')
                    // Assert 2: Verifikasi data kedua muncul
                    ->assertSee('PT Bata Merah')
                    ->assertSee('Bata Merah Premium')
                    ->assertSee('2500')
                    // Assert 3: Verifikasi action buttons ada
                    ->assertSee('Edit')
                    ->assertSee('Delete')
                    ->assertSee('Detail')
                    ->assertSee('Cetak PDF');
        });
    }

    /**
     * TEST CASE 3: Verifikasi form dan button navigasi berfungsi
     * - Memastikan button "Tambah" ada
     * - Memastikan page structure lengkap
     */
    public function test_add_button_and_navigation_visible()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/supplier/material/list')
                    ->pause(1000)
                    // Assert 1: Heading ada
                    ->assertSee('Supplier Material')
                    // Assert 2: Tombol tambah ada
                    ->assertSee('Tambah')
                    // Assert 3: Table structure ada
                    ->assertSee('List Table')
                    ->assertSee('supplier_id')
                    ->assertSee('company_name')
                    ->assertSee('product_name')
                    ->assertSee('base_price')
                    ->assertSee('Action');
        });
    }
}