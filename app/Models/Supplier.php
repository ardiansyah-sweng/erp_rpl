<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
{
    use HasFactory;
    protected $table = 'supplier';
    protected $fillable = ['supplier_id','company_name', 'address','phone_number','bank_account','created_at','updated_at'];

    protected $primaryKey = 'supplier_id';
    public $incrementing = false;
    protected $keyType = 'string';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config('db_constants.table.supplier');
        $this->fillable = array_values(config('db_constants.column.supplier') ?? []);
    }

    public static function updateSupplier($supplier_id, array $data)//Sudah sesuai pada ERP RPL
    {
        $supplier = self::find($supplier_id);
        if (!$supplier) {
            return null;
        }
        $supplier->update($data);

        return $supplier;
    }
    public function getSupplierById($id)
    {
        return self::where($this->getKeyName(), $id)->first();
    }
    public static function countSupplier(){
        return self::count();   
    }

    public static function addSupplier($data)
    {
        return self::create($data);
    }
    //relasi ke order
    public function orders()
    {
    return $this->hasMany(PurchaseOrder::class, 'supplier_id', 'supplier_id');
    }

    //mendapat getsupplier
    public static function getSupplier()
    {
    return self::withCount('orders')->get();
    }

    public static function getSupplierByKeywords($keywords = null)
    {
            $query = self::query();

            if (!empty($keywords)) {
                $query->where('company_name', 'like', "%{$keywords}%");
            }

            return $query->get();
    }

    public static function deleteSupplier($id)
    {
        $supplier = self::find($id);

        if (!$supplier) {
            return ['success' => false, 'message' => 'Supplier tidak ditemukan.'];
        }

        $supplier->delete();

        return ['success' => true, 'message' => 'Supplier berhasil dihapus.'];
    }
}