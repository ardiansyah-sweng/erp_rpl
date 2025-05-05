<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierPic extends Model
{
    protected $table;
    protected $fillable = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Gunakan config hanya jika config tersedia
        $this->table = config('db_constants.table.supplier_pic', 'supplier_pic');
        $this->fillable = array_values(config('db_constants.column.supplier_pic', ['supplier_id', 'name', 'phone_number']));
    }
}
