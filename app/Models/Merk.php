<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Merk extends Model
{
    protected $table;
    protected $fillable = ['merk'];
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config('db_constants.table.merk') ?? 'merks';
        
        $configFillable = config('db_constants.column.merk');
        if ($configFillable) {
            $this->fillable = array_values($configFillable);
        }
    }

    // TUGAS 214
    public static function updateMerk($id, array $data)
    {
        // Cari data berdasarkan ID
        $merk = self::find($id);

        // Jika tidak ditemukan, kembalikan null
        if (!$merk) {
            return null;
        }

        // Filter data agar hanya kolom yang diizinkan yang diupdate
        $fillable = (new self)->getFillable();
        $filteredData = collect($data)->only($fillable)->toArray();
        
        // Lakukan update
        $merk->update($filteredData);

        return $merk;
    }

    public static function countMerek()
    {
        return self::count();
    }

    public function getMerkById($id)
    {
        return self::where('id', $id)->first();
    }

    public static function getAllMerk()
    {
        return self::orderBy('created_at', 'asc')->paginate(10);
    }

    public static function searchMerk($keyword)
    {
        return self::where('merk', 'like', '%' . $keyword . '%')
                ->orderBy('created_at', 'asc')
                ->paginate(10);
    }

    public static function deleteMerk($id)
    {
        $merk = self::find($id);

        if ($merk) {
            return $merk->delete();
        }

        return false;
    }

    public static function addMerk($namaMerk, $active = 1)
    {
        $merk = new self();

        $merk->fill(['merk' => $namaMerk]); 
        
        $merk->is_active = $active;
        
        $merk->save();

        return $merk;
    }
}