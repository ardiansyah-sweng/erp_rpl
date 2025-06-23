<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Supplier;

class SupplierTest extends TestCase
{
    /** @test */
    public function add_supplier()
    {
        $data = [
            'supplier_id'   => 'SUP111',
            'company_name'  => 'PT. Karya Sejahtera',
            'address'       => 'Jl. Sukamaju No. 45',
            'phone_number'  => '081298765432',
            'bank_account'  => '0987654321',
        ];

        $supplier = Supplier::addSupplier($data);

        $this->assertDatabaseHas('supplier', [
            'supplier_id'  => 'SUP111',
            'company_name' => 'PT. Karya Sejahtera',
        ]);

        $this->assertInstanceOf(Supplier::class, $supplier);
    }
}
