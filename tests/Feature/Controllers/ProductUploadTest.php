<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\Category;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductUploadTest extends TestCase
{
    use RefreshDatabase; // Bersihkan database setelah test selesai

    public function test_admin_bisa_menambahkan_produk_dengan_foto()
    {
        // 1. Siapkan "Gudang Palsu" agar file test tidak nyampur ke folder public beneran
        Storage::fake('public');

        // 2. Buat data kategori dummy karena produk butuh relasi kategori
        $category = Category::create([
            'category' => 'Perabotan Kayu',
            'is_active' => 1
        ]);

        // 3. Buat file foto palsu (dummy) bernama meja.jpg sebesar 500kb
        $fakeImage = UploadedFile::fake()->create('meja.jpg', 500, 'image/jpeg');

        // 4. Kirim request POST ke form Tambah Produk
        $response = $this->post(route('product.add'), [
            'product_id' => '9999',
            'product_name' => 'Meja Test',
            'product_type' => 'FG',
            'product_category' => $category->id,
            'product_description' => 'Ini adalah barang test',
            'image' => $fakeImage, // Masukkan foto palsunya
        ]);

        // 5. Pastikan redirect sukses (status 302)
        $response->assertStatus(302);
        $response->assertSessionHas('success');

        // 6. Pastikan datanya masuk ke database MySQL
        $this->assertDatabaseHas('products', [
            'product_id' => '9999',
            'name' => 'Meja Test',
            'type' => 'FG'
        ]);

        // 7. AMBIL data produknya dari database untuk mengecek nama file fotonya
        $product = \App\Models\Product::where('product_id', '9999')->first();

        // 8. Pastikan file fotonya benar-benar tersimpan di dalam folder 'products'
        Storage::disk('public')->assertExists($product->image);
    }
}