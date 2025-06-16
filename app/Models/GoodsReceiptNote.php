<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsReceiptNote extends Model
{
    protected $table = 'grn';
    protected $fillable = ['id', 'po_number', 'product_id', 'delivery_date', 'delivered_quantity', 'comments', 'created_at', 'updated_at'];

    protected $primaryKey = 'id';
    public $incrementing = false;


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config('db_constants.table.grn');
        $this->fillable = array_values(config('db_constants.column.grn') ?? []);
    }
<<<<<<< 2200018237_andika
    public static function updateGoodsReceiptNote($id, array $data) //
    {
        $grn = self::find($id);
        if (!$grn) {
            return null;
        }
        $grn->update($data);

=======

    public static function getGoodsReceiptNote($po_number)
    {
        return self::where('po_number', $po_number)->first();
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
        
>>>>>>> development
        return $grn;
    }
}
