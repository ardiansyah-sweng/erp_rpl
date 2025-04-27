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
    $query = self::query()
        ->select('item.*', 'measurement_unit.unit_name') // Pilih kolom dari tabel items dan unit_name
        ->join('measurement_unit', 'item.measurement_unit', '=', 'measurement_unit.id'); // Join dengan tabel measurement_unit
    

    // Jika ada input pencarian, tambahkan kondisi pencarian
    if ($search) {
        // Cek apakah pencarian adalah angka dan gunakan '=' untuk ID
        if (is_numeric($search)) {
            $query->where('item.id', '=', $search);
        } else {
            // Jika bukan angka, gunakan LIKE untuk item_name dan sku
            $query->where(function($q) use ($search) {
                $q->where('item.item_name', 'LIKE', "%{$search}%")
                  ->orWhere('item.sku', 'LIKE', "%{$search}%");
            });
        }
    }

    return $query->orderBy('item.id', 'asc')->paginate(10);
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

    
}
