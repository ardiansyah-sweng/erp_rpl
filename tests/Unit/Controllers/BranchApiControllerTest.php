<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use App\Models\Branch;
use App\Constants\BranchColumns;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;

class BranchApiControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
    }

    /**
     * Test index endpoint with default behavior
     */
    public function test_index_returns_all_branches()
    {
        // Arrange: Create test data
        Branch::factory()->count(3)->create();

        // Act: Call endpoint
        $response = $this->getJson('/api/branches');

        // Assert: Check response
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'branch_name',
                        'branch_address',
                        'branch_telephone',
                        'is_active',
                        'status'
                    ]
                ],
                'meta' => [
                    'total',
                    'active_count',
                    'inactive_count'
                ]
            ]);
        
        $this->assertCount(3, $response->json('data'));
    }

    /**
     * Test index endpoint with search functionality
     */
    public function test_index_with_search_parameter()
    {
        // Arrange: Create searchable data
        Branch::factory()->create([BranchColumns::NAME => 'Jakarta Pusat Branch']);
        Branch::factory()->create([BranchColumns::NAME => 'Surabaya Branch']);
        Branch::factory()->create([BranchColumns::ADDRESS => 'Jakarta Street 123']);

        // Act: Search for Jakarta
        $response = $this->getJson('/api/branches?search=Jakarta');

        // Assert: Only Jakarta related branches returned
        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(2, $data);
        
        foreach ($data as $branch) {
            $containsJakarta = strpos($branch['branch_name'], 'Jakarta') !== false ||
                              strpos($branch['branch_address'], 'Jakarta') !== false;
            $this->assertTrue($containsJakarta);
        }
    }

    /**
     * Test index endpoint with status filtering
     */
    public function test_index_with_status_filter()
    {
        // Arrange: Create mixed status branches
        Branch::factory()->count(2)->active()->create();
        Branch::factory()->count(1)->inactive()->create();

        // Act: Filter active only
        $response = $this->getJson('/api/branches?status=active');

        // Assert: Only active branches returned
        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(2, $data);
        
        foreach ($data as $branch) {
            $this->assertTrue($branch['is_active']);
        }
    }

    /**
     * Test store endpoint with valid data
     */
    public function test_store_creates_branch_with_valid_data()
    {
        // Arrange: Prepare valid data
        $branchData = [
            BranchColumns::NAME => 'New Test Branch',
            BranchColumns::ADDRESS => 'Test Address 123',
            BranchColumns::PHONE => '021-12345678'
        ];

        // Act: Create branch
        $response = $this->postJson('/api/branches', $branchData);

        // Assert: Branch created successfully
        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Branch created successfully'
            ])
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'branch_name',
                    'branch_address',
                    'branch_telephone',
                    'is_active'
                ]
            ]);

        // Assert: Data in database
        $this->assertDatabaseHas('branches', [
            BranchColumns::NAME => 'New Test Branch'
        ]);
    }

    /**
     * Test store endpoint with invalid data
     */
    public function test_store_fails_with_invalid_data()
    {
        // Arrange: Invalid data (missing required fields)
        $invalidData = [
            BranchColumns::ADDRESS => 'Some address'
            // Missing branch_name (required)
        ];

        // Act: Try to create branch
        $response = $this->postJson('/api/branches', $invalidData);

        // Assert: Validation error
        $response->assertStatus(422)
            ->assertJsonValidationErrors([BranchColumns::NAME]);
    }

    /**
     * Test show endpoint with existing branch
     */
    public function test_show_returns_existing_branch()
    {
        // Arrange: Create test branch
        $branch = Branch::factory()->create([
            BranchColumns::NAME => 'Specific Test Branch'
        ]);

        // Act: Get specific branch
        $response = $this->getJson("/api/branches/{$branch->id}");

        // Assert: Correct branch returned
        $response->assertStatus(200)
            ->assertJson([
                'id' => $branch->id,
                'branch_name' => 'Specific Test Branch'
            ]);
    }

    /**
     * Test show endpoint with non-existent branch
     */
    public function test_show_returns_404_for_non_existent_branch()
    {
        // Act: Request non-existent branch
        $response = $this->getJson('/api/branches/999999');

        // Assert: 404 error
        $response->assertStatus(404)
            ->assertJson([
                'success' => false
            ]);
    }

    /**
     * Test show endpoint with invalid ID format
     */
    public function test_show_returns_400_for_invalid_id()
    {
        // Act: Request with invalid ID
        $response = $this->getJson('/api/branches/invalid-id');

        // Assert: 400 error
        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => 'Invalid branch ID format'
            ]);
    }

    /**
     * Test update endpoint with valid data
     */
    public function test_update_modifies_existing_branch()
    {
        // Arrange: Create test branch
        $branch = Branch::factory()->create();
        $updateData = [
            BranchColumns::NAME => 'Updated Branch Name',
            BranchColumns::ADDRESS => 'Updated Address'
        ];

        // Act: Update branch
        $response = $this->putJson("/api/branches/{$branch->id}", $updateData);

        // Assert: Update successful
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Branch updated successfully'
            ]);

        // Assert: Database updated
        $this->assertDatabaseHas('branches', [
            'id' => $branch->id,
            BranchColumns::NAME => 'Updated Branch Name'
        ]);
    }

    /**
     * Test destroy endpoint
     */
    public function test_destroy_deletes_existing_branch()
    {
        // Arrange: Create test branch
        $branch = Branch::factory()->create();

        // Act: Delete branch
        $response = $this->deleteJson("/api/branches/{$branch->id}");

        // Assert: Delete successful
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Branch deleted successfully'
            ]);

        // Assert: Removed from database
        $this->assertDatabaseMissing('branches', [
            'id' => $branch->id
        ]);
    }

    /**
     * Test active branches endpoint
     */
    public function test_active_returns_only_active_branches()
    {
        // Arrange: Create mixed branches
        Branch::factory()->count(2)->active()->create();
        Branch::factory()->count(1)->inactive()->create();

        // Act: Get active branches
        $response = $this->getJson('/api/branches/filter/active');

        // Assert: Only active branches
        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(2, $data);
        
        foreach ($data as $branch) {
            $this->assertTrue($branch['is_active']);
        }
    }

    /**
     * Test statistics endpoint
     */
    public function test_statistics_returns_branch_analytics()
    {
        // Arrange: Create test data
        Branch::factory()->count(3)->active()->create();
        Branch::factory()->count(1)->inactive()->create();

        // Act: Get statistics
        $response = $this->getJson('/api/branches/analytics/statistics');

        // Assert: Statistics returned
        $response->assertStatus(200)
            ->assertJson([
                'success' => true
            ])
            ->assertJsonStructure([
                'data' => [
                    'total_branches',
                    'active_branches',
                    'inactive_branches',
                    'latest_branch',
                    'oldest_branch',
                    'city_distribution'
                ]
            ]);

        $data = $response->json('data');
        $this->assertEquals(4, $data['total_branches']);
        $this->assertEquals(3, $data['active_branches']);
        $this->assertEquals(1, $data['inactive_branches']);
    }

    /**
     * Test bulk status update endpoint
     */
    public function test_bulk_update_status_modifies_multiple_branches()
    {
        // Arrange: Create test branches
        $branches = Branch::factory()->count(3)->inactive()->create();
        $branchIds = $branches->pluck('id')->toArray();

        // Act: Bulk update to active
        $response = $this->postJson('/api/branches/bulk/update-status', [
            'branch_ids' => $branchIds,
            'status' => true
        ]);

        // Assert: Bulk update successful
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'updated_count' => 3,
                    'new_status' => 'active'
                ]
            ]);

        // Assert: All branches now active
        foreach ($branchIds as $id) {
            $this->assertDatabaseHas('branches', [
                'id' => $id,
                BranchColumns::IS_ACTIVE => true
            ]);
        }
    }

    /**
     * Test advanced search endpoint
     */
    public function test_advanced_search_finds_matching_branches()
    {
        // Arrange: Create searchable branches
        Branch::factory()->create([BranchColumns::NAME => 'Jakarta Main Branch']);
        Branch::factory()->create([BranchColumns::ADDRESS => 'Jakarta Street 456']);
        Branch::factory()->create([BranchColumns::PHONE => '021-jakarta-789']);
        Branch::factory()->create([BranchColumns::NAME => 'Surabaya Branch']);

        // Act: Search for Jakarta
        $response = $this->getJson('/api/branches/search/advanced?q=jakarta');

        // Assert: Jakarta related branches found
        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(3, $data);
    }

    /**
     * Test search with field filtering
     */
    public function test_search_with_specific_fields()
    {
        // Arrange: Create test data
        Branch::factory()->create([BranchColumns::NAME => 'Jakarta Name']);
        Branch::factory()->create([BranchColumns::ADDRESS => 'Jakarta Address']);

        // Act: Search only in name field
        $response = $this->getJson('/api/branches/search/advanced?q=jakarta&fields[]=name');

        // Assert: Only name field matches returned
        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(1, $data);
        $this->assertStringContainsString('Jakarta', $data[0]['branch_name']);
    }
}
