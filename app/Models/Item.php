<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table;
    protected $fillable = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config('db_constants.table.item');
        $this->fillable = array_values(config('db_constants.column.item') ?? []);
    }

    public function getItem()
    {
        return self::all();
    }

    // public static function deleteItemById($id)
    // {
    //     return self::destroy($id);
    // }

    
    public static function getAllItems($search = null)
{
    $query = self::with('unit');

    // Jika ada input pencarian, tambahkan kondisi pencarian
    if ($search) {
        // Cek apakah pencarian adalah angka dan gunakan '=' untuk ID
        if (is_numeric($search)) {
            $query->where('id', '=', $search);
        } else {
            // Jika bukan angka, gunakan LIKE untuk item_name dan sku
            $query->where(function($q) use ($search) {
                $q->where('item_name', 'LIKE', "%{$search}%")
                  ->orWhere('sku', 'LIKE', "%{$search}%");
            });
        }
    }

    return $query->orderBy('id', 'asc')->paginate(10);
}

    public static function deleteItemById($id)
    {
        // Cari item berdasarkan ID
        $item = self::find($id);

        // Jika item ditemukan, hapus dan kembalikan true
        if ($item) {
            // Hapus item
            $item->delete();

            
            self::where('id', '>', $id)->decrement('id');

            return true;
        }

        // Jika item tidak ditemukan, kembalikan false
        return false;
    }
     public function addItem($data)
    {
        return self::create($data);
    }

    public function unit()
    {
        return $this->belongsTo(MeasurementUnit::class, 'measurement_unit', 'id');
    }

    

    
}
