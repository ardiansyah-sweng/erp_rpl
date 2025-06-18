<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Merk;

class MerkControllerTest extends TestCase
{
    use RefreshDatabase;

    // Factory sederhana untuk Merk
    protected function createMerk($attributes = [])
    {
        $defaults = [
            'id' => 'M001',
            'merk' => 'Merk Test',
            'active' => 1,
        ];
        return Merk::create(array_merge($defaults, $attributes));
    }

    /** @test */
    public function it_can_list_merks()
    {
        $this->createMerk(['id' => 'M001', 'merk' => 'Merk A']);
        $this->createMerk(['id' => 'M002', 'merk' => 'Merk B']);

        $response = $this->get('/merks');
        $response->assertStatus(200);
        $response->assertJsonFragment(['merk' => 'Merk A']);
        $response->assertJsonFragment(['merk' => 'Merk B']);
    }

    /** @test */
    public function it_can_show_merk_detail()
    {
        $merk = $this->createMerk(['id' => 'M010', 'merk' => 'Merk Detail']);

        $response = $this->get('/merk/' . $merk->id);
        $response->assertStatus(200);
        $response->assertSee('Merk Detail'); 
    }

    /** @test */
    public function it_can_update_merk()
    {
        $merk = $this->createMerk(['id' => 'M020', 'merk' => 'Merk Lama']);

        $data = [
            'id' => $merk->id,
            'merk' => 'Merk Baru',
        ];
        $response = $this->post('/merk/update/' . $merk->id, $data);
        $response->assertStatus(200);
        $response->assertJsonFragment(['message' => 'Data Merk berhasil diperbarui']);
        $this->assertDatabaseHas('merks', ['id' => $merk->id, 'merk' => 'Merk Baru']);
    }

    /** @test */
    public function it_can_delete_merk()
    {
        $merk = $this->createMerk(['id' => 'M030', 'merk' => 'Merk Hapus']);
        $response = $this->delete('/merk/' . $merk->id);
        $response->assertStatus(302);
        $this->assertDatabaseMissing('merks', ['id' => $merk->id]);
    }
}
