<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierPic extends Model
{
    protected $table = 'supplier_pic';
    protected $fillable = [
        'id',
        'supplier_id',
        'name',
        'phone_number',
        'email',
        'assigned_date',
        'created_at',
        'updated_at'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Tetapkan nama tabel dan kolom
        $this->table = config('db_constants.table.supplier_pic');
        $this->fillable = array_values(config('db_constants.column.supplier_pic') ?? []);
    }

    public function addSupplierPIC(array $data)
    {
        return $this->create($data);
    }
}
