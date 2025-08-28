<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Supplier;

class AddSupplierTest extends TestCase
{
    /** @test */
    public function it_can_add_a_supplier_through_web()
    {
        $response = $this->post('/supplier/add', [
            'supplier_id'    => 'SUP020',
            'company_name'   => 'ABC Corporation',
            'address'        => '123 Main Street',
            'phone_number'   => '08123456789',
            'bank_account'   => '123-456-7890',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Supplier Berhasil Di Tambahkan');

        $this->assertDatabaseHas('supplier', [
            'supplier_id'  => 'SUP020',
            'company_name' => 'ABC Corporation',
        ]);
    }
}
