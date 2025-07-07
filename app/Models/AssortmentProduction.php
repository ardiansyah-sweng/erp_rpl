<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public static function SearchOfAssortmentProduction($keywords = null)
    {
        $query = self::query();

        if ($keywords) {
            $query->where('id', 'LIKE', "%{$keywords}%")
                  ->orWhere('in_production', 'LIKE', "%{$keywords}%")
                  ->orWhere('production_number', 'LIKE', "%{$keywords}%")
                  ->orWhere('sku', 'LIKE', "%{$keywords}%")
                  ->orWhere('branch_id', 'LIKE', "%{$keywords}%")
                  ->orWhere('rm_whouse_id', 'LIKE', "%{$keywords}%")
                  ->orWhere('fg_whouse_id', 'LIKE', "%{$keywords}%")
                  ->orWhere('production_date', 'LIKE', "%{$keywords}%")
                  ->orWhere('finished_date', 'LIKE', "%{$keywords}%")
                  ->orWhere('description', 'LIKE', "%{$keywords}%")
                  ->orWhere('created_at', 'LIKE', "%{$keywords}%");
        }

        return $query->orderBy('created_at', 'asc')->paginate(10);
    }

}


