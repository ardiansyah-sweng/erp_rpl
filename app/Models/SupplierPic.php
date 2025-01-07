<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierPic extends Model
{
    protected $table = 'supplier_pic';
    protected $fillable = ['supplier_id','name','phone_number','email','assigned_date'];

}
