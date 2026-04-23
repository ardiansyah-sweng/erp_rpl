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

    public static function deleteProduction($production_number)
    {
        $production = self::where('production_number', $production_number)->first();

        if (!$production) {
            return response()->json(['message' => 'Production not found'], 404);
        }

        if ($production->in_production != 0) {
            return response()->json(['message' => 'Cannot delete production that is in progress'], 403);
        }

        $production->delete();

        return response()->json(['message' => 'Production deleted successfully']);
    }


    public static function addProduction($data)
    {
        return self::create($data);
    }

    public static function updateProduction($id, array $data)
    {
        $production = self::find($id);
        if (!$production) {
            return null;
        }

        $production->update($data);
        return $production;
    }
    public static function countProduction()
    {
        $inProduction = self::where('in_production', 1)->count();
        $doneProduction = self::where('in_production', 0)->count();
        $totalProduction = self::count();

        return [
            'in_production' => $inProduction,
            'done_production' => $doneProduction,
            'total_production' => $totalProduction,
        ];
    }
}
