<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LogAvgBasePrice;

use Illuminate\Support\Facades\Http;
use Faker\Factory as Faker;

class TransactionSeeder extends Seeder
{
    public function __construct()
    {
        $this->faker = Faker::create('id_ID');
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $transactions = Http::get('http://127.0.0.1:8001/transactions')->json();

        foreach ($transactions as $transaction)
        {
            echo $transaction['id'].' '.$transaction['transaction_id'].' '.$transaction['product_id'].' '.$transaction['price'].' '.$transaction['quantity'].' '.$transaction['amount'].' '.$transaction['total'].' '.$transaction['created_at']."\n";
        }
    }
}
