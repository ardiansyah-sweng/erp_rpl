<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use App\Models\BillOfMaterialModel;

class BillOfMaterialModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_count_bill_of_material()
    {
        // Menyisipkan 2 data ke tabel bill_of_material
        DB::table('bill_of_material')->insert([
            ['bom_id' => 'BOM001', 'bom_name' => 'BOM A', 'measurement_unit' => 1],
            ['bom_id' => 'BOM002', 'bom_name' => 'BOM B', 'measurement_unit' => 2],
        ]);

        // Memanggil fungsi dari model
        $count = BillOfMaterialModel::countBillOfMaterial();

        // Memastikan jumlah data sesuai harapan
        $this->assertEquals(2, $count);
    }
}
