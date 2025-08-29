<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Branch;
use App\Constants\BranchColumns;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Exception;

class BranchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test data
        Branch::factory()->create([
            BranchColumns::NAME => 'Jakarta Pusat',
            BranchColumns::ADDRESS => 'Jl. Sudirman No. 123',
            BranchColumns::PHONE => '021-12345678',
            BranchColumns::IS_ACTIVE => true
        ]);
        
        Branch::factory()->create([
            BranchColumns::NAME => 'Surabaya Timur',
            BranchColumns::ADDRESS => 'Jl. Pemuda No. 456',
            BranchColumns::PHONE => '031-87654321',
            BranchColumns::IS_ACTIVE => false
        ]);
    }

    /** @test */
    public function it_can_get_all_branches_without_search()
    {
        $branches = Branch::getAllBranch();
        
        $this->assertInstanceOf(LengthAwarePaginator::class, $branches);
        $this->assertEquals(2, $branches->total());
        $this->assertEquals(10, $branches->perPage()); // Default pagination
    }

    /** @test */
    public function it_can_search_branches_by_name()
    {
        $branches = Branch::getAllBranch('Jakarta');
        
        $this->assertEquals(1, $branches->total());
        $this->assertEquals('Jakarta Pusat', $branches->first()->branch_name);
    }

    /** @test */
    public function it_can_search_branches_by_address()
    {
        $branches = Branch::getAllBranch('Pemuda');
        
        $this->assertEquals(1, $branches->total());
        $this->assertEquals('Surabaya Timur', $branches->first()->branch_name);
    }

    /** @test */
    public function it_can_search_branches_by_phone()
    {
        $branches = Branch::getAllBranch('021');
        
        $this->assertEquals(1, $branches->total());
        $this->assertEquals('Jakarta Pusat', $branches->first()->branch_name);
    }

    /** @test */
    public function it_returns_empty_results_for_non_matching_search()
    {
        $branches = Branch::getAllBranch('NonExistent');
        
        $this->assertEquals(0, $branches->total());
    }

    /** @test */
    public function search_is_case_insensitive()
    {
        $branches = Branch::getAllBranch('jakarta');
        
        $this->assertEquals(1, $branches->total());
        $this->assertEquals('Jakarta Pusat', $branches->first()->branch_name);
    }

    /** @test */
    public function it_can_add_new_branch()
    {
        $data = [
            BranchColumns::NAME => 'Bandung Utara',
            BranchColumns::ADDRESS => 'Jl. Asia Afrika No. 789',
            BranchColumns::PHONE => '022-11111111',
            BranchColumns::IS_ACTIVE => true
        ];
        
        $branch = Branch::addBranch($data);
        
        $this->assertInstanceOf(Branch::class, $branch);
        $this->assertEquals('Bandung Utara', $branch->branch_name);
        $this->assertDatabaseHas(config('db_tables.branch'), $data);
    }

    /** @test */
    public function it_can_get_branch_by_id()
    {
        $branch = Branch::first();
        $foundBranch = Branch::getBranchById($branch->id);
        
        $this->assertInstanceOf(Branch::class, $foundBranch);
        $this->assertEquals($branch->id, $foundBranch->id);
        $this->assertEquals($branch->branch_name, $foundBranch->branch_name);
    }

    /** @test */
    public function it_returns_null_for_non_existent_id()
    {
        $branch = Branch::getBranchById(9999);
        
        $this->assertNull($branch);
    }

    /** @test */
    public function it_can_get_random_branch_id()
    {
        $randomId = Branch::getRandomBranchID();
        
        $this->assertIsInt($randomId);
        $this->assertGreaterThan(0, $randomId);
        
        // Verify ID exists in database
        $this->assertDatabaseHas(config('db_tables.branch'), ['id' => $randomId]);
    }

    /** @test */
    public function it_returns_null_for_random_id_when_no_branches()
    {
        Branch::query()->delete();
        
        $randomId = Branch::getRandomBranchID();
        
        $this->assertNull($randomId);
    }

    /** @test */
    public function it_can_update_existing_branch()
    {
        $branch = Branch::first();
        $updateData = [
            BranchColumns::NAME => 'Updated Branch Name',
            BranchColumns::ADDRESS => 'Updated Address'
        ];
        
        $result = Branch::updateBranch($branch->id, $updateData);
        
        $this->assertTrue($result);
        $this->assertDatabaseHas(config('db_tables.branch'), [
            'id' => $branch->id,
            BranchColumns::NAME => 'Updated Branch Name',
            BranchColumns::ADDRESS => 'Updated Address'
        ]);
    }

    /** @test */
    public function it_returns_false_when_updating_non_existent_branch()
    {
        $result = Branch::updateBranch(9999, [BranchColumns::NAME => 'Test']);
        
        $this->assertFalse($result);
    }

    /** @test */
    public function it_can_find_branch_with_exception_handling()
    {
        $branch = Branch::first();
        $foundBranch = Branch::findBranch($branch->id);
        
        $this->assertInstanceOf(Branch::class, $foundBranch);
        $this->assertEquals($branch->id, $foundBranch->id);
    }

    /** @test */
    public function it_throws_exception_when_branch_not_found()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Cabang tidak ditemukan!');
        
        Branch::findBranch(9999);
    }

    /** @test */
    public function it_can_delete_branch()
    {
        $branch = Branch::first();
        $branchId = $branch->id;
        
        $deleted = Branch::deleteBranch($branchId);
        
        $this->assertEquals(1, $deleted); // Number of deleted records
        $this->assertDatabaseMissing(config('db_tables.branch'), ['id' => $branchId]);
    }

    /** @test */
    public function delete_returns_zero_for_non_existent_branch()
    {
        $deleted = Branch::deleteBranch(9999);
        
        $this->assertEquals(0, $deleted);
    }

    /** @test */
    public function it_can_count_all_branches()
    {
        $count = Branch::countBranch();
        
        $this->assertEquals(2, $count);
        $this->assertIsInt($count);
    }

    /** @test */
    public function count_returns_zero_for_empty_table()
    {
        Branch::query()->delete();
        
        $count = Branch::countBranch();
        
        $this->assertEquals(0, $count);
    }

    /** @test */
    public function it_can_count_branches_by_status()
    {
        $counts = Branch::countBranchByStatus();
        
        $this->assertIsArray($counts);
        $this->assertArrayHasKey('aktif', $counts);
        $this->assertArrayHasKey('nonaktif', $counts);
        $this->assertEquals(1, $counts['aktif']);
        $this->assertEquals(1, $counts['nonaktif']);
    }

    /** @test */
    public function status_count_handles_empty_table()
    {
        Branch::query()->delete();
        
        $counts = Branch::countBranchByStatus();
        
        $this->assertEquals(0, $counts['aktif']);
        $this->assertEquals(0, $counts['nonaktif']);
    }

    /** @test */
    public function it_can_get_active_branches_only()
    {
        $activeBranches = Branch::getActiveBranches();
        
        $this->assertCount(1, $activeBranches);
        $this->assertEquals(1, $activeBranches->first()->is_active);
        $this->assertEquals('Jakarta Pusat', $activeBranches->first()->branch_name);
    }

    /** @test */
    public function active_branches_are_sorted_by_name()
    {
        // Create additional active branches
        Branch::factory()->create([
            BranchColumns::NAME => 'Bandung Selatan',
            BranchColumns::IS_ACTIVE => true
        ]);
        
        Branch::factory()->create([
            BranchColumns::NAME => 'Medan Utara', 
            BranchColumns::IS_ACTIVE => true
        ]);
        
        $activeBranches = Branch::getActiveBranches();
        
        $this->assertCount(3, $activeBranches);
        
        // Check sorting by name (ascending)
        $names = $activeBranches->pluck('branch_name')->toArray();
        $sortedNames = $names;
        sort($sortedNames);
        
        $this->assertEquals($sortedNames, $names);
    }

    /** @test */
    public function it_can_check_if_name_exists()
    {
        $exists = Branch::nameExists('Jakarta Pusat');
        $notExists = Branch::nameExists('Non Existent');
        
        $this->assertTrue($exists);
        $this->assertFalse($notExists);
    }

    /** @test */
    public function it_can_check_name_exists_with_exception()
    {
        $branch = Branch::where(BranchColumns::NAME, 'Jakarta Pusat')->first();
        
        // Same name, but except current ID (should return false)
        $exists = Branch::nameExists('Jakarta Pusat', $branch->id);
        
        $this->assertFalse($exists);
    }

    /** @test */
    public function name_exists_handles_case_sensitivity()
    {
        $exists = Branch::nameExists('Jakarta Pusat'); // exact case
        $existsLowercase = Branch::nameExists('jakarta pusat'); // different case
        
        $this->assertTrue($exists); // Should match exact case
        // MySQL is case-insensitive by default, so this will also match
        $this->assertTrue($existsLowercase); // Will match due to MySQL collation
    }

    /** @test */
    public function it_can_search_with_basic_filters()
    {
        $filters = ['search' => 'Jakarta'];
        $query = Branch::searchWithFilters($filters);
        $branches = $query->get();
        
        $this->assertCount(1, $branches);
        $this->assertEquals('Jakarta Pusat', $branches->first()->branch_name);
    }

    /** @test */
    public function it_can_filter_by_active_status()
    {
        $filters = ['status' => 'active'];
        $query = Branch::searchWithFilters($filters);
        $branches = $query->get();
        
        $this->assertCount(1, $branches);
        $this->assertEquals(1, $branches->first()->is_active);
    }

    /** @test */
    public function it_can_filter_by_inactive_status()
    {
        $filters = ['status' => 'inactive'];
        $query = Branch::searchWithFilters($filters);
        $branches = $query->get();
        
        $this->assertCount(1, $branches);
        $this->assertEquals(0, $branches->first()->is_active);
    }

    /** @test */
    public function it_ignores_invalid_status_filter()
    {
        $filters = ['status' => 'invalid_status'];
        $query = Branch::searchWithFilters($filters);
        $branches = $query->get();
        
        // Should return all branches when status is invalid
        $this->assertCount(2, $branches);
    }

    /** @test */
    public function it_can_search_by_specific_name_field()
    {
        $filters = ['name' => 'Jakarta'];
        $query = Branch::searchWithFilters($filters);
        $branches = $query->get();
        
        $this->assertCount(1, $branches);
        $this->assertEquals('Jakarta Pusat', $branches->first()->branch_name);
    }

    /** @test */
    public function it_can_search_by_specific_address_field()
    {
        $filters = ['address' => 'Sudirman'];
        $query = Branch::searchWithFilters($filters);
        $branches = $query->get();
        
        $this->assertCount(1, $branches);
        $this->assertEquals('Jakarta Pusat', $branches->first()->branch_name);
    }

    /** @test */
    public function it_can_search_by_specific_phone_field()
    {
        $filters = ['phone' => '021'];
        $query = Branch::searchWithFilters($filters);
        $branches = $query->get();
        
        $this->assertCount(1, $branches);
        $this->assertEquals('Jakarta Pusat', $branches->first()->branch_name);
    }

    /** @test */
    public function it_can_filter_by_is_active_boolean()
    {
        $filters = ['is_active' => true];
        $query = Branch::searchWithFilters($filters);
        $branches = $query->get();
        
        $this->assertCount(1, $branches);
        $this->assertEquals(1, $branches->first()->is_active);
        
        $filters = ['is_active' => false];
        $query = Branch::searchWithFilters($filters);
        $branches = $query->get();
        
        $this->assertCount(1, $branches);
        $this->assertEquals(0, $branches->first()->is_active);
    }

    /** @test */
    public function it_can_sort_results_by_name()
    {
        $filters = [
            'sort_by' => BranchColumns::NAME,
            'sort_order' => 'asc'
        ];
        
        $query = Branch::searchWithFilters($filters);
        $branches = $query->get();
        
        $this->assertEquals('Jakarta Pusat', $branches->first()->branch_name);
        $this->assertEquals('Surabaya Timur', $branches->last()->branch_name);
    }

    /** @test */
    public function it_can_sort_results_by_created_at_desc()
    {
        $filters = [
            'sort_by' => BranchColumns::CREATED_AT,
            'sort_order' => 'desc'
        ];
        
        $query = Branch::searchWithFilters($filters);
        $sql = $query->toSql();
        
        $this->assertStringContainsString('order by `created_at` desc', $sql);
    }

    /** @test */
    public function it_uses_default_sorting_for_invalid_fields()
    {
        $filters = [
            'sort_by' => 'invalid_field',
            'sort_order' => 'asc'
        ];
        
        $query = Branch::searchWithFilters($filters);
        $branches = $query->get();
        
        // Should return results without order by clause for invalid fields
        $this->assertCount(2, $branches);
    }

    /** @test */
    public function it_combines_multiple_filters()
    {
        $filters = [
            'search' => 'Jakarta',
            'status' => 'active',
            'sort_by' => BranchColumns::NAME,
            'sort_order' => 'asc'
        ];
        
        $query = Branch::searchWithFilters($filters);
        $branches = $query->get();
        
        $this->assertCount(1, $branches);
        $this->assertEquals('Jakarta Pusat', $branches->first()->branch_name);
        $this->assertEquals(1, $branches->first()->is_active);
    }

    /** @test */
    public function it_can_get_active_branches_with_pagination()
    {
        // Create more test data
        Branch::factory()->count(20)->create([BranchColumns::IS_ACTIVE => true]);
        
        $branches = Branch::getActiveBranchesPaginated(15);
        
        $this->assertInstanceOf(LengthAwarePaginator::class, $branches);
        $this->assertEquals(15, $branches->perPage());
        $this->assertEquals(21, $branches->total()); // 20 + 1 from setUp
    }

    /** @test */
    public function active_branches_pagination_uses_custom_per_page()
    {
        Branch::factory()->count(25)->create([BranchColumns::IS_ACTIVE => true]);
        
        $branches = Branch::getActiveBranchesPaginated(5);
        
        $this->assertEquals(5, $branches->perPage());
        $this->assertEquals(26, $branches->total()); // 25 + 1 from setUp
    }

    /** @test */
    public function it_can_get_statistics()
    {
        $stats = Branch::getStatistics();
        
        $this->assertIsArray($stats);
        $this->assertArrayHasKey('total_branches', $stats);
        $this->assertArrayHasKey('active_branches', $stats);
        $this->assertArrayHasKey('inactive_branches', $stats);
        $this->assertArrayHasKey('active_percentage', $stats);
        
        $this->assertEquals(2, $stats['total_branches']);
        $this->assertEquals(1, $stats['active_branches']);
        $this->assertEquals(1, $stats['inactive_branches']);
        $this->assertEquals(50.0, $stats['active_percentage']);
    }

    /** @test */
    public function it_handles_empty_database_statistics()
    {
        Branch::query()->delete();
        
        $stats = Branch::getStatistics();
        
        $this->assertEquals(0, $stats['total_branches']);
        $this->assertEquals(0, $stats['active_branches']);
        $this->assertEquals(0, $stats['inactive_branches']);
        $this->assertEquals(0, $stats['active_percentage']);
    }

    /** @test */
    public function statistics_calculates_percentage_correctly()
    {
        // Add more branches to test percentage calculation
        Branch::factory()->count(8)->create([BranchColumns::IS_ACTIVE => true]); // 9 active total
        Branch::factory()->count(1)->create([BranchColumns::IS_ACTIVE => false]); // 2 inactive total
        
        $stats = Branch::getStatistics();
        
        $this->assertEquals(11, $stats['total_branches']);
        $this->assertEquals(9, $stats['active_branches']);
        $this->assertEquals(2, $stats['inactive_branches']);
        $this->assertEquals(81.82, $stats['active_percentage']); // 9/11 * 100 rounded to 2 decimals
    }

    /** @test */
    public function search_with_filters_returns_query_builder()
    {
        $query = Branch::searchWithFilters([]);
        
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Builder::class, $query);
    }

    /** @test */
    public function it_handles_empty_search_filters()
    {
        $query = Branch::searchWithFilters([]);
        $branches = $query->get();
        
        $this->assertCount(2, $branches); // Should return all branches
    }

    /** @test */
    public function search_with_empty_string_filters()
    {
        $filters = [
            'search' => '',
            'name' => '',
            'address' => '',
            'phone' => ''
        ];
        
        $query = Branch::searchWithFilters($filters);
        $branches = $query->get();
        
        $this->assertCount(2, $branches); // Should return all branches when filters are empty strings
    }
}