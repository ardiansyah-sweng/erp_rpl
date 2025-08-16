<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Branch;
use App\Constants\BranchColumns;
use Illuminate\Database\Eloquent\Model;

class BranchTest extends ModelTestCase
{
    public function test_it_can_add_a_new_branch()
    {
        // Arrange
        $branchData = [
            BranchColumns::NAME => 'Cabang'.' '.$this->faker->name(),
            BranchColumns::ADDRESS => $this->faker->address(),
            BranchColumns::PHONE => $this->faker->phoneNumber(),
            BranchColumns::IS_ACTIVE => $this->faker->boolean(),
        ];

        // Act
        $result = Branch::addBranch($branchData);

        // Assert
        $this->assertInstanceOf(Branch::class, $result);
        $this->assertDatabaseHas('branches', $branchData);
        $this->assertEquals($branchData[BranchColumns::NAME], $result->branch_name);
        $this->assertEquals($branchData[BranchColumns::ADDRESS], $result->branch_address);
        $this->assertEquals($branchData[BranchColumns::PHONE], $result->branch_telephone);
        $this->assertEquals($branchData[BranchColumns::IS_ACTIVE], $result->is_active);
    }
}