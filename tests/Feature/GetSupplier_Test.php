<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Supplier;

class GetSupplier_Test extends TestCase
{
    use RefreshDatabase; // agar database bersih untuk test, opsional kalau pakai database testing

    public function test_model_getSupplier(): void
    {
        // Pastikan minimal ada satu data supplier
        $supplier = Supplier::factory()->create(); // jika pakai factory
        $suppliers = Supplier::getSupplier();

        $this->assertNotEmpty($suppliers);
        foreach ($suppliers as $sup) {
            $this->assertArrayHasKey('orders_count', $sup->toArray());
        }
    }

    public function test_controller_getSupplier(): void
    {
        // Pastikan minimal ada satu data supplier
        $supplier = Supplier::factory()->create(); // jika pakai factory

        $response = $this->get('/supplier'); // sesuaikan route dengan yang kamu buat
        $response->assertStatus(200);
        $response->assertViewHas('suppliers', function ($suppliers) {
            return !$suppliers->isEmpty() && isset($suppliers[0]->orders_count);
        });
    }
}
