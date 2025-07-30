<?php

namespace Tests\Feature;

use Tests\TestCase;

class WarehouseControllerTest extends TestCase
{
    /** @test */
    public function it_returns_warehouses_if_exist()
    {
        $response = $this->get('/warehouse/all');

        if ($response->status() === 404) {
            $response->assertJson([
                'message' => 'Tidak ada warehouse yang ditemukan'
            ]);
        } else {
            $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data',
                ]);
        }
    }
}
