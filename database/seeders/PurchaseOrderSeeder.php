<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Branch;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use App\Models\LogBasePrice;
use Carbon\Carbon;
use Faker\Factory as Faker;
use App\Enums\POStatus;

class PurchaseOrderSeeder extends Seeder
{
    protected $faker;
    protected $colPO;
    protected $colPODetail;

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
        // Membangkitkan PO dalam kurun Januari-Maret 2025
        
        // 1. Membangkitkan jumlah PO secara random
        $prefix = 'PO';
        $numOfPurchaseOrder = $this->faker->numberBetween(1, 100);

        for ($i = 1; $i <= $numOfPurchaseOrder; $i++) {
            $branchID = Branch::getRandomBranchID();
            $formattedNumber = str_pad($i, 4, '0', STR_PAD_LEFT);
            $po_number = $prefix . $formattedNumber;

            $start_date = Carbon::parse('2025-01-01');
            $end_date = Carbon::parse('2025-03-15');
                                  
            // 2. Pilih satu supplier secara acak
            $supplierID = Supplier::select('supplier_id')
                ->distinct()
                ->pluck('supplier_id')
                ->shuffle()
                ->first();

            $orderDate = Carbon::parse($start_date->format('Y-m-d'))
                ->addDays(rand(0, $start_date->diffInDays($end_date)))
                ->format('Y-m-d');
            print_r($branchID . ' ' . $supplierID . ' ' . $orderDate);
            echo "\n";

            // 3. Mengambil raw material item secara random untuk diorder
            $rawMaterial = (new Product())->getSKURawMaterialItem();
            $numOfSKU = $this->faker->numberBetween(1, $rawMaterial->count());
<<<<<<< HEAD
            $shuffledRawMaterial = $rawMaterial->shuffle();
            $selectedRawMaterial = $shuffledRawMaterial->take($numOfSKU)->unique();
=======
            $selectedRawMaterial = $rawMaterial -> inRandomOrder()
                                    ->limit($this->faker->numberBetween(1, $numOfSKU))
                                    ->get();
>>>>>>> a1a8828c1f44d5d8170c2a312c97589135088883

            // 3. Membaca tiap raw material
            $total = 0;

            foreach ($selectedRawMaterial as $rawMaterial) {
                // 4. Masukkan tiap raw material ke tabel PO Detail
                $quantity = $this->faker->numberBetween(1, 500);
                
                // 5. Mendapatkan base price dari supplier
                $basePrice = LogBasePrice::where('supplier_id', $supplierID)
                    ->where('product_id', $rawMaterial->sku)
                    ->latest('id')
                    ->first();
                
                if ($basePrice['new_base_price'] ?? false) {
                    $amount = $basePrice['new_base_price'] * $quantity;
                    $total += $amount;

<<<<<<< HEAD
                    print_r($po_number . ' ' . $rawMaterial . ' ' . $quantity . ' ' . $basePrice['new_base_price'] . ' ' . $amount);
                    echo "\n";
                    
                    PurchaseOrderDetail::create([
                        $this->colPODetail['po_number'] => $po_number,
                        $this->colPODetail['product_id'] => $rawMaterial,
                        $this->colPODetail['quantity'] => $quantity,
                        $this->colPODetail['amount'] => $amount
=======
                    print_r($po_number .' '. $rawMaterial->sku.' '. $quantity.' '.$basePrice['new_base_price'].' '. $amount);
                    echo "\n";
                    
                    PurchaseOrderDetail::create([
                        $this->colPODetail['po_number']=>$po_number,
                        $this->colPODetail['product_id']=>$rawMaterial->sku,
                        $this->colPODetail['quantity']=>$quantity,
                        $this->colPODetail['amount']=>$amount
>>>>>>> a1a8828c1f44d5d8170c2a312c97589135088883
                    ]);
                }
            }
            print_r('Total ',$total);
            echo "\n";

            PurchaseOrder::create([
                $this->colPO['po_number'] => $po_number,
                $this->colPO['supplier_id'] => $supplierID,
                $this->colPO['total'] => $total,
                $this->colPO['branch_id'] => $branchID,
                $this->colPO['order_date'] => $orderDate,
                $this->colPO['status'] => $this->faker->randomElement(POStatus::cases())->value
            ]);
        }
    }
}