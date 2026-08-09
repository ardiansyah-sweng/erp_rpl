<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PurchaseOrderPayment extends Model
{
    protected $table;

    protected $fillable = [];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'integer',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config('db_constants.table.po_payment');
        $this->fillable = array_values(config('db_constants.column.po_payment') ?? []);
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'po_number', 'po_number');
    }

    public static function getPayments($search = null)
    {
        $query = self::with('purchaseOrder.supplier');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('po_number', 'LIKE', "%{$search}%")
                    ->orWhere('method', 'LIKE', "%{$search}%")
                    ->orWhere('note', 'LIKE', "%{$search}%");
            });
        }

        return $query->orderBy('payment_date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();
    }

    public static function getTotalPaid($poNumber)
    {
        return (int) self::where('po_number', $poNumber)->sum('amount');
    }

    public static function addPayment(array $data)
    {
        return DB::transaction(function () use ($data) {
            $po = PurchaseOrder::where('po_number', $data['po_number'])
                ->lockForUpdate()
                ->first();

            if (! $po) {
                throw ValidationException::withMessages([
                    'po_number' => 'Purchase Order tidak ditemukan.',
                ]);
            }

            if (Carbon::parse($data['payment_date'])->lt(Carbon::parse($po->order_date))) {
                throw ValidationException::withMessages([
                    'payment_date' => 'Tanggal pembayaran tidak boleh lebih awal dari tanggal order PO.',
                ]);
            }

            $alreadyPaid = self::where('po_number', $po->po_number)
                ->lockForUpdate()
                ->sum('amount');

            $remaining = $po->total - $alreadyPaid;

            if ($remaining <= 0) {
                throw ValidationException::withMessages([
                    'po_number' => 'Purchase Order ini sudah lunas.',
                ]);
            }

            if ($data['amount'] > $remaining) {
                throw ValidationException::withMessages([
                    'amount' => "Jumlah pembayaran melebihi sisa tagihan (maksimal {$remaining}).",
                ]);
            }

            return self::create([
                'po_number' => $po->po_number,
                'payment_date' => $data['payment_date'],
                'amount' => $data['amount'],
                'method' => $data['method'] ?? null,
                'note' => $data['note'] ?? null,
            ]);
        });
    }
}
