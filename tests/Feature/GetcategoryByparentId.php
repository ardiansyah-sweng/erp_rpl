<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Category;
//
class GetcategoryByparentId extends TestCase
{
    public function test_model_getCategoryByParent(): void
    {
        $parentCategory = Category::inRandomOrder()->first();
        $this->assertNotNull($parentCategory);

        $childCategories = Category::getCategoryByParent($parentCategory->id);

        $this->assertNotNull($childCategories);
        foreach ($childCategories as $child) {
            $this->assertEquals($parentCategory->id, $child->parent_id);
        }
    }
}/