<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\Request;
use App\Http\Controllers\ItemController;

class ItemControllerDirectCallTest extends TestCase
{
    public function test_add_item_direct_call_triggers_validation_failure()
    {
        $request = Request::create('/item/add', 'POST', [
            'product_id' => 'ABC',
            'sku' => 'SK123',
            'item_name' => 'Test Item',
            'measurement_unit' => '1',
            'selling_price' => 1000,
        ]);

        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $controller = new ItemController();
        $controller->addItem($request);
    }
}