<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SupplierPic extends Model
{
    protected $table = 'supplier_pic'; // sesuaikan nama tabel
    protected $fillable = ['name', 'email', 'phone_number', 'supplier_id'];
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config('db_constants.table.supplier_pic');
        $this->fillable = array_values(config('db_constants.column.supplier_pic') ?? []);
    }

    // method untuk ambil data berdasarkan ID
    public static function getPICByID($id)
    {
        return self::find($id);
    }

    // relasi ke Supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public static function getSupplierPICAll($perPage = 10)
    {
        return self::paginate($perPage);
    }

    public static function addSupplierPIC($supplierID, $data)
    {
        $data['supplier_id'] = $supplierID;
        return self::create($data);
    }

    public static function assignmentDuration($pic)
    {
        if (!$pic->assigned_date) {
            return 'Tanggal penugasan tidak tersedia';
        }

        $startDate = Carbon::parse($pic->assigned_date);
        $now = Carbon::now();

        $diff = $startDate->diff($now);

        return json_encode([
            'years' => $diff->y,
            'months' => $diff->m,
            'days' => $diff->d,
        ]);
    }

    public static function deleteSupplierPIC($id)
    {
        $pic = self::find($id);
        if ($pic) {
            return $pic->delete();
        }
        return false;
    }

    public static function isDuplicatePIC($supplierID, $name, $email, $phone_number)
    {
        return self::where('supplier_id', $supplierID)
            ->where('name', $name)
            ->where('email', $email)
            ->where('phone_number', $phone_number)
            ->exists();
    }

    public function updateSupplierPICDetail(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'id'            => 'required|integer|exists:suppliers,id',
            'name'          => 'required|string|max:255',
            'phone_number'  => 'required|string|max:20',
            'email'         => 'required|email|unique:supplier_pics,email,' . $id,
            'assigned_date' => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors(),
            ]);
        }

        $result = SupplierPICModel::updateSupplierPIC($id, $data);

        return response()->json([
            'status'  => $result['status'],
            'message' => $result['message'],
            'data'    => $result['data'] ?? null,
        ], $result['code']);
    }
}
