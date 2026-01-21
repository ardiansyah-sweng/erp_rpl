<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;
use App\Constants\PurchaseOrderDetailColumns;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PurchaseOrderDetail>
 */
class PurchaseOrderDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = Faker::create('id_ID');

        $quantity = $faker->numberBetween(1, 100);
        $basePrice = $faker->numberBetween(1000, 100000);
        $amount = $quantity * $basePrice;

        return [
            PurchaseOrderDetailColumns::PO_NUMBER     => strtoupper($faker->bothify('PO####')),
            PurchaseOrderDetailColumns::PRODUCT_ID    => strtoupper($faker->bothify('PROD-#####')),
            PurchaseOrderDetailColumns::BASE_PRICE    => $basePrice,
            PurchaseOrderDetailColumns::QUANTITY      => $quantity,
            PurchaseOrderDetailColumns::AMOUNT        => $amount,
            PurchaseOrderDetailColumns::RECEIVED_DAYS => $faker->numberBetween(0, 30),
        ];
    }
}
