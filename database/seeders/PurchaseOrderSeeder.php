<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Branch;
use App\Models\Item;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\SupplierProduct;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use App\Models\LogBasePrice;
use App\Models\LogStock;
use App\Models\GoodsReceiptNote;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PurchaseOrderSeeder extends Seeder
{
    public function __construct()
    {
        $this->faker = Faker::create('id_ID');
        $this->colPO = config('db_constants.column.po');
        $this->colPODetail = config('db_constants.column.po_detail');
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        # Membangkitkan PO dalam kurun Januari-Maret 2025
        
        # 1. Membangkitkan jumlah PO secara random
        $prefix = 'PO';
        $numOfPurchaseOrder = $this->faker->numberBetween(1, 100);

        for ($i=1; $i <= $numOfPurchaseOrder; $i++)
        {
            $branchID = Branch::getRandomBranchID();
            $formattedNumber = str_pad($i, 4, '0', STR_PAD_LEFT);
            $po_number = $prefix.$formattedNumber;

            $start_date = Carbon::parse('2025-01-01');
            $end_date = Carbon::parse('2025-02-28');
                                  
            # 2. Pilih satu supplier secara acak
            $supplierID = Supplier::select('supplier_id')
                      ->distinct()
                      ->pluck('supplier_id')
                      ->shuffle()
                      ->first();

            $orderDate = Carbon::parse($start_date->format('Y-m-d'))->addDays(rand(0, $start_date->diffInDays($end_date)))->format('Y-m-d');
            print_r($branchID.' '.$supplierID.' '.$orderDate);
            echo "\n";

            # 3. Mengambil raw material item secara random untuk diorder
            $rawMaterial = (new Product()) -> getSKURawMaterialItem();
            $numOfSKU = $this->faker->numberBetween(1, $rawMaterial->count());
            $shuffledRawMaterial = $rawMaterial -> shuffle();
            $selectedRawMaterial = $shuffledRawMaterial -> take($numOfSKU) -> unique();

            # 3. Membaca tiap raw material
            $total = 0;
            foreach ($selectedRawMaterial as $rawMaterial) {
                # 4. Masukkan tiap raw material ke tabel PO Detail
                $quantity = $this->faker->numberBetween(1, 500);
                
                # 5. Mendapatkan base price dari supplier
                $basePrice = LogBasePrice::where('supplier_id', $supplierID)
                    ->where('product_id', $rawMaterial)
                    ->latest('id')
                    ->first();
                
                if ($basePrice['new_base_price'] ?? false) {
                    $amount = $basePrice['new_base_price'] * $quantity;
                    $total = $total + $amount;
                    print_r($po_number .' '. $rawMaterial.' '. $quantity.' '.$basePrice['new_base_price'].' '. $amount);
                    echo "\n";
                    
                    PurchaseOrderDetail::create([
                        $this->colPODetail['po_number']=>$po_number,
                        $this->colPODetail['product_id']=>$rawMaterial,
                        $this->colPODetail['quantity']=>$quantity,
                        $this->colPODetail['amount']=>$amount,
                    ]);
                }
            }
            PurchaseOrder::create([
                $this->colPO['po_number']=>$po_number,
                $this->colPO['supplier_id']=>$supplierID,
                $this->colPO['total']=>$total,
                $this->colPO['branch_id']=>$branchID,
                $this->colPO['order_date']=>$orderDate
            ]);
        }

        $res = DB::table('purchase_order as po')
                        ->join('purchase_order_detail as pod', 'po.po_number', '=', 'pod.po_number')
                        ->select('po.order_date')
                        ->pluck('po.order_date');

        foreach ($res as $orderDate)
        {
            // // Ubah tanggal mulai ke format timestamp
            $startTimestamp = strtotime($orderDate);
            // // Ambil timestamp untuk hari ini
            $endTimestamp = time();
            
            //     // Pastikan startDate tidak lebih besar dari hari ini
            if ($startTimestamp > $endTimestamp) {
                throw new Exception("Tanggal mulai tidak boleh lebih besar dari hari ini.");
            }
            
            // Ambil timestamp acak antara startTimestamp dan endTimestamp
            $randomTimestamp = mt_rand($startTimestamp, $endTimestamp);
            
            // Format ke tanggal
            //print_r(date("Y-m-d", $randomTimestamp));

            // Hitung selisih hari antara startTimestamp dan randomTimestamp
            $differenceInDays = abs(($randomTimestamp - $startTimestamp) / 86400);

            print_r($startTimestamp.' '.date("Y-m-d", $randomTimestamp).' '.round($differenceInDays));
            echo "\n";
            
            // Contoh penggunaan:
            // $randomDate = getRandomDate("2023-01-01"); // Ambil tanggal acak dari 1 Januari 2023 sampai hari ini
            // echo $randomDate;
            
        }

        dd('done');

        for ($i=1; $i <= $numOfPurchaseOrder; $i++)
        {
            $formattedNumber = str_pad($i, 4, '0', STR_PAD_LEFT);
            $po_number = $prefix.$formattedNumber;

            foreach ($sku as $sku)
            {
                #ambil sejumlah supplier secara random untuk tiap item yang dipasok
                $numOfSupplier = $this->faker->numberBetween(1, Supplier::count());
            }

            $supplierID = Supplier::select('supplier_id')
                      ->distinct()
                      ->pluck('supplier_id')
                      ->shuffle()
                      ->first();
            
            #ambil produk dari Log Base Price
            $logBasePrice = LogBasePrice::select('product_id')
                                                ->distinct()
                                                ->where('supplier_id', $supplierID);
            $shuffledProduct = $logBasePrice->pluck('product_id')->shuffle()->take($this->faker->numberBetween(1, $logBasePrice->count()));
            
            $total = 0;

            #ambil seluruh created_at dari shuffledProduct, lalu urutkan dan ambil paling baru sebagai dasar PO date
            $ordateDates = [];
            foreach ($shuffledProduct as $productID)
            {
                #ambil acak new_base_price
                $basePrice = LogBasePrice::where('product_id', $productID)->where('supplier_id', $supplierID);
                $id = $basePrice->pluck('id')->shuffle()->take(1, $basePrice->count())->first();
                $product = $basePrice->where('id', $id)->first();

                $ordateDates[] = [$product->created_at, $product->new_base_price, $product->product_id];
            }

            usort($ordateDates, function ($a, $b) {
                return strtotime($b[0]) <=> strtotime($a[0]);
            });
            $orderDate = Carbon::parse($ordateDates[0][0])->addDay()->format('Y-m-d H:i:s');

            #mengisi purchase_order_detail
            for ($j=0; $j<count($ordateDates); $j++)
            {
                $quantity = $this->faker->numberBetween(1, 500);
                $basePrice = $ordateDates[$j][1];
                $product_id = $ordateDates[$j][2];

                $amount = $basePrice * $quantity;
                $total = $total + $amount;

                $currentStock = Product::where('product_id', $product_id)->get()->first();

                $purchaseOrderDetail = new PurchaseOrderDetail([
                    'po_number' => $po_number,
                    'product_id' => $product_id,
                    'quantity' => $quantity,
                    'amount' => $amount,
                    'updated_at' => $orderDate,
                    'created_at' => $orderDate,
                ]);
            
                // Explicitly set timestamps
                $purchaseOrderDetail->timestamps = false;
            
                // Save the model
                $purchaseOrderDetail->save();

                LogStock::create([
                    'log_id'=>$po_number,
                    'product_id'=>$product_id,
                    'old_stock'=>$currentStock['current_stock'],
                    'new_stock'=>$quantity + $currentStock['current_stock'],
                    'created_at'=>$orderDate, #untuk sementara, idealnya ambil di tanggal GRN
                    'updated_at'=>$orderDate  #untuk sementara, idealnya ambil di tanggal GRN
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