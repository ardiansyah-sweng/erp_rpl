<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Supplier;

class GetSupplierById_Test extends TestCase
{
    public function test_model_getSupplierById(): void
    {
        $supplier = Supplier::inRandomOrder()->first();
        $this->assertNotNull($supplier);
        $foundSupplier = (new Supplier())->getSupplierById($supplier->supplier_id);

        $this->assertNotNull($foundSupplier);
        $this->assertEquals($supplier->supplier_id, $foundSupplier->supplier_id);
    }

    public function test_controller_getSupplierById(): void
    {
        $supplier = Supplier::inRandomOrder()->first();
        $this->assertNotNull($supplier);
        $response = $this->get('/supplier/detail/' . $supplier->supplier_id);

        $response->assertStatus(200);

        $response->assertViewIs('Supplier.detail');
        $response->assertViewHas('sup', function ($sup) use ($supplier) {
            return $sup->supplier_id === $supplier->supplier_id;
        });
    }
}