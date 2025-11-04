<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Warehouse;
use Illuminate\Support\Facades\DB;
use App\Constants\WarehouseColumns;


class DeleteWarehouseTest extends TestCase
{
    use RefreshDatabase;

    public function test_delete_warehouse_fails_if_used_in_assortment_production()
    {
        
$warehouse = Warehouse::create([
    WarehouseColumns::NAME => 'Warehouse Dipakai',
    WarehouseColumns::ADDRESS => 'Jl. Dipakai',
    WarehouseColumns::PHONE => '021-9999999',
    WarehouseColumns::IS_ACTIVE => true,
]);

     DB::table('assortment_production')->insert([
        'rm_whouse_id' => $warehouse->id,
        'fg_whouse_id' => $warehouse->id,
        'production_number' => 'TEST-001', // atau nilai dummy lain
        'sku' => 'SKU-TEST-001',
        'branch_id' => 1,
        'production_date' => now(),
        'description' => 'Test production for warehouse deletion',
    ]);

        $result = Warehouse::deleteWarehouse($warehouse->id);

    $this->assertFalse($result['status']);
    $this->assertEquals(
        'Warehouse tidak dapat dihapus karena masih digunakan di tabel assortment_production.',
        $result['message']
    );
    }
}