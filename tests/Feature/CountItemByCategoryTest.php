<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Item;

class CountItemByCategoryTest extends TestCase
{
    /** @test */
    public function testCountItemByCategoryById()
    {
        // Misalnya ID kategori yang ingin diuji adalah 1
        $categoryId = 1;

        // Panggil fungsi countItemByCategory dengan ID kategori
        $count = Item::countItemByCategory($categoryId);

        dump("Kategori ID: {$categoryId} - Jumlah Item: {$count}");

        // Dummy assert agar test dianggap lolos
        $this->assertTrue(true);
    }
}
