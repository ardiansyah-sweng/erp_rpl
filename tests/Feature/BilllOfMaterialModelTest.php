<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\BillOfMaterialModel;

class BilllOfMaterialModelTest extends TestCase
{
    // Tidak menggunakan RefreshDatabase agar data asli tidak di-reset
    public function test_count_bill_of_material()
    {
        // Menghitung jumlah data langsung dari query builder
        $expectedCount = BillOfMaterialModel::query()->count();

        // Membandingkan dengan hasil dari method yang diuji
        $this->assertEquals($expectedCount, BillOfMaterialModel::countBillOfMaterial());
    }
}
