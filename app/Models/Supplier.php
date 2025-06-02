<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Supplier extends Model
{
    protected $table = 'supplier';
    protected $fillable = ['company_name', 'address', 'phone_number'];

    protected $primaryKey = 'supplier_id';
    public $incrementing = false;
    protected $keyType = 'string';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config('db_constants.table.supplier');
        $this->fillable = array_values(config('db_constants.column.supplier') ?? []);
    }

    public static function getSupplierById($id)
    {
        return DB::table(config('db_constants.table.supplier'))
            ->where('supplier_id', $id)
            ->first();
    }

    public static function updateSupplier($supplier_id, array $data)
    {
        $affected = DB::table(config('db_constants.table.supplier'))
            ->where('supplier_id', $supplier_id)
            ->update($data);

        if ($affected) {
            return (object) array_merge(['supplier_id' => $supplier_id], $data);
        }

        return null;
    }
}