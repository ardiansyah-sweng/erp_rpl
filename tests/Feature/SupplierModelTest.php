<?php

namespace Tests\Feature;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Supplier;
use App\Http\Controller;
use App\Models\PurchaseOrder;

class SupplierModelTest extends TestCase
{

    /** @test */
    public function it_can_get_suppliers_with_order_frequency()
{
    $suppliers = Supplier::withCount('purchaseOrders')->get();

    $result = $suppliers->map(function ($supplier) {
        return [
            'supplier_id' => $supplier->supplier_id,
            'supplier_name' => $supplier->supplier_name,
            'order_frequency' => $supplier->purchase_orders_count,
        ];
    });
    
    $this->assertNotEmpty($result);
}

}
