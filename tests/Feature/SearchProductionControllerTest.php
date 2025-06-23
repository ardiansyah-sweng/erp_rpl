<?php

namespace Tests\Feature;

use Illuminate\Support\Str; 
use Tests\TestCase;

class SearchProductionControllerTest extends TestCase
{
    public function testSearchProductionReturnsJson()
    {
        $randomKeyword = Str::random(10);

        $response = $this->get('/productions/search/' . $randomKeyword);
        $response->assertStatus(200);

        $this->assertIsArray($response->json());
        foreach ($response->json() as $item) {
            $this->assertArrayHasKey('id', $item);
            $this->assertArrayHasKey('sku', $item);
        }
    }
}
