<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Product; // Pastiin model Product ada
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ProductListTest extends DuskTestCase
{
    use DatabaseMigrations; // Biar DB bersih tiap test

    /**
     * Test fitur Produk: Nama Tipe Lengkap, Encrypt ID, & PDF.
     */
    public function test_product_list_features_and_pdf()
    {
        // 1. Setup Data Dummy
        // Kita bikin user buat login
        $user = User::factory()->create();

        // Kita bikin produk dummy.
        // Anggap aja di DB 'type' disimpen 'EL', tapi di layar harus muncul 'Elektronik'
        // Ini buat ngetes poin "Pastikan product_type nama lengkap"
        $product = Product::factory()->create([
            'name' => 'Laptop Gaming Gacor',
            'product_type' => 'EL', // Disini kode
             // Nanti di view harusnya di-convert jadi "Elektronik" (Mungkin via Accessor/Helper Wahyu)
        ]);

        $this->browse(function (Browser $browser) use ($user, $product) {
            $browser->loginAs($user)
                    ->visit(route('product.list')) // Pastiin route ini bener
                    ->assertSee('Product List');   // Judul Halaman

            // --- TEST 1: Cek Product Type Nama Lengkap ---
            // Di sini kita expect gak liat kode 'EL', tapi liat 'Elektronik' (atau nama lengkap sesuai logic Mas Wahyu)
            // Ganti 'Elektronik' sesuai data real lu.
            $browser->assertSee('Laptop Gaming Gacor')
                    ->assertDontSee('EL') // Gak boleh muncul kodenya doang
                    ->assertSee('Elektronik'); // Harus muncul nama lengkapnya

            // --- TEST 2: Cek Link Detail (Encrypt ID) ---
            // Cari tombol/link detail, pastiin URL-nya gak telanjang (bukan /product/1)
            // Tapi something like /product/eyJpdiI... (hash)
            $browser->assertPresent('.btn-detail') // Pastiin class tombol detailnya .btn-detail
                    ->attribute('.btn-detail', 'href')
                    ->assertNotEquals(route('product.detail', ['id' => $product->id])); 
                    // Logic di atas: URL tombol TIDAK BOLEH sama dengan ID mentah (validasi enkripsi simpel)

            // --- TEST 3: Cek Generate PDF ---
            // Klik tombol cetak PDF
            $browser->assertSee('Cetak PDF') // Cek tombol ada
                    ->clickLink('Cetak PDF'); // Klik linknya
            
            // Karena biasanya PDF kebuka di tab baru (target="_blank"), kita pindah window
            $window = collect($browser->driver->getWindowHandles())->last();
            $browser->driver->switchTo()->window($window);

            // Cek apakah URL-nya beneran route PDF
            $browser->assertPathIs('/product/print-pdf') // Sesuaikan sama route lu
                    ->assertSee('Laporan Daftar Produk'); // Cek text yang mungkin ada di PDF (kalo browser bisa render PDF text)
        });
    }
}