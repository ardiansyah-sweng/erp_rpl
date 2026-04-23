<?php
namespace App\Models;

<<<<<<< HEAD
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
=======

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Constants\SupplierColumns;

class Supplier extends Model
{
    use HasFactory;
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
    /**
     * Ambil seluruh data supplier beserta frekuensi order (jumlah purchase_orders per supplier)
     * @return \Illuminate\Support\Collection
     */
    public static function getSupplier()
    {
<<<<<<< HEAD
        $supplierTable = config('db_constants.table.supplier');
=======
        // $supplierTable = config('db_constants.table.supplier');
        $model = new self;
        $supplierTable = $model->getTable();
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
        $poTable = config('db_constants.table.po');

        // Ambil semua kolom supplier + frekuensi order
        return self::query()
            ->leftJoin($poTable, $supplierTable . '.supplier_id', '=', $poTable . '.supplier_id')
            ->select(
                $supplierTable . '.*',
<<<<<<< HEAD
                \DB::raw('COUNT(' . $poTable . '.supplier_id) as order_frequency')
=======
                DB::raw('COUNT(' . $poTable . '.supplier_id) as order_frequency')
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
            )
            ->groupBy(
                $supplierTable . '.supplier_id',
                $supplierTable . '.company_name',
                $supplierTable . '.address',
<<<<<<< HEAD
                $supplierTable . '.phone_number',
=======
                $supplierTable . '.telephone',
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
                $supplierTable . '.bank_account',
                $supplierTable . '.created_at',
                $supplierTable . '.updated_at'
            )
            ->get();
    }
<<<<<<< HEAD
    protected $table = 'supplier';
    protected $fillable = ['supplier_id','company_name', 'address','phone_number','bank_account','created_at','updated_at'];
=======
    protected $table = null;
    protected $fillable = [];
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d

    protected $primaryKey = 'supplier_id';
    public $incrementing = false;
    protected $keyType = 'string';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

<<<<<<< HEAD
        $this->table = config('db_constants.table.supplier');
        $this->fillable = array_values(config('db_constants.column.supplier') ?? []);
=======
        // set table name from config and fillable from constant definitions
        $this->table = config('db_tables.supplier') ?? 'suppliers';
        $this->fillable = SupplierColumns::getFillable();
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
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