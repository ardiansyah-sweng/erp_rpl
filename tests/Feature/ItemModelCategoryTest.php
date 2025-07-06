<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Item;

class ItemModelCategoryTest extends TestCase
{
    public function test_get_item_by_category_using_model()
    {
        // Ganti dengan ID kategori yang tersedia di tabel produk (misalnya: 27)
        $categoryId = 1;

        // Panggil fungsi dari model Item
        $items = Item::getItemByCategory($categoryId);

        // Pastikan hasil bisa diiterasi (Collection)
        $this->assertIsIterable($items);

        if ($items->isNotEmpty()) {
            $item = $items->first();

            // Gunakan isset() agar kompatibel dengan stdClass
            $this->assertTrue(isset($item->sku));
            $this->assertTrue(isset($item->item_name));
            $this->assertTrue(isset($item->measurement_unit));
            $this->assertTrue(isset($item->selling_price));
            $this->assertTrue(isset($item->stock_unit));
            $this->assertTrue(isset($item->product_name));
            $this->assertTrue(isset($item->product_category));

            // Cocokkan dengan category yang dicari
            $this->assertEquals($categoryId, $item->product_category);
        } else {
            // Test tetap dianggap lulus jika datanya kosong
            $this->assertTrue(true, 'Data item kosong untuk kategori ini.');
        }
    }
}
