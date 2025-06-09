<?php

namespace Tests\Feature;

use App\Models\GoodsReceiptNote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Faker\Factory as Faker;


class GRN_Test extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_grnModelUpdate(): void
    {
        $faker = Faker::create();
        $grn = GoodsReceiptNote::inRandomOrder()->first();

        dump($grn);
        $newData = [
            'delivery_date' => $faker->date(),
            'delivered_quantity' => $faker->numberBetween(1, 100),
            'comments' => $faker->sentence()
        ];
        $updatedgrn = GoodsReceiptNote::updateGoodsReceiptNote($grn->id, $newData);
    }
    public function test_GrnControllerUpdate(): void
    {
        $faker = Faker::create(); // inisiasi faker agar data yang dihasilkan acak bukan statis
        $grn = GoodsReceiptNote::inRandomOrder()->first(); // pastikan pakai model, bukan controller
        dump($grn);

        $UpdateDelivery_date = $faker->date();
        $updateDelivered_qty = $faker->numberBetween(1, 100);
        $updateComments = $faker->sentence();

        $newData = [
            'delivery_date' => $UpdateDelivery_date,
            'delivered_quantity' => $updateDelivered_qty,
            'comments' => $updateComments
        ];

        $response = $this->put('/grn/update/' . $grn->id, $newData);
    }
}
