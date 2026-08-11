<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Branch;
use App\Constants\BranchColumns;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use PHPUnit\Framework\Attributes\Test;

class BranchAdvancedTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_get_random_branch_id()
    {
        // Arrange
        $branches = Branch::factory()->count(5)->create();
        $branchIds = $branches->pluck('id')->toArray();

        // Act
        $randomId = Branch::getRandomBranchID();

        // Assert
        $this->assertContains($randomId, $branchIds);
        $this->assertIsInt($randomId);
    }

    #[Test]
    public function it_returns_paginated_results_for_get_all_branch()
    {
        // Arrange
        Branch::factory()->count(15)->create();

        // Act
        $result = Branch::getAllBranch();

        // Assert
        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertEquals(10, $result->perPage()); // Default pagination is 10
        $this->assertEquals(15, $result->total());
        $this->assertEquals(2, $result->lastPage());
    }

    #[Test]
    public function it_can_search_branches_by_address()
    {
        // Arrange
        Branch::factory()->create([
            BranchColumns::NAME => 'Cabang A',
            BranchColumns::ADDRESS => 'Jl. Sudirman Jakarta'
        ]);
        Branch::factory()->create([
            BranchColumns::NAME => 'Cabang B', 
            BranchColumns::ADDRESS => 'Jl. Thamrin Jakarta'
        ]);
        Branch::factory()->create([
            BranchColumns::NAME => 'Cabang C',
            BranchColumns::ADDRESS => 'Jl. Malioboro Yogyakarta'
        ]);

        // Act
        $results = Branch::getAllBranch('Jakarta');

        // Assert
        $this->assertEquals(2, $results->count());
        foreach ($results as $branch) {
            $this->assertStringContainsString('Jakarta', $branch->{BranchColumns::ADDRESS});
        }
    }

    #[Test]
    public function it_can_search_branches_by_phone()
    {
        // Arrange
        Branch::factory()->create([
            BranchColumns::NAME => 'Cabang A',
            BranchColumns::PHONE => '021-12345678'
        ]);
        Branch::factory()->create([
            BranchColumns::NAME => 'Cabang B',
            BranchColumns::PHONE => '022-87654321'
        ]);
        Branch::factory()->create([
            BranchColumns::NAME => 'Cabang C',
            BranchColumns::PHONE => '021-11111111'
        ]);

        // Act
        $results = Branch::getAllBranch('021');

        // Assert
        $this->assertEquals(2, $results->count());
        foreach ($results as $branch) {
            $this->assertStringContainsString('021', $branch->{BranchColumns::PHONE});
        }
    }

    #[Test]
    public function it_returns_all_branches_when_no_search_term_provided()
    {
        // Arrange
        Branch::factory()->count(5)->create();

        // Act
        $results = Branch::getAllBranch();

        // Assert
        $this->assertEquals(5, $results->count());
    }

    #[Test]
    public function it_returns_empty_result_when_search_term_not_found()
    {
        // Arrange
        Branch::factory()->count(3)->create([
            BranchColumns::NAME => 'Cabang Jakarta'
        ]);

        // Act
        $results = Branch::getAllBranch('Bandung');

        // Assert
        $this->assertEquals(0, $results->count());
    }

    #[Test]
    public function it_orders_results_by_created_at_ascending()
    {
        // Arrange - Create branches with specific timestamps
        $firstBranch = Branch::factory()->create([
            BranchColumns::NAME => 'First Branch',
            BranchColumns::CREATED_AT => now()->subDays(3)
        ]);
        
        $secondBranch = Branch::factory()->create([
            BranchColumns::NAME => 'Second Branch', 
            BranchColumns::CREATED_AT => now()->subDays(1)
        ]);
        
        $thirdBranch = Branch::factory()->create([
            BranchColumns::NAME => 'Third Branch',
            BranchColumns::CREATED_AT => now()
        ]);

        // Act
        $results = Branch::getAllBranch();

        // Assert - Should be ordered by created_at ASC
        $this->assertEquals('First Branch', $results->first()->{BranchColumns::NAME});
        $this->assertEquals('Third Branch', $results->last()->{BranchColumns::NAME});
    }

    #[Test]
    public function it_can_get_branch_by_id_using_instance_method()
    {
        // Arrange
        $branch = Branch::factory()->create([
            BranchColumns::NAME => 'Test Branch'
        ]);
        $branchInstance = new Branch();

        // Act
        $foundBranch = $branchInstance->getBranchById($branch->id);

        // Assert
        $this->assertInstanceOf(Branch::class, $foundBranch);
        $this->assertEquals($branch->id, $foundBranch->id);
        $this->assertEquals('Test Branch', $foundBranch->{BranchColumns::NAME});
    }

    #[Test]
    public function it_returns_null_when_branch_not_found_by_id()
    {
        // Arrange
        $branchInstance = new Branch();
        $nonExistentId = 99999;

        // Act
        $result = $branchInstance->getBranchById($nonExistentId);

        // Assert
        $this->assertNull($result);
    }

    #[Test]
    public function it_can_count_branches_with_mixed_status()
    {
        // Arrange
        Branch::factory()->count(4)->active()->create();
        Branch::factory()->count(3)->inactive()->create();
        Branch::factory()->count(2)->create([BranchColumns::IS_ACTIVE => 1]);

        // Act
        $statusCount = Branch::countBranchByStatus();

        // Assert
        $this->assertEquals(6, $statusCount['aktif']); // 4 + 2
        $this->assertEquals(3, $statusCount['nonaktif']);
    }

    #[Test]
    public function it_returns_zero_counts_when_no_branches_exist()
    {
        // Arrange - Empty table
        Branch::query()->delete();

        // Act
        $totalCount = Branch::countBranch();
        $statusCount = Branch::countBranchByStatus();

        // Assert
        $this->assertEquals(0, $totalCount);
        $this->assertEquals(0, $statusCount['aktif']);
        $this->assertEquals(0, $statusCount['nonaktif']);
    }

    #[Test]
    public function it_uses_correct_table_name_from_config()
    {
        // Arrange
        $branch = new Branch();

        // Act & Assert
        $this->assertEquals(config('db_tables.branch'), $branch->getTable());
    }

    #[Test]
    public function it_has_correct_fillable_attributes_from_constants()
    {
        // Arrange
        $branch = new Branch();
        $expectedFillable = BranchColumns::getFillable();

        // Act
        $actualFillable = $branch->getFillable();

        // Assert
        $this->assertEquals($expectedFillable, $actualFillable);
        $this->assertContains(BranchColumns::NAME, $actualFillable);
        $this->assertContains(BranchColumns::ADDRESS, $actualFillable);
        $this->assertContains(BranchColumns::PHONE, $actualFillable);
        $this->assertContains(BranchColumns::IS_ACTIVE, $actualFillable);
    }

    #[Test]
    public function it_can_create_branch_with_inactive_status()
    {
        // Arrange
        $branchData = [
            BranchColumns::NAME => 'Inactive Branch',
            BranchColumns::ADDRESS => 'Test Address',
            BranchColumns::PHONE => '021-12345678',
            BranchColumns::IS_ACTIVE => 0,
        ];

        // Act
        $branch = Branch::addBranch($branchData);

        // Assert
        $this->assertInstanceOf(Branch::class, $branch);
        $this->assertEquals(0, $branch->{BranchColumns::IS_ACTIVE});
        $this->assertDatabaseHas('branches', [
            BranchColumns::NAME => 'Inactive Branch',
            BranchColumns::IS_ACTIVE => 0
        ]);
    }

    #[Test]
    public function it_can_handle_case_insensitive_search()
    {
        // Arrange
        Branch::factory()->create([
            BranchColumns::NAME => 'Cabang JAKARTA Pusat'
        ]);

        // Act
        $results = Branch::getAllBranch('jakarta');

        // Assert
        $this->assertEquals(1, $results->count());
        $this->assertStringContainsString('JAKARTA', $results->first()->{BranchColumns::NAME});
    }

    #[Test]
    public function it_can_search_with_partial_matches()
    {
        // Arrange
        Branch::factory()->create([
            BranchColumns::NAME => 'Cabang Jakarta Selatan'
        ]);
        Branch::factory()->create([
            BranchColumns::NAME => 'Cabang Jakarta Utara'
        ]);
        Branch::factory()->create([
            BranchColumns::NAME => 'Cabang Bandung'
        ]);

        // Act
        $results = Branch::getAllBranch('Jak');

        // Assert
        $this->assertEquals(2, $results->count());
        foreach ($results as $branch) {
            $this->assertStringContainsString('Jakarta', $branch->{BranchColumns::NAME});
        }
    }

    #[Test]
    public function it_can_delete_multiple_branches()
    {
        // Arrange
        $branch1 = Branch::factory()->create();
        $branch2 = Branch::factory()->create();
        $branch3 = Branch::factory()->create();

        // Act
        $deleted1 = Branch::deleteBranch($branch1->id);
        $deleted2 = Branch::deleteBranch($branch2->id);

        // Assert
        $this->assertEquals(1, $deleted1);
        $this->assertEquals(1, $deleted2);
        $this->assertDatabaseMissing('branches', ['id' => $branch1->id]);
        $this->assertDatabaseMissing('branches', ['id' => $branch2->id]);
        $this->assertDatabaseHas('branches', ['id' => $branch3->id]); // Should still exist
    }

    #[Test]
    public function it_returns_zero_when_deleting_nonexistent_branch()
    {
        // Arrange
        $nonExistentId = 99999;

        // Act
        $result = Branch::deleteBranch($nonExistentId);

        // Assert
        $this->assertEquals(0, $result); // No rows affected
    }
}