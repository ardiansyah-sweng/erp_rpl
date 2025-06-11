<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Category;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function it_can_get_category_by_category_name()
    {
        $category = Category::create([
            'category' => 'Elektronik',
            'parent_id' => null,
            'active' => 1,
        ]);

        $result = Category::getCategoryByName('Elektronik');

        $this->assertCount(1, $result);
        $this->assertEquals('Elektronik', $result->first()->category);
    }
}
