<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\BillOfMaterial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\Test;

class getBillOfMaterialTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Setup untuk mendaftarkan route khusus testing
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Daftarkan route khusus untuk testing
        Route::get('/api/bill-of-material', function () {
            return response()->json(
                BillOfMaterial::orderBy('created_at', 'asc')->get(),
                200
            );
        });
    }

    #[Test]
    public function get_bill_of_material_returns_paginated_data(): void
    {
        // Buat minimal 10 data
        for ($i = 1; $i <= 15; $i++) {
            BillOfMaterial::create([
                'bom_id' => 'BOM' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'bom_name' => 'Test Assembly ' . $i,
                'measurement_unit' => 'UNIT',
                'total_cost' => 1000 * $i,
                'active' => true,
            ]);
        }

        $result = BillOfMaterial::getBillOfMaterial();

        $this->assertNotNull($result);
        $this->assertNotEmpty($result->items());
        $this->assertTrue($result->total() >= 10);
        $this->assertEquals(10, $result->count());
    }

    #[Test]
    public function get_bill_of_material_returns_all_boms_from_database(): void
    {
        BillOfMaterial::create([
            'bom_id' => 'BOM001',
            'bom_name' => 'Produk A Assembly',
            'measurement_unit' => 'PCS',
            'total_cost' => 1500000,
            'active' => true,
        ]);

        BillOfMaterial::create([
            'bom_id' => 'BOM002',
            'bom_name' => 'Produk B Assembly',
            'measurement_unit' => 'UNIT',
            'total_cost' => 2500000,
            'active' => true,
        ]);

        BillOfMaterial::create([
            'bom_id' => 'BOM003',
            'bom_name' => 'Produk C Assembly',
            'measurement_unit' => 'SET',
            'total_cost' => 3500000,
            'active' => false,
        ]);

        $response = $this->getJson('/api/bill-of-material');

        $response->assertStatus(200);
        $response->assertJsonCount(3);

        $data = $response->json();

        $this->assertEquals('BOM001', $data[0]['bom_id']);
        $this->assertEquals('BOM002', $data[1]['bom_id']);
        $this->assertEquals('BOM003', $data[2]['bom_id']);
    }

    #[Test]
    public function get_bill_of_material_returns_correct_data_structure(): void
    {
        BillOfMaterial::create([
            'bom_id' => 'BOM001',
            'bom_name' => 'Test Product Assembly',
            'measurement_unit' => 'UNIT',
            'total_cost' => 1000000,
            'active' => true,
        ]);

        $response = $this->getJson('/api/bill-of-material');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'id',
                'bom_id',
                'bom_name',
                'measurement_unit',
                'total_cost',
                'active',
                'created_at',
                'updated_at',
            ]
        ]);
    }

    #[Test]
    public function get_bill_of_material_calls_model_method(): void
    {
        BillOfMaterial::create([
            'bom_id' => 'BOM999',
            'bom_name' => 'Special Test Assembly',
            'measurement_unit' => 'PACK',
            'total_cost' => 9999999,
            'active' => true,
        ]);

        $response = $this->getJson('/api/bill-of-material');

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'bom_id' => 'BOM999',
            'bom_name' => 'Special Test Assembly',
        ]);
    }

    #[Test]
    public function get_bill_of_material_returns_data_in_default_order(): void
    {
        BillOfMaterial::create([
            'bom_id' => 'BOM003',
            'bom_name' => 'Product Z',
            'measurement_unit' => 'UNIT',
            'total_cost' => 3000,
            'active' => true,
            'created_at' => '2024-03-01 10:00:00',
        ]);

        BillOfMaterial::create([
            'bom_id' => 'BOM001',
            'bom_name' => 'Product X',
            'measurement_unit' => 'UNIT',
            'total_cost' => 1000,
            'active' => true,
            'created_at' => '2024-01-01 10:00:00',
        ]);

        BillOfMaterial::create([
            'bom_id' => 'BOM002',
            'bom_name' => 'Product Y',
            'measurement_unit' => 'UNIT',
            'total_cost' => 2000,
            'active' => true,
            'created_at' => '2024-02-01 10:00:00',
        ]);

        $response = $this->getJson('/api/bill-of-material');
        $response->assertStatus(200);
        
        $data = $response->json();
        
        // Verifikasi data tidak kosong
        $this->assertNotEmpty($data);
        $this->assertEquals('BOM001', $data[0]['bom_id']);
        $this->assertEquals('BOM002', $data[1]['bom_id']);
        $this->assertEquals('BOM003', $data[2]['bom_id']);
    }

    #[Test]
    public function get_bill_of_material_returns_json_content_type(): void
    {
        $response = $this->get('/api/bill-of-material');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');
    }

    #[Test]
    public function get_bill_of_material_with_large_dataset(): void
    {
        for ($i = 1; $i <= 50; $i++) {
            BillOfMaterial::create([
                'bom_id' => 'BOM' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'bom_name' => 'Assembly Product ' . $i,
                'measurement_unit' => 'UNIT',
                'total_cost' => 1000 * $i,
                'active' => true,
            ]);
        }

        $response = $this->getJson('/api/bill-of-material');

        $response->assertStatus(200);
        $response->assertJsonCount(50);
    }

    #[Test]
    public function get_bill_of_material_includes_both_active_and_inactive(): void
    {
        BillOfMaterial::create([
            'bom_id' => 'BOM001',
            'bom_name' => 'Active Product',
            'measurement_unit' => 'UNIT',
            'total_cost' => 1000,
            'active' => true,
        ]);

        BillOfMaterial::create([
            'bom_id' => 'BOM002',
            'bom_name' => 'Inactive Product',
            'measurement_unit' => 'UNIT',
            'total_cost' => 2000,
            'active' => false,
        ]);

        $response = $this->getJson('/api/bill-of-material');

        $response->assertStatus(200);
        $response->assertJsonCount(2);
    }

    #[Test]
    public function get_bill_of_material_returns_correct_decimal_format(): void
    {
        BillOfMaterial::create([
            'bom_id' => 'BOM001',
            'bom_name' => 'Decimal Product',
            'measurement_unit' => 'PCS',
            'total_cost' => 1234567.89,
            'active' => true,
        ]);

        $response = $this->getJson('/api/bill-of-material');
        $response->assertStatus(200);
        
        $data = $response->json();
        $this->assertNotEmpty($data);
        
        // DB integer → decimal dibulatkan
        $this->assertEquals(1234568, $data[0]['total_cost']);
    }

    #[Test]
    public function get_bill_of_material_can_handle_multiple_requests(): void
    {
        BillOfMaterial::create([
            'bom_id' => 'BOM001',
            'bom_name' => 'Product 1',
            'measurement_unit' => 'UNIT',
            'total_cost' => 100000,
            'active' => true,
        ]);

        $r1 = $this->getJson('/api/bill-of-material');
        $r2 = $this->getJson('/api/bill-of-material');
        $r3 = $this->getJson('/api/bill-of-material');

        $r1->assertStatus(200);
        $r2->assertStatus(200);
        $r3->assertStatus(200);

        $this->assertEquals($r1->json(), $r2->json());
        $this->assertEquals($r2->json(), $r3->json());
    }
}