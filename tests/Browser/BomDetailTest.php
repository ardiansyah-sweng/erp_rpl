<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\BillOfMaterial;

class BomDetailTest extends DuskTestCase
{

    use DatabaseTruncation;

    /**
     * Test menampilkan detail BOM dengan data lengkap
     * Test case ini verifikasi bahwa saat user klik "Lihat" detail BOM
     */
    public function test_display_bill_of_material_detail_successfully()
    {
        // Setup: Buat data BOM untuk testing
        $bom = BillOfMaterial::create([
            'bom_id' => 'BOM001',
            'bom_name' => 'Produk A',
            'measurement_unit' => 1,
            'total_cost' => 200000,
            'active' => 1,
        ]);

        // Insert detail BOM ke database
        \DB::table('bom_detail')->insert([
            [
                'bom_id' => 'BOM001',
                'sku' => 'SKU001',
                'quantity' => 10,
                'cost' => 50000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'bom_id' => 'BOM001',
                'sku' => 'SKU002',
                'quantity' => 5,
                'cost' => 100000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Buka browser dan navigasi ke halaman list BOM
        $this->browse(function (Browser $browser) use ($bom) {
            $browser->visit('/bom/list')
                    ->waitForText('Daftar Bill of Materials', 10)
                    // Klik tombol "Lihat" untuk membuka detail BOM
                    ->click("button[onclick*='getDetail(1)']")
                    // Tunggu modal muncul dengan response dari API
                    ->waitFor('#bomModal', 10)
                    // Verifikasi judul modal
                    ->assertSeeIn('#bomModalLabel', 'Detail Bill of Material')
                    // Verifikasi data BOM ditampilkan dengan benar
                    ->assertSeeIn('#bom_name', 'Produk A')
                    // Verifikasi total cost ditampilkan
                    ->assertSeeIn('#total_cost', 'Rp.')
                    // Verifikasi status aktif
                    ->assertSeeIn('#active_status', 'AKTIF')
                    // Verifikasi tabel detail komponen ada
                    ->assertPresent('#bom_details')
                    // Verifikasi data detail BOM ditampilkan
                    ->assertSeeIn('#bom_details', 'SKU001')
                    ->assertSeeIn('#bom_details', 'SKU002');
        });
    }

    /**
     * Test verifikasi modal detail BOM menampilkan informasi dengan format benar
     * Tes ini fokus pada struktur dan format data yang ditampilkan
     */
    public function test_bill_of_material_detail_shows_correct_format()
    {
        $bom = BillOfMaterial::create([
            'bom_id' => 'BOM005',
            'bom_name' => 'Produk Test',
            'measurement_unit' => 2,
            'total_cost' => 500000,
            'active' => 1,
        ]);

        \DB::table('bom_detail')->insert([
            [
                'bom_id' => 'BOM005',
                'sku' => 'TEST-SKU-01',
                'quantity' => 20,
                'cost' => 500000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $this->browse(function (Browser $browser) use ($bom) {
            $browser->visit('/bom/list')
                    ->waitForText('Daftar Bill of Materials', 10)
                    ->click("button[onclick*='getDetail(1)']")
                    ->waitFor('#bomModal', 10)
                    // Verifikasi format currency Rp. ditampilkan
                    ->assertSeeIn('#total_cost', 'Rp.')
                    // Verifikasi detail table ada di dalam modal
                    ->assertPresent('#bomModal table')
                    // Verifikasi kolom header table
                    ->assertSee('SKU')
                    ->assertSee('Quantity')
                    ->assertSee('Cost')
                    // Verifikasi data detail BOM ditampilkan
                    ->assertSeeIn('#bom_details', 'TEST-SKU-01')
                    ->assertSeeIn('#bom_details', '20');
        });
    }
}