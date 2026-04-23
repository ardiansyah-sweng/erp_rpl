<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Product;

class ProductsTypeTest extends TestCase
{
    public function test_count_product_by_product_type_fg()
    {
        // Ambil nama kolom dari config
        $col = config('db_constants.column.products')['type'];

        // Hitung jumlah produk langsung dari DB pakai where biasa
        $expectedCount = Product::where($col, 'FG')->count();

        // Jalankan fungsi yang mau diuji
        $count = Product::countProductByProductType('FG');

        // Bandingkan hasilnya
        $this->assertEquals($expectedCount, $count, "Jumlah produk dengan tipe 'FG' tidak sesuai.");
    }

    public function test_count_product_by_product_type_rm()
    {
        $col = config('db_constants.column.products')['type'];
        $expectedCount = Product::where($col, 'RM')->count();

        $count = Product::countProductByProductType('RM');

        $this->assertEquals($expectedCount, $count, "Jumlah produk dengan tipe 'RM' tidak sesuai.");
    }

    public function test_count_product_by_product_type_yang_tidak_ada()
    {
        $count = Product::countProductByProductType('XYZ');

        $this->assertEquals(0, $count, "Produk dengan tipe 'XYZ' seharusnya tidak ditemukan.");
    }
}
