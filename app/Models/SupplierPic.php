<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SupplierPic extends Model
{
    protected $table;
    protected $fillable = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('db_constants.table.supplier_pic');
        $this->fillable = array_values(config('db_constants.column.supplier_pic') ?? []);
    }

    /**
     * Ambil data PIC berdasarkan ID + relasi supplier
     */
    public static function getPICByID($id)
    {
        return self::with('supplier')->find($id); // relasi langsung tersedia
    }

    /**
     * Ambil semua data PIC dengan paginasi
     */
    public static function getSupplierPICAll($perPage = 10)
    {
        return self::paginate($perPage);
    }

    /**
     * Tambah data PIC untuk supplier tertentu
     */
    public static function addSupplierPIC($supplierID, $data)
    {
        $data['supplier_id'] = $supplierID;
        return self::create($data);
    }

    /**
     * Hitung lama penugasan dari tanggal penugasan hingga sekarang
     */
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

    /**
     * Hitung jumlah PIC berdasarkan supplier_id
     */
    //
    public static function countSupplierPIC($supplier_id)
    {
        return self::select('supplier_id', DB::raw('COUNT(*) as jumlahnya'))
            ->where('supplier_id', $supplier_id)
            ->groupBy('supplier_id')
            ->first();
    }
    
    /**
     * Relasi ke tabel Supplier
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public static function isDuplicatePIC($supplierID, $name, $email, $phone_number)
    {
        return self::where('supplier_id', $supplierID)
            ->where('name', $name)
            ->where('email', $email)
            ->where('phone_number', $phone_number)
            ->exists();
    }
    ///
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
