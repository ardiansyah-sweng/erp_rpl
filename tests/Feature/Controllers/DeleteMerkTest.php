<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Merk;
use App\Constants\Messages;

class DeleteMerkTest extends TestCase
{
    use RefreshDatabase;

    public function test_web_delete_merks_deletes_and_redirects()
    {
        $merk = Merk::factory()->create();

        $response = $this->delete(route('merks.destroy', $merk->id));

        $response->assertRedirect(route('merk.index'));

        // BENER: Rapet, 'id' nempel sama kutipnya
        $this->assertDatabaseMissing('merks', ['id' => $merk->id]);
    }

    public function test_api_delete_merk_returns_json_and_deletes()
    {
        $merk = Merk::factory()->create();

        $response = $this->deleteJson('/api/merk/' . $merk->id);

        $response->assertOk()
                 ->assertJson([
                     'success' => true,
                     'message' => Messages::MERK_DELETED,
                 ]);
        $this->assertDatabaseMissing('merks', ['id' => $merk->id]);
    }
}
