<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SupplierMaterialControllerByCategoryTest extends TestCase
{
    /** @test */
    public function test_supplier_item_join_endpoint_returns_data()
    {
        // Kirim HTTP request ke controller
        $response = $this->get('/supplier/joined-data');

        // Pastikan status 200 OK
        $response->assertStatus(200);

        // Pastikan format JSON (karena controller return-nya response()->json())
        $response->assertJsonStructure([
            '*' => [ // array of objects
                'supplier_id',
                'product_id',
                'item_name',
                'measurement_unit'
                // bisa tambahkan field lain di sini
            ]
        ]);
    }
}
