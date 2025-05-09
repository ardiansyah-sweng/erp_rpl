<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Supplier extends Model
{
    protected $table;
    protected $fillable = ['company_name', 'address','phone_number'];

    protected $primaryKey = 'supplier_id';
    public $incrementing = false;
    protected $keyType = 'string';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config('db_constants.table.supplier');
        $this->fillable = array_values(config('db_constants.column.supplier') ?? []);
    }

    public static function getUpdateSupplier($supplier_id, array $data)
    {
        $supplier = self::find($supplier_id);
        if (!$supplier) {
            return null;
        }

        $fillable = (new self)->getFillable();
        $filteredData = array_intersect_key($data, array_flip($fillable));
        $supplier->update($filteredData);

        return $supplier;
    }
    public function getSupplierById($id)
    {
        return self::where($this->getKeyName(), $id)->first();
    }


    public static function deleteSupplier($id)
    {
        $supplier = static::where('supplier_id', $id)->first();

        if (!$supplier) {
            return false;
        }

    
        $hasPurchaseOrders = DB::table('purchase_order')->where('supplier_id', $id)->exists();

        if ($hasPurchaseOrders) {
        
            return 'Supplier ini tidak bisa dihapus karena sudah memiliki purchase order';
        }

    
        return $supplier->delete();
    }


}
