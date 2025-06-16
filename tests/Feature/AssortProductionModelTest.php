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

        //5 data pertama
        $sample = $data->take(5);

        echo "\n5 Data dari AssortmentProduction:\n";
        foreach ($sample as $index => $item) {
            echo "Data ke-" . ($index + 1) . ": " . json_encode($item->toArray(), JSON_PRETTY_PRINT) . "\n";
        }

        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $data);
    }
}