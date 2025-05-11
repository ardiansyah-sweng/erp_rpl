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
    protected $casts = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $dbConfig = config('db_constants');
        $grnConfig = $dbConfig['column']['grn'] ?? [];

        $this->table = $dbConfig['table']['grn'] ?? 'goods_receipt_notes';
        $this->primaryKey = $grnConfig['grn_number'] ?? 'grn_number';

        $this->fillable = array_values($grnConfig);

        $detailsColumnKey = 'details_payload';
        if (isset($grnConfig[$detailsColumnKey])) {
            $this->casts[$grnConfig[$detailsColumnKey]] = 'array';
        }
        if (isset($grnConfig['receipt_date'])) {
            $this->casts[$grnConfig['receipt_date']] = 'datetime';
        }
    }

    public function purchaseOrder()
    {
         return $this->belongsTo(PurchaseOrder::class, config('db_constants.column.grn.po_number'), config('db_constants.column.po.po_number'));
    }

    public static function addGoodsReceiptNote($data)
    {
        $headerData = $data['header'];
        $itemDetails = $data['details'];
        $colGrn = config('db_constants.column.grn');

        $createData = [
            $colGrn['grn_number']   => $headerData['grn_number'],
            $colGrn['po_number']    => $headerData['po_number'],
            $colGrn['receipt_date'] => $headerData['receipt_date'],
            $colGrn['received_by']  => $headerData['received_by'],
            $colGrn['status']       => 'Pending',
        ];

        $detailsColumnKey = 'details_payload';
        if (isset($colGrn[$detailsColumnKey])) {
            $createData[$colGrn[$detailsColumnKey]] = $itemDetails;
        }

        $grn = self::create($createData);

        return $grn;
    }
    
}