<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use App\Models\SupplierMaterial;

class GetSupplierMaterialByProductTypeTest extends TestCase
{
    protected $faker;
    protected $testProductId;
    protected $supplierId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->faker = Faker::create();

        //  Gunakan ID maksimal 6 karakter
        $this->testProductId = 'RM' . rand(10, 99); // contoh: RM45
        $this->supplierId = rand(1000, 9999);

        DB::table('products')->insert([
            'product_id' => $this->testProductId,
            'product_name' => $this->faker->word,
            'product_type' => 'RM',
            'product_category' => $this->faker->numberBetween(1, 9),
            'product_description' => $this->faker->sentence, 
        ]);

        DB::table('item')->insert([
            'product_id'       => $this->testProductId,
            'item_name'        => $this->faker->word,
            'measurement_unit' => 'kg',
            'stock_unit'       => $this->faker->numberBetween(1, 100),
            'sku'              => $this->faker->unique()->bothify('SKU-####'), 
        ]);


        DB::table('supplier_product')->insert([
            'supplier_id'   => $this->supplierId, 
            'company_name'  => $this->faker->company,
            'product_id'    => $this->testProductId,
            'product_name'  => $this->faker->words(2, true),
            'base_price'    => $this->faker->numberBetween(1000, 10000),
        ]);


    }

    protected function tearDown(): void
    {
        DB::table('supplier_product')->where('product_id', $this->testProductId)->delete();
        DB::table('item')->where('product_id', $this->testProductId)->delete();
        DB::table('products')->where('product_id', $this->testProductId)->delete();

        parent::tearDown();
    }

    public function test_get_data_with_valid_product_type()
    {
        $model = new SupplierMaterial();
        $results = $model->getSupplierMaterialByProductType($this->supplierId, 'RM');

        $this->assertNotEmpty($results);
        $this->assertEquals($this->testProductId, $results[0]->product_id);
        $this->assertEquals($this->supplierId, $results[0]->supplier_id);
    }

    public function test_get_data_with_invalid_product_type_returns_empty()
    {
        $model = new SupplierMaterial();
        $results = $model->getSupplierMaterialByProductType($this->supplierId, 'INVALID');

        $this->assertTrue($results->isEmpty());
    }
}
