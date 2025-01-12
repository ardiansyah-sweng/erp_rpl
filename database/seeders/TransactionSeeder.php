<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\LogAvgBasePrice;
use App\Models\LogStock;

use Illuminate\Support\Facades\Http;
use Faker\Factory as Faker;
use Carbon\Carbon;


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
            $stock = Product::where('product_id', $transaction['product_id'])->get()->first();
            $created_at = Carbon::parse($transaction['created_at'])->format('Y-m-d H:i:s');
            $newStock = $stock['current_stock'] - $transaction['quantity'];
            LogStock::create([
                'log_id' => $transaction['transaction_id'],
                'product_id' => $transaction['product_id'],
                'old_stock' => $stock['current_stock'],
                'new_stock' => $newStock,
                'created_at' => $created_at
            ]);
            Product::where('product_id', $transaction['product_id'])
                        ->update(['current_stock' => $newStock]);

            echo $transaction['id'].' '.$transaction['transaction_id'].' '.$transaction['product_id'].' '.$transaction['price'].' '.$transaction['quantity'].' '.$transaction['amount'].' '.$transaction['total'].' '.$created_at."\n";

        }
    }
}
