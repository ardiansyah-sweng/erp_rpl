<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasDynamicColumns;

class Supplier extends Model
{
    use HasDynamicColumns;

    protected $table;
    protected $fillable = [];
    protected $guarded = [];


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Tetapkan nama tabel dan kolom
        $this->table = config('db_constants.table.supplier');
        $this->fillable = array_values(config('db_constants.column.supplier') ?? []);
    }

    public function getSupplierById($id)
    {
        return self::where('id', $id)->first();
    }

    public static function getRandomSupplierID()
    {
        return self::inRandomOrder()->first()->id;
    }

    public static function getAllSupplier($search = null)
    {
        $query = self::query();

        if ($search) {
            $query->where('company_name', 'LIKE', "%{$search}%")
                  ->orWhere('supplier_address', 'LIKE', "%{$search}%")
                  ->orWhere('supplier_telephone', 'LIKE', "%{$search}%")
                  ->orWhere('bank_account', 'LIKE', "%{$search}%");
        }

        return $query->orderBy('created_at', 'asc')->paginate(10);
    }

    public static function addSupplier($data)
    {
        return self::create($data);
    }
}