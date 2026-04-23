<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\SupplierProduct;
use App\Models\PurchaseOrder;
use App\Models\GoodsReceiptNote;
use Carbon\Carbon;

class ProductPriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        #1. Ambil seluruh produk
        $products = Product::all();

        #2. Dapatkan seluruh PO berdasarkan produk tertentu, sort by date
        foreach ($products as $product)
        {
            $purchaseOrders = PurchaseOrder::join('purchase_order_detail as pod', 'purchase_order.po_number', '=', 'pod.po_number')
                ->where('pod.product_id', $product->product_id)
                ->select('purchase_order.*', 'pod.*')
                ->get();

            #2.1 Ambil created_at dari GRN dan supplier_id dari supplier
            $supplierGRN = [];
            foreach ($purchaseOrders as $po)
            {
                $poDetail = GoodsReceiptNote::where('po_number', $po->po_number)->first()->toArray();
                $supplierGRN[] = [$poDetail['created_at'], $po->supplier_id];
            }
            sort($supplierGRN);

            #2.2 Ambil base price dari supplier_product berdasarkan supplier_id
            $total = 0; $counter = 0;

            foreach($supplierGRN as $data)
            {
                $created_at = Carbon::parse($data[0])->format('Y-m-d H:i:s');
                $supplier_id = $data[1];

                $supplierProduct = SupplierProduct::where('supplier_id', $supplier_id)
                                                    ->where('product_id', $product->product_id)
                                                    ->first()->toArray();

                $total += $supplierProduct['base_price'];
                $counter++;
                $avg_base_price = $total/$counter;

                #2.2.1 Update base price pada product
                Product::where('product_id', $product->product_id)
                        ->update(['avg_base_price' => $avg_base_price, 'updated_at' => $created_at]);
            }
        }
    }
}
