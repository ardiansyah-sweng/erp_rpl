<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Constants\CustomerColumns;

class Customer extends Model
{
    use HasFactory;

    protected $table;
    protected $fillable = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Tetapkan nama tabel dan kolom
        $this->table = config('db_tables.customer');
        $this->fillable = CustomerColumns::getFillable();
    }

    public static function getCustomerAll($search = null, $status = null)
    {
        $query = self::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where(CustomerColumns::NAME, 'LIKE', "%{$search}%")
                  ->orWhere(CustomerColumns::ADDRESS, 'LIKE', "%{$search}%")
                  ->orWhere(CustomerColumns::PHONE, 'LIKE', "%{$search}%");
            });
        }

        if ($status === 'active') {
            $query->where(CustomerColumns::IS_ACTIVE, true);
        } elseif ($status === 'inactive') {
            $query->where(CustomerColumns::IS_ACTIVE, false);
        }

        return $query->orderBy(CustomerColumns::CREATED_AT, 'asc')->paginate(10);
    }

    public static function getCustomerById($id)
    {
        return self::find($id);
    }
}
