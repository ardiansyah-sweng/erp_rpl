<?php

namespace Tests\Feature\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use App\Models\BillOfMaterial;

class BillOfMaterialEditTest extends TestCase
{
    use RefreshDatabase;

    private function insertMeasurementUnit(): int
    {
        return DB::table('measurement_unit')->insertGetId([
            'unit_name'    => 'Pcs',
            'abbreviation' => 'Pcs',
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);
    }

    private function insertBom(int $unitId): int
    {
        $tableName = (new BillOfMaterial())->getTable();

        return DB::table($tableName)->insertGetId([
            'bom_id'           => 'BOM001',
            'bom_name'         => 'Resep Test',
            'measurement_unit' => $unitId,
            'total_cost'       => 500000,
            'active'           => 1,
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);
    }

    /** @test */
    public function it_can_show_edit_form()
    {
        $unitId = $this->insertMeasurementUnit();
        $id     = $this->insertBom($unitId);

        $response = $this->get("/bom/{$id}/edit");

        $response->assertStatus(200);
        $response->assertSee('Resep Test');
        $response->assertSee('BOM001');
    }

    /** @test */
    public function it_returns_404_when_bom_not_found()
    {
        $response = $this->get('/bom/9999/edit');

        $response->assertRedirect('/bom/list');
    }

    /** @test */
    public function it_can_update_bom_successfully()
    {
        $unitId = $this->insertMeasurementUnit();
        $id     = $this->insertBom($unitId);

        $response = $this->put("/bom/{$id}", [
            'bom_name'         => 'Resep Test Updated',
            'measurement_unit' => $unitId,
            'total_cost'       => 750000,
            'active'           => 1,
        ]);

        $response->assertRedirect('/bom/list');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas((new BillOfMaterial())->getTable(), [
            'id'       => $id,
            'bom_name' => 'Resep Test Updated',
            'total_cost' => 750000,
        ]);
    }

    /** @test */
    public function it_fails_update_when_bom_name_is_empty()
    {
        $unitId = $this->insertMeasurementUnit();
        $id     = $this->insertBom($unitId);

        $response = $this->put("/bom/{$id}", [
            'bom_name'         => '',
            'measurement_unit' => $unitId,
            'total_cost'       => 750000,
            'active'           => 1,
        ]);

        $response->assertSessionHasErrors('bom_name');
    }

    /** @test */
    public function it_fails_update_when_total_cost_is_negative()
    {
        $unitId = $this->insertMeasurementUnit();
        $id     = $this->insertBom($unitId);

        $response = $this->put("/bom/{$id}", [
            'bom_name'         => 'Resep Test',
            'measurement_unit' => $unitId,
            'total_cost'       => -100,
            'active'           => 1,
        ]);

        $response->assertSessionHasErrors('total_cost');
    }

    /** @test */
    public function it_fails_update_when_bom_name_duplicate_on_different_id()
    {
        $unitId = $this->insertMeasurementUnit();
        $id     = $this->insertBom($unitId);

        $tableName = (new BillOfMaterial())->getTable();
        DB::table($tableName)->insert([
            'bom_id'           => 'BOM002',
            'bom_name'         => 'Resep Lain',
            'measurement_unit' => $unitId,
            'total_cost'       => 100000,
            'active'           => 1,
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);

        $response = $this->put("/bom/{$id}", [
            'bom_name'         => 'Resep Lain',
            'measurement_unit' => $unitId,
            'total_cost'       => 500000,
            'active'           => 1,
        ]);

        $response->assertSessionHasErrors('bom_name');
    }

    /** @test */
    public function it_allows_update_with_same_bom_name_on_same_id()
    {
        $unitId = $this->insertMeasurementUnit();
        $id     = $this->insertBom($unitId);

        $response = $this->put("/bom/{$id}", [
            'bom_name'         => 'Resep Test',
            'measurement_unit' => $unitId,
            'total_cost'       => 600000,
            'active'           => 0,
        ]);

        $response->assertRedirect('/bom/list');
        $response->assertSessionHas('success');
    }

    /** @test */
    public function it_can_deactivate_bom()
    {
        $unitId = $this->insertMeasurementUnit();
        $id     = $this->insertBom($unitId);

        $this->put("/bom/{$id}", [
            'bom_name'         => 'Resep Test',
            'measurement_unit' => $unitId,
            'total_cost'       => 500000,
            'active'           => 0,
        ]);

        $this->assertDatabaseHas((new BillOfMaterial())->getTable(), [
            'id'     => $id,
            'active' => 0,
        ]);
    }
}
