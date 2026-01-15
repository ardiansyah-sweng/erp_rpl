<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Warehouse;
use App\Constants\WarehouseColumns;
use App\Constants\Messages;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class WarehouseControllerDeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_delete_returns_success()
    {
        $w = Warehouse::create([
            WarehouseColumns::NAME => 'DeleteTest',
            WarehouseColumns::ADDRESS => 'Jl Test',
            WarehouseColumns::PHONE => '021-000',
            WarehouseColumns::IS_RM_WAREHOUSE => false,
            WarehouseColumns::IS_FG_WAREHOUSE => true,
            WarehouseColumns::IS_ACTIVE => true,
        ]);

        $response = $this->deleteJson("/api/warehouses/{$w->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => Messages::WAREHOUSE_DELETED,
                 ]);

        $this->assertDatabaseMissing((new Warehouse())->getTable(), ['id' => $w->id]);
    }

    public function test_api_delete_returns_422_when_in_use()
    {
        $w = Warehouse::create([
            WarehouseColumns::NAME => 'InUseTest',
            WarehouseColumns::ADDRESS => 'Jl Test',
            WarehouseColumns::PHONE => '021-111',
            WarehouseColumns::IS_RM_WAREHOUSE => false,
            WarehouseColumns::IS_FG_WAREHOUSE => true,
            WarehouseColumns::IS_ACTIVE => true,
        ]);

        // Create temporary `stock` table for this test to simulate relation
        Schema::create('stock', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('warehouse_id');
            $table->string('note')->nullable();
        });

        DB::table('stock')->insert([
            'warehouse_id' => $w->id,
            'note' => 'in use',
        ]);

        $response = $this->deleteJson("/api/warehouses/{$w->id}");

        $response->assertStatus(422)
                 ->assertJson([
                     'success' => false,
                     'message' => Messages::WAREHOUSE_IN_USE,
                 ]);

        $this->assertDatabaseHas((new Warehouse())->getTable(), ['id' => $w->id]);

        // Clean up created table
        Schema::dropIfExists('stock');
    }

    public function test_api_delete_returns_404_when_not_found()
    {
        $response = $this->deleteJson('/api/warehouses/999999');

        $response->assertStatus(404)
                 ->assertJson([
                     'success' => false,
                     'message' => Messages::WAREHOUSE_NOT_FOUND,
                 ]);
    }

}
