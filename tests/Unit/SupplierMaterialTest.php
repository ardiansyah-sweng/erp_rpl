<?php

namespace Tests\Unit;

use App\Models\SupplierMaterial;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SupplierMaterialTest extends TestCase
{
    public function testAddSupplierMaterialCreatesSupplierMaterial()
    {
        config(['db_constants.table.supplier' => 'supplier_product']);
        config(['db_constants.column.supplier' => [
            'supplier_id',
            'company_name',
            'product_id',
            'product_name',
            'base_price'
        ]]);

        $data = [
            'supplier_id' => 'SUP090',
            'company_name' => 'Tes Company',
            'product_id' => 'P001-aut',
            'product_name' => 'Oblong Tes',
            'base_price' => '93849'
        ];

        $result = SupplierMaterial::addSupplierMaterial($data);

        $this->assertInstanceOf(SupplierMaterial::class, $result);
        $this->assertEquals($data['product_name'], $result->product_name);
        $this->assertEquals($data['product_id'], $result->product_id);
    }

    public function testAddSupplierMaterialHandlesEmptyData()
    {
        config(['db_constants.table.supplier' => 'supplier_product']);
        config(['db_constants.column.supplier' => [
            'supplier_id',
            'company_name',
            'product_id',
            'product_name',
            'base_price'
        ]]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Data tidak boleh kosong.');

        SupplierMaterial::addSupplierMaterial([]);
    }

    public function testAddBasePriceifdatavalid()
    {
        $model = new SupplierMaterial();
        $data = [
            'supplier_id'   => 'SUP123',
            'company_name'  => 'PT Sejahtera',
            'product_id'    => 'PROD-XYZ',
            'product_name'  => 'Material Uji Coba',
            'base_price'    => 150000,
        ];

        $result = $model->addBasePrice($data);

        $this->assertInstanceOf(SupplierMaterial::class, $result);
        $this->assertEquals('SUP123', $result->supplier_id);
        $this->assertEquals(150000, $result->base_price);
        $this->assertDatabaseHas('supplier_product', [
            'product_id' => 'PROD-XYZ',
            'company_name' => 'PT Sejahtera'
        ]);
    }

    public function testAddBasePriceifdataempty()
    {
        $model = new SupplierMaterial();

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Data untuk menambah harga dasar tidak boleh kosong.');

        $model->addBasePrice([]);
    }
}
