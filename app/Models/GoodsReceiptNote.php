<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class GoodsReceiptNote extends Model
{
    protected $table;
    protected $fillable = [];
    protected $primaryKey;
    public $incrementing = false;
    protected $keyType = 'string';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config('db_constants.table.grn');
        $this->primaryKey = config('db_constants.column.grn.grn_number');
        $this->fillable = array_values(config('db_constants.column.grn') ?? []);
    }

    public function details()
    {
        return $this->hasMany(GoodsReceiptNoteDetail::class, config('db_constants.column.grn_detail.grn_number'), $this->primaryKey);
    }

    public function purchaseOrder()
    {
         return $this->belongsTo(PurchaseOrder::class, config('db_constants.column.grn.po_number'), config('db_constants.column.po.po_number'));
    }

    public static function generateGrnNumber(): string
    {
         $datePart = Carbon::now()->format('Ymd');
         $randomPart = strtoupper(Str::random(4));
         return 'GRN-' . $datePart . '-' . $randomPart;
    }

    public static function addGoodsReceiptNote($data)
    {
        $headerData = $data['header'];
        $itemDetails = $data['details'];
        $colGrn = config('db_constants.column.grn');
        $colGrnDetail = config('db_constants.column.grn_detail');

        $grnNumber = self::generateGrnNumber();

        $grn = self::create([
            $colGrn['grn_number'] => $grnNumber,
            $colGrn['po_number'] => $headerData['po_number'],
            $colGrn['receipt_date'] => $headerData['receipt_date'],
            $colGrn['received_by'] => $headerData['received_by'],
            $colGrn['status'] => 'Pending',
        ]);

        foreach ($itemDetails as $item) {
            GoodsReceiptNoteDetail::create([
                $colGrnDetail['grn_number'] => $grnNumber,
                $colGrnDetail['product_id'] => $item['product_id'],
                $colGrnDetail['quantity_received'] => $item['quantity_received'],
                $colGrnDetail['notes'] => $item['notes'] ?? null,
            ]);
        }

        return $grn;

    }
}