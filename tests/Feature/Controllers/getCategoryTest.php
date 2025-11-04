<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\Category;
use App\Constants\CategoryColumns;
use App\Constants\Messages;

class getCategoryTest extends TestCase
{
	/**
	 * TC-CT-15: Arrange-Act-Assert for viewing a valid category by id
	 */
	public function test_view_category_detail_with_valid_id()
	{
		// Arrange: create a category using factory
		$category = Category::factory()->create([
			CategoryColumns::CATEGORY => 'Category Test ' . uniqid(),
			CategoryColumns::IS_ACTIVE => 1,
		]);

		// Act: request the show route (web)
		$response = $this->get(route('categories.show', $category->id));

	// Assert: should return 200, correct view and view data
	$response->assertStatus(200);
	// controller now returns the existing product.category.detail view
	$response->assertViewIs('product.category.detail');
		$response->assertViewHas('category');

		// The page should contain the category name
		$response->assertSee($category->category);

		// Validate view data integrity
		$viewCategory = $response->viewData('category');
		$this->assertEquals($category->id, $viewCategory->id);
		$this->assertEquals($category->category, $viewCategory->category);
	}

	/**
	 * TC-CT-16: Arrange-Act-Assert for viewing a non-existing category by id
	 */
	public function test_view_category_detail_with_invalid_id_returns_error()
	{
		// Arrange: pick an ID that does not exist
		$nonExistingId = 99999999;

		// Act: request the show route
		$response = $this->get(route('categories.show', $nonExistingId));

		// Assert: controller redirects to index with an error message per PRD
		$response->assertRedirect(route('categories.index'));
		$response->assertSessionHas('error', Messages::CATEGORY_NOT_FOUND);
	}
}

