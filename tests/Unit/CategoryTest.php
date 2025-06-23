<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Category;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_get_category_by_category_name() 
    {
        $category = Category::create([
            'category' => 'Alat Musik',
            'parent_id' => null,
            'active' => 1,
        ]);

        $result = Category::getCategoryByName('Alat Musik');

        $this->assertCount(1, $result);
        $this->assertEquals('Alat Musik', $result->first()->category);
    }
}
