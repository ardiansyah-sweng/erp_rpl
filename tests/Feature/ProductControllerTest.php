<?php

namespace Tests\Feature;

use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    /**
     * Menguji pengambilan produk dengan tipe 'FG' yang memang tersedia di database nyata.
     */
    public function testGetProductByTypeFG()
    {
        $type = 'FG'; // berdasarkan isi database kamu

        $response = $this->get("/products/type/{$type}");

        $response->assertStatus(200); // berhasil ditemukan
        $response->assertJson([
            'message' => 'Data produk berhasil ditemukan'
        ]);

        // Pastikan semua data dalam array 'data' memiliki product_type = FG
        $products = $response->json('data');
        foreach ($products as $product) {
            $this->assertEquals('FG', $product['product_type']);
        }
    }

    /**
     * Menguji pengambilan produk dengan tipe yang tidak ada di database (misal 'RM' atau 'XYZ').
     */
    public function testGetProductByTypeNotFound()
    {
        $type = 'XYZ'; // tipe yang tidak ada di data asli

        $response = $this->get("/products/type/{$type}");

        $response->assertStatus(404); // tidak ditemukan
        $response->assertJson([
            'message' => "Tidak ada produk dengan tipe: {$type}"
        ]);
    }
}