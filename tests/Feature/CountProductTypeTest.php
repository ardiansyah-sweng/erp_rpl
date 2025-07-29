<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Item;

class CountProductTypeTest extends TestCase
{
    /**
     * Test counting products by product type from existing database data.
     *
     * @return void
     */
    public function test_count_item_by_product_type_using_real_data()
    {
        $actualCount = Item::countItemByProductType();

        dd([
            'actual' => $actualCount 
        ]);
        $this->assertEquals( $actualCount, "Jumlah produk tidak sesuai dengan fungsi countItemByProductType()");
    }
}
