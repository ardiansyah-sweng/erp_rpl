<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierPic extends Model
{
    protected $table = '';
    protected $fillable = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Set nama tabel dan kolom dari config
        $this->table = config('db_constants.table.supplier_pic');
        $columns = config('db_constants.column.supplier_pic');
        $this->fillable = array_values(array_filter($columns, function ($key) {
            return !in_array($key, ['id', 'created_at', 'updated_at']);
        }, ARRAY_FILTER_USE_KEY));
    }

    public static function deleteSupplierPICByID($id)
    {
        $pic = self::find($id);
        if ($pic) {
            $pic->delete();
            return true;
        }
        return false;
    }

    // method untuk ambil data berdasarkan ID
    public static function getPICByID($id)
    {
        return self::find($id);
    }

    // relasi ke Supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public static function getSupplierPICAll($perPage = 10)
    {
        return self::paginate($perPage);
    }
}
