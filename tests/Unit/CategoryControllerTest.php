<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Category;

class CategoryControllerTest extends TestCase
{
    public function test_can_build_category_manually()
    {
        $parent = new Category();
        $parent->id = 1;
        $parent->name = 'Elektronik';
        $parent->slug = 'elektronik';
        $parent->parent_id = null;

        $child = new Category();
        $child->id = 2;
        $child->name = 'Laptop';
        $child->slug = 'laptop';
        $child->parent_id = 1;

        $this->assertEquals('Elektronik', $parent->name);
        $this->assertEquals(1, $child->parent_id);
        $this->assertEquals($parent->id, $child->parent_id);

    }
}
