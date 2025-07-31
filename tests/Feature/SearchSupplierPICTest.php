<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use App\Models\SupplierPic;
use App\Models\Supplier;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class SearchSupplierPICTest extends TestCase
{
    public function test_search_supplier_pic_returns_results_based_on_keywords()
    {
    $result = SupplierPic::searchSupplierPic('Kacung');

    $this->assertGreaterThan(0, $result->total());
    $this->assertEquals('Kacung Drajat Permadi', $result->first()->name);
    }

}
