<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Branch;
use App\Constants\BranchColumns;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Collection;

class BranchTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_branch_with_valid_data()
    {
        // Arrange
        $branchData = [
            BranchColumns::NAME => 'Cabang Jakarta Pusat',
            BranchColumns::ADDRESS => 'Jl. Sudirman No. 123, Jakarta',
            BranchColumns::PHONE => '021-12345678',
            BranchColumns::IS_ACTIVE => 1,
        ];

        // Act
        $branch = Branch::addBranch($branchData);

        // Assert
        $this->assertInstanceOf(Branch::class, $branch);
        $this->assertDatabaseHas('branches', [
            BranchColumns::NAME => 'Cabang Jakarta Pusat',
            BranchColumns::ADDRESS => 'Jl. Sudirman No. 123, Jakarta',
            BranchColumns::PHONE => '021-12345678',
            BranchColumns::IS_ACTIVE => 1,
        ]);
    }

    /** @test */
    public function it_has_correct_fillable_attributes()
    {
        // Arrange & Act
        $branch = new Branch();
        $expectedFillable = [
            BranchColumns::NAME,
            BranchColumns::ADDRESS,
            BranchColumns::PHONE,
            BranchColumns::IS_ACTIVE,
        ];

        // Assert
        $this->assertEquals($expectedFillable, $branch->getFillable());
    }

    /** @test */
    public function it_can_update_branch_information()
    {
        // Arrange
        $branch = Branch::factory()->create([
            BranchColumns::NAME => 'Cabang Lama',
            BranchColumns::ADDRESS => 'Alamat Lama',
        ]);

        $updateData = [
            BranchColumns::NAME => 'Cabang Baru',
            BranchColumns::ADDRESS => 'Alamat Baru',
        ];

        // Act
        $result = Branch::updateBranch($branch->id, $updateData);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseHas('branches', [
            'id' => $branch->id,
            BranchColumns::NAME => 'Cabang Baru',
            BranchColumns::ADDRESS => 'Alamat Baru',
        ]);
    }

    /** @test */
    public function it_returns_false_when_updating_nonexistent_branch()
    {
        // Arrange
        $nonExistentId = 99999;
        $updateData = [
            BranchColumns::NAME => 'Cabang Tidak Ada',
        ];

        // Act
        $result = Branch::updateBranch($nonExistentId, $updateData);

        // Assert
        $this->assertFalse($result);
    }

    /** @test */
    public function it_can_delete_branch()
    {
        // Arrange
        $branch = Branch::factory()->create();

        // Act
        $result = Branch::deleteBranch($branch->id);

        // Assert
        $this->assertEquals(1, $result); // 1 row affected
        $this->assertDatabaseMissing('branches', ['id' => $branch->id]);
    }

    /** @test */
    public function it_can_find_branch_by_id()
    {
        // Arrange
        $branch = Branch::factory()->create([
            BranchColumns::NAME => 'Cabang Test',
        ]);

        // Act
        $foundBranch = Branch::findBranch($branch->id);

        // Assert
        $this->assertInstanceOf(Branch::class, $foundBranch);
        $this->assertEquals($branch->id, $foundBranch->id);
        $this->assertEquals('Cabang Test', $foundBranch->{BranchColumns::NAME});
    }

    /** @test */
    public function it_throws_exception_when_branch_not_found()
    {
        // Arrange
        $nonExistentId = 99999;

        // Act & Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Cabang tidak ditemukan!');
        
        Branch::findBranch($nonExistentId);
    }

    /** @test */
    public function it_can_count_total_branches()
    {
        // Arrange
        Branch::factory()->count(5)->create();

        // Act
        $count = Branch::countBranch();

        // Assert
        $this->assertEquals(5, $count);
    }

    /** @test */
    public function it_can_count_branches_by_status()
    {
        // Arrange
        Branch::factory()->count(3)->active()->create();
        Branch::factory()->count(2)->inactive()->create();

        // Act
        $statusCount = Branch::countBranchByStatus();

        // Assert
        $this->assertEquals(3, $statusCount['aktif']);
        $this->assertEquals(2, $statusCount['nonaktif']);
    }

    /** @test */
    public function it_can_search_branches_by_name()
    {
        // Arrange
        Branch::factory()->create([BranchColumns::NAME => 'Cabang Jakarta']);
        Branch::factory()->create([BranchColumns::NAME => 'Cabang Bandung']);
        Branch::factory()->create([BranchColumns::NAME => 'Cabang Surabaya']);

        // Act
        $results = Branch::getAllBranch('Jakarta');

        // Assert
        $this->assertEquals(1, $results->count());
        $this->assertStringContainsString('Jakarta', $results->first()->{BranchColumns::NAME});
    }

    /** @test */
    public function it_can_be_created_using_factory()
    {
        // Act
        $branch = Branch::factory()->create();

        // Assert
        $this->assertInstanceOf(Branch::class, $branch);
        $this->assertDatabaseHas('branches', ['id' => $branch->id]);
        $this->assertNotNull($branch->{BranchColumns::NAME});
        $this->assertNotNull($branch->{BranchColumns::ADDRESS});
        $this->assertNotNull($branch->{BranchColumns::PHONE});
    }
}