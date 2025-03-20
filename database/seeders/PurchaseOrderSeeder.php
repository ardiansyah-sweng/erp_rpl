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
                        $this->colPODetail['amount']=>$amount
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
    }
}