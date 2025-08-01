<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Item; 

class ItemmModelTest extends TestCase
{
    /** @test */
    public function it_counts_items_by_category()
    {
        $count = Item::countItemByCategory(1); 
        echo "Jumlah item kategori: " . $count;

        $this->assertIsInt($count); 
    }
}
