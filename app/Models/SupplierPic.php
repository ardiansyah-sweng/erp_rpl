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

        // Ambil dari config
        $this->table = config('db_constants.table.supplier_pic', 'supplier_pic');
        $this->fillable = array_values(config('db_constants.column.supplier_pic') ?? ['supplier_id','name','phone_number']);
    }

    /**
     * Menghitung jumlah PIC berdasarkan supplier_id
     *
     * @param int $supplier_id
     * @return int
     */
    public static function countSupplierPIC($supplier_id)
    {
        return self::where('supplier_id', $supplier_id)->count();
    }

}
