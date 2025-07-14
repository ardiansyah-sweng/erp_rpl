<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Merk;
use Illuminate\Support\Str;

class DeleteMerkTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_delete_existing_merk()
    {
    try {
        // Hapus UUID, biarkan pakai auto-increment
        $merk = Merk::create(['id' => 999, 'merk' => 'Contoh Merk']);


        dump($merk);

        $this->assertNotNull($merk->id);

        $response = $this->delete(route('merk.delete', ['id' => $merk->id]));
        $response->assertRedirect();
    } catch (\Exception $e) {
        dump($e->getMessage());
        $this->fail("Terjadi error saat create: " . $e->getMessage());
    }
    }


    /** @test */
    public function delete_invalid_id()
    {
        $response = $this->delete('/merk/delete/abc');
        $response->assertStatus(302);
    }

    /** @test */
    public function delete_nonexistent_id()
    {
        $response = $this->delete(route('merk.delete', ['id' => 999]));
        $response->assertRedirect();
    }
}
