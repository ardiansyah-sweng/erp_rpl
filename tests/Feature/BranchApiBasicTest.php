<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class BranchApiBasicTest extends TestCase
{
    use RefreshDatabase;

    private const API_BRANCHES_ENDPOINT = '/api/branches';
    
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a user for authentication
        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user);
    }

    /**
     * Test basic API functionality - Create and retrieve branch
     */
    public function test_basic_branch_api_crud_operations()
    {
        // 1. Test CREATE - POST /api/branches
        $branchData = [
            'branch_name' => 'Cabang Test Jakarta',
            'branch_address' => 'Jl. Test No. 123, Jakarta',
            'branch_phone' => '081234567890',
            'is_active' => true
        ];

        $createResponse = $this->postJson(self::API_BRANCHES_ENDPOINT, $branchData);
        
        $createResponse->assertStatus(201)
                     ->assertJsonStructure([
                         'data' => [
                             'id',
                             'branch_name',
                             'branch_address',
                             'branch_phone',
                             'is_active'
                         ]
                     ]);

        $branchId = $createResponse->json('data.id');

        // 2. Test READ - GET /api/branches/{id}
        $showResponse = $this->getJson(self::API_BRANCHES_ENDPOINT . '/' . $branchId);
        
        $showResponse->assertStatus(200)
                    ->assertJsonFragment([
                        'branch_name' => 'Cabang Test Jakarta'
                    ]);

        // 3. Test UPDATE - PUT /api/branches/{id}
        $updateData = [
            'branch_name' => 'Cabang Test Jakarta Updated',
            'branch_address' => 'Jl. Updated No. 456, Jakarta',
            'branch_phone' => '089876543210',
            'is_active' => false
        ];

        $updateResponse = $this->putJson(self::API_BRANCHES_ENDPOINT . '/' . $branchId, $updateData);
        
        $updateResponse->assertStatus(200)
                      ->assertJsonFragment([
                          'branch_name' => 'Cabang Test Jakarta Updated',
                          'is_active' => false
                      ]);

        // 4. Test DELETE - DELETE /api/branches/{id}
        $deleteResponse = $this->deleteJson(self::API_BRANCHES_ENDPOINT . '/' . $branchId);
        
        $deleteResponse->assertStatus(204);

        // 5. Verify deletion
        $verifyResponse = $this->getJson(self::API_BRANCHES_ENDPOINT . '/' . $branchId);
        $verifyResponse->assertStatus(404);
    }

    /**
     * Test API list functionality
     */
    public function test_can_list_all_branches_via_api()
    {
        // Create test branches
        Branch::factory()->create(['branch_name' => 'Branch A', 'is_active' => true]);
        Branch::factory()->create(['branch_name' => 'Branch B', 'is_active' => true]);
        Branch::factory()->create(['branch_name' => 'Branch C', 'is_active' => false]);

        $response = $this->getJson(self::API_BRANCHES_ENDPOINT);

        $response->assertStatus(200)
                ->assertJsonCount(3, 'data');
    }

    /**
     * Test API validation
     */
    public function test_api_validation_works()
    {
        // Test with empty data
        $response = $this->postJson(self::API_BRANCHES_ENDPOINT, []);
        
        $response->assertStatus(422)
                ->assertJsonValidationErrors(['branch_name']);

        // Test with invalid data
        $response = $this->postJson(self::API_BRANCHES_ENDPOINT, [
            'branch_name' => '',
            'branch_phone' => str_repeat('x', 300), // Too long
            'is_active' => 'not_boolean'
        ]);
        
        $response->assertStatus(422)
                ->assertJsonValidationErrors(['branch_name', 'branch_phone', 'is_active']);
    }

    /**
     * Test API special endpoints
     */
    public function test_api_special_endpoints()
    {
        // Create test data
        Branch::factory()->create(['is_active' => true]);
        Branch::factory()->create(['is_active' => true]);
        Branch::factory()->create(['is_active' => false]);

        // Test active branches endpoint
        $activeResponse = $this->getJson(self::API_BRANCHES_ENDPOINT . '/active');
        $activeResponse->assertStatus(200);
        $this->assertCount(2, $activeResponse->json('data'));

        // Test statistics endpoint
        $statsResponse = $this->getJson(self::API_BRANCHES_ENDPOINT . '/statistics');
        $statsResponse->assertStatus(200)
                     ->assertJsonStructure([
                         'data' => [
                             'total_branches',
                             'active_branches', 
                             'inactive_branches',
                             'activation_rate'
                         ]
                     ]);

        // Test search endpoint
        Branch::factory()->create(['branch_name' => 'Jakarta Pusat']);
        Branch::factory()->create(['branch_name' => 'Bandung Utara']);

        $searchResponse = $this->getJson(self::API_BRANCHES_ENDPOINT . '/search?q=Jakarta');
        $searchResponse->assertStatus(200);
        
        $branches = $searchResponse->json('data');
        $this->assertGreaterThan(0, count($branches));
    }

    /**
     * Test API returns correct JSON structure
     */
    public function test_api_returns_correct_json_structure()
    {
        $branch = Branch::factory()->create([
            'branch_name' => 'Test Structure Branch',
            'branch_address' => 'Test Address',
            'branch_phone' => '081234567890',
            'is_active' => true
        ]);

        $response = $this->getJson(self::API_BRANCHES_ENDPOINT . '/' . $branch->id);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        'id',
                        'branch_name',
                        'branch_address', 
                        'branch_phone',
                        'is_active',
                        'created_at',
                        'updated_at'
                    ]
                ])
                ->assertJson([
                    'data' => [
                        'id' => $branch->id,
                        'branch_name' => 'Test Structure Branch',
                        'is_active' => true
                    ]
                ]);
    }
}
