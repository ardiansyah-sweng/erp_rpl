<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Item; 

class ItemModellTest extends TestCase
{
    /** @test */
    public function it_counts_items_by_category()
    {
        $count = Item::countItemByCategory(1); 
        echo "Jumlah item kategori: " . $count;

        $this->assertIsInt($count); 
    }
}
