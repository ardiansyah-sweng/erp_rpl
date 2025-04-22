<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    // method untuk ambil data berdasarkan ID 
    public static function getPICByID($id)
    {
        return self::find($id);
    }

    // tidak error kalau data tidak ditemukan
    public static function getPICOrFailByID($id)
    {
        return self::findOrFail($id);
    }
}
