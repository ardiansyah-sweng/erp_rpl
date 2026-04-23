<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\GoodsReceiptNote;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;

use Faker\Factory as Faker;

class GoodsReceiptNoteSeeder extends Seeder
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
        $tablePO = config('db_constants.table.po');
        $tablePOD = config('db_constants.table.po_detail');
        $colPO = config('db_constants.column.po');
        $colPOD = config('db_constants.column.po_detail');
        $colGRN = config('db_constants.column.grn');
        
        $purchaseOrders = PurchaseOrder::join($tablePOD.' as pod', $tablePO.'.'.$colPO['po_number'], '=', 'pod.'.$colPOD['po_number'])
            ->select($tablePO.'.*', 'pod.*')
            ->get();
            
        foreach ($purchaseOrders as $po)
        {
            $startTimestamp = strtotime($po->order_date);
            $endTimestamp = time();
            if ($startTimestamp > $endTimestamp) {
                    throw new Exception("Tanggal mulai tidak boleh lebih besar dari hari ini.");
            }
            $randomTimestamp = mt_rand($startTimestamp, $endTimestamp);
            $receivedDays = round(abs(($randomTimestamp - $startTimestamp) / 86400));
            $receivedDate = date('Y-m-d H:i:s', $randomTimestamp);

            GoodsReceiptNote::create([
                $colGRN['po_number'] => $po->po_number,
                $colGRN['product_id'] => $po->product_id,
                $colGRN['date'] => $receivedDate,
                $colGRN['qty'] => $this->faker->numberBetween(1, $po->quantity),
                $colGRN['comments'] => $this->faker->sentence,
                $colGRN['created_at'] => $receivedDate,
                $colGRN['updated_at'] => $receivedDate
            ]);

            PurchaseOrderDetail::where($colPOD['po_number'], $po->po_number)
                                    ->where($colPOD['product_id'], $po->product_id)
                                    ->update(['received_days'=>$receivedDays]);

            print_r($po->product_id.' '. $receivedDays . "\n");
        }
    }
}
