<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\SupplierPIController;
use App\Models\SupplierPICModel;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SupplierPIControllerTest extends TestCase
{
    use RefreshDatabase; // Membersihkan database sebelum setiap tes

    public function test_get_supplier_pic_all_returns_view_with_data()
    {
        // Buat data dummy tanpa factory
        SupplierPICModel::insert([
            ['supplier_id' => 'SUP001', 'name' => 'John Doe', 'phone_number' => '081234567890', 'email' => 'johndoe@example.com', 'assigned_date' => now(), 'active' => 1, 'avatar' => 'https://example.com/avatar1.png'],
            ['supplier_id' => 'SUP002', 'name' => 'Jane Smith', 'phone_number' => '082345678901', 'email' => 'janesmith@example.com', 'assigned_date' => now(), 'active' => 1, 'avatar' => 'https://example.com/avatar2.png'],
        ]);

        // Buat instance controller dan panggil metode
        $controller = new SupplierPIController();
        $response = $controller->getSupplierPICAll();

        // Pastikan tampilan yang dikembalikan benar
        $this->assertEquals('supplier.pic.list', $response->name());

        // Pastikan ada data yang dikirim ke tampilan
        $this->assertArrayHasKey('supplierPICs', $response->getData());
        $this->assertCount(2, $response->getData()['supplierPICs']); // Pastikan 2 data tersedia
    }
}