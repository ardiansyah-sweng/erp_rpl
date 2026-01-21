# PRAKTIKUM 03: API TESTING BRANCH MANAGEMENT
## Testing REST API Endpoints untuk Branch CRUD Operations

### **Informasi Modul**
- **Mata Kuliah**: Rekayasa Perangkat Lunak / Software Testing
- **Topik**: API Testing - Branch Management REST API
- **Framework**: Laravel 11 dengan PHPUnit
- **Durasi**: 90 menit
- **Level**: Intermediate
- **Focus**: Branch CRUD Operations API Testing

---

## **DAFTAR ISI**

1. [**Tujuan Pembelajaran**](#tujuan-pembelajaran)
2. [**Pendahuluan**](#pendahuluan)
3. [**Setup Project**](#setup-project)
4. [**Implementasi Branch API**](#implementasi-branch-api)
5. [**API Testing Implementation**](#api-testing-implementation)
6. [**Running Tests**](#running-tests)
7. [**Best Practices**](#best-practices)
8. [**Kesimpulan**](#kesimpulan)

---

## **TUJUAN PEMBELAJARAN**

Setelah menyelesaikan praktikum API testing ini, mahasiswa diharapkan mampu:

1. **Memahami API Testing** untuk Branch management system
2. **Mengimplementasikan CRUD API endpoints** untuk Branch entity
3. **Melakukan comprehensive API testing** dengan Laravel HTTP Testing
4. **Menguji API validation dan error handling** untuk Branch operations
5. **Menggunakan Laravel Factory dan Seeder** untuk test data preparation
6. **Mengimplementasikan API response structure testing** dan status codes
7. **Menerapkan RESTful API best practices** dalam Branch management

---

## **PENDAHULUAN**

### **Apa itu API Testing untuk Branch Management?**

**Branch Management API Testing** adalah proses pengujian REST API endpoints yang mengelola data cabang perusahaan, meliputi:

- ✅ **CRUD Operations**: Create, Read, Update, Delete branch data
- ✅ **Data Validation**: Validasi input branch (nama, alamat, kode cabang, dll)
- ✅ **Response Format**: JSON structure consistency untuk branch data
- ✅ **Error Handling**: Proper HTTP status codes dan error messages
- ✅ **Business Rules**: Validasi aturan bisnis khusus branch management

### **Branch Entity Structure**

Dalam praktikum ini, kita bekerja dengan **Branch entity** yang memiliki struktur dari migration `2025_02_21_105316_create_branch_table.php`:

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
📍 GET    /api/branches              → List all branches with pagination/filtering
📍 POST   /api/branches              → Create new branch  
📍 GET    /api/branches/{id}         → Get specific branch with detailed format
📍 PUT    /api/branches/{id}         → Update branch
📍 DELETE /api/branches/{id}         → Delete branch
📍 GET    /api/branches/filter/active → List active branches only
📍 GET    /api/branches/analytics/statistics → Branch statistics
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

```bash
# Copy environment file untuk testing
cp .env .env.testing

# Update database configuration untuk testing (opsional, bisa pakai sqlite)
DB_CONNECTION=sqlite
DB_DATABASE=:memory:

# Atau tetap gunakan MySQL untuk testing lebih realistis
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=erp_rpl_test
DB_USERNAME=root
DB_PASSWORD=
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

## **IMPLEMENTASI BRANCH API**

### **1. API Routes**

Routes API didefinisikan dalam file `routes/api.php` dengan urutan yang tepat:

```php
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BranchApiController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Branch API Routes
Route::prefix('branches')->name('api.branches.')->group(function () {
    // Custom endpoints (place specific routes before dynamic routes)
    Route::get('/filter/active', [BranchApiController::class, 'active'])->name('active');
    Route::get('/analytics/statistics', [BranchApiController::class, 'statistics'])->name('statistics');
    Route::post('/bulk/update-status', [BranchApiController::class, 'bulkUpdateStatus'])->name('bulk.update.status');
    Route::get('/search/advanced', [BranchApiController::class, 'search'])->name('search');
    
    // Basic CRUD operations
    Route::get('/', [BranchApiController::class, 'index'])->name('index');
    Route::post('/', [BranchApiController::class, 'store'])->name('store');
    Route::get('/{id}', [BranchApiController::class, 'show'])->name('show');
    Route::put('/{id}', [BranchApiController::class, 'update'])->name('update');
    Route::delete('/{id}', [BranchApiController::class, 'destroy'])->name('destroy');
});
```

### **2. Branch Factory untuk Test Data**

File `database/factories/BranchFactory.php`:

```php
<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Branch;
use App\Constants\BranchColumns;

class BranchFactory extends Factory
{
    protected $model = Branch::class;
    
    public function definition(): array
    {
        $cities = ['Jakarta', 'Surabaya', 'Bandung', 'Medan', 'Semarang', 'Makassar', 'Palembang'];
        $city = $this->faker->randomElement($cities);
        
        return [
            BranchColumns::NAME => 'Cabang ' . $city . ' ' . $this->faker->randomElement(['Pusat', 'Utara', 'Selatan', 'Timur', 'Barat']),
            BranchColumns::ADDRESS => $this->faker->streetAddress . ', ' . $city,
            BranchColumns::PHONE => $this->faker->phoneNumber,
            BranchColumns::IS_ACTIVE => $this->faker->boolean(80), // 80% kemungkinan aktif
        ];
    }
    
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            BranchColumns::IS_ACTIVE => true,
        ]);
    }
    
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            BranchColumns::IS_ACTIVE => false,
        ]);
    }
}
```

### **3. Branch Resources untuk JSON Response**

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

### **1. File Test Utama: BranchApiSimpleTest.php**

File `tests/Feature/BranchApiSimpleTest.php` berisi test cases yang sudah teruji:

```php
<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Branch;
use App\Constants\BranchColumns;

/**
 * Simple API Testing Examples for Learning
 * Demonstrasi testing API dengan contoh yang mudah dipahami
 */
class BranchApiSimpleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
    }

    /**
     * Test sederhana: Bisa mendapatkan daftar cabang
     */
    public function test_can_get_list_of_branches()
    {
        // 1. ARRANGE: Siapkan data test
        Branch::factory()->count(2)->create();

        // 2. ACT: Panggil API endpoint
        $response = $this->getJson('/api/branches');

        // 3. ASSERT: Periksa hasil response
        $response->assertStatus(200);                    // HTTP Status 200 OK
        $this->assertCount(2, $response->json('data')); // Ada 2 data
        
        // Periksa struktur response
        $response->assertJsonStructure([
            'data' => [
                '*' => ['id', 'branch_name', 'branch_address']
            ]
        ]);
    }

    /**
     * Test sederhana: Bisa membuat cabang baru
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
        $response->assertStatus(201);                    // HTTP Status 201 Created
        $response->assertJsonFragment([                  // Periksa data dalam response
            'branch_name' => 'Cabang Test Jakarta'
        ]);

        // Periksa data tersimpan di database
        $this->assertDatabaseHas('branches', [
            BranchColumns::NAME => 'Cabang Test Jakarta'
        ]);
    }

    /**
     * Test sederhana: Tidak bisa membuat cabang dengan data tidak valid
     */
    public function test_cannot_create_branch_with_invalid_data()
    {
        // 1. ARRANGE: Siapkan data tidak valid (nama kosong)
        $invalidData = [
            BranchColumns::ADDRESS => 'Alamat saja tanpa nama'
            // Tidak ada BranchColumns::NAME (required)
        ];

        // 2. ACT: Coba POST data tidak valid
        $response = $this->postJson('/api/branches', $invalidData);

        // 3. ASSERT: Harus gagal validasi
        $response->assertStatus(422);                    // HTTP Status 422 Unprocessable Entity
        $response->assertJsonValidationErrors([          // Error di field name
            BranchColumns::NAME
        ]);
    }

    /**
     * Test sederhana: Bisa mendapatkan cabang spesifik
     */
    public function test_can_get_specific_branch()
    {
        // 1. ARRANGE: Buat data test
        $branch = Branch::factory()->create([
            BranchColumns::NAME => 'Cabang Spesifik'
        ]);

        // 2. ACT: Ambil data via API
        $response = $this->getJson("/api/branches/{$branch->id}");

        // 3. ASSERT: Periksa response
        $response->assertStatus(200);
        $response->assertJson([                          // Periksa data sesuai
            'data' => [
                'id' => $branch->id,
                'branch_name' => 'Cabang Spesifik'
            ]
        ]);
    }

    /**
     * Test sederhana: Return 404 untuk cabang yang tidak ada
     */
    public function test_returns_404_for_missing_branch()
    {
        // 1. ACT: Cari cabang dengan ID yang tidak ada
        $response = $this->getJson('/api/branches/999999');

        // 2. ASSERT: Harus return 404
        $response->assertStatus(404);
        $response->assertJson([
            'success' => false
        ]);
    }

    /**
     * Test sederhana: Bisa update cabang yang ada
     */
    public function test_can_update_existing_branch()
    {
        // 1. ARRANGE: Buat data test
        $branch = Branch::factory()->create([
            BranchColumns::NAME => 'Cabang Lama'
        ]);

        $updateData = [
            BranchColumns::NAME => 'Cabang Baru Updated',
            BranchColumns::ADDRESS => 'Alamat Baru'
        ];

        // 2. ACT: Update via API
        $response = $this->putJson("/api/branches/{$branch->id}", $updateData);

        // 3. ASSERT: Periksa hasil
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'branch_name' => 'Cabang Baru Updated'
        ]);

        // Periksa database terupdate
        $this->assertDatabaseHas('branches', [
            'id' => $branch->id,
            BranchColumns::NAME => 'Cabang Baru Updated'
        ]);
    }

    /**
     * Test sederhana: Bisa menghapus cabang
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
     * Test sederhana: Bisa mencari cabang (search)
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
     * Test sederhana: Bisa filter berdasarkan status
     */
    public function test_can_filter_by_status()
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

1. ✅ **Memahami API Testing**: Konsep testing REST API untuk Branch management
2. ✅ **Implementasi CRUD API**: Endpoint lengkap untuk Branch operations  
3. ✅ **Laravel HTTP Testing**: Menggunakan TestCase untuk API testing
4. ✅ **Validation Testing**: Test input validation dan error handling
5. ✅ **Factory Pattern**: Data preparation dengan Laravel Factory
6. ✅ **Response Testing**: Structure dan content validation
7. ✅ **RESTful Best Practices**: HTTP status codes dan JSON responses

### **Key Takeaways**

1. **API Testing adalah Kunci** - Memastikan endpoint berfungsi sesuai spesifikasi
2. **Laravel 11 Configuration** - API routes harus dikonfigurasi di bootstrap/app.php
3. **Test Isolation** - RefreshDatabase memastikan test tidak saling mempengaruhi
4. **Comprehensive Assertions** - Test status, structure, content, dan database state
5. **Real-world Scenarios** - Test edge cases dan error conditions

### **Next Steps**

1. **Advanced API Testing**: Authentication, authorization, rate limiting
2. **Integration Testing**: Test dengan external services
3. **Performance Testing**: Load testing untuk API endpoints
4. **API Documentation**: Swagger/OpenAPI documentation
5. **CI/CD Integration**: Automated testing dalam deployment pipeline

---

### **Referensi**

- [Laravel HTTP Tests Documentation](https://laravel.com/docs/11.x/http-tests)
- [PHPUnit Documentation](https://phpunit.de/documentation.html)
- [RESTful API Best Practices](https://restfulapi.net/)
- [Laravel API Resources](https://laravel.com/docs/11.x/eloquent-resources)

---

**© 2025 - Software Testing Course - Branch API Testing Module**
