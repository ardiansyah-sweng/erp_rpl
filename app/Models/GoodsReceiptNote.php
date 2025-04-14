<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class GoodsReceiptNote extends Model
{
    protected $table;
    protected $fillable = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config('db_constants.table.grn');
        $this->fillable = array_values(config('db_constants.column.grn') ?? []);
    }

    /**
     * Add a new Goods Receipt Note
     *
     * @param array $data The goods receipt note data
     * @return int|bool Returns the inserted ID on success, false on failure
     */
    public function addGoodsReceiptNote(array $data)
    {
        try {
            // Begin transaction
            DB::beginTransaction();

            // Insert data into goods receipt note table
            $id = DB::table($this->table)->insertGetId($data);

            // Update purchase order status if needed
            if ($id && isset($data['purchase_order_id'])) {
                DB::table(config('db_constants.table.po'))
                    ->where('po_number', $data['purchase_order_id'])
                    ->update(['status' => 'received']);
            }

            // Commit transaction
            DB::commit();

            return $id;
        } catch (\Exception $e) {
            // Rollback transaction on error
            DB::rollBack();

            return false;
        }
    }
}

