<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\AssortmentProduction;

class AssortProductionModelTest extends TestCase
{
    public function test_get_production_data()
    {
        $model = new AssortmentProduction();
        $data = $model->getProduction();
        $this->assertNotEmpty($data);

        $sample = $data->take(5);

        echo "\n5 Data dari AssortmentProduction:\n";
        foreach ($sample as $index => $item) {
            echo "Data ke-" . ($index + 1) . ": " . json_encode($item->toArray(), JSON_PRETTY_PRINT) . "\n";
        }

        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $data);
    }

    public function test_add_production()
    {
        $data = [
            'production_number' => 'PROD-098',
            'sku' => 'SKU-098',
            'branch_id' => 2,
            'rm_whouse_id' => 20,
            'fg_whouse_id' => 20,
            'production_date' => now()->format('Y-m-d H:i:s'),
            'finished_date' => '2025-06-16',
            'in_production' => 0,
            'description' => 'Produksi uji 2 model tambah data',
        ];

        $created = AssortmentProduction::addProduction($data);

        $this->assertDatabaseHas('assortment_production', [
            'production_number' => 'PROD-098',
            'sku' => 'SKU-098',
        ]);

        $this->assertEquals('Produksi uji 2 model tambah data', $created->description);
    }

    public function test_update_production_coba()
    {
        $production = AssortmentProduction::create([
            'production_number' => 'CB-01',
            'sku' => 'SKU-TEST-001',
            'branch_id' => 1,
            'rm_whouse_id' => 10,
            'fg_whouse_id' => 20,
            'production_date' => now()->format('Y-m-d H:i:s'),
            'finished_date' => now()->addDays(2)->format('Y-m-d'),
            'in_production' => 0,
            'description' => 'Deskripsi awal uji update',
            'created_at' => now(),
        ]);

        $id = $production->id;

        $updateData = [
            'description' => 'Produksi uji 2 model update data',
            'in_production' => 1,
        ];

        $updated = AssortmentProduction::updateProduction($id, $updateData);

        $this->assertNotNull($updated);
        $this->assertEquals('Produksi uji 2 model update data', $updated->description);
        $this->assertEquals(1, $updated->in_production);

        $this->assertDatabaseHas('assortment_production', [
            'id' => $id,
            'description' => 'Produksi uji 2 model update data',
        ]);
    }

    public function test_delete_production()
    {
        $production = AssortmentProduction::create([
            'production_number' => 'DEL-OK-01',
            'sku' => 'SKU-DEL-01',
            'branch_id' => 2,
            'rm_whouse_id' => 10,
            'fg_whouse_id' => 20,
            'in_production' => 0,
            'production_date' => now(),
            'description' => 'Test delete success'
        ]);

        $response = AssortmentProduction::deleteProduction($production->production_number);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(['message' => 'Production deleted successfully'], $response->getData(true));

        $this->assertDatabaseMissing('assortment_production', [
            'production_number' => 'DEL-OK-01',
        ]);
    }
}
