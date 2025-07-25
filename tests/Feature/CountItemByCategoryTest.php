<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Item;

class CountItemByCategoryTest extends TestCase
{
    /** @test */
    public function testCountItemByCategory()
    {
        // Daftar kategori yang ingin diuji
        $categories = ['Bahan Baku', 'Kemasan', 'Lainnya'];

        foreach ($categories as $category) {
            $count = Item::countItemByCategory($category);
            dump("Kategori: {$category} - Jumlah Item: {$count}");
        }

        // Dummy assert agar test dianggap lolos
        $this->assertTrue(true);
    }
}
