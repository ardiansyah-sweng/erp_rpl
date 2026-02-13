<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\Merk;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Constants\Messages;

class DeleteMerkControllerTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

    /**
     * Test DELETE /api/merk/{id} - Delete existing merk successfully via API
     */
    public function test_delete_merk_via_api_success()
    {
        $merk = Merk::create([
            'merk' => 'Toyota',
            'is_active' => true,
        ]);
        
        $response = $this->deleteJson("/api/merk/{$merk->id}");
        
        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => Messages::MERK_DELETED
                 ]);

        // Verify merk is actually deleted
        $this->assertDatabaseMissing('merks', ['id' => $merk->id]);
    }

    /**
     * Test DELETE /api/merk/{id} - Delete non-existent merk returns 404
     */
    public function test_delete_merk_via_api_not_found()
    {
        $response = $this->deleteJson('/api/merk/99999');
        
        $response->assertStatus(404)
                 ->assertJson([
                     'success' => false,
                     'message' => Messages::MERK_NOT_FOUND
                 ]);
    }

    /**
     * Test Delete merk and verify it's removed from database
     */
    public function test_delete_merk_removes_from_database()
    {
        $merk = Merk::create([
            'merk' => 'Honda',
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('merks', ['id' => $merk->id]);
        
        $response = $this->deleteJson("/api/merk/{$merk->id}");
        
        $response->assertStatus(200);
        $this->assertDatabaseMissing('merks', ['id' => $merk->id]);
    }

    /**
     * Test Delete multiple merks sequentially
     */
    public function test_delete_multiple_merks_sequentially()
    {
        $merk1 = Merk::create([
            'merk' => 'Toyota',
            'is_active' => true,
        ]);
        
        $merk2 = Merk::create([
            'merk' => 'Honda',
            'is_active' => true,
        ]);
        
        $initialCount = Merk::count();
        
        // Delete first merk
        $response1 = $this->deleteJson("/api/merk/{$merk1->id}");
        $response1->assertStatus(200);
        $this->assertEquals($initialCount - 1, Merk::count());
        
        // Delete second merk
        $response2 = $this->deleteJson("/api/merk/{$merk2->id}");
        $response2->assertStatus(200);
        $this->assertEquals($initialCount - 2, Merk::count());
    }

    /**
     * Test Delete inactive merk
     */
    public function test_delete_inactive_merk()
    {
        $merk = Merk::create([
            'merk' => 'Suzuki',
            'is_active' => false,
        ]);
        
        $response = $this->deleteJson("/api/merk/{$merk->id}");
        
        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => Messages::MERK_DELETED
                 ]);

        $this->assertDatabaseMissing('merks', ['id' => $merk->id]);
    }

    /**
     * Test Delete then try to delete same merk again returns 404
     */
    public function test_delete_merk_twice_returns_not_found()
    {
        $merk = Merk::create([
            'merk' => 'Toyota',
            'is_active' => true,
        ]);
        
        $merkId = $merk->id;
        
        // First delete should succeed
        $response1 = $this->deleteJson("/api/merk/{$merkId}");
        $response1->assertStatus(200);
        
        // Second delete should fail
        $response2 = $this->deleteJson("/api/merk/{$merkId}");
        $response2->assertStatus(404)
                  ->assertJson([
                      'success' => false,
                      'message' => Messages::MERK_NOT_FOUND
                  ]);
    }

    /**
     * Test Delete merk with proper response structure
     */
    public function test_delete_merk_response_structure()
    {
        $merk = Merk::create([
            'merk' => 'Suzuki',
            'is_active' => false,
        ]);
        
        $response = $this->deleteJson("/api/merk/{$merk->id}");
        
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'message'
                 ]);

        $this->assertTrue($response['success']);
    }

    /**
     * Test Delete preserves other data integrity
     */
    public function test_delete_merk_preserves_other_data()
    {
        $merk1 = Merk::create([
            'merk' => 'Toyota',
            'is_active' => true,
        ]);
        
        $merk2 = Merk::create([
            'merk' => 'Honda',
            'is_active' => true,
        ]);
        
        $response = $this->deleteJson("/api/merk/{$merk1->id}");
        $response->assertStatus(200);
        
        // Verify merk2 still exists with correct data
        $this->assertDatabaseHas('merks', [
            'id' => $merk2->id,
            'merk' => 'Honda',
            'is_active' => true
        ]);
    }

    /**
     * Test Delete all merks
     */
    public function test_delete_all_merks()
    {
        Merk::create(['merk' => 'Toyota', 'is_active' => true]);
        Merk::create(['merk' => 'Honda', 'is_active' => true]);
        Merk::create(['merk' => 'Suzuki', 'is_active' => false]);
        
        $merks = Merk::all();
        
        foreach ($merks as $merk) {
            $response = $this->deleteJson("/api/merk/{$merk->id}");
            $response->assertStatus(200);
        }
        
        // Verify all are deleted
        $this->assertEquals(0, Merk::count());
    }

    /**
     * Test Delete with non-numeric ID
     */
    public function test_delete_merk_with_non_numeric_id()
    {
        $response = $this->deleteJson('/api/merk/invalid-id');
        
        // Should return 404
        $response->assertStatus(404);
    }
}
