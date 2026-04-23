<?php

namespace App\Models;

<<<<<<< HEAD
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
=======
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d

class SupplierPic extends Model
{
    protected $table = 'supplier_pic'; // sesuaikan nama tabel
<<<<<<< HEAD
    protected $fillable = ['name', 'email', 'phone_number', 'supplier_id'];
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
=======

    protected $fillable = ['name', 'email', 'phone_number', 'supplier_id'];

    protected $primaryKey = 'id';

    public $incrementing = true;

    protected $keyType = 'int';
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d

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
<<<<<<< HEAD
=======

>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
        return self::create($data);
    }

    public static function assignmentDuration($pic)
    {
<<<<<<< HEAD
        if (!$pic->assigned_date) {
=======
        if (! $pic->assigned_date) {
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
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
<<<<<<< HEAD
=======

>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
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

<<<<<<< HEAD
            if (!$supplierPic) {
                return [
                    'status' => 'error',
                    'message' => 'Supplier PIC tidak ditemukan.',
                    'code' => 404
=======
            if (! $supplierPic) {
                return [
                    'status' => 'error',
                    'message' => 'Supplier PIC tidak ditemukan.',
                    'code' => 404,
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
                ];
            }

            $updated = $supplierPic->update($data);
<<<<<<< HEAD
=======

>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
            return $updated
                ? [
                    'status' => 'success',
                    'message' => 'Supplier PIC berhasil diperbarui.',
                    'data' => $supplierPic,
<<<<<<< HEAD
                    'code' => 200
=======
                    'code' => 200,
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
                ]
                : [
                    'status' => 'error',
                    'message' => 'Gagal memperbarui Supplier PIC.',
<<<<<<< HEAD
                    'code' => 500
=======
                    'code' => 500,
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
                ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
<<<<<<< HEAD
                'message' => 'Exception: ' . $e->getMessage(),
                'code' => 500
=======
                'message' => 'Exception: '.$e->getMessage(),
                'code' => 500,
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
            ];
        }
    }

    public static function searchSupplierPic($keywords = null)
    {
        // Eager load relasi 'supplier' untuk akses company_name
        $query = self::with('supplier');

        if ($keywords) {
            $query->where('supplier_id', 'LIKE', "%{$keywords}%")
<<<<<<< HEAD
                  ->orWhere('name', 'LIKE', "%{$keywords}%")
                  ->orWhere('phone_number', 'LIKE', "%{$keywords}%")
                  ->orWhere('email', 'LIKE', "%{$keywords}%")
                  ->orWhere('assigned_date', 'LIKE', "%{$keywords}%")
                  ->orWhere('created_at', 'LIKE', "%{$keywords}%")
                  ->orWhere('updated_at', 'LIKE', "%{$keywords}%");
=======
                ->orWhere('name', 'LIKE', "%{$keywords}%")
                ->orWhere('phone_number', 'LIKE', "%{$keywords}%")
                ->orWhere('email', 'LIKE', "%{$keywords}%")
                ->orWhere('assigned_date', 'LIKE', "%{$keywords}%")
                ->orWhere('created_at', 'LIKE', "%{$keywords}%")
                ->orWhere('updated_at', 'LIKE', "%{$keywords}%");
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
        }

        return $query->orderBy('created_at', 'asc')->paginate(10);
    }
<<<<<<< HEAD
    
=======

>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
    public static function getSupplierPIC($supplierID)
    {
        return self::where('supplier_id', $supplierID)->get();
    }
<<<<<<< HEAD
    
=======

>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
    public static function countSupplierPIC($supplierID, $onlyActive = null)
    {
        $query = self::where('supplier_id', $supplierID);

<<<<<<< HEAD
        if (!is_null($onlyActive)) {
=======
        if (! is_null($onlyActive)) {
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
            $query->where('active', $onlyActive ? 1 : 0);
        }

        return $query->count();
    }
}
