<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SupplierPic extends Model
{
    protected $table = 'supplier_pic';
    protected $fillable = ['supplier_id','name','phone_number'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        
        $this->table = config('db_constants.table.supplier_pic');
        $this->fillable = array_values(config('db_constants.column.supplier_pic') ?? []);
    }

    public static function addSupplierPIC($supplierID, $data)
    {
        $data['supplier_id'] = $supplierID;
        return self::create($data);
    }

}
