<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\Supplier;
use App\Models\SupplierPic;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Carbon\Carbon;

class SupplierPicListTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Setup: Create dummy supplier and PIC data
     */
    protected function createSupplierAndPic($assignedDate)
    {
        $supplier = Supplier::create([
            'supplier_id' => 'SUP001',
            'company_name' => 'PT Maju Jaya',
            'address' => 'Jl. Sudirman No. 123',
            'telephone' => '0274-1234567',
            'bank_account' => '1234567890123456',
        ]);

        SupplierPic::create([
            'id' => 1,
            'supplier_id' => 'SUP001',
            'name' => 'John Doe',
            'email' => 'john@majujaya.com',
            'phone_number' => '081234567890',
            'assigned_date' => $assignedDate,
            'is_active' => true,
        ]);

        return $supplier;
    }

    /**
     * TEST 1: Kolom "Durasi Penugasan" Tampil dengan Format Benar
     * 
     * Scenario: PIC dengan assigned_date lebih dari 1 tahun
     * Expected: Tampil format "X tahun, Y bulan, Z hari"
     */
    public function test_assignment_duration_column_displayed_correctly(): void
    {
        // Create PIC dengan assigned_date 1 tahun 2 bulan 15 hari yang lalu
        $assignedDate = Carbon::now()->subYears(1)->subMonths(2)->subDays(15);
        $this->createSupplierAndPic($assignedDate->format('Y-m-d'));

        $this->browse(function (Browser $browser) {
            $browser->visit('/supplier/pic/list')
                ->waitForText('Daftar PIC Supplier')
                ->waitForText('John Doe') // Tunggu data dimuat
                ->assertPresent('table.table-bordered')
                // Cek bahwa kolom "Durasi Penugasan" ada di header
                ->assertSeeIn('thead', 'Durasi Penugasan')
                // Cek format durasi ditampilkan (tahun, bulan, hari)
                ->assertSeeIn('tbody', 'tahun')
                ->assertSeeIn('tbody', 'bulan')
                ->assertSeeIn('tbody', 'hari');
        });
    }

    /**
     * TEST 2: Durasi Penugasan Menampilkan Nilai Negatif saat assigned_date NULL
     * 
     * Scenario: PIC tanpa assigned_date
     * Expected: Tampil "Tanggal belum tersedia"
     */
    public function test_assignment_duration_shows_message_when_no_date(): void
    {
        // Create PIC tanpa assigned_date
        $supplier = Supplier::create([
            'supplier_id' => 'SUP002',
            'company_name' => 'PT Demo',
            'address' => 'Jl. Demo',
            'telephone' => '0274-demo',
            'bank_account' => 'demo123',
        ]);

        SupplierPic::create([
            'id' => 2,
            'supplier_id' => 'SUP002',
            'name' => 'Jane Smith',
            'email' => 'jane@demo.com',
            'phone_number' => '082345678901',
            'assigned_date' => null, // Tidak ada tanggal
            'is_active' => true,
        ]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/supplier/pic/list')
                ->waitForText('Daftar PIC Supplier')
                ->waitForText('Jane Smith')
                // Cek pesan ketika tidak ada assigned_date
                ->assertSeeIn('tbody', 'Tanggal belum tersedia');
        });
    }

    /**
     * TEST 3: Multiple PIC Menampilkan Durasi yang Berbeda-beda
     * 
     * Scenario: Ada 2 PIC dengan assigned_date berbeda
     * Expected: Masing-masing tampil durasi yang berbeda
     */
    public function test_multiple_pics_show_different_durations(): void
    {
        // Create supplier
        $supplier = Supplier::create([
            'supplier_id' => 'SUP003',
            'company_name' => 'PT Sejahtera',
            'address' => 'Jl. Jaya',
            'telephone' => '0274-555555',
            'bank_account' => 'sejahtera123',
        ]);

        // PIC 1: Penugasan 6 bulan lalu
        SupplierPic::create([
            'id' => 3,
            'supplier_id' => 'SUP003',
            'name' => 'Ahmad Sutrisno',
            'email' => 'ahmad@sejahtera.com',
            'phone_number' => '081111111111',
            'assigned_date' => Carbon::now()->subMonths(6)->format('Y-m-d'),
            'is_active' => true,
        ]);

        // PIC 2: Penugasan 2 tahun 3 bulan lalu
        SupplierPic::create([
            'id' => 4,
            'supplier_id' => 'SUP003',
            'name' => 'Siti Nurhaliza',
            'email' => 'siti@sejahtera.com',
            'phone_number' => '082222222222',
            'assigned_date' => Carbon::now()->subYears(2)->subMonths(3)->format('Y-m-d'),
            'is_active' => true,
        ]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/supplier/pic/list')
                ->waitForText('Daftar PIC Supplier')
                ->waitForText('Ahmad Sutrisno')
                ->waitForText('Siti Nurhaliza')
                // Pastikan tabel punya minimal 2 baris data
                ->assertPresent('table.table-bordered tbody tr:nth-child(1)')
                ->assertPresent('table.table-bordered tbody tr:nth-child(2)')
                // Cek bahwa durasi ditampilkan di kedua baris
                ->assertSeeIn('table.table-bordered tbody tr:nth-child(1)', 'bulan')
                ->assertSeeIn('table.table-bordered tbody tr:nth-child(2)', 'tahun');
        });
    }
}