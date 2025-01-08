<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\SupplierProduct;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use Faker\Factory as Faker;

class PurchaseOrderSeeder extends Seeder
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
        $prefix = 'PO';
        $numOfPurchaseOrder = $this->faker->numberBetween(5, 100);

        for ($i=1; $i <= $numOfPurchaseOrder; $i++)
        {
            
            $formattedNumber = str_pad($i, 4, '0', STR_PAD_LEFT);
            $po_number = $prefix.$formattedNumber;

            $supplierID = Supplier::pluck('supplier_id')->shuffle()[0];

            $supplierProduct = SupplierProduct::where('supplier_id', $supplierID)->pluck('product_id')->shuffle();
            $products = $supplierProduct->take($this->faker->numberBetween(1, $supplierProduct->count()));
            
            $total = 0;
            foreach ($products as $productID)
            {
                $quantity = $this->faker->numberBetween(1, 10000);
                $product = SupplierProduct::where('product_id', $productID)
                                            ->where('supplier_id', $supplierID)
                                            ->first();
                $amount = $product->base_price * $quantity;
                $total = $total + $amount;
                PurchaseOrderDetail::create([
                    'po_number'=>$po_number,
                    'product_id'=>$productID,
                    'quantity'=>$quantity,
                    'amount'=> $amount
                ]);
            }

            PurchaseOrder::create([
                'po_number'=>$po_number,
                'supplier_id'=>$supplierID,
                'total'=>$total
            ]);

        }

    }
}