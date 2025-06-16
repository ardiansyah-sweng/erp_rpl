<?php

namespace App\Models;

use Illuminate\Container\Attributes\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB as FacadesDB;

class AssortmentProduction extends Model
{
    protected $table;
    protected $fillable = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Tetapkan nama tabel dan kolom
        $this->table = config('db_constants.table.assort_prod');
        $this->fillable = array_values(config('db_constants.column.assort_prod') ?? []);
    }

    


    public static function getProductionDetail($production_number)
    {
        $header = self::where('production_number', $production_number)->first();
        if (!$header) {
            return response()->json(['message' => 'Production not found'], 404);
        }
        $details = FacadesDB::table('assortment_production_detail')
            ->where('production_number', $production_number)
            ->get();

        $result = [
            'header' => $header,
            'details' => $details,
        ];

        return response()->json($result);
    }
    public function getProduction()
    {
        return self::query()->from('assortment_production')->get();
    }
}
