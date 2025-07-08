<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;

class GetProductByCategoryTest extends TestCase
{
    public function test_get_product_by_existing_category()
    {
        // Ambil salah satu data produk dari database
        $sampleProduct = Product::first();

        if (!$sampleProduct) {
            $this->markTestSkipped('Database tidak memiliki data produk untuk diuji.');
        }

        $category = $sampleProduct->product_category;

        // Jalankan fungsi yang ingin diuji
        $result = Product::getProductByCategory($category);

        // Assert hasilnya adalah instance dari paginator
        $this->assertInstanceOf(LengthAwarePaginator::class, $result);

        // Pastikan semua hasil memiliki kategori yang sama
        foreach ($result as $product) {
            $this->assertEquals($category, $product->product_category);
        }
    }
}
