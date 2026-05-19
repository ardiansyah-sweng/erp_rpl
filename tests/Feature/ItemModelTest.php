<?php

namespace Tests\Feature;

use App\Models\Item;
use Tests\TestCase;

class ItemModelTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function it_can_find_existing_existing_item_named_Kaos(): void
    {
        $results = Item::searchItem('Kaos');
        $this->assertGreaterThan(0, $results->count(), 'item dengan kata kunci "Kaos" tidak ditemukan.');
        $this->assertTrue(
            $results->pluck('item_name')->contains(function ($name){
                return stripos($name, 'kaos') !== false;
            }),
            'item dengan nama mengandung "kaos" tidak ditemukan.'
        );
    }
    /** @test */
    public function it_returns_empty_if_item_does_not_exist(){
        $result = Item::searchItem('NamaItemYangPastiTidakAda');
        $this->assertCount(0, $result);
    }
}
