<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
        return self::where('bom_id', $id)->get();
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

    public static function updateBillOfMaterial($bom_id, array $data) //Sudah sesuai pada ERP RPL
    {
        $bom = self::find($bom_id);
        if (!$bom) {
            return null;
        }
        $bom->update($data);

        return $bom;
    }
    public static function getBomDetail($id)
    {
        $bom = self::where('id', $id)->first();

        if (!$bom) {
            return null;
        }

        // Ambil detail dari tabel bom_detail
        $details = DB::table('bom_detail')
            ->where('bom_id', $bom->bom_id)
            ->select('id', 'bom_id', 'sku', 'quantity', 'cost', 'created_at', 'updated_at')
            ->get();

        // Gabungkan data utama dan detail
        return [
            'id'               => $bom->id,
            'bom_id'           => $bom->bom_id,
            'bom_name'         => $bom->bom_name,
            'measurement_unit' => $bom->measurement_unit,
            'total_cost'       => $bom->total_cost,
            'active'           => $bom->active,
            'created_at'       => $bom->created_at,
            'updated_at'       => $bom->updated_at,
            'details'          => $details,
        ];
    }
  
     public static function countItemInBom($bomId)
    {
        $bomDetailTable = config('db_constants.table.bom_detail', 'bom_detail');

        return \DB::table($bomDetailTable)
              ->where('bom_id', $bomId)
              ->count();
    }

    public function items()
    {
        return $this->hasMany(BillOfMaterialDetail::class, 'bom_id', 'bom_id');
    }

}
