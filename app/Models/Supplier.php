<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Supplier extends Model
{
    protected $table;
    protected $fillable = ['supplier_id','company_name', 'address','phone_number','bank_account','created_at','updated_at'];

    protected $primaryKey = 'supplier_id';
    public $incrementing = false;
    protected $keyType = 'string';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config('db_constants.table.supplier');
        $this->fillable = array_values(config('db_constants.column.supplier') ?? []);
    }

    public static function updateSupplier($supplier_id, array $data)//Sudah sesuai pada ERP RPL
    {
        $supplier = self::find($supplier_id);
        if (!$supplier) {
            return null;
        }
        $supplier->update($data);

        return $supplier;
    }
    public function getSupplierById($id)
    {
        return self::where($this->getKeyName(), $id)->first();
    }
    public static function countSupplier(){
        return self::count();   
    }

    
    public static function deleteSupplier($id)
    {
        $supplier = static::where('supplier_id', $id)->first();

        if (!$supplier) {
            return false;
        }

        return $supplier->delete();
    }

}
