<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use App\Models\SupplierMaterial;

class SupplierMaterialSearchTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_search_supplier_material_by_keyword()
    {
        DB::table('supplier_product')->insert([
            [
                'supplier_id' => 'S01',
                'company_name' => 'PT Maju Jaya',
                'product_id' => 'P01',
                'product_name' => 'Besi Baja',
                'base_price' => 0,
            ],
            [
                'supplier_id' => 'S02',
                'company_name' => 'CV Makmur',
                'product_id' => 'P02',
                'product_name' => 'Aluminium Sheet',
                'base_price' => 0,
            ],
        ]);

        $result = SupplierMaterial::searchSupplierMaterial('Maju');

        $this->assertCount(1, $result);
        $this->assertEquals('PT Maju Jaya', $result->first()->company_name);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_returns_empty_when_no_match_found()
    {
        DB::table('supplier_product')->insert([
            [
                'supplier_id' => 'S03',
                'company_name' => 'CV Sejahtera',
                'product_id' => 'P03',
                'product_name' => 'Tembaga Lembaran',
                'base_price' => 0,
            ]
        ]);

        $result = SupplierMaterial::searchSupplierMaterial('Plastik');

        $this->assertCount(0, $result);
    }
}