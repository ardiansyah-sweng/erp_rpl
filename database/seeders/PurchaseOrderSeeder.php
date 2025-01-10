<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\SupplierProduct;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use App\Models\LogBasePrice;
use App\Models\GoodsReceiptNote;
use Carbon\Carbon;
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

            $supplierID = Supplier::select('supplier_id')
                      ->distinct()
                      ->pluck('supplier_id')
                      ->shuffle()
                      ->first();
            
            #ambil produk
            $logBasePrice = LogBasePrice::select('product_id')
                                                ->distinct()
                                                ->where('supplier_id', $supplierID);
            $shuffledProduct = $logBasePrice->pluck('product_id')->shuffle()->take($this->faker->numberBetween(1, $logBasePrice->count()));
            
            $total = 0;
            foreach ($shuffledProduct as $productID)
            {
                $quantity = $this->faker->numberBetween(1, 500);

                #ambil acak new_base_price
                $basePrice = LogBasePrice::where('product_id', $productID)->where('supplier_id', $supplierID);
                $id = $basePrice->pluck('id')->shuffle()->take(1, $basePrice->count())->first();
                $product = $basePrice->where('id', $id)->first();

                $amount = $product->new_base_price * $quantity;
                $total = $total + $amount;
                $orderDate = Carbon::parse($product->created_at)->addDays(1)->format('Y-m-d H:i:s');

                PurchaseOrderDetail::create([
                    'po_number'=>$po_number,
                    'product_id'=>$product->product_id,
                    'quantity'=>$quantity,
                    'amount'=> $amount,
                    'created_at'=>$orderDate,
                    'updated_at'=>$orderDate
                ]);
            }
            PurchaseOrder::create([
                'po_number'=>$po_number,
                'supplier_id'=>$supplierID,
                'total'=>$total,
                'created_at'=>$orderDate,
                'updated_at'=>$orderDate
            ]);
        }

        #update current_stock product
        $products = Product::all();
        foreach ($products as $product)
        {
            $stock = PurchaseOrderDetail::where('product_id', $product->product_id)->sum('quantity');
            Product::where('product_id', $product->product_id)->update(['current_stock'=>$stock]);
        }

        #Goods Receipt Note
        $purchaseOrders = PurchaseOrder::all();
        foreach ($purchaseOrders as $purchaseOrder)
        {
            $daysCount = $this->faker->numberBetween(1, 30);
            $receivedDate = Carbon::parse($purchaseOrder->created_at)->addDays($daysCount)->format('Y-m-d H:i:s');

            #insert goods_receipt_note
            GoodsReceiptNote::create([
                'po_number'=>$purchaseOrder->po_number,
                'created_at'=>$receivedDate,
                'updated_at'=>$receivedDate
            ]);

            #update purchase_order_detail
            PurchaseOrderDetail::where('po_number', $purchaseOrder->po_number)->update(['received_days'=>$daysCount]);
        }
    }
    
}