<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Constants\MerkColumns;

/**
 * Merk Model
 *
 * Represents a brand/merk in the system with comprehensive CRUD operations
 * and advanced querying capabilities following Laravel best practices.
 */
class Merk extends Model
{
    use HasFactory;

    protected $table;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'merk',
        'is_active'
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [];

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = ['status_label', 'display_name'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Tetapkan nama tabel dan kolom
        $this->table = config('db_constants.table.merk');
        $this->fillable = array_values(config('db_constants.column.merk') ?? []);
    }

    public static function updateMerk($id, array $data)
    {
        $merk = self::find($id);

        if (!$merk) {
            return null;
        }

         $fillable = (new self)->getFillable();
         $filteredData = collect($data)->only($fillable)->toArray();
         $merk->update($filteredData);

         return $merk;
    }

    /**
     * Get only active merk for dropdown/selection purposes.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getActiveMerk()
    {
        return self::active()
                   ->orderBy(MerkColumns::NAME, 'asc')
                   ->get([MerkColumns::ID, MerkColumns::NAME]);
    }

    /**
     * Get comprehensive statistics about merk.
     *
     * @return array
     */
    public static function getStatistics(): array
    {
        $total = self::count();
        $active = self::active()->count();

        return [
            'total_merk' => $total,
            'active_merk' => $active,
            'inactive_merk' => $total - $active,
            'percentage_active' => $total > 0 ? round(($active / $total) * 100, 2) : 0,
        ];
    }

     public static function getAllMerk()
    {
        return self::orderBy('created_at', 'asc')->paginate(10);
    }
    public static function searchMerk($keyword)
    {
        return self::getAllMerk($keyword);
    }

    /**
     * @deprecated Use direct Eloquent operations instead
     */
    public static function deleteMerk($id)
    {
        $merk = self::find($id);

        if ($merk) {
            return $merk->delete();
        }

        return false;
    }
    public static function addMerk($namaMerk, $active = 1)
    {
        $merk = new self();
        $merk->merk = $namaMerk;
        $merk->is_active = $active;
        $merk->save();

        return $merk;
    }

    /**
     * @deprecated Use getStatistics()['total_merk'] instead
     */
    public static function countMerek()
    {
        return self::count();
    }

    /**
     * @deprecated Use find() directly instead
     */
    public function getMerkById($id)
    {
        return self::find($id);
    }
}
