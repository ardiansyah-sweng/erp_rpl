<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Supplier extends Model
{
    protected $table;
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

    public static function deleteSupplier($id)
    {
        $supplier = static::where('supplier_id', $id)->first();

        if (!$supplier) {
            return false;
        }

        // Cek apakah ada purchase order terkait supplier ini
        $poExists = DB::table('purchase_order')
            ->where('supplier_id', $id)
            ->exists();

        if ($poExists) {
            return 'Supplier ini tidak bisa dihapus karena sudah memiliki purchase order';
        }

        // Hapus supplier secara permanen (pastikan tidak pakai SoftDeletes)
        try {
            $deleted = $supplier->delete();
            return $deleted; // true jika berhasil hapus, false jika gagal
        } catch (\Illuminate\Database\QueryException $e) {
            // Kalau error foreign key constraint atau error lain, kembalikan pesan error
            return 'Supplier tidak bisa dihapus karena terkait data lain di database.';
        }
    }


}
