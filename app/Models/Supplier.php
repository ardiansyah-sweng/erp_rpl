<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Constants\SupplierColumns;

class Supplier extends Model
{
    use HasFactory;
    /**
     * Ambil seluruh data supplier beserta frekuensi order (jumlah purchase_orders per supplier)
     * @return \Illuminate\Support\Collection
     */
    public static function getSupplier()
    {
        // $supplierTable = config('db_constants.table.supplier');
        $model = new self;
        $supplierTable = $model->getTable();
        $poTable = config('db_constants.table.po');

        // Ambil semua kolom supplier + frekuensi order
        return self::query()
            ->leftJoin($poTable, $supplierTable . '.supplier_id', '=', $poTable . '.supplier_id')
            ->select(
                $supplierTable . '.*',
                DB::raw('COUNT(' . $poTable . '.supplier_id) as order_frequency')
            )
            ->groupBy(
                $supplierTable . '.supplier_id',
                $supplierTable . '.company_name',
                $supplierTable . '.address',
                $supplierTable . '.telephone',
                $supplierTable . '.bank_account',
                $supplierTable . '.created_at',
                $supplierTable . '.updated_at'
            )
            ->get();
    }
    protected $table = null;
    protected $fillable = [];

    protected $primaryKey = 'supplier_id';
    public $incrementing = false;
    protected $keyType = 'string';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // set table name from config and fillable from constant definitions
        $this->table = config('db_tables.supplier') ?? 'suppliers';
        $this->fillable = SupplierColumns::getFillable();
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