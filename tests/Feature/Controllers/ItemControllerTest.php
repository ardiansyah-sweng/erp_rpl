<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\Category;
use App\Models\Product;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
// Hapus: use Barryvdh\DomPDF\Facade\Pdf; (Kita tidak pakai Facade untuk Mocking lagi)

class ItemControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
    }

    /**
     * Test Skenario 1: Export Berhasil (Tanpa Mockery)
     * Benar-benar menjalankan fungsi PDF stream.
     */
    public function test_export_item_by_category_returns_pdf_stream_successfully()
    {
        // 1. ARRANGE (SIAPKAN DATA)
        $category = Category::factory()->create([
            'category' => 'Elektronik Rumah'
        ]);

        $product = Product::factory()->create([
            'category' => $category->id, 
            'name' => 'Kulkas 2 Pintu' 
        ]);

        Item::factory()->create([
            'product_id' => $product->product_id,
            'name' => 'Kulkas Sharp'
        ]);

        // 2. ACT (JALANKAN ROUTE)
        // Kita tidak mencegat (mock) PDF lagi, biarkan controller bekerja aslinya.
        $response = $this->get(route('item.export.category.pdf', ['categoryId' => $category->id]));

        // 3. ASSERT (CEK HASIL ASLI)
        
        // Pastikan tidak ada error (Status 200)
        $response->assertStatus(200);

        // Cek apakah browser menerima file tipe PDF
        $response->assertHeader('Content-Type', 'application/pdf');
        
        // Opsional: Cek apakah nama file di header benar (biasanya ada di Content-Disposition)
        // $response->assertHeader('Content-Disposition', 'inline; filename="item-kategori-Elektronik Rumah.pdf"');
    }

    /**
     * Test Skenario 2: Export Gagal (Data Kosong)
     */
    public function test_export_item_by_category_redirects_back_if_data_empty()
    {
        // 1. ARRANGE
        $category = Category::factory()->create([
            'category' => 'Kategori Hantu'
        ]);

        // 2. ACT
        $response = $this->get(route('item.export.category.pdf', ['categoryId' => $category->id]));

        // 3. ASSERT
        $response->assertRedirect();
        $response->assertSessionHas('error', 'Data tidak ditemukan untuk kategori ini.');
    }
}