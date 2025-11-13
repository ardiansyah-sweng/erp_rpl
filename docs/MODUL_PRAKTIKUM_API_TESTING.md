# PRAKTIKUM 03: API TESTING BRANCH MANAGEMENT
## Testing REST API Endpoints dengan Unified Controller Architecture

### **Informasi Modul**
- **Mata Kuliah**: Rekayasa Perangkat Lunak / Software Testing
- **Topik**: API Testing - Branch Management REST API
- **Framework**: Laravel 11 dengan PHPUnit
- **Durasi**: 90 menit
- **Level**: Intermediate
- **Focus**: Unified Controller Architecture & API Testing Best Practices

---

## **DAFTAR ISI**

1. [**Tujuan Pembelajaran**](#tujuan-pembelajaran)
2. [**Pendahuluan**](#pendahuluan)
3. [**Setup Project**](#setup-project)
4. [**Unified Controller Architecture**](#unified-controller-architecture)
5. [**API Testing Implementation**](#api-testing-implementation)
6. [**Running Tests**](#running-tests)
7. [**Best Practices**](#best-practices)
8. [**Troubleshooting**](#troubleshooting)
9. [**Kesimpulan**](#kesimpulan)

---

## **TUJUAN PEMBELAJARAN**

Setelah menyelesaikan praktikum API testing ini, mahasiswa diharapkan mampu:

1. **Memahami Unified Controller Architecture** untuk web dan API endpoints
2. **Mengimplementasikan comprehensive API testing** dengan Laravel HTTP Testing
3. **Menerapkan Model-based query logic** sesuai best practices Laravel
4. **Menguji API Resources dan Collections** untuk response formatting
5. **Menggunakan Laravel Factory dan Seeder** untuk test data preparation
6. **Mengimplementasikan database sync utilities** untuk testing environment
7. **Menerapkan RESTful API best practices** dalam Branch management
8. **Memahami separation of concerns** antara Model dan Controller

---

## **PENDAHULUAN**

### **Apa itu Unified Controller Architecture?**

**Unified Controller Architecture** adalah pendekatan dimana satu controller menangani baik **web requests** (HTML) maupun **API requests** (JSON) menggunakan **content negotiation**. Keuntungannya:

- ✅ **Single Business Logic**: Tidak ada duplikasi kode
- ✅ **DRY Principle**: Don't Repeat Yourself
- ✅ **Maintainability**: Perubahan logic cukup di satu tempat
- ✅ **Consistency**: Behavior yang sama untuk web dan API
- ✅ **Clean Architecture**: Separation of concerns yang jelas

### **Branch Entity Structure**

Dalam praktikum ini, kita bekerja dengan **Branch entity** yang memiliki struktur:

```php
Branch (tabel: branches):
├── id (integer, primary key)
├── branch_name (string, max 50, required, unique)
├── branch_address (string, max 100, nullable)  
├── branch_telephone (string, max 30, nullable)
├── is_active (boolean, default true)
├── created_at (timestamp)
└── updated_at (timestamp)
```

### **API Endpoints yang Akan Ditest**

```
📍 GET    /api/branches                      → List branches with filtering
📍 POST   /api/branches                      → Create new branch  
📍 GET    /api/branches/{id}                 → Get specific branch
📍 PUT    /api/branches/{id}                 → Update branch
📍 DELETE /api/branches/{id}                 → Delete branch
📍 GET    /api/branches/filter/active        → List active branches
📍 GET    /api/branches/analytics/statistics → Branch statistics
📍 GET    /api/branches/search/advanced      → Advanced search
```

---

## **SETUP PROJECT**

### **1. Konfigurasi Laravel 11 API Routes**

**PENTING**: Laravel 11 memerlukan konfigurasi khusus untuk API routes di `bootstrap/app.php`:

```php
<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',  // ← TAMBAHKAN INI
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
```

### **2. Setup Test Environment**

File `.env.testing` sudah tersedia dengan konfigurasi khusus untuk testing. Anda dapat memilih salah satu dari dua opsi database:

**Opsi 1: SQLite In-Memory (Direkomendasikan untuk testing)**
```bash
DB_CONNECTION=sqlite
DB_DATABASE=:memory:
```
✅ **Keuntungan**: 
- Sangat cepat
- Isolated (tidak mempengaruhi data production)
- Tidak perlu setup database terpisah

**Opsi 2: MySQL Test Database (Lebih realistis)**
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=erp_rpl_test
DB_USERNAME=root
DB_PASSWORD=root
```
✅ **Keuntungan**: 
- Lebih mirip dengan environment production
- Dapat melihat data test di database management tool
- Testing dengan engine database yang sama

**Untuk menggunakan MySQL test database, buat database terlebih dahulu:**

**Metode 1: Manual SQL Command**
```sql
-- Di MySQL Command Line atau phpMyAdmin
CREATE DATABASE erp_rpl_test;
```

**Metode 2: Menggunakan Laravel Artisan Command (Recommended)**
```bash
# Sync full database (structure + data)
php artisan db:sync-test

# Fresh sync (drop and recreate test database)
php artisan db:sync-test --fresh

# Sync data only (preserve structure)
php artisan db:sync-test --data-only
```

**Metode 3: Menggunakan Batch Script (Windows)**
```bash
# Jalankan script untuk sync otomatis
.\sync_test_db.bat
```

**Metode 4: Menggunakan Laravel Seeder**
```bash
# Seed test database dengan data realistic
php artisan db:seed --class=TestDatabaseSeeder --env=testing

# Atau kombinasi dengan migration fresh
php artisan migrate:fresh --seed --seeder=TestDatabaseSeeder --env=testing
```

**File `.env.testing` sudah dikonfigurasi dengan:**
- SQLite in-memory sebagai default
- BCRYPT_ROUNDS=4 untuk testing lebih cepat
- CACHE_STORE=array untuk testing
- MAIL_MAILER=log untuk mencegah email terkirim
- SESSION_DRIVER=array untuk testing
- QUEUE_CONNECTION=sync untuk testing synchronous
```

### **3. Memastikan Model Branch Menggunakan Factory**

Pastikan model Branch memiliki HasFactory trait:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;  // ← PENTING
use Illuminate\Database\Eloquent\Model;
use App\Constants\BranchColumns;

class Branch extends Model
{
    use HasFactory;  // ← TAMBAHKAN INI
    
    protected $table;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('db_tables.branch');
        $this->fillable = BranchColumns::getFillable();
    }
    
    // ... methods lainnya
}
```

---

## **UNIFIED CONTROLLER ARCHITECTURE**

### **1. Unified BranchController Implementation**

File `app/Http/Controllers/BranchController.php` menggunakan **unified architecture** yang menangani baik web maupun API requests:

```php
<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Http\Requests\StoreBranchRequest;
use App\Http\Requests\UpdateBranchRequest;
use App\Http\Resources\BranchResource;
use App\Http\Resources\BranchCollection;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        // Use enhanced query for API requests
        if ($this->wantsJson($request)) {
            // Best Practice: Use Model method instead of Controller query
            $filters = [
                'search' => $search,
                'status' => $request->get('status'),
                'sort_by' => $request->get('sort_by', 'created_at'),
                'sort_order' => $request->get('sort_order', 'desc'),
            ];

            $query = Branch::searchWithFilters($filters);
            $branches = $query->paginate($request->get('per_page', 15));
            return new BranchCollection($branches);
        }

        // Web functionality - return HTML view
        $branches = Branch::getAllBranch($search);
        return view('branches.index', ['branches' => $branches]);
    }

    public function store(StoreBranchRequest $request)
    {
        try {
            $branch = Branch::addBranch([
                'branch_name' => $request->input('branch_name'),
                'branch_address' => $request->input('branch_address'),
                'branch_telephone' => $request->input('branch_telephone'),
            ]);

            // Handle API Response
            if ($this->wantsJson($request)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Branch created successfully',
                    'data' => new BranchResource($branch)
                ], 201);
            }

            // Handle Web Response
            return redirect()->route('branches.index')
                           ->with('success', 'Cabang berhasil ditambahkan!');
            
        } catch (\Exception $e) {
            if ($this->wantsJson($request)) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 422);
            }

            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Helper method to detect if request wants JSON response
     */
    private function wantsJson(Request $request): bool
    {
        return $request->expectsJson() || 
               $request->is('api/*') || 
               $request->header('Accept') === 'application/json' ||
               $request->header('Content-Type') === 'application/json';
    }

    // ... other methods (show, update, destroy)
}
```

### **2. API Routes Configuration**

File `routes/api.php` menggunakan **unified BranchController**:

```php
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BranchController; // ← Unified Controller

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Branch API Routes - Using unified BranchController
Route::prefix('branches')->name('api.branches.')->group(function () {
    // Custom endpoints (place specific routes before dynamic routes)
    Route::get('/filter/active', [BranchController::class, 'active'])->name('active');
    Route::get('/analytics/statistics', [BranchController::class, 'statistics'])->name('statistics');
    Route::post('/bulk/update-status', [BranchController::class, 'bulkUpdateStatus'])->name('bulk.update.status');
    Route::get('/search/advanced', [BranchController::class, 'search'])->name('search');
    
    // Basic CRUD operations
    Route::get('/', [BranchController::class, 'index'])->name('index');
    Route::post('/', [BranchController::class, 'store'])->name('store');
    Route::get('/{id}', [BranchController::class, 'show'])->name('show');
    Route::put('/{id}', [BranchController::class, 'update'])->name('update');
    Route::delete('/{id}', [BranchController::class, 'destroy'])->name('destroy');
});
```

### **3. Model-based Query Logic (Best Practice)**

File `app/Models/Branch.php` dengan query logic yang dipindahkan dari Controller:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Constants\BranchColumns;

class Branch extends Model
{
    use HasFactory;

    /**
     * Advanced search with filters for API endpoints
     * Best Practice: Query logic in Model, not Controller
     */
    public static function searchWithFilters(array $filters = [])
    {
        $query = self::query();

        // Search functionality
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where(BranchColumns::NAME, 'LIKE', "%{$search}%")
                  ->orWhere(BranchColumns::ADDRESS, 'LIKE', "%{$search}%")
                  ->orWhere(BranchColumns::PHONE, 'LIKE', "%{$search}%");
            });
        }

        // Status filtering
        if (isset($filters['status'])) {
            $status = $filters['status'];
            if ($status === 'active') {
                $query->where(BranchColumns::IS_ACTIVE, true);
            } elseif ($status === 'inactive') {
                $query->where(BranchColumns::IS_ACTIVE, false);
            }
        }

        // Sorting
        $sortBy = $filters['sort_by'] ?? BranchColumns::CREATED_AT;
        $sortOrder = $filters['sort_order'] ?? 'desc';
        
        $validSortFields = [
            BranchColumns::NAME,
            BranchColumns::ADDRESS,
            BranchColumns::CREATED_AT,
            BranchColumns::IS_ACTIVE
        ];
        
        if (in_array($sortBy, $validSortFields)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        return $query;
    }

    /**
     * Get branch statistics
     */
    public static function getStatistics()
    {
        $total = self::count();
        $active = self::where(BranchColumns::IS_ACTIVE, true)->count();
        $inactive = $total - $active;

        return [
            'total_branches' => $total,
            'active_branches' => $active,
            'inactive_branches' => $inactive,
            'active_percentage' => $total > 0 ? round(($active / $total) * 100, 2) : 0
        ];
    }

    // ... other methods
}
```

### **4. API Resources untuk Response Formatting**

File `app/Http/Resources/BranchResource.php`:

```php
<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Constants\BranchColumns;

class BranchResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'branch_name' => $this->{BranchColumns::NAME},
            'branch_address' => $this->{BranchColumns::ADDRESS},
            'branch_telephone' => $this->{BranchColumns::PHONE},
            'is_active' => (bool) $this->{BranchColumns::IS_ACTIVE},
            'status' => $this->getStatusText(),
            'status_badge' => $this->getStatusBadge(),
            
            // Formatted data
            'display_name' => $this->getDisplayName(),
            'short_address' => $this->getShortAddress(),
            'formatted_phone' => $this->getFormattedPhone(),
            
            // Timestamps
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
            'created_at_human' => $this->created_at?->diffForHumans(),
        ];
    }
    
    private function getStatusText(): string
    {
        return $this->{BranchColumns::IS_ACTIVE} ? 'Aktif' : 'Tidak Aktif';
    }
    
    private function getStatusBadge(): array
    {
        return [
            'text' => $this->getStatusText(),
            'color' => $this->{BranchColumns::IS_ACTIVE} ? 'success' : 'danger',
            'icon' => $this->{BranchColumns::IS_ACTIVE} ? 'check-circle' : 'x-circle'
        ];
    }
    
    private function getDisplayName(): string
    {
        $emoji = $this->{BranchColumns::IS_ACTIVE} ? '✅' : '❌';
        return $emoji . ' ' . $this->{BranchColumns::NAME};
    }
    
    // ... other formatting methods
}
```

File `app/Http/Resources/BranchCollection.php`:

```php
<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BranchCollection extends ResourceCollection
{
    public function toArray($request): array
    {
        return [
            'data' => $this->collection,
            'meta' => $this->getMeta(),
            'summary' => $this->getSummary(),
        ];
    }
    
    private function getMeta(): array
    {
        return [
            'total' => $this->count(),
            'active_count' => $this->collection->where('is_active', true)->count(),
            'inactive_count' => $this->collection->where('is_active', false)->count(),
            'percentage_active' => $this->getActivePercentage(),
        ];
    }
    
    // ... other methods
}
```

```php
<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Constants\BranchColumns;

class BranchResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'branch_name' => $this->{BranchColumns::NAME},
            'branch_address' => $this->{BranchColumns::ADDRESS},
            'branch_telephone' => $this->{BranchColumns::PHONE},
            'is_active' => (bool) $this->{BranchColumns::IS_ACTIVE},
            'status' => $this->getStatusText(),
            'status_badge' => $this->getStatusBadge(),
            
            // Formatted data
            'display_name' => $this->getDisplayName(),
            'short_address' => $this->getShortAddress(),
            'formatted_phone' => $this->getFormattedPhone(),
            
            // Timestamps
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
            'created_at_human' => $this->created_at?->diffForHumans(),
            'updated_at_human' => $this->updated_at?->diffForHumans(),
        ];
    }
    
    private function getStatusText(): string
    {
        return $this->{BranchColumns::IS_ACTIVE} ? 'Aktif' : 'Tidak Aktif';
    }
    
    private function getStatusBadge(): array
    {
        return [
            'text' => $this->getStatusText(),
            'color' => $this->{BranchColumns::IS_ACTIVE} ? 'success' : 'danger',
            'icon' => $this->{BranchColumns::IS_ACTIVE} ? 'check-circle' : 'x-circle'
        ];
    }
    
    private function getDisplayName(): string
    {
        $emoji = $this->{BranchColumns::IS_ACTIVE} ? '✅' : '❌';
        return $emoji . ' ' . $this->{BranchColumns::NAME};
    }
    
    private function getShortAddress(): string
    {
        $address = $this->{BranchColumns::ADDRESS};
        return strlen($address) > 30 ? substr($address, 0, 30) . '...' : $address;
    }
    
    private function getFormattedPhone(): string
    {
        return $this->{BranchColumns::PHONE} ?? 'Tidak ada';
    }
    
    public function with(Request $request): array
    {
        return [
            'links' => [
                'self' => route('api.branches.show', $this->id),
                'edit' => route('api.branches.show', $this->id),
                'delete' => route('api.branches.show', $this->id),
            ]
        ];
    }
}
```

---

## **API TESTING IMPLEMENTATION**

### **1. File Test Utama: BranchApiTest.php**

File `tests/Feature/BranchApiTest.php` berisi comprehensive test cases:

```php
<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Branch;
use App\Constants\BranchColumns;

/**
 * Comprehensive API Testing untuk Unified BranchController
 * Testing both basic CRUD dan advanced features
 */
class BranchApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
    }

    /**
     * Test: Mendapatkan daftar cabang dengan BranchCollection
     */
    public function test_can_get_list_of_branches()
    {
        // 1. ARRANGE: Siapkan data test
        Branch::factory()->count(3)->create();

        // 2. ACT: Panggil API endpoint
        $response = $this->getJson('/api/branches');

        // 3. ASSERT: Periksa hasil response
        $response->assertStatus(200);
        $this->assertCount(3, $response->json('data'));
        
        // Periksa BranchCollection structure
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id', 'branch_name', 'branch_address', 'branch_telephone',
                    'is_active', 'status', 'status_badge', 'display_name'
                ]
            ],
            'meta' => [
                'total', 'active_count', 'inactive_count', 'percentage_active'
            ],
            'summary' => [
                'status_distribution', 'cities', 'latest_branch'
            ]
        ]);
    }

    /**
     * Test: Membuat cabang baru dengan BranchResource response
     */
    public function test_can_create_new_branch()
    {
        // 1. ARRANGE: Siapkan data untuk POST
        $newBranchData = [
            BranchColumns::NAME => 'Cabang Test Jakarta',
            BranchColumns::ADDRESS => 'Jl. Test No. 123',
            BranchColumns::PHONE => '021-12345678'
        ];

        // 2. ACT: Kirim POST request
        $response = $this->postJson('/api/branches', $newBranchData);

        // 3. ASSERT: Periksa hasil
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'success', 'message', 
            'data' => [
                'id', 'branch_name', 'status_badge', 'display_name'
            ]
        ]);

        // Periksa data tersimpan di database
        $this->assertDatabaseHas('branches', [
            BranchColumns::NAME => 'Cabang Test Jakarta'
        ]);
    }

    /**
     * Test: Validation error dengan proper JSON response
     */
    public function test_cannot_create_branch_with_invalid_data()
    {
        // 1. ARRANGE: Data tidak valid (nama kosong)
        $invalidData = [
            BranchColumns::ADDRESS => 'Alamat saja tanpa nama'
        ];

        // 2. ACT: Coba POST data tidak valid
        $response = $this->postJson('/api/branches', $invalidData);

        // 3. ASSERT: Harus gagal validasi
        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            BranchColumns::NAME
        ]);
    }

    /**
     * Test: Mendapatkan cabang spesifik dengan BranchResource
     */
    public function test_can_get_specific_branch()
    {
        // 1. ARRANGE: Buat data test
        $branch = Branch::factory()->create([
            BranchColumns::NAME => 'Cabang Spesifik Test'
        ]);

        // 2. ACT: Ambil data via API
        $response = $this->getJson("/api/branches/{$branch->id}");

        // 3. ASSERT: Periksa BranchResource response
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id', 'branch_name', 'display_name', 'status_badge',
            'created_at', 'created_at_human'
        ]);
        
        $response->assertJsonFragment([
            'branch_name' => 'Cabang Spesifik Test'
        ]);
    }

    /**
     * Test: Advanced search functionality
     */
    public function test_can_search_branches()
    {
        // 1. ARRANGE: Buat beberapa cabang
        Branch::factory()->create([BranchColumns::NAME => 'Jakarta Pusat']);
        Branch::factory()->create([BranchColumns::NAME => 'Surabaya Timur']);
        Branch::factory()->create([BranchColumns::NAME => 'Jakarta Selatan']);

        // 2. ACT: Search dengan keyword 'Jakarta'
        $response = $this->getJson('/api/branches?search=Jakarta');

        // 3. ASSERT: Hanya cabang dengan Jakarta yang muncul
        $response->assertStatus(200);
        $this->assertCount(2, $response->json('data'));
        
        // Semua hasil mengandung 'Jakarta'
        $branches = $response->json('data');
        foreach ($branches as $branch) {
            $this->assertStringContainsString('Jakarta', $branch['branch_name']);
        }
    }

    /**
     * Test: Filter berdasarkan status aktif/non-aktif
     */
    public function test_can_filter_by_status()
    {
        // 1. ARRANGE: Buat cabang aktif dan non-aktif
        Branch::factory()->active()->count(3)->create();
        Branch::factory()->inactive()->count(2)->create();

        // 2. ACT: Filter cabang aktif saja
        $response = $this->getJson('/api/branches?status=active');

        // 3. ASSERT: Hanya cabang aktif yang muncul
        $response->assertStatus(200);
        $this->assertCount(3, $response->json('data'));

        $branches = $response->json('data');
        foreach ($branches as $branch) {
            $this->assertTrue($branch['is_active']);
        }
    }

    /**
     * Test: Active branches endpoint
     */
    public function test_active_branches_endpoint()
    {
        // 1. ARRANGE: Buat cabang aktif dan non-aktif
        Branch::factory()->active()->count(5)->create();
        Branch::factory()->inactive()->count(3)->create();

        // 2. ACT: Panggil endpoint active branches
        $response = $this->getJson('/api/branches/filter/active');

        // 3. ASSERT: Hanya 5 cabang aktif yang muncul
        $response->assertStatus(200);
        $this->assertCount(5, $response->json('data'));
    }

    /**
     * Test: Branch statistics endpoint
     */
    public function test_branch_statistics()
    {
        // 1. ARRANGE: Buat data dengan proporsi tertentu
        Branch::factory()->active()->count(5)->create();
        Branch::factory()->inactive()->count(3)->create();

        // 2. ACT: Panggil statistics endpoint
        $response = $this->getJson('/api/branches/analytics/statistics');

        // 3. ASSERT: Periksa angka statistik
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => [
                'total_branches' => 8,
                'active_branches' => 5,
                'inactive_branches' => 3
            ]
        ]);

        $this->assertEquals(62.5, $response->json('data.active_percentage'));
    }

    /**
     * Test: Update cabang dengan unified controller
     */
    public function test_can_update_existing_branch()
    {
        // 1. ARRANGE: Buat cabang test
        $branch = Branch::factory()->create([
            BranchColumns::NAME => 'Cabang Lama'
        ]);

        $updateData = [
            BranchColumns::NAME => 'Cabang Updated',
            BranchColumns::ADDRESS => 'Alamat Baru Updated'
        ];

        // 2. ACT: Update via API
        $response = $this->putJson("/api/branches/{$branch->id}", $updateData);

        // 3. ASSERT: Periksa hasil dengan BranchResource
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success', 'message', 
            'data' => ['id', 'branch_name', 'display_name']
        ]);

        // Periksa database terupdate
        $this->assertDatabaseHas('branches', [
            'id' => $branch->id,
            BranchColumns::NAME => 'Cabang Updated'
        ]);
    }

    /**
     * Test: Delete cabang
     */
    public function test_can_delete_branch()
    {
        // 1. ARRANGE: Buat data test
        $branch = Branch::factory()->create();

        // 2. ACT: Delete via API
        $response = $this->deleteJson("/api/branches/{$branch->id}");

        // 3. ASSERT: Periksa hasil
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Branch deleted successfully'
        ]);

        // Periksa data terhapus dari database
        $this->assertDatabaseMissing('branches', [
            'id' => $branch->id
        ]);
    }

    /**
     * Test: Advanced search dengan multiple parameters
     */
    public function test_advanced_search()
    {
        // 1. ARRANGE: Buat data dengan variasi
        Branch::factory()->create([
            BranchColumns::NAME => 'Jakarta Pusat',
            BranchColumns::ADDRESS => 'Jl. Sudirman Jakarta',
            BranchColumns::PHONE => '021-111-2222'
        ]);
        
        Branch::factory()->create([
            BranchColumns::NAME => 'Surabaya Timur', 
            BranchColumns::ADDRESS => 'Jl. Raya Surabaya'
        ]);

        // 2. ACT: Advanced search
        $response = $this->getJson('/api/branches/search/advanced?name=Jakarta&address=Sudirman');

        // 3. ASSERT: Hasil sesuai kriteria
        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
        
        $branch = $response->json('data')[0];
        $this->assertStringContainsString('Jakarta', $branch['branch_name']);
        $this->assertStringContainsString('Sudirman', $branch['branch_address']);
    }

    /**
     * Test: Consistent response format untuk semua endpoints
     */
    public function test_consistent_response_format()
    {
        // 1. ARRANGE: Buat data test
        $branch = Branch::factory()->create();

        // 2. ACT: Test berbagai endpoints
        $listResponse = $this->getJson('/api/branches');
        $showResponse = $this->getJson("/api/branches/{$branch->id}");
        $statsResponse = $this->getJson('/api/branches/analytics/statistics');

        // 3. ASSERT: Format response konsisten
        $listResponse->assertStatus(200);
        $this->assertArrayHasKey('data', $listResponse->json());
        $this->assertArrayHasKey('meta', $listResponse->json());

        $showResponse->assertStatus(200);
        $this->assertArrayHasKey('id', $showResponse->json());
        $this->assertArrayHasKey('status_badge', $showResponse->json());

        $statsResponse->assertStatus(200);
        $this->assertArrayHasKey('success', $statsResponse->json());
        $this->assertArrayHasKey('data', $statsResponse->json());
    }
}
```

### **2. Web vs API Testing Comparison**

Untuk membandingkan, terdapat juga `tests/Feature/Controllers/BranchControllerTest.php` yang menguji **web interface** dari controller yang sama:

```php
<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\Branch;

class BranchControllerTest extends TestCase
{
    /**
     * Test web interface - returns HTML view
     */
    public function test_it_displays_branches_index_page()
    {
        // ARRANGE: Create test data
        Branch::factory()->count(2)->create();

        // ACT: Visit web route (not API)
        $response = $this->get(route('branches.index'));

        // ASSERT: Web-specific assertions
        $response->assertStatus(200);
        $response->assertViewIs('branches.index');    // ← HTML View
        $response->assertViewHas('branches');         // ← View data
        $response->assertSee('Branch List');          // ← HTML content
    }

    /**
     * Test web form submission - returns redirect
     */
    public function test_store_succeeds_with_valid_data()
    {
        $validData = [
            'branch_name' => 'Cabang Web Test',
            'branch_address' => 'Alamat Web Test'
        ];

        // ACT: Submit web form (not API)
        $response = $this->post(route('branches.store'), $validData);

        // ASSERT: Web-specific behavior
        $response->assertStatus(302);                              // ← Redirect
        $response->assertRedirect(route('branches.index'));       // ← Redirect location
        $response->assertSessionHas('success', 'Cabang berhasil ditambahkan!'); // ← Flash message
    }
}
```

**Key Differences:**

| Aspect | API Test (BranchApiTest) | Web Test (BranchControllerTest) |
|--------|-------------------------|--------------------------------|
| **Request** | `$this->getJson('/api/branches')` | `$this->get(route('branches.index'))` |
| **Response** | JSON dengan BranchCollection | HTML View |
| **Assertions** | `assertJson()`, `assertJsonStructure()` | `assertViewIs()`, `assertSee()` |
| **Status** | JSON status codes | HTTP redirects (302) |
| **Data** | BranchResource format | Raw model data |
    {
        // 1. ARRANGE: Buat cabang aktif dan non-aktif
        Branch::factory()->count(2)->active()->create();   // 2 aktif
        Branch::factory()->count(1)->inactive()->create(); // 1 non-aktif

        // 2. ACT: Filter hanya yang aktif
        $response = $this->getJson('/api/branches?is_active=1');

        // 3. ASSERT: Hanya yang aktif
        $response->assertStatus(200);
        $this->assertCount(2, $response->json('data'));
        
        foreach ($response->json('data') as $branch) {
            $this->assertTrue($branch['is_active']);
        }
    }

    /**
     * Test endpoint khusus: Active branches
     */
    public function test_active_branches_endpoint()
    {
        // 1. ARRANGE
        Branch::factory()->count(3)->active()->create();
        Branch::factory()->count(2)->inactive()->create();

        // 2. ACT
        $response = $this->getJson('/api/branches/filter/active');

        // 3. ASSERT
        $response->assertStatus(200);
        $this->assertCount(3, $response->json('data'));
    }

    /**
     * Test endpoint statistik cabang
     */
    public function test_branch_statistics()
    {
        // 1. ARRANGE
        Branch::factory()->count(5)->active()->create();
        Branch::factory()->count(2)->inactive()->create();

        // 2. ACT
        $response = $this->getJson('/api/branches/analytics/statistics');

        // 3. ASSERT
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'total_branches',
            'active_branches', 
            'inactive_branches',
            'active_percentage'
        ]);
        
        $stats = $response->json();
        $this->assertEquals(7, $stats['total_branches']);
        $this->assertEquals(5, $stats['active_branches']);
        $this->assertEquals(2, $stats['inactive_branches']);
    }

    /**
     * Test format response yang konsisten
     */
    public function test_consistent_response_format()
    {
        // 1. ARRANGE: Buat data test
        $branch = Branch::factory()->create();

        // 2. ACT & ASSERT: Periksa format response
        $response = $this->getJson("/api/branches/{$branch->id}");
        
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'branch_name',
                    'branch_address',
                    'branch_telephone',
                    'is_active',
                    'status',                    // Human readable status
                    'display_name',              // With emoji indicator
                    'created_at_human'           // Human friendly timestamp
                ],
                'links' => [
                    'self',
                    'edit',
                    'delete'
                ]
            ]);

        // Periksa tipe data
        $data = $response->json();
        $branchData = $data['data']; // Akses data dari struktur response yang benar
        $this->assertIsInt($branchData['id']);
        $this->assertIsString($branchData['branch_name']);
        $this->assertIsBool($branchData['is_active']);
        $this->assertIsString($branchData['status']);
    }
}
```

---

## **RUNNING TESTS**

### **1. Menjalankan Test**

```bash
# Jalankan semua test Branch API
php artisan test tests/Feature/BranchApiSimpleTest.php

# Jalankan test spesifik
php artisan test tests/Feature/BranchApiSimpleTest.php::test_can_create_new_branch

# Jalankan dengan verbose output
php artisan test tests/Feature/BranchApiSimpleTest.php --verbose

# Jalankan dan stop jika ada error
php artisan test tests/Feature/BranchApiSimpleTest.php --stop-on-failure
```

### **2. Output yang Diharapkan**

```bash
PASS  Tests\Feature\BranchApiSimpleTest
✓ can get list of branches                          
✓ can create new branch                             
✓ cannot create branch with invalid data            
✓ can get specific branch                           
✓ returns 404 for missing branch                   
✓ can update existing branch                        
✓ can delete branch                                 
✓ can search branches                               
✓ can filter by status                              
✓ active branches endpoint                          
✓ branch statistics                                 
✓ consistent response format                        

Tests:    12 passed (67 assertions)
Duration: 8.45s
```

### **3. Troubleshooting Common Issues**

**Problem**: Routes tidak ditemukan (404)
```bash
# Solution: Pastikan API routes terdaftar di bootstrap/app.php
# dan clear cache
php artisan route:clear
php artisan config:clear
php artisan cache:clear
```

**Problem**: Factory tidak ditemukan
```bash
# Solution: Pastikan model menggunakan HasFactory trait
# dan factory file ada di database/factories/
```

**Problem**: Migration error
```bash
# Solution: Jalankan migration
php artisan migrate --env=testing
```

---

## **BEST PRACTICES**

### **1. Test Structure (AAA Pattern)**

```php
public function test_example()
{
    // 1. ARRANGE: Setup data dan kondisi
    $branch = Branch::factory()->create();
    
    // 2. ACT: Lakukan aksi yang akan ditest
    $response = $this->getJson("/api/branches/{$branch->id}");
    
    // 3. ASSERT: Verifikasi hasil
    $response->assertStatus(200);
}
```

### **2. Test Data Isolation**

```php
class BranchApiTest extends TestCase
{
    use RefreshDatabase; // Bersihkan database setiap test
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate'); // Fresh migration
    }
}
```

### **3. Comprehensive Assertions**

```php
public function test_create_branch()
{
    // Test HTTP status
    $response->assertStatus(201);
    
    // Test JSON structure
    $response->assertJsonStructure(['data' => ['id', 'name']]);
    
    // Test specific content
    $response->assertJsonFragment(['branch_name' => 'Test Branch']);
    
    // Test database state
    $this->assertDatabaseHas('branches', ['branch_name' => 'Test Branch']);
}
```

### **4. Edge Cases Testing**

```php
// Test batas input
public function test_branch_name_length_validation()
{
    $longName = str_repeat('a', 51); // Lebih dari 50 karakter
    
    $response = $this->postJson('/api/branches', [
        BranchColumns::NAME => $longName
    ]);
    
    $response->assertStatus(422);
}

// Test format data
public function test_invalid_phone_format()
{
    $response = $this->postJson('/api/branches', [
        BranchColumns::NAME => 'Test Branch',
        BranchColumns::PHONE => 'not-a-phone-number'
    ]);
    
    // Depending on validation rules
    $response->assertStatus(422);
}
```

---

## **KESIMPULAN**

### **Pencapaian Pembelajaran**

Setelah menyelesaikan praktikum ini, mahasiswa telah:

1. ✅ **Unified Controller Architecture**: Memahami single controller untuk web dan API
2. ✅ **Model-based Query Logic**: Memindahkan query logic ke Model (best practice)
3. ✅ **Comprehensive API Testing**: Testing dengan BranchResource dan BranchCollection
4. ✅ **Laravel HTTP Testing**: Menggunakan TestCase untuk API testing dengan content negotiation
5. ✅ **API Resources Usage**: Consistent response formatting dengan Resources
6. ✅ **Database Sync Utilities**: Tools untuk testing environment management
7. ✅ **Separation of Concerns**: Clean architecture dengan proper responsibility separation

### **Key Takeaways**

1. **Unified Architecture is Powerful** - Satu controller untuk web dan API eliminates code duplication
2. **Model-based Logic** - Query logic belongs in Model, not Controller (Single Responsibility Principle)  
3. **Laravel 11 Configuration** - API routes harus dikonfigurasi di bootstrap/app.php
4. **Content Negotiation** - `wantsJson()` method untuk detect request type
5. **API Resources Benefits** - Consistent, formatted responses untuk frontend integration
6. **Test Isolation** - RefreshDatabase memastikan test tidak saling mempengaruhi
7. **Clean Code Principles** - DRY, SOLID principles dalam Laravel development

### **Architecture Benefits Achieved**

| Aspect | Before (Separate Controllers) | After (Unified Controller) |
|--------|------------------------------|---------------------------|
| **Controllers** | BranchController + BranchApiController | Single BranchController |
| **Code Duplication** | ❌ Duplicate business logic | ✅ Single business logic |
| **Maintainability** | ❌ Changes in 2 places | ✅ Changes in 1 place |
| **Testing** | ❌ Test separate controllers | ✅ Test unified behavior |
| **Query Logic** | ❌ Mixed in controllers | ✅ Clean Model methods |

### **Next Steps**

1. **Advanced API Features**: Authentication, authorization, rate limiting
2. **Integration Testing**: Test dengan external services dan middleware
3. **Performance Testing**: Load testing untuk unified endpoints  
4. **API Documentation**: Swagger/OpenAPI documentation generation
5. **CI/CD Integration**: Automated testing dalam deployment pipeline
6. **Microservices Architecture**: Scaling dengan service-oriented design

### **Best Practices Learned**

- **Fat Model, Skinny Controller**: Business logic di Model
- **Single Responsibility**: Each class has one reason to change
- **DRY Principle**: Don't Repeat Yourself dalam controller logic
- **API Resources**: Consistent response formatting
- **Content Negotiation**: Same endpoint, different response formats
- **Database Sync**: Testing environment management

---

### **Referensi**

- [Laravel HTTP Tests Documentation](https://laravel.com/docs/11.x/http-tests)
- [Laravel API Resources](https://laravel.com/docs/11.x/eloquent-resources)
- [PHPUnit Documentation](https://phpunit.de/documentation.html)
- [RESTful API Best Practices](https://restfulapi.net/)
- [SOLID Principles in Laravel](https://laravel.com/docs/11.x/controllers#dependency-injection-and-controllers)

---

**© 2025 - Software Testing Course - Unified Controller Architecture & API Testing Module**
