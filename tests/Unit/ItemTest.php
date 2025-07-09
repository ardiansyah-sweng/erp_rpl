<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Item;

class ItemTest extends TestCase
{
    /** @test */
    public function it_counts_items_by_category_from_database()
    {
        $category = 'KAOS';
        $countFromFunction = Item::countItemByCategory($category);

        $countDirect = Item::where('product_id', $category)->count();
        $this->assertEquals($countDirect, $countFromFunction);
    }
}
