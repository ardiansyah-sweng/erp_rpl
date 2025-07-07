<?php

namespace Tests\Feature;

use Tests\TestCase;

class SupplierSearchTest extends TestCase
{
    public function test_can_search_supplier_product_by_keyword()
    {
        // Contoh kata kunci dari data yang memang ada di DB kamu
        $response = $this->get('/suppliers/search?keywords=Sawit');

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'status' => 'success',
        ]);

        // Contoh validasi fragment yang ada di tabel supplier_product
        $response->assertJsonFragment([
            'company_name' => 'PD Budi Sawit Interfood',
        ]);
    }
}