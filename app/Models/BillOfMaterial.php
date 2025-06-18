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

    public static function getBillOfMaterial()
    {
        return self::orderBy('created_at', 'asc')->paginate(10);
    }
}
