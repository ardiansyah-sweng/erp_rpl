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

    public static function updateSupplierPIC($id, $data)
    {
        try {
            $supplierPic = self::find($id);

            if (!$supplierPic) {
                return [
                    'status' => 'error',
                    'message' => 'Supplier PIC tidak ditemukan.',
                    'code' => 404
                ];
            }

            $updated = $supplierPic->update($data);
            return $updated
                ? [
                    'status' => 'success',
                    'message' => 'Supplier PIC berhasil diperbarui.',
                    'data' => $supplierPic,
                    'code' => 200
                ]
                : [
                    'status' => 'error',
                    'message' => 'Gagal memperbarui Supplier PIC.',
                    'code' => 500
                ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Exception: ' . $e->getMessage(),
                'code' => 500
            ];
        }
    }
    
    public static function countSupplierPIC($supplier_id)
    {
        return self::select('supplier_id', DB::raw('COUNT(*) as jumlahnya'))
            ->where('supplier_id', $supplier_id)
            ->groupBy('supplier_id')
            ->first();
    }
    
    public static function countPICByStatus($supplier_id)   
    {
    $data = self::select('active', \DB::raw('COUNT(*) as total'))
        ->where('supplier_id', $supplier_id)
        ->groupBy('active')
        ->get();

    $active = 0;
    $inactive = 0;

    foreach ($data as $row) {
        if ((int) $row->active === 1) {
            $active = $row->total;
        } elseif ((int) $row->active === 0) {
            $inactive = $row->total;
        }
    }

    return [
        'active' => $active,
        'inactive' => $inactive,
        'total' => $active + $inactive,
    ];
    }
}
