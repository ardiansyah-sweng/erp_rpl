<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Branch;

class BranchApiCompleteTest extends TestCase
{
	public function test_delete_branch()
	{
		$branch = Branch::factory()->create();

		$response = $this->deleteJson("/api/branches/{$branch->id}");

		$response->assertStatus(200)
			->assertJsonPath('success', true)
			->assertJsonPath('message', \App\Constants\Messages::BRANCH_DELETED);

		$this->assertDatabaseMissing(config('db_tables.branch'), ['id' => $branch->id]);
	}

	public function test_statistics_endpoint_returns_counts()
	{
		Branch::factory()->count(3)->active()->create();
		Branch::factory()->count(2)->inactive()->create();

		$response = $this->getJson('/api/branches/analytics/statistics');

		$response->assertStatus(200)
			->assertJsonPath('success', true)
			->assertJsonStructure(['data' => ['total_branches', 'active_branches', 'inactive_branches']])
			->assertJsonPath('data.total_branches', 5);
	}
}
