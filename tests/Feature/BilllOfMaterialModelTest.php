<?php
namespace Tests\Feature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use App\Models\BillOfMaterialModel;

class BilllOfMaterialModelTest extends TestCase
{
    use RefreshDatabase;
    public function test_count_bill_of_material()
    {
        DB::table('bill_of_material')->insert([
            ['bom_id' => 'BOM001', 'bom_name' => 'BOM A', 'measurement_unit' => 1],
            ['bom_id' => 'BOM002', 'bom_name' => 'BOM B', 'measurement_unit' => 2],
        ]);
        $this->assertEquals(2, BillOfMaterialModel::countBillOfMaterial());
    }
}
