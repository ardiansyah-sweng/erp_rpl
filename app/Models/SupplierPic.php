<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SupplierPic extends Model
{
    protected $table = 'supplier_pic';
    protected $fillable = ['supplier_id','name','phone_number'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Tetapkan nama tabel dan kolom
        $this->table = config('db_constants.table.supplier_pic');
        $this->fillable = array_values(config('db_constants.column.supplier_pic') ?? []);
    }

    public static function countSupplierPIC($supplier_id){
        // Menghitung jumlah PIC berdasarkan supplier_id, dengan pengelompokkan
        return self::select('supplier_id', DB::raw('COUNT(*) as jumlah'))
                    ->where('supplier_id', $supplier_id)
                    ->groupBy('supplier_id')
                    ->first(); // Kembalikan hasil pertama
    }
}