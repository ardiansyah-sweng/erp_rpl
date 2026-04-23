<?php

namespace Tests\Feature;
use Tests\TestCase;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasDynamicColumns;
use Illuminate\Support\Facades\DB;
use App\Models\Item;
use App\Models\Category; 
use App\Enums\ProductType;
use App\Models\Product;
use Faker\Factory as Faker; // Import Library Faker untuk menghasilkan data acak

class UpdateProduct_Test extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_modelUpdateProduct(): void
    {
        $faker = Faker::create(); // Inisiasi faker
        $product = Product::inRandomOrder()->first();

        dump($product);

        $newData = [
            'product_name'        => $faker->word,
            'product_type'        => strtoupper($faker->lexify(str_repeat('?', rand(2, 3)))),
            'product_category'    => $faker->numberBetween(1, 50),
            'product_description' => $faker->sentence,
        ];

        $updateProduct = Product::updateProduct($product->id, $newData);

    }

    // Make Controller Test UpdateProductTest

    public function test_controllerUpdateProduct(): void
    {
        $faker = Faker::create(); // Inisiasi faker
        $product = Product::inRandomOrder()->first();

        dump($product);

        $updateName        = $faker->word;
        $updateType        = strtoupper($faker->lexify(str_repeat('?', rand(2, 3))));
        $updateCategory    = $faker->numberBetween(1, 50);
        $updateDescription = $faker->sentence;

        $newData = [
            'product_name'        => $updateName,
            'product_type'        => $updateType,
            'product_category'    => $updateCategory,
            'product_description' => $updateDescription,
        ];

        $response = $this->put('/product/update/' . $product->id, $newData);

    }
}