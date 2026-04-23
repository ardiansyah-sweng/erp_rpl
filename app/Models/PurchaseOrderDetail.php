<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
<<<<<<< HEAD

class PurchaseOrderDetail extends Model
{
    protected $table = 'purchase_order_detail';
    protected $fillable = ['po_number','product_id','quantity','amount','created_at','updated_at'];
=======
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseOrderDetail extends Model
{
    use HasFactory;
    
    protected $table = 'purchase_order_detail';
    protected $fillable = ['po_number','product_id','base_price','quantity','amount','received_days','created_at','updated_at'];
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
}