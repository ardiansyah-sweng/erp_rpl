<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Branch;
use App\Constants\BranchColumns;

class BranchApiFeatureTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
        
        // Set JSON headers for all requests
        $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ]);
    }

    /**
     * Feature Test: Complete CRUD workflow
     */
    public function test_complete_branch_crud_workflow()
    {
        // 1. CREATE - Store new branch
        $branchData = [
            BranchColumns::NAME => 'Jakarta Pusat Branch',
            BranchColumns::ADDRESS => 'Jl. Sudirman No. 123, Jakarta',
            BranchColumns::PHONE => '021-12345678'
        ];

        $createResponse = $this->postJson('/api/branches', $branchData);
        $createResponse->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Branch created successfully'
            ]);

        $branchId = $createResponse->json('data.id');

        // 2. READ - Get the created branch
        $showResponse = $this->getJson("/api/branches/{$branchId}");
        $showResponse->assertStatus(200)
            ->assertJson([
                'id' => $branchId,
                'branch_name' => 'Jakarta Pusat Branch',
                'branch_address' => 'Jl. Sudirman No. 123, Jakarta',
                'branch_telephone' => '021-12345678',
                'is_active' => true
            ]);

        // 3. UPDATE - Modify the branch
        $updateData = [
            BranchColumns::NAME => 'Jakarta Pusat Branch Updated',
            BranchColumns::ADDRESS => 'Jl. Sudirman No. 456, Jakarta',
            BranchColumns::PHONE => '021-87654321',
            BranchColumns::IS_ACTIVE => false
        ];

        $updateResponse = $this->putJson("/api/branches/{$branchId}", $updateData);
        $updateResponse->assertStatus(200)
            ->assertJson([
                'message' => 'Branch updated successfully'
            ]);

        // Verify update
        $verifyResponse = $this->getJson("/api/branches/{$branchId}");
        $verifyResponse->assertJson([
            'branch_name' => 'Jakarta Pusat Branch Updated',
            'is_active' => false,
            'status' => 'Tidak Aktif'
        ]);

        // 4. DELETE - Remove the branch
        $deleteResponse = $this->deleteJson("/api/branches/{$branchId}");
        $deleteResponse->assertStatus(200)
            ->assertJson([
                'message' => 'Branch deleted successfully'
            ]);

        // Verify deletion
        $notFoundResponse = $this->getJson("/api/branches/{$branchId}");
        $notFoundResponse->assertStatus(404);
    }

    /**
     * Feature Test: Branch listing with filtering and search
     */
    public function test_branch_listing_with_filtering()
    {
        // Setup test data
        Branch::factory()->create([
            BranchColumns::NAME => 'Jakarta Pusat',
            BranchColumns::IS_ACTIVE => true
        ]);
        
        Branch::factory()->create([
            BranchColumns::NAME => 'Jakarta Utara', 
            BranchColumns::IS_ACTIVE => false
        ]);
        
        Branch::factory()->create([
            BranchColumns::NAME => 'Surabaya Selatan',
            BranchColumns::IS_ACTIVE => true
        ]);

        // Test 1: Get all branches
        $allResponse = $this->getJson('/api/branches');
        $allResponse->assertStatus(200);
        $this->assertCount(3, $allResponse->json('data'));
        $this->assertEquals(3, $allResponse->json('meta.total'));
        $this->assertEquals(2, $allResponse->json('meta.active_count'));
        $this->assertEquals(1, $allResponse->json('meta.inactive_count'));

        // Test 2: Filter by status - active only
        $activeResponse = $this->getJson('/api/branches?status=active');
        $activeResponse->assertStatus(200);
        $activeData = $activeResponse->json('data');
        $this->assertCount(2, $activeData);
        foreach ($activeData as $branch) {
            $this->assertTrue($branch['is_active']);
        }

        // Test 3: Filter by status - inactive only
        $inactiveResponse = $this->getJson('/api/branches?status=inactive');
        $inactiveResponse->assertStatus(200);
        $inactiveData = $inactiveResponse->json('data');
        $this->assertCount(1, $inactiveData);
        $this->assertFalse($inactiveData[0]['is_active']);

        // Test 4: Search functionality
        $searchResponse = $this->getJson('/api/branches?search=Jakarta');
        $searchResponse->assertStatus(200);
        $searchData = $searchResponse->json('data');
        $this->assertCount(2, $searchData);
        foreach ($searchData as $branch) {
            $this->assertStringContainsString('Jakarta', $branch['branch_name']);
        }

        // Test 5: Combined search and filter
        $combinedResponse = $this->getJson('/api/branches?search=Jakarta&status=active');
        $combinedResponse->assertStatus(200);
        $combinedData = $combinedResponse->json('data');
        $this->assertCount(1, $combinedData);
        $this->assertStringContainsString('Jakarta', $combinedData[0]['branch_name']);
        $this->assertTrue($combinedData[0]['is_active']);
    }

    /**
     * Feature Test: Validation errors handling
     */
    public function test_validation_errors_handling()
    {
        // Test 1: Missing required fields
        $emptyResponse = $this->postJson('/api/branches', []);
        $emptyResponse->assertStatus(422)
            ->assertJsonValidationErrors([
                BranchColumns::NAME,
                BranchColumns::ADDRESS,
                BranchColumns::PHONE
            ]);

        // Test 2: Field too short
        $shortResponse = $this->postJson('/api/branches', [
            BranchColumns::NAME => 'AB', // Too short (min:3)
            BranchColumns::ADDRESS => 'XY', // Too short (min:3)
            BranchColumns::PHONE => '12' // Too short (min:3)
        ]);
        $shortResponse->assertStatus(422)
            ->assertJsonValidationErrors([
                BranchColumns::NAME,
                BranchColumns::ADDRESS,
                BranchColumns::PHONE
            ]);

        // Test 3: Field too long
        $longResponse = $this->postJson('/api/branches', [
            BranchColumns::NAME => str_repeat('A', 51), // Too long (max:50)
            BranchColumns::ADDRESS => str_repeat('B', 101), // Too long (max:100)
            BranchColumns::PHONE => str_repeat('1', 31) // Too long (max:30)
        ]);
        $longResponse->assertStatus(422)
            ->assertJsonValidationErrors([
                BranchColumns::NAME,
                BranchColumns::ADDRESS,
                BranchColumns::PHONE
            ]);

        // Test 4: Duplicate name
        Branch::factory()->create([BranchColumns::NAME => 'Existing Branch']);
        
        $duplicateResponse = $this->postJson('/api/branches', [
            BranchColumns::NAME => 'Existing Branch',
            BranchColumns::ADDRESS => 'Some address',
            BranchColumns::PHONE => '021-12345678'
        ]);
        $duplicateResponse->assertStatus(422)
            ->assertJsonValidationErrors([BranchColumns::NAME]);
    }

    /**
     * Feature Test: Error handling for non-existent resources
     */
    public function test_error_handling_for_non_existent_resources()
    {
        // Test 1: Get non-existent branch
        $showResponse = $this->getJson('/api/branches/999999');
        $showResponse->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Cabang tidak ditemukan!'
            ]);

        // Test 2: Update non-existent branch
        $updateResponse = $this->putJson('/api/branches/999999', [
            BranchColumns::NAME => 'Updated Name'
        ]);
        $updateResponse->assertStatus(404);

        // Test 3: Delete non-existent branch
        $deleteResponse = $this->deleteJson('/api/branches/999999');
        $deleteResponse->assertStatus(404);

        // Test 4: Invalid ID format
        $invalidIdResponse = $this->getJson('/api/branches/invalid-id');
        $invalidIdResponse->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => 'Invalid branch ID format'
            ]);
    }

    /**
     * Feature Test: Custom endpoints functionality
     */
    public function test_custom_endpoints_functionality()
    {
        // Setup test data
        Branch::factory()->count(3)->active()->create();
        Branch::factory()->count(2)->inactive()->create();

        // Test 1: Active branches endpoint
        $activeResponse = $this->getJson('/api/branches/filter/active');
        $activeResponse->assertStatus(200);
        $activeData = $activeResponse->json('data');
        $this->assertCount(3, $activeData);
        foreach ($activeData as $branch) {
            $this->assertTrue($branch['is_active']);
        }

        // Test 2: Statistics endpoint
        $statsResponse = $this->getJson('/api/branches/analytics/statistics');
        $statsResponse->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'total_branches' => 5,
                    'active_branches' => 3,
                    'inactive_branches' => 2
                ]
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
    }

    /**
     * Feature Test: Advanced search functionality
     */
    public function test_advanced_search_functionality()
    {
        // Setup searchable test data
        Branch::factory()->create([
            BranchColumns::NAME => 'Jakarta Main Office',
            BranchColumns::ADDRESS => 'Jl. Sudirman Jakarta',
            BranchColumns::PHONE => '021-jakarta-123'
        ]);

        Branch::factory()->create([
            BranchColumns::NAME => 'Surabaya Branch',
            BranchColumns::ADDRESS => 'Jl. Pemuda Surabaya', 
            BranchColumns::PHONE => '031-surabaya-456'
        ]);

        // Test 1: Basic search
        $searchResponse = $this->getJson('/api/branches/search/advanced?q=jakarta');
        $searchResponse->assertStatus(200);
        $searchData = $searchResponse->json('data');
        $this->assertCount(1, $searchData);
        $this->assertStringContainsString('Jakarta', $searchData[0]['branch_name']);

        // Test 2: Search with field restriction
        $fieldSearchResponse = $this->getJson('/api/branches/search/advanced?q=jakarta&fields[]=name');
        $fieldSearchResponse->assertStatus(200);
        $fieldData = $fieldSearchResponse->json('data');
        $this->assertCount(1, $fieldData);

        // Test 3: Search with status filter
        Branch::factory()->create([
            BranchColumns::NAME => 'Jakarta Inactive',
            BranchColumns::IS_ACTIVE => false
        ]);

        $statusSearchResponse = $this->getJson('/api/branches/search/advanced?q=jakarta&status=active');
        $statusSearchResponse->assertStatus(200);
        $statusData = $statusSearchResponse->json('data');
        $this->assertCount(1, $statusData);
        $this->assertTrue($statusData[0]['is_active']);

        // Test 4: Search validation - minimum query length
        $shortQueryResponse = $this->getJson('/api/branches/search/advanced?q=a');
        $shortQueryResponse->assertStatus(422)
            ->assertJsonValidationErrors(['q']);
    }

    /**
     * Feature Test: Bulk operations
     */
    public function test_bulk_operations()
    {
        // Setup test data
        $branches = Branch::factory()->count(3)->inactive()->create();
        $branchIds = $branches->pluck('id')->toArray();

        // Test 1: Bulk status update to active
        $bulkUpdateResponse = $this->postJson('/api/branches/bulk/update-status', [
            'branch_ids' => $branchIds,
            'status' => true
        ]);

        $bulkUpdateResponse->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'updated_count' => 3,
                    'new_status' => 'active'
                ]
            ]);

        // Verify all branches are now active
        foreach ($branchIds as $id) {
            $branch = Branch::find($id);
            $this->assertTrue($branch->{BranchColumns::IS_ACTIVE});
        }

        // Test 2: Bulk update validation - invalid branch IDs
        $invalidBulkResponse = $this->postJson('/api/branches/bulk/update-status', [
            'branch_ids' => [999999, 999998],
            'status' => true
        ]);
        $invalidBulkResponse->assertStatus(422)
            ->assertJsonValidationErrors(['branch_ids.0', 'branch_ids.1']);

        // Test 3: Bulk update validation - missing fields
        $missingFieldsResponse = $this->postJson('/api/branches/bulk/update-status', []);
        $missingFieldsResponse->assertStatus(422)
            ->assertJsonValidationErrors(['branch_ids', 'status']);
    }

    /**
     * Feature Test: JSON Response structure consistency
     */
    public function test_json_response_structure_consistency()
    {
        $branch = Branch::factory()->create();

        // Test single resource structure
        $singleResponse = $this->getJson("/api/branches/{$branch->id}");
        $singleResponse->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'branch_name',
                'branch_address', 
                'branch_telephone',
                'is_active',
                'status',
                'status_badge' => [
                    'text',
                    'color',
                    'icon'
                ],
                'display_name',
                'short_address',
                'formatted_phone',
                'created_at',
                'updated_at',
                'created_at_human',
                'updated_at_human'
            ]);

        // Test collection structure
        $collectionResponse = $this->getJson('/api/branches');
        $collectionResponse->assertStatus(200)
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
                    'inactive_count',
                    'percentage_active'
                ],
                'summary' => [
                    'status_distribution',
                    'cities',
                    'latest_branch'
                ]
            ]);
    }

    /**
     * Feature Test: API Performance
     */
    public function test_api_performance()
    {
        // Create large dataset
        Branch::factory()->count(100)->create();

        // Test response time for listing
        $startTime = microtime(true);
        $response = $this->getJson('/api/branches');
        $endTime = microtime(true);
        
        $response->assertStatus(200);
        $responseTime = $endTime - $startTime;
        
        // Should respond within 1 second for 100 records
        $this->assertLessThan(1.0, $responseTime, 'API should respond within 1 second');
        
        // Test pagination for large datasets
        $paginatedResponse = $this->getJson('/api/branches?per_page=10');
        $paginatedResponse->assertStatus(200);
        $this->assertCount(10, $paginatedResponse->json('data'));
    }

    /**
     * Feature Test: Content-Type and Accept headers
     */
    public function test_content_type_and_accept_headers()
    {
        $branch = Branch::factory()->create();

        // Test JSON response with proper headers
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ])->getJson("/api/branches/{$branch->id}");

        $response->assertStatus(200)
            ->assertHeader('Content-Type', 'application/json');

        // Test that API returns JSON even without Accept header
        $noHeaderResponse = $this->get("/api/branches/{$branch->id}");
        $noHeaderResponse->assertStatus(200);
    }
}
