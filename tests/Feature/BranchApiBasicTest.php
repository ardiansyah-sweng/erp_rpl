<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Branch;

class BranchApiBasicTest extends TestCase
{
	public function test_index_returns_paginated_list()
	{
		Branch::factory()->count(30)->create();

		$response = $this->getJson('/api/branches');

		$response->assertStatus(200)
			->assertJsonStructure(['data', 'links', 'meta'])
			->assertJsonCount(15, 'data'); // default pagination in config
	}

	public function test_store_and_show_branch()
	{
		$payload = [
			'branch_name' => 'Cabang Test API ' . microtime(true),
			'branch_address' => 'Jl. Testing No 1',
			'branch_telephone' => '021-99999999',
			'is_active' => true,
		];

		$store = $this->postJson('/api/branches', $payload);
		$store->assertStatus(201)
			->assertJsonPath('data.branch_name', $payload['branch_name']);

		$id = $store->json('data.id');

		$show = $this->getJson("/api/branches/{$id}");
		$show->assertStatus(200)
			->assertJsonPath('data.branch_address', $payload['branch_address']);
	}
}
