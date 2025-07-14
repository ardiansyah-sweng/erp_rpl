<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use App\Models\BillOfMaterialModel;
use PHPUnit\Framework\Attributes\Test;

class BillOfMaterialModelTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_delete_bom_using_model_method()
    {
        $bomId = 'BOM' . mt_rand(1000, 9999);

        $id = DB::table('bill_of_material')->insertGetId([
            'bom_id' => $bomId,
            'bom_name' => 'Model Delete Test',
            'measurement_unit' => 1,
            'total_cost' => 1000,
            'active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $deleted = BillOfMaterialModel::deleteBom($id);

        $this->assertTrue($deleted);
        $this->assertDatabaseMissing('bill_of_material', ['id' => $id]);
    }

    #[Test]
    public function it_returns_false_if_bom_not_found()
    {
        $deleted = BillOfMaterialModel::deleteBom(999999); // ID fiktif
        $this->assertFalse($deleted);
    }
}

