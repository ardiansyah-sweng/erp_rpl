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

}