<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'supplier';
    protected $fillable = ['supplier_id', 'company_name', 'address', 'phone_number', 'bank_account'];
}