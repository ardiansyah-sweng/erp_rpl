<?php

namespace Tests\Unit\Factories;

use Tests\TestCase;
use App\Models\Branch;
use App\Constants\BranchColumns;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BranchFactoryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
    }

    /**
     * Test factory creates branch with valid data
     */
    public function test_factory_creates_valid_branch()
    {
        $branch = Branch::factory()->create();

        $this->assertNotNull($branch->id);
        $this->assertNotEmpty($branch->{BranchColumns::NAME});
        $this->assertNotEmpty($branch->{BranchColumns::ADDRESS});
        $this->assertNotEmpty($branch->{BranchColumns::PHONE});
        $this->assertIsBool($branch->{BranchColumns::IS_ACTIVE});
    }

    /**
     * Test factory can create multiple branches
     */
    public function test_factory_creates_multiple_branches()
    {
        $branches = Branch::factory()->count(5)->create();

        $this->assertCount(5, $branches);
        $this->assertEquals(5, Branch::count());

        // Check all have unique names
        $names = $branches->pluck(BranchColumns::NAME)->toArray();
        $this->assertEquals(count($names), count(array_unique($names)));
    }

    /**
     * Test active state method
     */
    public function test_active_state_creates_active_branch()
    {
        $branch = Branch::factory()->active()->create();

        $this->assertTrue($branch->{BranchColumns::IS_ACTIVE});
    }

    /**
     * Test inactive state method
     */
    public function test_inactive_state_creates_inactive_branch()
    {
        $branch = Branch::factory()->inactive()->create();

        $this->assertFalse($branch->{BranchColumns::IS_ACTIVE});
    }

    /**
     * Test Jakarta state method
     */
    public function test_jakarta_state_creates_jakarta_branch()
    {
        $branch = Branch::factory()->jakarta()->create();

        $this->assertStringContainsString('Jakarta', $branch->{BranchColumns::NAME});
        $this->assertStringContainsString('Jakarta', $branch->{BranchColumns::ADDRESS});
        $this->assertStringStartsWith('021-', $branch->{BranchColumns::PHONE});
    }

    /**
     * Test inCity state method
     */
    public function test_in_city_state_creates_city_specific_branch()
    {
        $city = 'Surabaya';
        $branch = Branch::factory()->inCity($city)->create();

        $this->assertStringContainsString($city, $branch->{BranchColumns::NAME});
        $this->assertStringContainsString($city, $branch->{BranchColumns::ADDRESS});
    }

    /**
     * Test minimal state method
     */
    public function test_minimal_state_creates_minimal_data()
    {
        $branch = Branch::factory()->minimal()->create();

        $this->assertEquals('Test Branch', $branch->{BranchColumns::NAME});
        $this->assertEquals('Test Address 123', $branch->{BranchColumns::ADDRESS});
        $this->assertEquals('021-12345678', $branch->{BranchColumns::PHONE});
    }

    /**
     * Test maxLength state method
     */
    public function test_max_length_state_creates_maximum_length_data()
    {
        $branch = Branch::factory()->maxLength()->create();

        $this->assertEquals(50, strlen($branch->{BranchColumns::NAME}));
        $this->assertEquals(100, strlen($branch->{BranchColumns::ADDRESS}));
        $this->assertEquals(30, strlen($branch->{BranchColumns::PHONE}));
    }

    /**
     * Test factory generates Indonesian phone numbers
     */
    public function test_factory_generates_indonesian_phone_numbers()
    {
        $branches = Branch::factory()->count(10)->create();

        foreach ($branches as $branch) {
            // Check phone format (area_code-number)
            $phone = $branch->{BranchColumns::PHONE};
            $this->assertMatchesRegularExpression('/^\d{3,4}-\d{8}$/', $phone);
        }
    }

    /**
     * Test factory creates realistic Indonesian branch names
     */
    public function test_factory_creates_realistic_branch_names()
    {
        $branches = Branch::factory()->count(10)->create();

        foreach ($branches as $branch) {
            $name = $branch->{BranchColumns::NAME};
            $this->assertStringStartsWith('Cabang ', $name);
            
            // Should contain Indonesian city and direction
            $containsValidParts = false;
            $cities = ['Jakarta', 'Surabaya', 'Bandung', 'Medan', 'Semarang', 'Makassar', 'Palembang'];
            $directions = ['Pusat', 'Utara', 'Selatan', 'Timur', 'Barat'];
            
            foreach ($cities as $city) {
                if (strpos($name, $city) !== false) {
                    $containsValidParts = true;
                    break;
                }
            }
            
            $this->assertTrue($containsValidParts, "Branch name should contain Indonesian city: $name");
        }
    }

    /**
     * Test factory respects validation rules
     */
    public function test_factory_respects_validation_rules()
    {
        $branch = Branch::factory()->create();

        // Test name length (3-50 chars)
        $nameLength = strlen($branch->{BranchColumns::NAME});
        $this->assertGreaterThanOrEqual(3, $nameLength);
        $this->assertLessThanOrEqual(50, $nameLength);

        // Test address length (3-100 chars)
        $addressLength = strlen($branch->{BranchColumns::ADDRESS});
        $this->assertGreaterThanOrEqual(3, $addressLength);
        $this->assertLessThanOrEqual(100, $addressLength);

        // Test phone length (3-30 chars)
        $phoneLength = strlen($branch->{BranchColumns::PHONE});
        $this->assertGreaterThanOrEqual(3, $phoneLength);
        $this->assertLessThanOrEqual(30, $phoneLength);
    }

    /**
     * Test factory can create branches for edge case testing
     */
    public function test_factory_supports_edge_case_testing()
    {
        // Test boundary values
        $branches = collect([
            Branch::factory()->minimal()->create(),
            Branch::factory()->maxLength()->create(),
            Branch::factory()->active()->create(),
            Branch::factory()->inactive()->create(),
        ]);

        $this->assertCount(4, $branches);
        $this->assertEquals(4, Branch::count());
    }
}
