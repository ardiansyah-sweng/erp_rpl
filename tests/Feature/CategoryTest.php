<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Category;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
public function it_can_insert_category_to_database()
{
    $categoryData = [
        'category' => 'Elektronik', 
    ];

    \App\Models\Category::create($categoryData);

    $this->assertDatabaseHas('categories', [
        'category' => 'Elektronik'
    ]);
}
}