<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SupplierPic extends Model
{
    protected $table;
    protected $fillable = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        
        $this->table = config('db_constants.table.supplier_pic');
        $this->fillable = array_values(config('db_constants.column.supplier_pic') ?? []);
    }

    public static function countSupplierPIC($supplier_id)
    {
        return self::select('supplier_id',DB::raw('COUNT(*) as jumlahnya'))// Menghitung jumlah PIC berdasarkan supplier_id, dengan pengelompokkan
            ->where('supplier_id', $supplier_id)
            ->groupBy('supplier_id')
            ->first();
    }
    
    public static function getSupplierById($id)
    {
        return self::find($id);
    }

    public static function getUpdateSupplier($supplier_id, $data)
    {
        $supplier = self::find($supplier_id);
        if (!$supplier) return null;

        $supplier->update($data);
        return $supplier;
    }

    //t
    
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
}


