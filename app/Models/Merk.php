<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Merk extends Model
{
    protected $table;
    protected $fillable = ['merk'];
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Tetapkan nama tabel dan kolom
        $this->table = config('db_constants.table.merk');
        $this->fillable = array_values(config('db_constants.column.merk') ?? []);
    }

    public static function updateMerk($id, array $data)
    {
        $merk = self::find($id);

        if (!$merk) {
            return null;
        }

         $fillable = (new self)->getFillable();
         $filteredData = collect($data)->only($fillable)->toArray();
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
}
