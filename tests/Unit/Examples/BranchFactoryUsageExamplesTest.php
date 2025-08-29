<?php

namespace Tests\Unit\Examples;

use Tests\TestCase;
use App\Models\Branch;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Demo penggunaan Branch Factory untuk berbagai scenario testing
 */
class BranchFactoryUsageExamplesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
    }

    /**
     * Example 1: Basic factory usage
     */
    public function test_basic_factory_usage_examples()
    {
        // 1. Create single branch
        $branch = Branch::factory()->create();
        $this->assertNotNull($branch->id);

        // 2. Create multiple branches
        $branches = Branch::factory()->count(3)->create();
        $this->assertCount(3, $branches);

        // 3. Create branch without saving to database (make vs create)
        $unsavedBranch = Branch::factory()->make();
        $this->assertNull($unsavedBranch->id);
    }

    /**
     * Example 2: Using states
     */
    public function test_state_usage_examples()
    {
        // 1. Create active branch
        $activeBranch = Branch::factory()->active()->create();
        $this->assertTrue($activeBranch->is_active);

        // 2. Create inactive branch
        $inactiveBranch = Branch::factory()->inactive()->create();
        $this->assertFalse($inactiveBranch->is_active);

        // 3. Create Jakarta branch
        $jakartaBranch = Branch::factory()->jakarta()->create();
        $this->assertStringContainsString('Jakarta', $jakartaBranch->branch_name);

        // 4. Create branch in specific city
        $surabayaBranch = Branch::factory()->inCity('Surabaya')->create();
        $this->assertStringContainsString('Surabaya', $surabayaBranch->branch_name);
    }

    /**
     * Example 3: Override specific attributes
     */
    public function test_attribute_override_examples()
    {
        // 1. Override single attribute
        $branch = Branch::factory()->create([
            'branch_name' => 'My Custom Branch Name'
        ]);
        $this->assertEquals('My Custom Branch Name', $branch->branch_name);

        // 2. Override multiple attributes
        $customBranch = Branch::factory()->create([
            'branch_name' => 'Cabang Khusus',
            'branch_address' => 'Alamat Khusus 123',
            'is_active' => false
        ]);
        $this->assertEquals('Cabang Khusus', $customBranch->branch_name);
        $this->assertEquals('Alamat Khusus 123', $customBranch->branch_address);
        $this->assertFalse($customBranch->is_active);
    }

    /**
     * Example 4: Combining states with overrides
     */
    public function test_combining_states_and_overrides()
    {
        // Combine state with attribute override
        $branch = Branch::factory()
            ->jakarta()
            ->active()
            ->create([
                'branch_name' => 'Cabang Jakarta Khusus'
            ]);

        $this->assertEquals('Cabang Jakarta Khusus', $branch->branch_name);
        $this->assertTrue($branch->is_active);
        $this->assertStringStartsWith('021-', $branch->branch_telephone);
    }

    /**
     * Example 5: Testing scenario setups
     */
    public function test_testing_scenario_examples()
    {
        // Scenario 1: Mixed active/inactive branches
        $activeBranches = Branch::factory()->count(3)->active()->create();
        $inactiveBranches = Branch::factory()->count(2)->inactive()->create();
        
        $this->assertEquals(3, Branch::where('is_active', true)->count());
        $this->assertEquals(2, Branch::where('is_active', false)->count());

        // Scenario 2: Branches in different cities
        $jakartaBranches = Branch::factory()->count(2)->jakarta()->create();
        $surabayaBranches = Branch::factory()->count(2)->inCity('Surabaya')->create();
        
        $this->assertEquals(2, Branch::where('branch_name', 'LIKE', '%Jakarta%')->count());
        $this->assertEquals(2, Branch::where('branch_name', 'LIKE', '%Surabaya%')->count());

        // Scenario 3: Edge case data
        $minimalBranch = Branch::factory()->minimal()->create();
        $maxLengthBranch = Branch::factory()->maxLength()->create();
        
        $this->assertEquals('Test Branch', $minimalBranch->branch_name);
        $this->assertEquals(50, strlen($maxLengthBranch->branch_name));
    }

    /**
     * Example 6: Collection operations with factory
     */
    public function test_collection_operations_examples()
    {
        // Create and filter
        $branches = Branch::factory()->count(10)->create();
        $activeBranches = $branches->where('is_active', true);
        $jakartaBranches = $branches->filter(function ($branch) {
            return strpos($branch->branch_name, 'Jakarta') !== false;
        });

        $this->assertLessThanOrEqual(10, $activeBranches->count());
        $this->assertGreaterThanOrEqual(0, $jakartaBranches->count());
    }

    /**
     * Example 7: Factory for API testing scenarios
     */
    public function test_api_testing_scenarios()
    {
        // Setup for API list endpoint
        $allBranches = Branch::factory()->count(5)->create();
        $activeBranches = Branch::factory()->count(3)->active()->create();
        $inactiveBranches = Branch::factory()->count(2)->inactive()->create();

        // Assertions for API responses
        $this->assertEquals(10, Branch::count()); // Total branches
        $this->assertEquals(8, Branch::where('is_active', true)->count()); // 5 random + 3 active
        $this->assertEquals(2, Branch::where('is_active', false)->count()); // 2 inactive

        // Setup for search functionality
        $searchableBranch = Branch::factory()->create([
            'branch_name' => 'Searchable Branch Name',
            'branch_address' => 'Searchable Address'
        ]);

        $searchResults = Branch::where('branch_name', 'LIKE', '%Searchable%')->get();
        $this->assertCount(1, $searchResults);
    }

    /**
     * Example 8: Performance testing setup
     */
    public function test_performance_testing_setup()
    {
        // Create large dataset for performance testing
        $startTime = microtime(true);
        
        Branch::factory()->count(100)->create();
        
        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        $this->assertEquals(100, Branch::count());
        $this->assertLessThan(5, $executionTime, 'Factory should create 100 records in less than 5 seconds');
    }
}
