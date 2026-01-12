<?php

namespace Tests\Feature\Browser;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use App\Models\SupplierPICModel;

class SupplierPicDetailViewTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // run migrations for a clean DB
        $this->artisan('migrate');
    }

    private function createSupplier($supplierId, $companyName = 'Test Company')
    {
        DB::table('suppliers')->insert([
            'supplier_id' => $supplierId,
            'company_name' => $companyName,
            'address' => 'Jl. Test',
            'telephone' => '021-1111111',
            'bank_account' => '1234567890',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_detail_view_displays_pic_information_when_record_exists()
    {
        // Arrange
        $this->createSupplier('SUP001', 'Acme Corp');

        $pic = SupplierPICModel::create([
            'supplier_id' => 'SUP001',
            'name' => 'John Doe',
            'phone_number' => '081234567890',
            'email' => 'john@example.com',
            'is_active' => 1,
            'assigned_date' => '2024-01-15',
        ]);

        // Act
        $response = $this->get('/supplier/pic/detail/' . $pic->id);

        // Assert
        $response->assertStatus(200);
        $response->assertSee('Detail PIC Supplier');
        $response->assertSee('ID Supplier');
        $response->assertSee('Nama Supplier');
        $response->assertSee('Nama PIC');
        $response->assertSee('Email');
        $response->assertSee('Telephone');
        $response->assertSee('Assignment Date');

        // check the actual values are rendered
        $response->assertSee('SUP001');
        $response->assertSee('Acme Corp');
        $response->assertSee('John Doe');
        $response->assertSee('john@example.com');
        $response->assertSee('081234567890');
        $response->assertSee('2024-01-15');
        $response->assertSee('Aktif');
    }
}
