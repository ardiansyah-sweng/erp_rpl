<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class ProductPDFTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        // Create test category
        $category = Category::create([
            'category' => 'Test Category',
            'parent_id' => 0,
            'active' => true
        ]);        // Create test product
        Product::create([
            'product_id' => 'T001',  // Shortened to ensure it fits in DB column
            'product_name' => 'Test Product',
            'product_type' => 'Test Type',
            'product_category' => $category->id,
            'product_description' => 'Test Description'
        ]);
    }    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_preview_product_pdf()
    {
        $response = $this->get('/product/pdf/preview');

        $response->assertStatus(200)
                ->assertViewIs('product.preview-pdf')
                ->assertViewHas('products');
    }    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_generate_product_pdf()
    {
        $response = $this->get('/product/pdf');

        $response->assertStatus(200)
                ->assertHeader('Content-Type', 'application/pdf')
                ->assertHeader('Content-Disposition', 'inline; filename=daftar_produk.pdf');
    }    #[\PHPUnit\Framework\Attributes\Test]
    public function generated_pdf_contains_product_data()
    {
        $response = $this->get('/product/pdf/preview');
        
        $response->assertSee('Test Product')
                ->assertSee('Test Type')
                ->assertSee('Test Category')
                ->assertSee('Test Description');
    }    #[\PHPUnit\Framework\Attributes\Test]
    public function pdf_preview_page_has_correct_buttons()
    {        $response = $this->get('/product/pdf/preview');        $response->assertSee('Cetak PDF', false)
                ->assertSee('Kembali', false);
    }
}
