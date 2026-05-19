<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\AssortmentProductionModel;

class AssortProductionControllerTest extends TestCase
{
    // Jika kamu ingin reset database untuk test
    use RefreshDatabase;

    public function testGetProductionReturnsJson()
    {

        $response = $this->get('/production');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => ['id', 'sku'] // sesuaikan dengan kolom tabel kamu
        ]);
    }
}
