<?php

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

    public static function updateGoodsReceiptNote($po_number, array $data)
    {
        $grn = self::where('po_number', $po_number)->first();
        
        if (!$grn) {
            return null;
        }
        
        $fillable = (new self)->getFillable();
        $filteredData = array_intersect_key($data, array_flip($fillable));
        $grn->update($filteredData);
        
        return $grn;
    }
}
