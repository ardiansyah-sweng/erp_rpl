<?php


namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\Supplier;
use App\Models\SupplierPic;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SupplierPrintPICTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Setup Data Sederhana
     */
    protected function createDummyData()
    {
        $supplier = Supplier::create([
            'supplier_id' => 'SUP001',
            'company_name' => 'PT Demo Aman',
            'address' => 'Jl. Aman No. 1',
            'telephone' => '081234567890',
            'bank_account' => '1234567890',
        ]);

        SupplierPic::create([
            'supplier_id' => 'SUP001',
            'name' => 'Budi Demo',
            'email' => 'budi@demo.com',
            'phone_number' => '081234567892',
            'assigned_date' => now()->subYears(1)->format('Y-m-d'),
        ]);
        
        return $supplier;
    }

    /**
     * 1. Pastikan Halaman Bisa Diakses
     */
    public function test_user_can_access_page(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/supplier/pic/list')
                ->waitForText('Daftar PIC Supplier') // Pakai waitFor biar aman kalau loading lama
                ->assertPresent('table.table-bordered');
        });
    }

    /**
     * 2. Pastikan Tombol Print Muncul
     */
    public function test_print_button_exists(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/supplier/pic/list')
                ->waitForText('Cetak PDF PIC')
                ->assertPresent('button.btn-danger.dropdown-toggle');
        });
    }

    /**
     * 3. Test Fitur Utama: Klik Cetak Semua (Happy Path)
     */
    public function test_print_all_flow(): void
    {
        $this->createDummyData();

        $this->browse(function (Browser $browser) {
            $browser->visit('/supplier/pic/list')
                ->waitForText('Budi Demo') // Tunggu data muncul
                ->press('Cetak PDF PIC')
                ->waitForLink('Cetak Semua') // Tunggu dropdown turun
                ->clickLink('Cetak Semua')
                ->pause(2000) // Beri waktu download dimulai (aman)
                ->assertPathIs('/supplier/pic/list'); // Pastikan tidak crash
        });
    }

    /**
     * 4. Test Fitur Utama: Cetak Per Supplier
     */
    public function test_print_per_supplier_flow(): void
    {
        $this->createDummyData();

        $this->browse(function (Browser $browser) {
            $browser->visit('/supplier/pic/list')
                ->waitForText('Cetak PDF PIC')
                ->press('Cetak PDF PIC')
                ->waitForText('SUP001 - PT Demo Aman')
                ->clickLink('SUP001 - PT Demo Aman')
                ->pause(1000)
                ->assertPathIs('/supplier/pic/list');
        });
    }

    /**
     * 5. Test Data Tampil Benar
     */
    public function test_data_displayed_correctly(): void
    {
        $this->createDummyData();

        $this->browse(function (Browser $browser) {
            $browser->visit('/supplier/pic/list')
                ->waitForText('PT Demo Aman')
                ->assertSee('Budi Demo')
                ->assertSee('budi@demo.com');
        });
    }
}