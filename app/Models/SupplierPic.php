<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupplierPic extends Model
{
    protected $table;
    protected $fillable = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Atur nama tabel dan kolom fillable dari config
        $this->table = config('db_constants.table.supplier_pic');
        $this->fillable = array_values(config('db_constants.column.supplier_pic') ?? []);
    }

    /**
     * Relasi ke Supplier
     */
    public function supplier()
{
    return $this->belongsTo(Supplier::class, config('db_constants.column.supplier_pic.supplier_id'), config('db_constants.column.supplier.supplier_id'));
}

}
