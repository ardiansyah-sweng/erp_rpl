<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table;
    protected $fillable = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Tetapkan nama tabel dan kolom
        $this->table = config('db_constants.table.supplier');
        $this->fillable = array_values(config('db_constants.column.supplier') ?? []);
    }

    public static function getUpdateSupplier($supplier_id, array $data)
    {
        $supplier = self::find($supplier_id);
        if (!$supplier) {
            return null;
        }

        $fillable = (new self)->getFillable();
        $filteredData = array_intersect_key($data, array_flip($fillable));
        $supplier->update($filteredData);

        return $supplier;
    }
    public function getSupplierById($id)
    {
        return self::where($this->getKeyName(), $id)->first();
    }
}