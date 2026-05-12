<?php

namespace Tests\Unit\Resources;

use Tests\TestCase;
use App\Models\Branch;
use App\Http\Resources\BranchResource;
use App\Http\Resources\BranchCollection;
use App\Constants\BranchColumns;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;

class BranchResourceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
    }

    /**
     * Test BranchResource transforms model correctly
     */
    public function test_branch_resource_transforms_model_correctly()
    {
        $branch = Branch::factory()->create([
            BranchColumns::NAME => 'Test Branch Jakarta',
            BranchColumns::ADDRESS => 'Jl. Test No. 123, Jakarta',
            BranchColumns::PHONE => '021-12345678',
            BranchColumns::IS_ACTIVE => true,
        ]);

        $resource = new BranchResource($branch);
        $array = $resource->toArray(new Request());

        // Test basic fields
        $this->assertEquals($branch->id, $array['id']);
        $this->assertEquals('Test Branch Jakarta', $array['branch_name']);
        $this->assertEquals('Jl. Test No. 123, Jakarta', $array['branch_address']);
        $this->assertEquals('021-12345678', $array['branch_telephone']);
        $this->assertTrue($array['is_active']);
        $this->assertEquals('Aktif', $array['status']);
    }

    /**
     * Test BranchResource computed fields
     */
    public function test_branch_resource_computed_fields()
    {
        $activeBranch = Branch::factory()->active()->create([
            BranchColumns::NAME => 'Active Branch',
        ]);

        $inactiveBranch = Branch::factory()->inactive()->create([
            BranchColumns::NAME => 'Inactive Branch',
        ]);

        // Test active branch
        $activeResource = new BranchResource($activeBranch);
        $activeArray = $activeResource->toArray(new Request());

        $this->assertEquals('✅ Active Branch', $activeArray['display_name']);
        $this->assertEquals('Aktif', $activeArray['status']);
        $this->assertEquals('success', $activeArray['status_badge']['color']);
        $this->assertEquals('check-circle', $activeArray['status_badge']['icon']);

        // Test inactive branch
        $inactiveResource = new BranchResource($inactiveBranch);
        $inactiveArray = $inactiveResource->toArray(new Request());

        $this->assertEquals('❌ Inactive Branch', $inactiveArray['display_name']);
        $this->assertEquals('Tidak Aktif', $inactiveArray['status']);
        $this->assertEquals('danger', $inactiveArray['status_badge']['color']);
        $this->assertEquals('x-circle', $inactiveArray['status_badge']['icon']);
    }

    /**
     * Test BranchResource formatted fields
     */
    public function test_branch_resource_formatted_fields()
    {
        $branch = Branch::factory()->create([
            BranchColumns::ADDRESS => 'This is a very long address that should be shortened when displayed in lists because it exceeds fifty characters',
            BranchColumns::PHONE => '02112345678', // Unformatted phone
        ]);

        $resource = new BranchResource($branch);
        $array = $resource->toArray(new Request());

        // Test short address
        $this->assertLessThanOrEqual(50, strlen($array['short_address']));
        $this->assertStringEndsWith('...', $array['short_address']);

        // Test formatted phone
        $this->assertEquals('021-12345678', $array['formatted_phone']);
    }

    /**
     * Test BranchResource conditional fields
     */
    public function test_branch_resource_conditional_fields()
    {
        $branch = Branch::factory()->create();

        // Request without details
        $request = new Request();
        $resource = new BranchResource($branch);
        $array = $resource->toArray($request);

        $this->assertArrayNotHasKey('detailed_info', $array);

        // Request with details
        $requestWithDetails = new Request(['include_details' => 'true']);
        $arrayWithDetails = $resource->toArray($requestWithDetails);

        $this->assertArrayHasKey('detailed_info', $arrayWithDetails);
        $this->assertArrayHasKey('created_by', $arrayWithDetails['detailed_info']);
        $this->assertArrayHasKey('age_in_days', $arrayWithDetails['detailed_info']);
    }

    /**
     * Test BranchResource human readable timestamps
     */
    public function test_branch_resource_human_readable_timestamps()
    {
        $branch = Branch::factory()->create();

        $resource = new BranchResource($branch);
        $array = $resource->toArray(new Request());

        $this->assertNotNull($array['created_at']);
        $this->assertNotNull($array['updated_at']);
        $this->assertNotNull($array['created_at_human']);
        $this->assertNotNull($array['updated_at_human']);

        // Test human readable format
        $this->assertStringContainsString('ago', $array['created_at_human']);
    }

    /**
     * Test BranchResource with method
     */
    public function test_branch_resource_with_method()
    {
        $branch = Branch::factory()->create();
        $resource = new BranchResource($branch);

        $request = new Request();
        $with = $resource->with($request);

        $this->assertArrayHasKey('links', $with);
        $this->assertArrayHasKey('self', $with['links']);
        $this->assertArrayHasKey('edit', $with['links']);
        $this->assertArrayHasKey('delete', $with['links']);
    }
}

class BranchCollectionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
    }

    /**
     * Test BranchCollection structure
     */
    public function test_branch_collection_structure()
    {
        $branches = Branch::factory()->count(5)->create();
        $collection = new BranchCollection($branches);
        $array = $collection->toArray(new Request());

        $this->assertArrayHasKey('data', $array);
        $this->assertArrayHasKey('meta', $array);
        $this->assertArrayHasKey('summary', $array);
        $this->assertCount(5, $array['data']);
    }

    /**
     * Test BranchCollection meta information
     */
    public function test_branch_collection_meta_information()
    {
        // Create mixed branches
        Branch::factory()->count(3)->active()->create();
        Branch::factory()->count(2)->inactive()->create();

        $branches = Branch::all();
        $collection = new BranchCollection($branches);
        $array = $collection->toArray(new Request());

        $meta = $array['meta'];

        $this->assertEquals(5, $meta['total']);
        $this->assertEquals(3, $meta['active_count']);
        $this->assertEquals(2, $meta['inactive_count']);
        $this->assertEquals(60.0, $meta['percentage_active']); // 3/5 * 100
    }

    /**
     * Test BranchCollection summary statistics
     */
    public function test_branch_collection_summary_statistics()
    {
        // Create branches in different cities
        Branch::factory()->jakarta()->count(2)->create();
        Branch::factory()->inCity('Surabaya')->count(1)->create();

        $branches = Branch::all();
        $collection = new BranchCollection($branches);
        $array = $collection->toArray(new Request());

        $summary = $array['summary'];

        $this->assertArrayHasKey('status_distribution', $summary);
        $this->assertArrayHasKey('cities', $summary);
        $this->assertArrayHasKey('latest_branch', $summary);

        // Test status distribution
        $this->assertArrayHasKey('active', $summary['status_distribution']);
        $this->assertArrayHasKey('inactive', $summary['status_distribution']);

        // Test cities (should have Jakarta and Surabaya)
        $cities = collect($summary['cities'])->pluck('city');
        $this->assertContains('Jakarta', $cities);
        $this->assertContains('Surabaya', $cities);
    }

    /**
     * Test BranchCollection with empty collection
     */
    public function test_branch_collection_with_empty_collection()
    {
        $collection = new BranchCollection(collect([]));
        $array = $collection->toArray(new Request());

        $this->assertCount(0, $array['data']);
        $this->assertEquals(0, $array['meta']['total']);
        $this->assertEquals(0, $array['meta']['active_count']);
        $this->assertEquals(0, $array['meta']['inactive_count']);
        $this->assertEquals(0, $array['meta']['percentage_active']);
        $this->assertNull($array['summary']['latest_branch']);
    }

    /**
     * Test BranchCollection with method
     */
    public function test_branch_collection_with_method()
    {
        $branches = Branch::factory()->count(3)->create();
        $collection = new BranchCollection($branches);

        $request = new Request();
        $with = $collection->with($request);

        $this->assertArrayHasKey('links', $with);
        $this->assertArrayHasKey('filters', $with);
        $this->assertArrayHasKey('available', $with['filters']);
    }

    /**
     * Test BranchCollection city distribution
     */
    public function test_branch_collection_city_distribution()
    {
        // Create specific pattern branches
        Branch::factory()->create(['branch_name' => 'Cabang Jakarta Pusat']);
        Branch::factory()->create(['branch_name' => 'Cabang Jakarta Utara']);
        Branch::factory()->create(['branch_name' => 'Cabang Surabaya Selatan']);

        $branches = Branch::all();
        $collection = new BranchCollection($branches);
        $array = $collection->toArray(new Request());

        $cities = $array['summary']['cities'];
        
        // Should have Jakarta (2) and Surabaya (1)
        $jakartaCity = collect($cities)->firstWhere('city', 'Jakarta');
        $surabayaCity = collect($cities)->firstWhere('city', 'Surabaya');

        $this->assertNotNull($jakartaCity);
        $this->assertNotNull($surabayaCity);
        $this->assertEquals(2, $jakartaCity['count']);
        $this->assertEquals(1, $surabayaCity['count']);
    }
}
