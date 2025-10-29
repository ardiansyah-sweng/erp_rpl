<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\SupplierMaterial;
use App\Models\Supplier;
use App\Models\Product;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SupplierMaterial>
 */
class SupplierMaterialFactory extends Factory
{
    protected $model = SupplierMaterial::class;

    public function definition(): array
    {
        $supplier = Supplier::factory()->create();
        $product  = Product::factory()->create(['type' => 'RM']);

        return [
            'supplier_id'  => $supplier->supplier_id,
            'company_name' => $supplier->company_name,
            'product_id'   => $product->product_id . '-01',
            'product_name' => $product->name,
            'base_price'   => $this->faker->numberBetween(10000, 100000),
            'created_at'   => now(),
            'updated_at'   => now(),
        ];
    }
}
