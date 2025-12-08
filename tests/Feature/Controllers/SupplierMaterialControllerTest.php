<?php 

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\SupplierMaterial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class SupplierMaterialControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function test_searchSupplierMaterial_with_valid_keyword()
    {
        SupplierMaterial::create([
            'supplier_id' => 'SUP001',
            'company_name' => 'PT Maju Jaya',
            'product_id' => 'PROD-001',
            'product_name' => 'Tepung Terigu',
            'base_price' => 50000,
        ]);

        SupplierMaterial::create([
            'supplier_id' => 'SUP002',
            'company_name' => 'CV Berkah',
            'product_id' => 'PROD-002',
            'product_name' => 'Gula Pasir',
            'base_price' => 15000,
        ]);

        $response = $this->get(route('supplier.material.search', ['keyword' => 'Tepung']));

        $response->assertStatus(200);
        $response->assertViewIs('supplier.material.list');
        $response->assertViewHas('materials');
        $response->assertSee('Tepung Terigu');
        $response->assertDontSee('Gula Pasir');
    }

    /**
     * @test
     */
    public function test_searchSupplierMaterial_with_empty_keyword()
    {
        SupplierMaterial::create([
            'supplier_id' => 'SUP001',
            'company_name' => 'PT Maju Jaya',
            'product_id' => 'PROD-001',
            'product_name' => 'Tepung Terigu',
            'base_price' => 50000,
        ]);

        SupplierMaterial::create([
            'supplier_id' => 'SUP002',
            'company_name' => 'CV Berkah',
            'product_id' => 'PROD-002',
            'product_name' => 'Gula Pasir',
            'base_price' => 15000,
        ]);

        $response = $this->get(route('supplier.material.search'));

        $response->assertStatus(200);
        $response->assertViewIs('supplier.material.list');
        $response->assertSee('Tepung Terigu');
        $response->assertSee('Gula Pasir');
    }

    /**
     * @test
     */
    public function test_searchSupplierMaterial_by_supplier_id()
    {
        SupplierMaterial::create([
            'supplier_id' => 'SUP001',
            'company_name' => 'PT Maju Jaya',
            'product_id' => 'PROD-001',
            'product_name' => 'Tepung Terigu',
            'base_price' => 50000,
        ]);

        SupplierMaterial::create([
            'supplier_id' => 'SUP002',
            'company_name' => 'CV Berkah',
            'product_id' => 'PROD-002',
            'product_name' => 'Gula Pasir',
            'base_price' => 15000,
        ]);

        $response = $this->get(route('supplier.material.search', ['keyword' => 'SUP001']));

        $response->assertStatus(200);
        $response->assertSee('SUP001');
        $response->assertSee('PT Maju Jaya');
        $response->assertDontSee('SUP002');
    }

    /**
     * @test
     */
    public function test_searchSupplierMaterial_by_company_name()
    {
        SupplierMaterial::create([
            'supplier_id' => 'SUP001',
            'company_name' => 'PT Maju Jaya',
            'product_id' => 'PROD-001',
            'product_name' => 'Tepung Terigu',
            'base_price' => 50000,
        ]);

        SupplierMaterial::create([
            'supplier_id' => 'SUP002',
            'company_name' => 'CV Berkah Sentosa',
            'product_id' => 'PROD-002',
            'product_name' => 'Gula Pasir',
            'base_price' => 15000,
        ]);

        $response = $this->get(route('supplier.material.search', ['keyword' => 'Berkah']));

        $response->assertStatus(200);
        $response->assertSee('CV Berkah Sentosa');
        $response->assertDontSee('PT Maju Jaya');
    }

    /**
     * @test
     */
    public function test_searchSupplierMaterial_by_product_id()
    {
        SupplierMaterial::create([
            'supplier_id' => 'SUP001',
            'company_name' => 'PT Maju Jaya',
            'product_id' => 'PROD-001',
            'product_name' => 'Tepung Terigu',
            'base_price' => 50000,
        ]);

        $response = $this->get(route('supplier.material.search', ['keyword' => 'PROD-001']));

        $response->assertStatus(200);
        $response->assertSee('PROD-001');
        $response->assertSee('Tepung Terigu');
    }

    /**
     * @test
     */
    public function test_searchSupplierMaterial_keyword_not_found()
    {
        SupplierMaterial::create([
            'supplier_id' => 'SUP001',
            'company_name' => 'PT Maju Jaya',
            'product_id' => 'PROD-001',
            'product_name' => 'Tepung Terigu',
            'base_price' => 50000,
        ]);

        $response = $this->get(route('supplier.material.search', ['keyword' => 'Tidak Ada']));

        $response->assertStatus(200);
        $response->assertDontSee('Tepung Terigu');
    }

    /**
     * @test
     */
    public function test_searchSupplierMaterial_with_partial_keyword()
    {
        SupplierMaterial::create([
            'supplier_id' => 'SUP001',
            'company_name' => 'PT Maju Jaya',
            'product_id' => 'PROD-001',
            'product_name' => 'Tepung Terigu Premium',
            'base_price' => 50000,
        ]);

        $response = $this->get(route('supplier.material.search', ['keyword' => 'Ter']));

        $response->assertStatus(200);
        $response->assertSee('Tepung Terigu Premium');
    }

    /**
     * @test
     */
    public function test_searchSupplierMaterial_pagination()
    {
        for ($i = 1; $i <= 15; $i++) {
            SupplierMaterial::create([
                'supplier_id' => 'SUP' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'company_name' => 'PT Supplier ' . $i,
                'product_id' => 'PROD-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'product_name' => 'Produk Test ' . $i,
                'base_price' => 10000 * $i,
            ]);
        }

        $response = $this->get(route('supplier.material.search', ['keyword' => 'Produk']));

        $response->assertStatus(200);

        $materials = $response->viewData('materials');
        $this->assertEquals(10, $materials->perPage());
        $this->assertEquals(15, $materials->total());
    }
}
