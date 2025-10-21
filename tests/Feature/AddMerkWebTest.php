<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Merk;

class AddMerkWebTest extends TestCase
{

    /** @test */
    public function user_can_add_new_merk_via_web_route()
    {
        $response = $this->post('/merk/add', [
            'merk' => 'LG',
            'active' => true,
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Merk berhasil ditambahkan',
                     'data' => [
                         'merk' => 'LG',
                         'active' => true,
                     ],
                 ]);

        $this->assertDatabaseHas('merks', [
            'merk' => 'LG',
            'active' => true,
        ]);
    }

    /** @test */
    public function it_fails_validation_when_merk_is_missing()
    {
        $response = $this->post('/merk/add', [
            'merk' => '',
        ]);

        $response->assertStatus(302); // Redirect karena validation gagal
        $response->assertSessionHasErrors(['merk']);
    }
}
