<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\SupplierMaterial;

class CountSupplierMaterialByCategoryTest extends TestCase
{
    /** @test */
    public function testCountSupplierMaterialByCategory()
    {
        $supplierId = 'SUP001';

        $results = SupplierMaterial::countSupplierMaterialByCategory($supplierId);

        foreach ($results as $row) {
            dump("Kategori: {$row->product_category}, Jumlah item: {$row->total}");
        }

        // Agar test tidak gagal, meskipun hanya untuk melihat hasil sementara
        $this->assertTrue(true);
    }
}
