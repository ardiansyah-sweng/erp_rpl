<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use App\Models\SupplierMaterial;

class GetSupplierMaterialByProductTypeTest extends TestCase
{
    /** @test */
    public function it_returns_data_for_existing_supplier_and_product_type()
    {
        // Ambil supplier_id & product_type yang ada di database
        $data = DB::table('supplier_product')
            ->join('products', 'supplier_product.product_id', '=', 'products.product_id')
            ->select('supplier_product.supplier_id', 'products.product_type')
            ->first();

        $this->assertNotNull($data, "Tidak ada data supplier dan product_type di database test");

        // Panggil method model langsung
        $results = SupplierMaterial::getSupplierMaterialByProductType(
            $data->supplier_id,
            $data->product_type
        );

        dump($results);

        $this->assertNotEmpty(
            $results,
            "Data tidak ditemukan untuk supplier_id={$data->supplier_id} dan product_type={$data->product_type}"
        );
    }

    /** @test */
    public function it_returns_empty_for_non_existing_supplier()
    {
        $results = SupplierMaterial::getSupplierMaterialByProductType('SUPXXX', 'FG');
        $this->assertEmpty($results, "Seharusnya tidak ada data untuk supplier_id=SUPXXX");
    }

    /** @test */
    public function it_returns_empty_for_invalid_product_type()
    {
        $results = SupplierMaterial::getSupplierMaterialByProductType('SUP001', 'INVALID_TYPE');
        $this->assertEmpty($results, "Seharusnya tidak ada data untuk product_type tidak valid");
    }
}
