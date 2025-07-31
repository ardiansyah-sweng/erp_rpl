<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillOfMaterial extends Model
{
    protected $table;
    protected $fillable = [
        'bom_id',
        'bom_name',
        'measurement_unit',
        'total_cost',
        'active',
        'created_at',
        'updated_at',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Tetapkan nama tabel dan kolom
        $this->table = config('db_constants.table.bom');
        $this->fillable = array_values(config('db_constants.column.bom') ?? []);
    }

    public static function addBillOfMaterial($data)
    {
        if (empty($data)) {
            throw new \Exception('Data tidak boleh kosong.');
        }
        return self::create($data);
    }

    public static function getBillOfMaterialById($id)
    {
        return self::where('bom_id',$id)->get();
    }

    public static function getBillOfMaterial()
    {
        return self::orderBy('created_at', 'asc')->paginate(10);
    }

    public static function SearchOfBillMaterial($keywords = null)
    {
        $query = self::query();

        if ($keywords) {
            $query->where('bom_id', 'LIKE', "%{$keywords}%")
                  ->orWhere('bom_name', 'LIKE', "%{$keywords}%")
                  ->orWhere('measurement_unit', 'LIKE', "%{$keywords}%")
                  ->orWhere('total_cost', 'LIKE', "%{$keywords}%")
                  ->orWhere('active', 'LIKE', "%{$keywords}%")
                  ->orWhere('created_at', 'LIKE', "%{$keywords}%")
                  ->orWhere('updated_at', 'LIKE', "%{$keywords}%");
        }

        return $query->orderBy('created_at', 'asc')->paginate(10);
    }

    public static function updateBillOfMaterial($bom_id, array $data)//Sudah sesuai pada ERP RPL
    {
        $bom = self::find($bom_id);
        if (!$bom) {
            return null;
        }
        $bom->update($data);

        return $bom;
    }

    // Accessor untuk mengkonversi measurement_unit dari angka ke text == convert
    public function getMeasurementUnitTextAttribute()
    {
        $measurementUnits = [
            31 => 'Pcs', // Default untuk pieces
            32 => 'Kg',
            33 => 'Liter', 
            34 => 'Meter',
            35 => 'Box',
            36 => 'Karton',
            37 => 'Dus',
            38 => 'Gram',
            39 => 'Ton',
            40 => 'Kwintal'
        ];

        return $measurementUnits[$this->measurement_unit] ?? 'Unit';
    }

}
