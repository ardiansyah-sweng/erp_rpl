<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use Database\Seeders\SupplierSeeder;

class SupplierPrintPdfTest extends DuskTestCase
{
    use DatabaseMigrations; // Ini akan mereset DB setiap test jalan (mirip RefreshDatabase)

    /**
     * Setup data awal sebelum browser dibuka
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        // 1. Jalankan Seeder agar tabel supplier ada isinya
        $this->seed(SupplierSeeder::class);
    }

    /**
     * Skenario: User membuka halaman list, melihat tombol Cetak PDF, dan mengkliknya.
     */
    public function test_tombol_cetak_pdf_ada_dan_berfungsi()
    {
        $this->browse(function (Browser $browser) {
         
            $browser->visit(route('supplier.list')) // 1. Buka halaman Supplier List di Chrome
                    
                    // 2. Assert See: Memastikan mata user bisa melihat tulisan "Cetak PDF"
                    ->assertSee('Cetak PDF') 
                    
                    // 3. Pastikan link-nya benar (opsional tapi bagus)
                    // Cek apakah di source code ada link ke route print-pdf
                    ->assertSourceHas(route('supplier.print-pdf'))

                    // 4. Simulasi Klik: Klik tombol bertuliskan "Cetak PDF"
                    ->clickLink('Cetak PDF');

            // 5. Handling Tab Baru (Karena di view kamu pakai target="_blank")
            // Kita pindah fokus browser ke tab baru yang terbuka
            $window = collect($browser->driver->getWindowHandles())->last();
            $browser->driver->switchTo()->window($window);

            // 6. Verifikasi URL di tab baru adalah endpoint PDF
            // Note: Dusk susah mengecek isi binary PDF, tapi kita bisa cek URL-nya
            $browser->assertUrlIs(route('supplier.print-pdf'));
        });
    }
}