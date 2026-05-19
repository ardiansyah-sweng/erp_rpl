<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;

class CountProductByCategoryTest extends TestCase
{
    /** @test */
    public function testCountProductByCategory()
    {
       
        $results = Product::countProductByCategory();

        foreach ($results as $row) {
            dump("Kategori: {$row->product_category} - Jumlah: {$row->total}");
        }

        
        $this->assertTrue(true);
    }
}