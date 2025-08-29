<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Constants\BranchColumns;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BranchApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private const API_BASE_URL = '/api/branches';

    // ========== SHOW METHOD TESTS ==========
    
    /**
     * Test API show method with valid branch ID
     */
    public function test_api_show_branch_success(): void
    {
        // Create test branch
        $branch = Branch::factory()->create([
            BranchColumns::NAME => 'Test Branch Show ' . uniqid(),
            BranchColumns::ADDRESS => '123 Test Address',
            BranchColumns::PHONE => '081234567890'
        ]);

        // API request with Accept: application/json header
        $response = $this->getJson(self::API_BASE_URL . "/{$branch->id}");

        // Assertions
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         'id',
                         'branch_name', 
                         'branch_address',
                         'branch_telephone',
                         'is_active',
                         'status',
                         'status_badge',
                         'display_name',
                         'short_address',
                         'formatted_phone',
                         'created_at',
                         'updated_at'
                     ]
                 ])
                 ->assertJsonPath('data.id', $branch->id)
                 ->assertJsonPath('data.branch_name', $branch->branch_name)
                 ->assertJsonPath('data.branch_address', '123 Test Address')
                 ->assertJsonPath('data.branch_telephone', '081234567890');
    }

    /**
     * Test API show method with non-existent branch ID
     */
    public function test_api_show_branch_not_found(): void
    {
        $nonExistentId = 99999;

        // API request
        $response = $this->getJson(self::API_BASE_URL . "/{$nonExistentId}");

        // Should return 404 with JSON error
        $response->assertStatus(404)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Cabang tidak ditemukan!'
                 ]);
    }

    /**
     * Test show method with invalid ID format
     */
    public function test_show_branch_with_invalid_id(): void
    {
        // Test with string ID that's not numeric
        $response = $this->getJson(self::API_BASE_URL . "/invalid-id");

        $response->assertStatus(404);
    }

    // ========== INDEX METHOD TESTS ==========

    /**
     * Test GET /api/branches - Index method basic functionality
     */
    public function test_can_get_all_branches_via_api_index()
    {
        // Arrange
        Branch::factory()->count(3)->create();

        // Act
        $response = $this->getJson(self::API_BASE_URL);

        // Assert
        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'id',
                            'branch_name',
                            'branch_address',
                            'branch_telephone',
                            'is_active',
                            'status',
                            'created_at',
                            'updated_at'
                        ]
                    ]
                ]);

        $this->assertCount(3, $response->json('data'));
    }

    /**
     * Test GET /api/branches - Index with pagination
     */
    public function test_can_get_paginated_branches_via_api_index()
    {
        // Arrange
        Branch::factory()->count(5)->create();

        // Act
        $response = $this->getJson(self::API_BASE_URL . '?per_page=3');

        // Assert
        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'id',
                            'branch_name',
                            'branch_address',
                            'branch_telephone',
                            'is_active'
                        ]
                    ],
                    'meta' => [
                        'current_page',
                        'per_page',
                        'total'
                    ]
                ]);

        // Verify pagination is working - should have meta information
        $this->assertArrayHasKey('meta', $response->json());
        $this->assertArrayHasKey('current_page', $response->json('meta'));
        $this->assertArrayHasKey('total', $response->json('meta'));
    }

    /**
     * Test GET /api/branches - Index with search functionality
     */
    public function test_can_search_branches_via_api_index()
    {
        // Arrange
        Branch::factory()->create([
            BranchColumns::NAME => 'Jakarta Central Branch'
        ]);
        Branch::factory()->create([
            BranchColumns::NAME => 'Bandung Main Office'
        ]);

        // Act
        $response = $this->getJson(self::API_BASE_URL . '?search=Jakarta');

        // Assert
        $response->assertStatus(200);
        $branches = $response->json('data');
        $this->assertCount(1, $branches);
        $this->assertStringContainsString('Jakarta', $branches[0]['branch_name']);
    }

    /**
     * Test GET /api/branches - Index with sorting
     */
    public function test_can_sort_branches_via_api_index()
    {
        // Arrange
        $olderBranch = Branch::factory()->create([
            BranchColumns::NAME => 'Older Branch',
            BranchColumns::CREATED_AT => now()->subDays(2)
        ]);
        $newerBranch = Branch::factory()->create([
            BranchColumns::NAME => 'Newer Branch',
            BranchColumns::CREATED_AT => now()->subDay()
        ]);

        // Act - Test ascending sort
        $response = $this->getJson(self::API_BASE_URL . '?sort_by=created_at&sort_order=asc');

        // Assert
        $response->assertStatus(200);
        $branches = $response->json('data');
        $this->assertEquals($olderBranch->id, $branches[0]['id']);
        $this->assertEquals($newerBranch->id, $branches[1]['id']);
    }

    /**
     * Test POST /api/branches - Store method creates new branch successfully
     */
    public function test_can_create_branch_via_api_store()
    {
        // Arrange
        $branchData = [
            'branch_name' => 'New API Branch',
            'branch_address' => 'Jl. API Store No. 456',
            'branch_telephone' => '081234567890',
            BranchColumns::IS_ACTIVE => true
        ];

        // Act
        $response = $this->postJson(self::API_BASE_URL, $branchData);

        // Assert
        $response->assertStatus(201)
                ->assertJson([
                    'success' => true,
                    'message' => 'Branch created successfully'
                ])
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        'id',
                        'branch_name',
                        'branch_address',
                        'branch_telephone',
                        'is_active'
                    ]
                ]);

        // Verify in database
        $this->assertDatabaseHas('branches', [
            BranchColumns::NAME => 'New API Branch',
            BranchColumns::ADDRESS => 'Jl. API Store No. 456'
        ]);
    }

    /**
     * Test POST /api/branches - Store method validation errors
     */
    public function test_store_method_validation_errors()
    {
        // Act - Empty data
        $response = $this->postJson(self::API_BASE_URL, []);

        // Assert
        $response->assertStatus(422)
                ->assertJsonValidationErrors(['branch_name']);

        // Act - Invalid data (too short)
        $response = $this->postJson(self::API_BASE_URL, [
            'branch_name' => 'AB', // Too short
            'branch_address' => 'X', // Too short
            'branch_telephone' => '12', // Too short
        ]);

        // Assert
        $response->assertStatus(422)
                ->assertJsonValidationErrors([
                    'branch_name',
                    'branch_address', 
                    'branch_telephone'
                ]);
    }

    /**
     * Test POST /api/branches - Store with different input formats (legacy compatibility)
     */
    public function test_store_supports_different_input_formats()
    {
        // Test with legacy field names (branch_name, branch_address, branch_telephone)
        $branchData1 = [
            'branch_name' => 'Legacy Format Branch',
            'branch_address' => 'Legacy Address',
            'branch_telephone' => '081111111111'
        ];

        $response1 = $this->postJson(self::API_BASE_URL, $branchData1);
        $response1->assertStatus(201);

        // Test with BranchColumns constants format
        $branchData2 = [
            BranchColumns::NAME => 'Constants Format Branch',
            BranchColumns::ADDRESS => 'Constants Address', 
            BranchColumns::PHONE => '082222222222'
        ];

        $response2 = $this->postJson(self::API_BASE_URL, $branchData2);
        $response2->assertStatus(201);

        // Verify both are in database
        $this->assertDatabaseHas('branches', [BranchColumns::NAME => 'Legacy Format Branch']);
        $this->assertDatabaseHas('branches', [BranchColumns::NAME => 'Constants Format Branch']);
    }

    /**
     * Test API response uses BranchResource/BranchCollection format
     */
    public function test_api_uses_correct_resource_format()
    {
        // Arrange
        $branch = Branch::factory()->create([
            BranchColumns::NAME => 'Resource Test Branch',
            BranchColumns::ADDRESS => 'Resource Test Address',
            BranchColumns::PHONE => '081234567890',
            BranchColumns::IS_ACTIVE => true
        ]);

        // Act - Test index returns BranchCollection
        $indexResponse = $this->getJson(self::API_BASE_URL);

        // Assert - Check BranchCollection structure
        $indexResponse->assertStatus(200)
                     ->assertJsonStructure([
                         'data' => [
                             '*' => [
                                 'id',
                                 'branch_name',
                                 'branch_address', 
                                 'branch_telephone',
                                 'is_active',
                                 'status',
                                 'created_at',
                                 'updated_at'
                             ]
                         ]
                     ]);

        $responseData = $indexResponse->json('data');
        $this->assertCount(1, $responseData);
        $this->assertEquals('Resource Test Branch', $responseData[0]['branch_name']);
        $this->assertTrue($responseData[0]['is_active']);

        // Act - Test store returns BranchResource
        $storeData = [
            'branch_name' => 'Store Resource Test',
            'branch_address' => 'Store Resource Address',
            'branch_telephone' => '089876543210'
        ];

        $storeResponse = $this->postJson(self::API_BASE_URL, $storeData);

        // Assert - Check BranchResource structure
        $storeResponse->assertStatus(201)
                     ->assertJsonStructure([
                         'success',
                         'message',
                         'data' => [
                             'id',
                             'branch_name',
                             'branch_address',
                             'branch_telephone',
                             'is_active'
                         ]
                     ]);
    }

    /**
     * Test API request detection (wantsJson method)
     */
    public function test_api_request_detection()
    {
        // Arrange
        Branch::factory()->create();

        // Act - Test with Accept: application/json header
        $response1 = $this->json('GET', self::API_BASE_URL, [], ['Accept' => 'application/json']);
        $response1->assertStatus(200);

        // Act - Test with /api/* route pattern
        $response2 = $this->getJson(self::API_BASE_URL);
        $response2->assertStatus(200);

        // Both should return JSON API responses
        $response1->assertJsonStructure(['data']);
        $response2->assertJsonStructure(['data']);
    }

    /**
     * Test API error handling for store method
     */
    public function test_store_method_error_handling()
    {
        // Act - Test with invalid JSON structure
        $response = $this->postJson(self::API_BASE_URL, [
            'invalid_field' => 'test'
        ]);

        // Assert - Should return 422 validation error
        $response->assertStatus(422);

        // Act - Test exception handling (if model method throws exception)
        // This would require mocking Branch::addBranch to throw exception
        // For now, just verify normal error responses work
        $response2 = $this->postJson(self::API_BASE_URL, [
            'branch_name' => '', // Will fail validation
        ]);

        $response2->assertStatus(422)
                  ->assertJsonStructure(['errors']);
    }
}
