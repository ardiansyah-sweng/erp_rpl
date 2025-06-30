<?php
//andika
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    // Accessor agar field pada response API konsisten dengan test
    public function getReceiptDateAttribute()
    {
        return $this->attributes[config('db_constants.column.grn.date')] ?? null;
    }

    public function getNoteAttribute()
    {
        return $this->attributes[config('db_constants.column.grn.comments')] ?? null;
    }

    public static function getGoodsReceiptNote($po_number)
    {
        return self::where(config('db_constants.column.grn.po_number'), $po_number)->first();
    }

    public static function updateGoodsReceiptNote($po_number, array $data)
    {
        $grn = self::getGoodsReceiptNote($po_number);

        if (!$grn) {
            return null;
        }

        $fillable = (new self)->getFillable();
        $filteredData = array_intersect_key($data, array_flip($fillable));
        $grn->update($filteredData);

        return $grn;
    }
}
