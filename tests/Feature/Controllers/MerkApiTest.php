<?php

namespace Tests\Feature\Controllers;

use App\Models\Merk;
use App\Constants\MerkColumns;
use App\Constants\Messages;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MerkApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private const API_BASE_URL = '/api/merks';

    // ========== GETMERKBYID METHOD TESTS ==========

    /**
     * Test MerkController::getMerkById dengan ID yang valid
     * Menguji apakah method berhasil mengambil merk berdasarkan ID
     */
    public function test_get_merk_by_id_success(): void
    {
        // Arrange: Buat merk test
        $merk = Merk::factory()->create([
            MerkColumns::MERK => 'Test Merk ' . uniqid(),
            MerkColumns::IS_ACTIVE => true
        ]);

        // Act: Panggil endpoint GET /api/merks/{id}
        $response = $this->getJson(self::API_BASE_URL . "/{$merk->id}");

        // Assert: Verifikasi response
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'id',
                    'merk',
                    'is_active',
                    'status_label',
                    'display_name',
                    'created_at',
                    'updated_at'
                ]
            ])
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $merk->id)
            ->assertJsonPath('data.merk', $merk->merk)
            ->assertJsonPath('data.is_active', true);
    }

    /**
     * Test MerkController::getMerkById dengan ID yang tidak ditemukan
     * Menguji apakah method mengembalikan 404 ketika merk tidak ada
     */
    public function test_get_merk_by_id_not_found(): void
    {
        // Arrange: ID yang tidak ada di database
        $nonExistentId = 99999;

        // Act: Panggil endpoint dengan ID tidak valid
        $response = $this->getJson(self::API_BASE_URL . "/{$nonExistentId}");

        // Assert: Harus return 404 dengan pesan error
        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => Messages::MERK_NOT_FOUND
            ]);
    }

    /**
     * Test MerkController::getMerkById dengan format ID tidak valid
     * Menguji apakah method menangani ID yang tidak valid dengan benar
     */
    public function test_get_merk_by_id_invalid_format(): void
    {
        // Act: Panggil endpoint dengan ID string non-numerik
        $response = $this->getJson(self::API_BASE_URL . "/invalid-id");

        // Assert: Harus return 404
        $response->assertStatus(404);
    }

    /**
     * Test MerkController::getMerkById dengan merk yang tidak aktif
     * Menguji apakah method tetap mengembalikan merk yang tidak aktif
     */
    public function test_get_merk_by_id_inactive_merk(): void
    {
        // Arrange: Buat merk yang tidak aktif
        $merk = Merk::factory()->create([
            MerkColumns::MERK => 'Inactive Merk ' . uniqid(),
            MerkColumns::IS_ACTIVE => false
        ]);

        // Act
        $response = $this->getJson(self::API_BASE_URL . "/{$merk->id}");

        // Assert
        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $merk->id)
            ->assertJsonPath('data.is_active', false)
            ->assertJsonPath('data.status_label', 'Tidak Aktif');
    }

    /**
     * Test MerkController::getMerkById dengan accessors
     * Menguji apakah accessor status_label dan display_name berfungsi
     */
    public function test_get_merk_by_id_has_accessors(): void
    {
        // Arrange
        $merk = Merk::factory()->create([
            MerkColumns::MERK => 'Accessor Test Merk',
            MerkColumns::IS_ACTIVE => true
        ]);

        // Act
        $response = $this->getJson(self::API_BASE_URL . "/{$merk->id}");

        // Assert: Verifikasi accessor
        $response->assertStatus(200)
            ->assertJsonPath('data.status_label', 'Aktif')
            ->assertJsonPath('data.display_name', '✅ Accessor Test Merk');
    }

    // ========== INDEX METHOD TESTS ==========

    /**
     * Test GET /api/merks - Index method
     */
    public function test_api_index_returns_all_merks(): void
    {
        // Arrange: Buat beberapa merk
        Merk::factory()->count(3)->create();

        // Act
        $response = $this->getJson(self::API_BASE_URL);

        // Assert
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'merk',
                        'is_active',
                        'created_at',
                        'updated_at'
                    ]
                ],
                'meta' => [
                    'current_page',
                    'last_page',
                    'per_page',
                    'total'
                ]
            ]);

        $this->assertCount(3, $response->json('data'));
    }

    /**
     * Test GET /api/merks dengan pagination
     */
    public function test_api_index_with_pagination(): void
    {
        // Arrange
        Merk::factory()->count(5)->create();

        // Act
        $response = $this->getJson(self::API_BASE_URL . '?per_page=2');

        // Assert
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'meta' => [
                    'current_page',
                    'per_page',
                    'total'
                ]
            ]);

        $this->assertCount(2, $response->json('data'));
        $this->assertArrayHasKey('meta', $response->json());
    }

    /**
     * Test GET /api/merks dengan search
     */
    public function test_api_index_with_search(): void
    {
        // Arrange
        Merk::factory()->create([MerkColumns::MERK => 'Samsung Merk']);
        Merk::factory()->create([MerkColumns::MERK => 'Apple Merk']);
        Merk::factory()->create([MerkColumns::MERK => 'Sony Merk']);

        // Act
        $response = $this->getJson(self::API_BASE_URL . '?search=Samsung');

        // Assert
        $response->assertStatus(200);
        $merks = $response->json('data');
        $this->assertCount(1, $merks);
        $this->assertStringContainsString('Samsung', $merks[0]['merk']);
    }

    /**
     * Test GET /api/merks dengan sorting
     */
    public function test_api_index_with_sorting(): void
    {
        // Arrange
        $olderMerk = Merk::factory()->create([
            MerkColumns::MERK => 'Older Merk',
            MerkColumns::CREATED_AT => now()->subDays(2)
        ]);
        $newerMerk = Merk::factory()->create([
            MerkColumns::MERK => 'Newer Merk',
            MerkColumns::CREATED_AT => now()->subDay()
        ]);

        // Act - Ascending sort
        $response = $this->getJson(self::API_BASE_URL . '?sort_by=created_at&sort_order=asc');

        // Assert
        $response->assertStatus(200);
        $merks = $response->json('data');
        $this->assertEquals($olderMerk->id, $merks[0]['id']);
        $this->assertEquals($newerMerk->id, $merks[1]['id']);
    }

    /**
     * Test GET /api/merks dengan filter is_active
     */
    public function test_api_index_with_active_filter(): void
    {
        // Arrange
        Merk::factory()->create([MerkColumns::MERK => 'Active 1', MerkColumns::IS_ACTIVE => true]);
        Merk::factory()->create([MerkColumns::MERK => 'Active 2', MerkColumns::IS_ACTIVE => true]);
        Merk::factory()->create([MerkColumns::MERK => 'Inactive', MerkColumns::IS_ACTIVE => false]);

        // Act
        $response = $this->getJson(self::API_BASE_URL . '?is_active=true');

        // Assert
        $response->assertStatus(200);
        $merks = $response->json('data');
        $this->assertCount(2, $merks);
        foreach ($merks as $merk) {
            $this->assertTrue($merk['is_active']);
        }
    }

    // ========== STORE METHOD TESTS ==========

    /**
     * Test POST /api/merks - Create new merk
     */
    public function test_api_store_creates_new_merk(): void
    {
        // Arrange
        $merkData = [
            'merk' => 'New Test Merk',
            'is_active' => true
        ];

        // Act
        $response = $this->postJson(self::API_BASE_URL, $merkData);

        // Assert
        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => Messages::MERK_CREATED
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'merk',
                    'is_active'
                ]
            ]);

        // Verify in database
        $this->assertDatabaseHas('merks', [
            MerkColumns::MERK => 'New Test Merk',
            MerkColumns::IS_ACTIVE => true
        ]);
    }

    /**
     * Test POST /api/merks - Validation errors
     */
    public function test_api_store_validation_errors(): void
    {
        // Act - Empty data
        $response = $this->postJson(self::API_BASE_URL, []);

        // Assert
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['merk']);

        // Act - Name too short
        $response = $this->postJson(self::API_BASE_URL, [
            'merk' => 'AB' // Too short
        ]);

        // Assert
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['merk']);
    }

    // ========== UPDATE METHOD TESTS ==========

    /**
     * Test PUT /api/merks/{id} - Update merk
     */
    public function test_api_update_merk_success(): void
    {
        // Arrange
        $merk = Merk::factory()->create([
            MerkColumns::MERK => 'Original Name'
        ]);

        // Act
        $response = $this->putJson(self::API_BASE_URL . "/{$merk->id}", [
            'merk' => 'Updated Name',
            'is_active' => false
        ]);

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => Messages::MERK_UPDATED
            ])
            ->assertJsonPath('data.merk', 'Updated Name')
            ->assertJsonPath('data.is_active', false);

        // Verify in database
        $this->assertDatabaseHas('merks', [
            'id' => $merk->id,
            MerkColumns::MERK => 'Updated Name',
            MerkColumns::IS_ACTIVE => false
        ]);
    }

    /**
     * Test PUT /api/merks/{id} - Update not found
     */
    public function test_api_update_merk_not_found(): void
    {
        // Act
        $response = $this->putJson(self::API_BASE_URL . "/99999", [
            'merk' => 'Updated Name'
        ]);

        // Assert
        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => Messages::MERK_NOT_FOUND
            ]);
    }

    // ========== DELETE METHOD TESTS ==========

    /**
     * Test DELETE /api/merks/{id} - Delete merk
     */
    public function test_api_delete_merk_success(): void
    {
        // Arrange
        $merk = Merk::factory()->create([
            MerkColumns::MERK => 'To Be Deleted'
        ]);

        // Act
        $response = $this->deleteJson(self::API_BASE_URL . "/{$merk->id}");

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => Messages::MERK_DELETED
            ]);

        // Verify in database
        $this->assertDatabaseMissing('merks', [
            'id' => $merk->id
        ]);
    }

    /**
     * Test DELETE /api/merks/{id} - Delete not found
     */
    public function test_api_delete_merk_not_found(): void
    {
        // Act
        $response = $this->deleteJson(self::API_BASE_URL . "/99999");

        // Assert
        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => Messages::MERK_NOT_FOUND
            ]);
    }

    // ========== ADDITIONAL ENDPOINT TESTS ==========

    /**
     * Test GET /api/merks/active - Get active merks
     */
    public function test_api_active_returns_only_active(): void
    {
        // Arrange
        Merk::factory()->create([MerkColumns::MERK => 'Active 1', MerkColumns::IS_ACTIVE => true]);
        Merk::factory()->create([MerkColumns::MERK => 'Active 2', MerkColumns::IS_ACTIVE => true]);
        Merk::factory()->create([MerkColumns::MERK => 'Inactive', MerkColumns::IS_ACTIVE => false]);

        // Act
        $response = $this->getJson(self::API_BASE_URL . '/active');

        // Assert
        $response->assertStatus(200)
            ->assertJsonPath('success', true);

        $merks = $response->json('data');
        $this->assertCount(2, $merks);
        foreach ($merks as $merk) {
            $this->assertTrue($merk['is_active']);
        }
    }

    /**
     * Test GET /api/merks/statistics - Get statistics
     */
    public function test_api_statistics_returns_correct_data(): void
    {
        // Arrange
        Merk::factory()->count(5)->create([MerkColumns::IS_ACTIVE => true]);
        Merk::factory()->count(3)->create([MerkColumns::IS_ACTIVE => false]);

        // Act
        $response = $this->getJson(self::API_BASE_URL . '/statistics');

        // Assert
        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'data' => [
                    'total_merk',
                    'active_merk',
                    'inactive_merk',
                    'percentage_active'
                ]
            ]);

        $stats = $response->json('data');
        $this->assertEquals(8, $stats['total_merk']);
        $this->assertEquals(5, $stats['active_merk']);
        $this->assertEquals(3, $stats['inactive_merk']);
    }

    /**
     * Test GET /api/merks/search - Search endpoint
     */
    public function test_api_search_merks(): void
    {
        // Arrange
        Merk::factory()->create([MerkColumns::MERK => 'Samsung Galaxy']);
        Merk::factory()->create([MerkColumns::MERK => 'Samsung TV']);
        Merk::factory()->create([MerkColumns::MERK => 'Apple iPhone']);

        // Act
        $response = $this->getJson(self::API_BASE_URL . '/search?name=Samsung');

        // Assert
        $response->assertStatus(200)
            ->assertJsonPath('success', true);

        $merks = $response->json('data');
        $this->assertCount(2, $merks);
        foreach ($merks as $merk) {
            $this->assertStringContainsString('Samsung', $merk['merk']);
        }
    }
}

