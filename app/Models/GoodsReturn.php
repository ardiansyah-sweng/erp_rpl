<?php

namespace App\Models;

use App\Constants\ItemColumns;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class GoodsReturn extends Model
{
    protected $table;

    protected $fillable = [];

    protected $casts = [
        'return_date' => 'date',
        'return_quantity' => 'integer',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config('db_constants.table.goods_return', 'goods_returns');
        $this->fillable = array_values(config('db_constants.column.goods_return', [
            'grn_id',
            'po_number',
            'product_id',
            'return_date',
            'return_quantity',
            'reason',
        ]));
    }

    public function goodsReceiptNote()
    {
        return $this->belongsTo(GoodsReceiptNote::class, 'grn_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'product_id', ItemColumns::SKU);
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'po_number', 'po_number');
    }

    public static function getGoodsReturns($search = null)
    {
        $query = self::with(['item', 'purchaseOrder.supplier']);

        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('po_number', 'LIKE', "%{$search}%")
                    ->orWhere('product_id', 'LIKE', "%{$search}%")
                    ->orWhere('reason', 'LIKE', "%{$search}%")
                    ->orWhereHas('item', function ($itemQuery) use ($search) {
                        $itemQuery->where(ItemColumns::NAME, 'LIKE', "%{$search}%");
                    });
            });
        }

        return $query->orderBy('return_date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();
    }

    public static function addGoodsReturn(array $data)
    {
        return DB::transaction(function () use ($data) {
            $grn = GoodsReceiptNote::lockForUpdate()->findOrFail($data['grn_id']);

            if (Carbon::parse($data['return_date'])->lt(Carbon::parse($grn->delivery_date))) {
                throw ValidationException::withMessages([
                    'return_date' => 'Tanggal return tidak boleh lebih awal dari tanggal penerimaan barang.',
                ]);
            }

            $returnedQuantity = self::where('grn_id', $grn->id)
                ->lockForUpdate()
                ->get()
                ->sum('return_quantity');

            $remainingQuantity = $grn->delivered_quantity - $returnedQuantity;
            $item = Item::where(ItemColumns::SKU, $grn->product_id)
                ->lockForUpdate()
                ->first();

            if (! $item) {
                throw ValidationException::withMessages([
                    'grn_id' => 'Item dari Goods Receipt Note ini tidak ditemukan.',
                ]);
            }

            $availableQuantity = min($remainingQuantity, $item->{ItemColumns::STOCK_UNIT});

            if ($availableQuantity < 1) {
                throw ValidationException::withMessages([
                    'grn_id' => 'Barang pada Goods Receipt Note ini sudah tidak dapat direturn.',
                ]);
            }

            if ($data['return_quantity'] > $availableQuantity) {
                throw ValidationException::withMessages([
                    'return_quantity' => "Jumlah return maksimal {$availableQuantity} unit.",
                ]);
            }

            $goodsReturn = self::create([
                'grn_id' => $grn->id,
                'po_number' => $grn->po_number,
                'product_id' => $grn->product_id,
                'return_date' => $data['return_date'],
                'return_quantity' => $data['return_quantity'],
                'reason' => $data['reason'],
            ]);

            $oldStock = $item->{ItemColumns::STOCK_UNIT};
            $newStock = $oldStock - $data['return_quantity'];
            $item->{ItemColumns::STOCK_UNIT} = $newStock;
            $item->save();

            DB::table(config('db_constants.table.log_matory'))->insert([
                'log_id' => 'RETURN#'.$goodsReturn->id,
                'sku' => $grn->product_id,
                'old_stock' => $oldStock,
                'new_stock' => $newStock,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return $goodsReturn;
        });
    }

    public function getReturnNumberAttribute(): string
    {
        return 'RTN-'.str_pad((string) $this->id, 6, '0', STR_PAD_LEFT);
    }
}
