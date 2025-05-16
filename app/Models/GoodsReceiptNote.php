<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GoodsReceiptNote extends Model
{
    protected $table = 'goods_receipt_notes';

    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'po_number',
        'product_id',
        'delivery_date',
        'delivered_quantity',
        'comments',
    ];

    protected $casts = [
        'delivery_date' => 'datetime',
    ];

    public function purchaseOrder()
    {
         return $this->belongsTo(PurchaseOrder::class, 'po_number', config('db_constants.column.po.po_number', 'po_number'));
    }

    /**
     * Creates Goods Receipt Note items from validated header and detail data.
     * Manages its own database transaction.
     *
     * @param array $headerData Validated header data (po_number, delivery_date)
     * @param array $itemDetailsArray Array of validated item details
     * @return bool True on success, throws exception on failure
     * @throws \Exception If creation fails
     */
    public static function createGrnWithItems(array $headerData, array $itemDetailsArray): bool
    {
        DB::beginTransaction();
        try {
            foreach ($itemDetailsArray as $itemDetail) {
                $createData = [
                    'po_number'          => $headerData['po_number'],
                    'delivery_date'      => $headerData['delivery_date'],
                    'product_id'         => $itemDetail['product_id'],
                    'delivered_quantity' => $itemDetail['delivered_quantity'],
                    'comments'           => $itemDetail['comments'] ?? null,
                ];
                self::create($createData);
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Model GRN Creation Failed: ' . $e->getMessage() . ' Trace: ' . $e->getTraceAsString());
            throw $e;
        }
    }
}