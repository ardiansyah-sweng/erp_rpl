<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasDynamicColumns;

class SupplierPic extends Model
{
    use HasDynamicColumns;
    
    protected $table = 'supplier_pic';
    protected $fillable = ['supplier_id','name','phone_number','email','assigned_date'];

}
