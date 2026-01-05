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
        
        // Use config for table name like other models in the project
        $this->table = config('db_tables.merk');
    }

    /**
     * ACCESSORS & MUTATORS
     */
    
    /**
     * Get the status label attribute.
     */
    public function getStatusLabelAttribute(): string
    {
        return $this->is_active ? 'Aktif' : 'Tidak Aktif';
    }

    /**
     * Get the display name with emoji indicator.
     */
    public function getDisplayNameAttribute(): string
    {
        $emoji = $this->is_active ? '✅' : '❌';
        return "{$emoji} {$this->merk}";
    }

    /**
     * SCOPES
     */
    
    /**
     * Scope a query to only include active merk.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where(MerkColumns::IS_ACTIVE, true);
    }

    /**
     * Scope a query to only include inactive merk.
     */
    public function scopeInactive(Builder $query): Builder
    {
        return $query->where(MerkColumns::IS_ACTIVE, false);
    }

    /**
     * Scope for search functionality.
     */
    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (!$search) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($search) {
            $q->where(MerkColumns::MERK, 'LIKE', "%{$search}%");
        });
    }

    /**
     * STATIC METHODS - FOLLOWING BEST PRACTICES
     */

    /**
     * Get all merk with search functionality and pagination.
     * 
     * @param string|null $search
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function getAllMerk(?string $search = null)
    {
        return self::search($search)
                   ->orderBy(MerkColumns::CREATED_AT, 'desc')
                   ->paginate(config('pagination.merk_per_page', 15));
    }

    /**
     * Enhanced search with multiple filters for API endpoints.
     * 
     * @param array $filters
     * @return Builder
     */
    public static function searchWithFilters(array $filters): Builder
    {
        $query = self::query();

        // General search across name and description
        if (!empty($filters['search'])) {
            $query->search($filters['search']);
        }

        // Specific field filters
        if (!empty($filters['name'])) {
            $query->where(MerkColumns::NAME, 'LIKE', "%{$filters['name']}%");
        }

        if (!empty($filters['description'])) {
            $query->where(MerkColumns::DESCRIPTION, 'LIKE', "%{$filters['description']}%");
        }

        // Status filter
        if (isset($filters['is_active']) && $filters['is_active'] !== null) {
            $query->where(MerkColumns::IS_ACTIVE, $filters['is_active']);
        }

        // Dynamic sorting
        $sortBy = $filters['sort_by'] ?? MerkColumns::CREATED_AT;
        $sortOrder = strtolower($filters['sort_order'] ?? 'desc');
        
        // Validate sort order
        $sortOrder = in_array($sortOrder, ['asc', 'desc']) ? $sortOrder : 'desc';
        
        $query->orderBy($sortBy, $sortOrder);

        return $query;
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

    /**
     * Check if merk name is unique (for validation).
     * 
     * @param string $name
     * @param int|null $excludeId
     * @return bool
     */
    public static function isNameUnique(string $name, ?int $excludeId = null): bool
    {
        $query = self::where(MerkColumns::NAME, $name);
        
        if ($excludeId) {
            $query->where(MerkColumns::ID, '!=', $excludeId);
        }
        
        return !$query->exists();
    }

    /**
     * DEPRECATED METHODS - Keep for backward compatibility
     * These will be removed in future versions
     */

    // /**
    //  * @deprecated Use getAllMerk() instead
    //  */
    // public static function searchMerk($keyword)
    // {
    //     return self::getAllMerk($keyword);
    // }

    // /**
    //  * @deprecated Use direct Eloquent operations instead
    //  */
    // public static function deleteMerk($id)
    // {
    //     $merk = self::find($id);
    //     return $merk ? $merk->delete() : false;
    // }

    // /**
    //  * @deprecated Use direct Eloquent create() instead
    //  */
    // public static function addMerk($namaMerk, $active = 1)
    // {
    //     return self::create([
    //         MerkColumns::NAME => $namaMerk,
    //         MerkColumns::IS_ACTIVE => $active
    //     ]);
    // }

    // /**
    //  * @deprecated Use direct Eloquent operations instead
    //  */
    // public static function updateMerk($id, array $data)
    // {
    //     $merk = self::find($id);
    //     if (!$merk) {
    //         return null;
    //     }
        
    //     $merk->update($data);
    //     return $merk;
    // }

    // /**
    //  * @deprecated Use getStatistics()['total_merk'] instead
    //  */
     public static function countMerek()
     {
         return self::count();
     }

    // /**
    //  * @deprecated Use find() directly instead
    //  */
     public function getMerkById($id)
     {
         return self::find($id);
     }
}
