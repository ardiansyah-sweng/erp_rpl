<?php

namespace Tests\Feature\Product;

use Tests\TestCase;

class ProductDetailViewTest extends TestCase
{
    public function test_product_detail_view_renders_product_info()
    {
        // create a dummy product with nested objects expected by the view
        $product = new class {
            public $id = 123;
            public $product_id = 'P-123';
            public $product_name = 'Kaos Polos';
            public $product_description = 'Deskripsi produk contoh';
            public $created_at = '2025-01-01 00:00:00';
            public $updated_at = '2025-01-02 00:00:00';
            public $category;
            public $product_type;

            public function __construct()
            {
                $this->category = (object)['category' => 'Pakaian'];
                $this->product_type = new class {
                    public function label()
                    {
                        return 'Type A';
                    }
                };
            }
        };

        $view = $this->view('product.detail', ['product' => $product]);

        $view->assertSee('Detail Produk');
        $view->assertSee('Informasi Produk');
        $view->assertSee('ID');
        $view->assertSee((string) $product->id);
        $view->assertSee($product->product_id);
        $view->assertSee($product->product_name);
        $view->assertSee('Type A');
        $view->assertSee('Pakaian');
        $view->assertSee($product->product_description);
    }
}
