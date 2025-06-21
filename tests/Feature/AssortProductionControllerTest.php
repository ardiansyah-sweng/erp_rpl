<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\AssortProductionModel;
use App\Models\AssortmentProduction;

class AssortProductionControllerTest extends TestCase
{

    public function testGetProductionReturnsJson()
    {

        $response = $this->get('/production');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => ['id', 'sku'] // sesuaikan dengan kolom tabel kamu
        ]);
    }
    public function testGetProductionDetail()
    {
        $id = 3; // ganti dengan id yang ada di database Anda
        $response = $this->get("/details/{$id}");
        $response->assertStatus(200);

        $data = $response->decodeResponseJson();
        dump($data);
        $this->assertEquals($id, $data['id']);
    }
}
