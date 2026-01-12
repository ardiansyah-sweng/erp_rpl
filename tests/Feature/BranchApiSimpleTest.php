<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Branch;

class BranchApiSimpleTest extends TestCase
{
	public function test_update_branch()
	{
		$branch = Branch::factory()->create();

		$updateData = [
			'branch_name' => 'Updated Name ' . microtime(true),
			'branch_address' => 'Updated Address 99',
			'branch_telephone' => '021-11112222',
			'is_active' => false,
		];

		$response = $this->putJson("/api/branches/{$branch->id}", $updateData);

		$response->assertStatus(200)
			->assertJsonPath('data.branch_name', $updateData['branch_name'])
			->assertJsonPath('data.is_active', false);

		$this->assertDatabaseHas(config('db_tables.branch'), [
			'id' => $branch->id,
			'branch_name' => $updateData['branch_name'],
		]);
	}
}
