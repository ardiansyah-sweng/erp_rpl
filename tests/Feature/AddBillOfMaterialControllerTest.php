<?php

namespace Tests\Feature;

use App\Models\BillOfMaterial;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AddBillOfMaterialControllerTest extends TestCase
{
    use WithFaker;

    public function test_controller_add_bill_of_material()
    {
        $bomName = $this->faker->unique()->words(2, true);
        $requestData = [
            'bom_name' => $bomName,
            'measurement_unit' => '31',
            'total_cost' => 100000,
            'active' => true,
        ];

        $response = $this->post('/billofmaterial/add', $requestData);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Bill of Material berhasil ditambahkan!');

        $this->assertDatabaseHas('bill_of_material', [
            'bom_name' => $bomName,
        ]);
    }
}
