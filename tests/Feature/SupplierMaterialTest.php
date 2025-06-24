<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\SupplierMaterial;
use PHPUnit\Framework\Attributes\Test;

class SupplierMaterialTest extends TestCase
{
    #[Test]
    public function get_by_kategory_only()
    {
        $data = SupplierMaterial::getSupplierMaterialByCategory('P001-qui');
        dump($data); // gunakan dump agar test tetap lanjut
        $this->assertNotEmpty($data); // pastikan data tidak kosong
    }

    #[Test]
    public function get_by_supplier_only()
    {
        $data = SupplierMaterial::getSupplierMaterialByCategory(null, 'Nutriplex Pack qui');
        dump($data);
        $this->assertNotEmpty($data);
    }

    #[Test]
    public function get_by_both()
    {
        $data = SupplierMaterial::getSupplierMaterialByCategory('P001-qui', 'Nutriplex Pack qui');
        dump($data);
        $this->assertNotEmpty($data);
    }
}
