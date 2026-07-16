<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Constants\ActivityLogColumns;

class ActivityLog extends Model
{
    use HasFactory;

    protected $table = 'activity_logs';

    protected $fillable = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->fillable = ActivityLogColumns::getFillable();
    }

    /**
     * Relasi ke User.
     */
    public function user()
    {
        return $this->belongsTo(User::class, ActivityLogColumns::USER_ID);
    }

    /**
     * Mencatat log aktivitas ke database.
     *
     * @param string $action Tipe aksi (create, update, delete)
     * @param string $module Modul yang diakses (branch, warehouse, dll)
     * @param string $description Deskripsi aktivitas
     * @param string|null $targetId ID record yang terpengaruh
     * @return ActivityLog
     */
    public static function logActivity(string $action, string $module, string $description, $targetId = null): self
    {
        return self::create([
            ActivityLogColumns::USER_ID => auth()->id(),
            ActivityLogColumns::USER_NAME => auth()->user()->name ?? 'System',
            ActivityLogColumns::ACTION => $action,
            ActivityLogColumns::MODULE => $module,
            ActivityLogColumns::DESCRIPTION => $description,
            ActivityLogColumns::TARGET_ID => $targetId,
            ActivityLogColumns::IP_ADDRESS => request()->ip(),
        ]);
    }

    /**
     * Ambil semua log dengan search, filter modul, filter aksi, dan pagination.
     *
     * @param string|null $search Keyword pencarian
     * @param string|null $module Filter berdasarkan modul
     * @param string|null $action Filter berdasarkan aksi
     * @param int $perPage Jumlah data per halaman
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function getAll(?string $search = null, ?string $module = null, ?string $action = null, int $perPage = 15)
    {
        $query = self::query();

        // Search berdasarkan deskripsi, nama user, atau target_id
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where(ActivityLogColumns::DESCRIPTION, 'LIKE', "%{$search}%")
                  ->orWhere(ActivityLogColumns::USER_NAME, 'LIKE', "%{$search}%")
                  ->orWhere(ActivityLogColumns::TARGET_ID, 'LIKE', "%{$search}%");
            });
        }

        // Filter berdasarkan modul
        if ($module) {
            $query->where(ActivityLogColumns::MODULE, $module);
        }

        // Filter berdasarkan aksi
        if ($action) {
            $query->where(ActivityLogColumns::ACTION, $action);
        }

        return $query->orderBy(ActivityLogColumns::CREATED_AT, 'desc')->paginate($perPage);
    }

    /**
     * Ambil log berdasarkan modul tertentu.
     *
     * @param string $module
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getByModule(string $module)
    {
        return self::where(ActivityLogColumns::MODULE, $module)
                    ->orderBy(ActivityLogColumns::CREATED_AT, 'desc')
                    ->get();
    }

    /**
     * Ambil log berdasarkan user tertentu.
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getByUser(int $userId)
    {
        return self::where(ActivityLogColumns::USER_ID, $userId)
                    ->orderBy(ActivityLogColumns::CREATED_AT, 'desc')
                    ->get();
    }

    /**
     * Hitung total log per modul (untuk statistik).
     *
     * @return \Illuminate\Support\Collection
     */
    public static function countByModule()
    {
        return self::selectRaw(ActivityLogColumns::MODULE . ', COUNT(*) as total')
                    ->groupBy(ActivityLogColumns::MODULE)
                    ->orderBy('total', 'desc')
                    ->get();
    }

    /**
     * Hitung total log per aksi (untuk statistik).
     *
     * @return \Illuminate\Support\Collection
     */
    public static function countByAction()
    {
        return self::selectRaw(ActivityLogColumns::ACTION . ', COUNT(*) as total')
                    ->groupBy(ActivityLogColumns::ACTION)
                    ->get();
    }
}
