<<<<<<< HEAD
# PRAKTIKUM 03: API TESTING DALAM LARAVEL
## Testing REST API Endpoints dengan PHPUnit dan Laravel

### **Informasi Modul**
- **Mata Kuliah**: Rekayasa Perangkat Lunak / Software Testing
- **Topik**: API Testing - REST API Endpoint Testing
- **Framework**: Laravel 10/11 dengan PHPUnit
- **Durasi**: 120 menit (2 jam)
- **Level**: Intermediate to Advanced

> **📋 MODULES SERIES**:  
> **[MODUL_PRAKTIKUM_UNIT_TESTING.md](./MODUL_PRAKTIKUM_UNIT_TESTING.md)** → **[MODUL_PRAKTIKUM_INTEGRATION_TESTING.md](./MODUL_PRAKTIKUM_INTEGRATION_TESTING.md)** → **MODUL_PRAKTIKUM_API_TESTING.md** ← *You are here*
=======
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
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d

---

## **TUJUAN PEMBELAJARAN**

Setelah menyelesaikan praktikum API testing ini, mahasiswa diharapkan mampu:

<<<<<<< HEAD
1. **Memahami konsep API Testing** dan perbedaannya dengan testing types lain
2. **Mengimplementasikan REST API testing** dengan HTTP requests/responses
3. **Menguji API authentication dan authorization** (Sanctum/Passport)
4. **Melakukan API contract testing** untuk memastikan konsistensi response structure
5. **Menggunakan Laravel HTTP Testing helpers** untuk API endpoint testing
6. **Mengimplementasikan comprehensive API test coverage** untuk CRUD operations
7. **Menguji API error handling dan validation** dengan berbagai scenarios
8. **Menganalisis API performance dan response time** dalam testing
=======
1. **Memahami Unified Controller Architecture** untuk web dan API endpoints
2. **Mengimplementasikan comprehensive API testing** dengan Laravel HTTP Testing
3. **Menerapkan Model-based query logic** sesuai best practices Laravel
4. **Menguji API Resources dan Collections** untuk response formatting
5. **Menggunakan Laravel Factory dan Seeder** untuk test data preparation
6. **Mengimplementasikan database sync utilities** untuk testing environment
7. **Menerapkan RESTful API best practices** dalam Branch management
8. **Memahami separation of concerns** antara Model dan Controller
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d

---

## **PENDAHULUAN**

<<<<<<< HEAD
### **Apa itu API Testing?**

**API Testing** adalah process pengujian Application Programming Interface (API) untuk memastikan:

- ✅ **Functional Correctness**: API berfungsi sesuai spesifikasi
- ✅ **Data Exchange**: Request/response format yang correct
- ✅ **Error Handling**: Proper error codes dan messages
- ✅ **Performance**: Response time dan throughput yang acceptable
- ✅ **Security**: Authentication, authorization, dan data protection

### **API Testing vs Other Testing Types**

```
🔹 Unit Testing      → Isolated components (no HTTP)
🔹 Integration       → Component interactions (no HTTP)  
🔹 Feature Testing   → Web workflows (HTML responses)
🔹 API Testing       → HTTP API endpoints (JSON responses) 🎯
🔹 E2E Testing       → Complete user journeys (browser)
```

### **API-First Development: Konsep dan Implementasi**

**API-First Development** adalah pendekatan pengembangan software di mana **API design dan contract** didefinisikan **sebelum** implementasi backend dan frontend. Pendekatan ini menjadi standar industri modern.

#### **🎯 Prinsip API-First Development**

```mermaid
graph TD
    A[API Contract Design] --> B[API Documentation]
    B --> C[Mock API Development]
    C --> D[Frontend Development]
    C --> E[Backend Implementation]
    D --> F[Integration Testing]
    E --> F
    F --> G[Production Deployment]
```

#### **📋 Karakteristik API-First**

| **Aspek** | **Traditional Development** | **API-First Development** |
|-----------|---------------------------|----------------------------|
| **Design Flow** | Backend → Frontend → API | **API Contract → Backend & Frontend** |
| **Documentation** | After implementation | **Before implementation** |
| **Testing Strategy** | End-to-end focus | **API contract focus** |
| **Team Coordination** | Sequential development | **Parallel development** |
| **Contract Changes** | Breaking changes common | **Versioned, backwards compatible** |

#### **🔧 API-First Implementation Steps**

**1. API Contract Definition (OpenAPI/Swagger)**
```yaml
# api-contract.yaml
openapi: 3.0.0
info:
  title: ERP Branch Management API
  version: 1.0.0
paths:
  /api/branches:
    post:
      summary: Create new branch
      requestBody:
        required: true
        content:
          application/json:
            schema:
              type: object
              required: [name, address, telephone]
              properties:
                name:
                  type: string
                  maxLength: 100
                address:
                  type: string
                  maxLength: 255
                telephone:
                  type: string
                  pattern: '^[0-9\-\+\(\)\ ]+$'
      responses:
        201:
          description: Branch created successfully
          content:
            application/json:
              schema:
                type: object
                properties:
                  success:
                    type: boolean
                  message:
                    type: string
                  data:
                    $ref: '#/components/schemas/Branch'
```

**2. Mock API Development**
```php
// routes/api.php - Mock Implementation
Route::post('/branches', function (Request $request) {
    return response()->json([
        'success' => true,
        'message' => 'Branch created successfully',
        'data' => [
            'id' => 999,
            'name' => $request->name,
            'address' => $request->address,
            'telephone' => $request->telephone,
            'created_at' => now()->toISOString(),
            'updated_at' => now()->toISOString()
        ]
    ], 201);
});
```

**3. Contract Testing Implementation**
```php
// tests/Feature/BranchApiContractTest.php
class BranchApiContractTest extends TestCase
{
    public function test_branch_creation_follows_api_contract()
    {
        $response = $this->postJson('/api/branches', [
            'name' => 'Test Branch',
            'address' => 'Test Address',
            'telephone' => '021-12345678'
        ]);
        
        // Contract validation
        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message', 
                'data' => [
                    'id',
                    'name',
                    'address', 
                    'telephone',
                    'created_at',
                    'updated_at'
                ]
            ])
            ->assertJson([
                'success' => true,
                'message' => 'Branch created successfully'
            ]);
            
        // Data type validation
        $data = $response->json('data');
        $this->assertIsInt($data['id']);
        $this->assertIsString($data['name']);
        $this->assertMatchesRegularExpression('/^\d{4}-\d{2}-\d{2}T/', $data['created_at']);
    }
}
```

#### **🏆 Keuntungan API-First Development**

**1. Parallel Development**
- Frontend dan backend teams dapat bekerja secara bersamaan
- Mengurangi waktu development cycle

**2. Consistent API Design**
- API contract sebagai single source of truth
- Mengurangi miscommunication antar team

**3. Better Testing Strategy**
- Contract testing memastikan API consistency
- Easier mocking dan testing

**4. Documentation-Driven**
- API documentation selalu up-to-date
- Lebih mudah untuk onboarding developer baru

**5. Scalable Architecture**
- Mudah untuk add new clients (mobile, web, integrations)
- Microservices-ready architecture

#### **🚀 Tools Ecosystem untuk API-First**

```bash
# API Design & Documentation
- OpenAPI/Swagger Specification
- Postman Collections
- Insomnia REST Client

# Mock API Development  
- JSON Server
- WireMock
- Prism Mock Server

# Contract Testing
- Pact (Consumer-Driven Contracts)
- OpenAPI Validator
- Spectral (API Linting)

# Laravel Specific
- Laravel Sanctum (Authentication)
- Laravel API Resources
- Spatie Laravel API Health Check
```

### **REST API Testing Focus**

```http
# HTTP Request Testing
POST /api/branches HTTP/1.1
Content-Type: application/json
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGc...

{
    "name": "Cabang Jakarta Selatan",
    "address": "Jl. Sudirman No. 123",
    "telephone": "021-87654321"
}

# HTTP Response Testing  
HTTP/1.1 201 Created
Content-Type: application/json

{
    "success": true,
    "message": "Branch created successfully",
    "data": {
        "id": 15,
        "name": "Cabang Jakarta Selatan",
        "address": "Jl. Sudirman No. 123",
        "telephone": "021-87654321",
        "created_at": "2025-08-11T14:30:00.000000Z",
        "updated_at": "2025-08-11T14:30:00.000000Z"
    }
}
```

### **Industry Context: API-First Development**

Modern applications menggunakan **API-First approach**, yang mengubah fundamental cara kita melakukan testing:

| Architecture Pattern | API Testing Importance | Testing Strategy |
|----------------------|------------------------|------------------|
| **Mobile Apps** | ⭐⭐⭐⭐⭐ Critical | API contract validation, response time testing |
| **Single Page Apps (SPA)** | ⭐⭐⭐⭐⭐ Critical | JSON API testing, state management APIs |
| **Microservices** | ⭐⭐⭐⭐⭐ Critical | Service-to-service API testing, contract testing |
| **Third-party Integrations** | ⭐⭐⭐⭐ High | External API mocking, error handling testing |
| **Multi-platform Apps** | ⭐⭐⭐⭐⭐ Critical | Cross-platform API consistency testing |

#### **🔄 API-First Testing Impact**

**Traditional Testing Pyramid:**
```
    /\     E2E Tests (Few, Slow, Expensive)
   /  \    
  /____\   Integration Tests (Some, Medium)
 /______\  Unit Tests (Many, Fast, Cheap)
```

**API-First Testing Pyramid:**
```
    /\     E2E Tests (Few, Slow, Expensive)
   /  \    
  /____\   API Contract Tests (Many, Fast, Critical) 🆕
 /______\  Unit Tests (Many, Fast, Cheap)
  \____/   API Integration Tests (Some, Medium) 🆕
```

#### **💡 Why API Testing is Critical in API-First World**

1. **Frontend Independence**: Frontend dapat dikembangkan terpisah dari backend
2. **Contract Stability**: API contract testing memastikan breaking changes tidak terjadi
3. **Multi-client Support**: Satu API melayani multiple clients (web, mobile, desktop)
4. **Performance Requirements**: API response time directly impact user experience
5. **Security Boundaries**: API adalah titik masuk utama untuk security threats

---

## **DASAR TEORI API TESTING**

### **1. HTTP Status Code Testing**

```php
// Success Status Codes
200 OK          → GET requests successful
201 Created     → POST requests successful  
204 No Content  → DELETE requests successful

// Client Error Status Codes
400 Bad Request    → Invalid request format
401 Unauthorized   → Authentication required
403 Forbidden      → Access denied
404 Not Found      → Resource doesn't exist
422 Unprocessable  → Validation errors

// Server Error Status Codes
500 Internal Error → Server-side errors
503 Service Unavailable → Server overload
```

### **2. API Response Structure Testing**

```php
// Standardized API Response Format
{
    "success": boolean,           // Operation status
    "message": string,           // Human-readable message
    "data": object|array|null,   // Response payload
    "meta": {                    // Metadata (pagination, etc.)
        "total": integer,
        "per_page": integer,
        "current_page": integer
    },
    "errors": object|null        // Validation errors
}
```

### **3. Authentication Testing Types**

```php
// 1. No Authentication (Public endpoints)
GET /api/public/branches

// 2. Token-based Authentication (Sanctum)
Authorization: Bearer 1|abc123def456...

// 3. Session Authentication (Web routes)
Cookie: laravel_session=abc123...

// 4. API Key Authentication
X-API-Key: your-api-key-here
```

### **4. API Contract Testing**

**Contract Testing** memastikan API response structure consistent:

```php
// Expected Contract
{
    "data": {
        "id": integer,
        "name": string,
        "address": string,
        "telephone": string,
        "status": boolean,
        "created_at": string (ISO 8601),
        "updated_at": string (ISO 8601)
    }
}

// Test validates actual response matches this contract
```

---

## **ALUR API TESTING LENGKAP**

### **FASE 1: PERSIAPAN API TESTING ENVIRONMENT** ⏱️ 20 menit

#### **Langkah 1.1: Setup API Routes**

Buat file API routes `routes/api.php`:
=======
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
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d

```php
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
<<<<<<< HEAD
use App\Http\Controllers\API\BranchController;
use App\Http\Controllers\API\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes for Testing
|--------------------------------------------------------------------------
*/

// Public routes
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/user', [AuthController::class, 'user']);
    
    // Branch CRUD API
    Route::apiResource('branches', BranchController::class);
    Route::get('branches/{branch}/statistics', [BranchController::class, 'statistics']);
});

// Version-specific routes
Route::prefix('v1')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::apiResource('branches', BranchController::class);
    });
});
```

#### **Langkah 1.2: Buat API Controllers**

Buat file `app/Http/Controllers/API/BranchController.php`:
=======
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
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d

```php
<?php

<<<<<<< HEAD
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBranchRequest;
use App\Http\Requests\UpdateBranchRequest;
use App\Http\Resources\BranchResource;
use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    /**
     * Display a listing of branches
     */
    public function index(Request $request)
    {
        $query = Branch::query();
        
        // Search functionality
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        // Status filter
        if ($request->has('status')) {
            $query->where('status', $request->boolean('status'));
        }
        
        $branches = $query->paginate($request->get('per_page', 10));
        
        return $this->successResponse(
            BranchResource::collection($branches),
            'Branches retrieved successfully'
        );
    }

    /**
     * Store a newly created branch
     */
    public function store(StoreBranchRequest $request)
    {
        $branch = Branch::create($request->validated());
        
        return $this->successResponse(
            new BranchResource($branch),
            'Branch created successfully',
            201
        );
    }

    /**
     * Display the specified branch
     */
    public function show(Branch $branch)
    {
        return $this->successResponse(
            new BranchResource($branch),
            'Branch retrieved successfully'
        );
    }

    /**
     * Update the specified branch
     */
    public function update(UpdateBranchRequest $request, Branch $branch)
    {
        $branch->update($request->validated());
        
        return $this->successResponse(
            new BranchResource($branch),
            'Branch updated successfully'
        );
    }

    /**
     * Remove the specified branch
     */
    public function destroy(Branch $branch)
    {
        $branch->delete();
        
        return $this->successResponse(
            null,
            'Branch deleted successfully'
        );
=======
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
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
    }

    /**
     * Get branch statistics
     */
<<<<<<< HEAD
    public function statistics(Branch $branch)
    {
        $statistics = [
            'total_employees' => $branch->employees()->count(),
            'total_warehouses' => $branch->warehouses()->count(),
            'monthly_revenue' => $branch->calculateMonthlyRevenue(),
            'status' => $branch->status ? 'active' : 'inactive'
        ];
        
        return $this->successResponse(
            $statistics,
            'Branch statistics retrieved successfully'
        );
    }

    /**
     * Success response helper
     */
    private function successResponse($data = null, $message = null, $code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $code);
    }
}
```

#### **Langkah 1.3: Setup API Resources**

Buat file `app/Http/Resources/BranchResource.php`:
=======
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
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d

```php
<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
<<<<<<< HEAD

class BranchResource extends JsonResource
{
    /**
     * Transform the resource into an array
     */
=======
use App\Constants\BranchColumns;

class BranchResource extends JsonResource
{
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
<<<<<<< HEAD
            'name' => $this->name,
            'address' => $this->address,
            'telephone' => $this->telephone,
            'status' => $this->status,
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
            
            // Conditional includes
            'employees_count' => $this->when(
                $request->routeIs('api.branches.show'),
                $this->employees()->count()
            ),
            
            'warehouses' => $this->when(
                $request->get('include') === 'warehouses',
                WarehouseResource::collection($this->warehouses)
            )
        ];
    }
}
```

#### **Langkah 1.4: Setup Authentication Controller**

Buat file `app/Http/Controllers/API/AuthController.php`:
=======
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
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d

```php
<?php

<<<<<<< HEAD
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer'
            ]
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout successful'
        ]);
    }

    public function user(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'User data retrieved successfully',
            'data' => $request->user()
        ]);
=======
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
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
    }
}
```

<<<<<<< HEAD
#### **Langkah 1.5: Setup API Request Validation**

Buat file `app/Http/Requests/StoreBranchRequest.php`:

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class StoreBranchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:50|unique:branches,name',
            'address' => 'required|string|min:10|max:200',
            'telephone' => 'required|string|min:8|max:20',
            'status' => 'sometimes|boolean'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Branch name is required',
            'name.unique' => 'Branch name already exists',
            'name.min' => 'Branch name must be at least 3 characters',
            'address.required' => 'Branch address is required',
            'address.min' => 'Address must be at least 10 characters',
            'telephone.required' => 'Telephone number is required'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Validation errors occurred',
                'errors' => $validator->errors()
            ], 422)
        );
    }
}
```

**✅ Checkpoint 1**: API environment setup complete dengan routes, controllers, dan validation

---

### **FASE 2: IMPLEMENTASI BASIC API TESTING** ⏱️ 30 menit

#### **Langkah 2.1: Setup API Test Base Class**

Buat file `tests/Feature/API/APITestCase.php`:
=======
---

## **API TESTING IMPLEMENTATION**

### **1. File Test Utama: BranchApiTest.php**

File `tests/Feature/BranchApiTest.php` berisi comprehensive test cases:
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d

```php
<?php

<<<<<<< HEAD
namespace Tests\Feature\API;

use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class APITestCase extends TestCase
{
    use RefreshDatabase;

    protected User $apiUser;
    protected string $apiToken;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create API user for testing
        $this->apiUser = User::factory()->create([
            'email' => 'api-test@example.com',
            'name' => 'API Test User'
        ]);
    }

    /**
     * Authenticate API user using Sanctum
     */
    protected function authenticateApi(User $user = null): self
    {
        $user = $user ?? $this->apiUser;
        
        Sanctum::actingAs($user, ['*']);
        
        return $this;
    }

    /**
     * Get authentication headers with Bearer token
     */
    protected function getAuthHeaders(User $user = null): array
    {
        $user = $user ?? $this->apiUser;
        $token = $user->createToken('test-token')->plainTextToken;
        
        return [
            'Authorization' => "Bearer {$token}",
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ];
    }

    /**
     * Assert API response structure
     */
    protected function assertApiResponseStructure(
        $response, 
        array $dataStructure = null
    ): void {
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => $dataStructure
        ]);
    }

    /**
     * Assert API success response
     */
    protected function assertApiSuccess(
        $response, 
        int $status = 200, 
        string $message = null
    ): void {
        $response->assertStatus($status)
                 ->assertJson(['success' => true]);
                 
        if ($message) {
            $response->assertJsonPath('message', $message);
        }
    }

    /**
     * Assert API error response
     */
    protected function assertApiError(
        $response, 
        int $status = 400, 
        string $message = null
    ): void {
        $response->assertStatus($status)
                 ->assertJson(['success' => false]);
                 
        if ($message) {
            $response->assertJsonPath('message', $message);
        }
    }
}
```

#### **Langkah 2.2: API Authentication Testing**

Buat file `tests/Feature/API/AuthenticationAPITest.php`:

```php
<?php

namespace Tests\Feature\API;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthenticationAPITest extends APITestCase
{
    /**
     * Test successful login via API
     * @test
     */
    public function user_can_login_via_api(): void
    {
        // Arrange
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123')
        ]);

        // Act
        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        // Assert
        $this->assertApiSuccess($response, 200, 'Login successful');
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'user' => ['id', 'name', 'email'],
                'token',
                'token_type'
            ]
        ]);
        
        $this->assertEquals('Bearer', $response->json('data.token_type'));
        $this->assertNotEmpty($response->json('data.token'));
    }

    /**
     * Test login with invalid credentials
     * @test
     */
    public function login_fails_with_invalid_credentials(): void
    {
        // Arrange
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('correct-password')
        ]);

        // Act
        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'wrong-password'
        ]);

        // Assert
        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }

    /**
     * Test login validation errors
     * @test
     */
    public function login_validates_required_fields(): void
    {
        // Act
        $response = $this->postJson('/api/auth/login', []);

        // Assert
        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email', 'password']);
    }

    /**
     * Test authenticated user can access protected routes
     * @test
     */
    public function authenticated_user_can_access_protected_routes(): void
    {
        // Arrange
        $this->authenticateApi();

        // Act
        $response = $this->getJson('/api/auth/user');

        // Assert
        $this->assertApiSuccess($response, 200, 'User data retrieved successfully');
        $response->assertJsonPath('data.email', $this->apiUser->email);
    }

    /**
     * Test logout functionality
     * @test
     */
    public function user_can_logout_via_api(): void
    {
        // Arrange
        $this->authenticateApi();

        // Act
        $response = $this->postJson('/api/auth/logout');

        // Assert
        $this->assertApiSuccess($response, 200, 'Logout successful');
    }

    /**
     * Test unauthenticated access to protected routes
     * @test
     */
    public function unauthenticated_requests_are_rejected(): void
    {
        // Act
        $response = $this->getJson('/api/auth/user');

        // Assert
        $response->assertStatus(401)
                 ->assertJson(['message' => 'Unauthenticated.']);
    }
}
```

#### **Langkah 2.3: Basic CRUD API Testing**

Buat file `tests/Feature/API/BranchCrudAPITest.php`:

```php
<?php

namespace Tests\Feature\API;

use App\Models\Branch;

class BranchCrudAPITest extends APITestCase
{
    /**
     * Test API can list all branches
     * @test
     */
    public function api_can_list_all_branches(): void
    {
        // Arrange
        $this->authenticateApi();
        Branch::factory()->count(3)->create();

        // Act
        $response = $this->getJson('/api/branches');

        // Assert
        $this->assertApiSuccess($response, 200, 'Branches retrieved successfully');
        $response->assertJsonStructure([
            'success',
            'message', 
            'data' => [
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'address',
                        'telephone',
                        'status',
                        'created_at',
                        'updated_at'
                    ]
                ],
                'current_page',
                'per_page',
                'total'
            ]
        ]);
        
        $this->assertCount(3, $response->json('data.data'));
    }

    /**
     * Test API can create a new branch
     * @test
     */
    public function api_can_create_new_branch(): void
    {
        // Arrange
        $this->authenticateApi();
        $branchData = [
            'name' => 'API Test Branch',
            'address' => 'Jl. API Testing No. 123, Jakarta',
            'telephone' => '021-API-TEST'
        ];

        // Act
        $response = $this->postJson('/api/branches', $branchData);

        // Assert
        $this->assertApiSuccess($response, 201, 'Branch created successfully');
        $response->assertJsonPath('data.name', 'API Test Branch');
        $response->assertJsonPath('data.address', 'Jl. API Testing No. 123, Jakarta');
        
        $this->assertDatabaseHas('branches', $branchData);
    }

    /**
     * Test API can show specific branch
     * @test
     */
    public function api_can_show_specific_branch(): void
    {
        // Arrange
        $this->authenticateApi();
        $branch = Branch::factory()->create([
            'name' => 'Specific Branch Test'
        ]);

        // Act
        $response = $this->getJson("/api/branches/{$branch->id}");

        // Assert
        $this->assertApiSuccess($response, 200, 'Branch retrieved successfully');
        $response->assertJsonPath('data.id', $branch->id);
        $response->assertJsonPath('data.name', 'Specific Branch Test');
    }

    /**
     * Test API can update existing branch
     * @test
     */
    public function api_can_update_existing_branch(): void
    {
        // Arrange
        $this->authenticateApi();
        $branch = Branch::factory()->create([
            'name' => 'Original Name'
        ]);
        
        $updateData = [
            'name' => 'Updated Branch Name',
            'address' => 'Updated Address 456',
            'telephone' => '021-UPDATED'
        ];

        // Act
        $response = $this->putJson("/api/branches/{$branch->id}", $updateData);

        // Assert
        $this->assertApiSuccess($response, 200, 'Branch updated successfully');
        $response->assertJsonPath('data.name', 'Updated Branch Name');
        
        $this->assertDatabaseHas('branches', [
            'id' => $branch->id,
            'name' => 'Updated Branch Name'
=======
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
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
        ]);
    }

    /**
<<<<<<< HEAD
     * Test API can delete branch
     * @test
     */
    public function api_can_delete_branch(): void
    {
        // Arrange
        $this->authenticateApi();
        $branch = Branch::factory()->create();

        // Act
        $response = $this->deleteJson("/api/branches/{$branch->id}");

        // Assert
        $this->assertApiSuccess($response, 200, 'Branch deleted successfully');
        $this->assertDatabaseMissing('branches', ['id' => $branch->id]);
    }

    /**
     * Test API returns 404 for non-existent branch
     * @test
     */
    public function api_returns_404_for_non_existent_branch(): void
    {
        // Arrange
        $this->authenticateApi();

        // Act
        $response = $this->getJson('/api/branches/999999');

        // Assert
        $response->assertStatus(404);
    }
}
```

**✅ Checkpoint 2**: Basic API testing implemented untuk authentication dan CRUD operations

---

### **FASE 3: ADVANCED API TESTING** ⏱️ 40 menit

#### **Langkah 3.1: API Validation Testing**

Buat file `tests/Feature/API/BranchValidationAPITest.php`:

```php
<?php

namespace Tests\Feature\API;

use App\Models\Branch;

class BranchValidationAPITest extends APITestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->authenticateApi();
    }

    /**
     * Test API validates required fields
     * @test
     */
    public function api_validates_required_fields(): void
    {
        // Act
        $response = $this->postJson('/api/branches', []);

        // Assert
        $this->assertApiError($response, 422, 'Validation errors occurred');
        $response->assertJsonValidationErrors([
            'name', 'address', 'telephone'
        ]);
    }

    /**
     * Test API validates field lengths
     * @test
     * @dataProvider fieldLengthProvider
     */
    public function api_validates_field_lengths($field, $value, $shouldFail): void
    {
        // Arrange
        $data = [
            'name' => 'Valid Branch Name',
            'address' => 'Valid Address with sufficient length',
            'telephone' => '021-12345678'
        ];
        $data[$field] = $value;

        // Act
        $response = $this->postJson('/api/branches', $data);

        // Assert
        if ($shouldFail) {
            $response->assertStatus(422)
                     ->assertJsonValidationErrors([$field]);
        } else {
            $this->assertApiSuccess($response, 201);
        }
    }

    public function fieldLengthProvider(): array
    {
        return [
            // name field tests
            ['name', 'AB', true],                    // Too short (min 3)
            ['name', 'ABC', false],                  // Minimum valid
            ['name', str_repeat('A', 50), false],    // Maximum valid
            ['name', str_repeat('A', 51), true],     // Too long (max 50)
            
            // address field tests
            ['address', 'Short', true],              // Too short (min 10)
            ['address', '1234567890', false],        // Minimum valid
            ['address', str_repeat('A', 200), false], // Maximum valid
            ['address', str_repeat('A', 201), true], // Too long (max 200)
            
            // telephone field tests
            ['telephone', '123456', true],           // Too short (min 8)
            ['telephone', '12345678', false],        // Minimum valid
            ['telephone', str_repeat('1', 20), false], // Maximum valid
            ['telephone', str_repeat('1', 21), true], // Too long (max 20)
        ];
    }

    /**
     * Test API validates unique branch name
     * @test
     */
    public function api_validates_unique_branch_name(): void
    {
        // Arrange
        Branch::factory()->create(['name' => 'Existing Branch']);

        // Act
        $response = $this->postJson('/api/branches', [
            'name' => 'Existing Branch',
            'address' => 'Some address here',
            'telephone' => '021-12345678'
        ]);

        // Assert
        $this->assertApiError($response, 422);
        $response->assertJsonValidationErrors(['name']);
        $response->assertJsonPath('errors.name.0', 'Branch name already exists');
    }

    /**
     * Test API validates boolean status field
     * @test
     * @dataProvider booleanStatusProvider
     */
    public function api_validates_boolean_status($status, $shouldPass): void
    {
        // Act
        $response = $this->postJson('/api/branches', [
            'name' => 'Test Branch',
            'address' => 'Test Address 123',
            'telephone' => '021-12345678',
            'status' => $status
        ]);

        // Assert
        if ($shouldPass) {
            $this->assertApiSuccess($response, 201);
        } else {
            $response->assertStatus(422)
                     ->assertJsonValidationErrors(['status']);
        }
    }

    public function booleanStatusProvider(): array
    {
        return [
            [true, true],
            [false, true],
            [1, true],
            [0, true],
            ['true', false],
            ['false', false],
            ['invalid', false],
            [[], false]
        ];
    }

    /**
     * Test API validation error message format
     * @test
     */
    public function api_returns_proper_validation_error_format(): void
    {
        // Act
        $response = $this->postJson('/api/branches', [
            'name' => 'AB', // Too short
            'address' => '',  // Required
            'telephone' => '123' // Too short
        ]);

        // Assert
        $response->assertStatus(422)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'errors' => [
                         'name',
                         'address', 
                         'telephone'
                     ]
                 ]);
                 
        $this->assertFalse($response->json('success'));
        $this->assertEquals('Validation errors occurred', $response->json('message'));
    }
}
```

#### **Langkah 3.2: API Search dan Filtering Testing**

Buat file `tests/Feature/API/BranchSearchAPITest.php`:

```php
<?php

namespace Tests\Feature\API;

use App\Models\Branch;

class BranchSearchAPITest extends APITestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->authenticateApi();
        
        // Create test data
        Branch::factory()->create(['name' => 'Jakarta Pusat', 'status' => true]);
        Branch::factory()->create(['name' => 'Jakarta Selatan', 'status' => true]);
        Branch::factory()->create(['name' => 'Bandung Utara', 'status' => false]);
        Branch::factory()->create(['name' => 'Surabaya Timur', 'status' => true]);
    }

    /**
     * Test API search functionality
     * @test
     */
    public function api_can_search_branches_by_name(): void
    {
        // Act
        $response = $this->getJson('/api/branches?search=Jakarta');

        // Assert
        $this->assertApiSuccess($response);
        $branches = $response->json('data.data');
        
        $this->assertCount(2, $branches);
        $this->assertStringContainsString('Jakarta', $branches[0]['name']);
        $this->assertStringContainsString('Jakarta', $branches[1]['name']);
    }

    /**
     * Test API status filter
     * @test
     */
    public function api_can_filter_branches_by_status(): void
    {
        // Test active branches
        $response = $this->getJson('/api/branches?status=1');
        $this->assertApiSuccess($response);
        $activeBranches = $response->json('data.data');
        
        $this->assertCount(3, $activeBranches);
        foreach ($activeBranches as $branch) {
            $this->assertTrue($branch['status']);
        }

        // Test inactive branches
        $response = $this->getJson('/api/branches?status=0');
        $this->assertApiSuccess($response);
        $inactiveBranches = $response->json('data.data');
        
        $this->assertCount(1, $inactiveBranches);
        $this->assertFalse($inactiveBranches[0]['status']);
    }

    /**
     * Test API pagination
     * @test
     */
    public function api_paginates_branch_results(): void
    {
        // Create more test data
        Branch::factory()->count(15)->create();

        // Act
        $response = $this->getJson('/api/branches?per_page=5');

        // Assert
        $this->assertApiSuccess($response);
        $response->assertJsonStructure([
            'data' => [
                'data',
                'current_page',
                'per_page',
                'total',
                'last_page',
                'from',
                'to'
            ]
        ]);
        
        $this->assertEquals(5, $response->json('data.per_page'));
        $this->assertCount(5, $response->json('data.data'));
        $this->assertGreaterThanOrEqual(19, $response->json('data.total')); // 4 setup + 15 factory
    }

    /**
     * Test combined search and filter
     * @test
     */
    public function api_can_combine_search_and_filter(): void
    {
        // Act
        $response = $this->getJson('/api/branches?search=Jakarta&status=1');

        // Assert
        $this->assertApiSuccess($response);
        $branches = $response->json('data.data');
        
        $this->assertCount(2, $branches);
        foreach ($branches as $branch) {
            $this->assertStringContainsString('Jakarta', $branch['name']);
            $this->assertTrue($branch['status']);
        }
    }

    /**
     * Test search with no results
     * @test
     */
    public function api_returns_empty_results_for_no_matches(): void
    {
        // Act
        $response = $this->getJson('/api/branches?search=NonExistentCity');

        // Assert
        $this->assertApiSuccess($response);
        $this->assertCount(0, $response->json('data.data'));
    }
}
```

#### **Langkah 3.3: API Performance Testing**

Buat file `tests/Feature/API/BranchPerformanceAPITest.php`:

```php
<?php

namespace Tests\Feature\API;

use App\Models\Branch;

class BranchPerformanceAPITest extends APITestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->authenticateApi();
    }

    /**
     * Test API response time for listing branches
     * @test
     */
    public function api_listing_responds_within_acceptable_time(): void
    {
        // Arrange - Create substantial test data
        Branch::factory()->count(100)->create();

        // Act
        $startTime = microtime(true);
        $response = $this->getJson('/api/branches');
        $endTime = microtime(true);

        // Calculate response time in milliseconds
        $responseTime = ($endTime - $startTime) * 1000;

        // Assert
        $this->assertApiSuccess($response);
        $this->assertLessThan(1000, $responseTime, 
            "API response time ({$responseTime}ms) exceeds 1000ms threshold"
        );
    }

    /**
     * Test API response time for creating branch
     * @test
     */
    public function api_creation_responds_within_acceptable_time(): void
    {
        // Arrange
        $branchData = [
            'name' => 'Performance Test Branch',
            'address' => 'Performance Test Address 123',
            'telephone' => '021-PERF-TEST'
        ];

        // Act
        $startTime = microtime(true);
        $response = $this->postJson('/api/branches', $branchData);
        $endTime = microtime(true);

        // Calculate response time in milliseconds
        $responseTime = ($endTime - $startTime) * 1000;

        // Assert
        $this->assertApiSuccess($response, 201);
        $this->assertLessThan(500, $responseTime,
            "API creation time ({$responseTime}ms) exceeds 500ms threshold"
        );
    }

    /**
     * Test API memory usage during bulk operations
     * @test
     */
    public function api_handles_bulk_operations_efficiently(): void
    {
        // Arrange
        $initialMemory = memory_get_usage(true);
        Branch::factory()->count(50)->create();

        // Act
        $response = $this->getJson('/api/branches?per_page=50');

        // Check memory usage
        $finalMemory = memory_get_usage(true);
        $memoryIncrease = $finalMemory - $initialMemory;
        $memoryIncreaseMB = $memoryIncrease / 1024 / 1024;

        // Assert
        $this->assertApiSuccess($response);
        $this->assertLessThan(50, $memoryIncreaseMB,
            "Memory increase ({$memoryIncreaseMB}MB) exceeds 50MB threshold"
        );
    }

    /**
     * Test API concurrent request handling
     * @test
     */
    public function api_handles_concurrent_requests(): void
    {
        // Create test data
        $branches = Branch::factory()->count(5)->create();

        // Simulate concurrent requests
        $responses = [];
        $startTime = microtime(true);

        foreach ($branches as $branch) {
            $responses[] = $this->getJson("/api/branches/{$branch->id}");
        }

        $endTime = microtime(true);
        $totalTime = ($endTime - $startTime) * 1000;

        // Assert all requests succeeded
        foreach ($responses as $response) {
            $this->assertApiSuccess($response);
        }

        // Assert reasonable total time for 5 requests
        $this->assertLessThan(2000, $totalTime,
            "Total time for 5 concurrent requests ({$totalTime}ms) exceeds 2000ms"
        );
    }

    /**
     * Test API rate limiting (if implemented)
     * @test
     */
    public function api_respects_rate_limits(): void
    {
        // Note: This test assumes rate limiting is configured
        // Skip if rate limiting is not implemented
        if (!config('app.rate_limiting_enabled', false)) {
            $this->markTestSkipped('Rate limiting not enabled');
        }

        // Simulate rapid requests
        $successfulRequests = 0;
        $rateLimitedRequests = 0;

        for ($i = 0; $i < 100; $i++) {
            $response = $this->getJson('/api/branches');
            
            if ($response->status() === 200) {
                $successfulRequests++;
            } elseif ($response->status() === 429) {
                $rateLimitedRequests++;
                break; // Stop when rate limited
            }
        }

        // Assert rate limiting kicks in
        $this->assertGreaterThan(0, $rateLimitedRequests,
            'Rate limiting should activate after multiple rapid requests'
        );
=======
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
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
    }
}
```

<<<<<<< HEAD
**✅ Checkpoint 3**: Advanced API testing implemented untuk validation, search, filtering, dan performance

---

### **FASE 4: API CONTRACT & INTEGRATION TESTING** ⏱️ 30 menit

#### **Langkah 4.1: API Contract Testing**

Buat file `tests/Feature/API/BranchContractAPITest.php`:
=======
### **2. Web vs API Testing Comparison**

Untuk membandingkan, terdapat juga `tests/Feature/Controllers/BranchControllerTest.php` yang menguji **web interface** dari controller yang sama:
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d

```php
<?php

<<<<<<< HEAD
namespace Tests\Feature\API;

use App\Models\Branch;

class BranchContractAPITest extends APITestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->authenticateApi();
    }

    /**
     * Test API response structure consistency for list endpoint
     * @test
     */
    public function api_list_response_follows_contract(): void
    {
        // Arrange
        Branch::factory()->count(2)->create();

        // Act
        $response = $this->getJson('/api/branches');

        // Assert - Test complete contract structure
        $this->assertApiSuccess($response);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'address',
                        'telephone',
                        'status',
                        'created_at',
                        'updated_at'
                    ]
                ],
                'current_page',
                'per_page',
                'total',
                'last_page',
                'from',
                'to',
                'first_page_url',
                'last_page_url',
                'next_page_url',
                'prev_page_url',
                'path',
                'links' => [
                    '*' => [
                        'url',
                        'label',
                        'active'
                    ]
                ]
            ]
        ]);

        // Verify data types
        $firstBranch = $response->json('data.data.0');
        $this->assertIsInt($firstBranch['id']);
        $this->assertIsString($firstBranch['name']);
        $this->assertIsString($firstBranch['address']);
        $this->assertIsString($firstBranch['telephone']);
        $this->assertIsBool($firstBranch['status']);
        $this->assertMatchesRegularExpression(
            '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}\.\d{6}Z$/',
            $firstBranch['created_at']
        );
    }

    /**
     * Test API response structure for single item endpoint
     * @test
     */
    public function api_show_response_follows_contract(): void
    {
        // Arrange
        $branch = Branch::factory()->create();

        // Act
        $response = $this->getJson("/api/branches/{$branch->id}");

        // Assert
        $this->assertApiSuccess($response);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'id',
                'name',
                'address',
                'telephone',
                'status',
                'created_at',
                'updated_at'
            ]
        ]);

        // Verify exact data matches
        $responseData = $response->json('data');
        $this->assertEquals($branch->id, $responseData['id']);
        $this->assertEquals($branch->name, $responseData['name']);
        $this->assertEquals($branch->address, $responseData['address']);
        $this->assertEquals($branch->telephone, $responseData['telephone']);
        $this->assertEquals($branch->status, $responseData['status']);
    }

    /**
     * Test API error response structure consistency
     * @test
     */
    public function api_error_response_follows_contract(): void
    {
        // Act - Request non-existent resource
        $response = $this->getJson('/api/branches/999999');

        // Assert
        $response->assertStatus(404)
                 ->assertJsonStructure([
                     'message'
                 ]);
    }

    /**
     * Test API validation error response structure
     * @test
     */
    public function api_validation_error_response_follows_contract(): void
    {
        // Act
        $response = $this->postJson('/api/branches', []);

        // Assert
        $response->assertStatus(422)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'errors' => [
                         'name',
                         'address',
                         'telephone'
                     ]
                 ]);

        $this->assertFalse($response->json('success'));
        $this->assertIsArray($response->json('errors.name'));
        $this->assertIsArray($response->json('errors.address'));
        $this->assertIsArray($response->json('errors.telephone'));
    }

    /**
     * Test API statistics endpoint contract
     * @test
     */
    public function api_statistics_response_follows_contract(): void
    {
        // Arrange
        $branch = Branch::factory()->create();

        // Act
        $response = $this->getJson("/api/branches/{$branch->id}/statistics");

        // Assert
        $this->assertApiSuccess($response);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'total_employees',
                'total_warehouses',
                'monthly_revenue',
                'status'
            ]
        ]);

        // Verify data types
        $stats = $response->json('data');
        $this->assertIsInt($stats['total_employees']);
        $this->assertIsInt($stats['total_warehouses']);
        $this->assertIsNumeric($stats['monthly_revenue']);
        $this->assertIsString($stats['status']);
        $this->assertContains($stats['status'], ['active', 'inactive']);
    }

    /**
     * Test API versioning contract
     * @test
     */
    public function api_versioning_maintains_contract(): void
    {
        // Test v1 API endpoint
        $response = $this->getJson('/api/v1/branches');

        // Should maintain same structure as non-versioned API
        $this->assertApiSuccess($response);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'address',
                        'telephone',
                        'status',
                        'created_at',
                        'updated_at'
                    ]
                ]
            ]
        ]);
=======
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
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
    }
}
```

<<<<<<< HEAD
#### **Langkah 4.2: Third-party Integration Testing**

Buat file `tests/Feature/API/ExternalIntegrationAPITest.php`:

```php
<?php

namespace Tests\Feature\API;

use App\Models\Branch;
use Illuminate\Support\Facades\Http;

class ExternalIntegrationAPITest extends APITestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->authenticateApi();
    }

    /**
     * Test external API integration for geocoding
     * @test
     */
    public function api_integrates_with_geocoding_service(): void
    {
        // Mock external geocoding API
        Http::fake([
            'api.geocoding.com/*' => Http::response([
                'latitude' => -6.2088,
                'longitude' => 106.8456,
                'address' => 'Jakarta, Indonesia'
            ], 200)
        ]);

        // Arrange
        $branchData = [
            'name' => 'Branch with Geocoding',
            'address' => 'Jl. Sudirman No. 1, Jakarta',
            'telephone' => '021-12345678',
            'enable_geocoding' => true
        ];

        // Act
        $response = $this->postJson('/api/branches', $branchData);

        // Assert
        $this->assertApiSuccess($response, 201);
        
        // Verify external API was called
        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'api.geocoding.com') &&
                   str_contains($request->body(), 'Jl. Sudirman');
        });
    }

    /**
     * Test external API failure handling
     * @test
     */
    public function api_handles_external_service_failures_gracefully(): void
    {
        // Mock external service failure
        Http::fake([
            'api.geocoding.com/*' => Http::response([], 500)
        ]);

        // Arrange
        $branchData = [
            'name' => 'Branch with Failed Geocoding',
            'address' => 'Jl. Test No. 1, Jakarta',
            'telephone' => '021-87654321',
            'enable_geocoding' => true
        ];

        // Act
        $response = $this->postJson('/api/branches', $branchData);

        // Assert - Should still create branch despite geocoding failure
        $this->assertApiSuccess($response, 201);
        $response->assertJsonPath('data.name', 'Branch with Failed Geocoding');
        
        // Verify branch was created without coordinates
        $this->assertDatabaseHas('branches', [
            'name' => 'Branch with Failed Geocoding',
            'latitude' => null,
            'longitude' => null
        ]);
    }

    /**
     * Test webhook integration
     * @test
     */
    public function api_triggers_webhooks_on_branch_creation(): void
    {
        // Mock webhook endpoint
        Http::fake([
            'webhook.example.com/*' => Http::response(['received' => true], 200)
        ]);

        // Arrange
        $branchData = [
            'name' => 'Webhook Test Branch',
            'address' => 'Webhook Test Address',
            'telephone' => '021-WEBHOOK'
        ];

        // Act
        $response = $this->postJson('/api/branches', $branchData);

        // Assert
        $this->assertApiSuccess($response, 201);
        
        // Verify webhook was triggered
        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'webhook.example.com') &&
                   $request->method() === 'POST' &&
                   json_decode($request->body(), true)['event'] === 'branch.created';
        });
    }

    /**
     * Test API rate limiting with external services
     * @test
     */
    public function api_respects_external_service_rate_limits(): void
    {
        // Mock rate-limited external service
        Http::fake([
            'api.slow-service.com/*' => Http::sequence()
                ->push(['data' => 'success'], 200)
                ->push([], 429, ['Retry-After' => '60'])
                ->push(['data' => 'success'], 200)
        ]);

        // First request should succeed
        $response1 = $this->postJson('/api/branches/external-data', [
            'service' => 'slow-service'
        ]);
        $this->assertApiSuccess($response1);

        // Second request should handle rate limit
        $response2 = $this->postJson('/api/branches/external-data', [
            'service' => 'slow-service'
        ]);
        $response2->assertStatus(429);

        // Third request should succeed after retry
        $response3 = $this->postJson('/api/branches/external-data', [
            'service' => 'slow-service'
        ]);
        $this->assertApiSuccess($response3);
=======
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
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
    }
}
```

<<<<<<< HEAD
**✅ Checkpoint 4**: API contract testing dan external integration testing implemented

---

## **MENJALANKAN API TESTS**

### **Execution Commands**

```bash
# Run semua API tests
php artisan test --testsuite=Feature tests/Feature/API/

# Run specific API test class
php artisan test tests/Feature/API/BranchCrudAPITest.php

# Run dengan verbose output untuk debugging
php artisan test tests/Feature/API/ --verbose

# Run dengan coverage untuk API tests
php artisan test tests/Feature/API/ --coverage-text

# Run performance tests specifically
php artisan test tests/Feature/API/BranchPerformanceAPITest.php

# Filter specific test methods
php artisan test --filter=test_api_can_create_new_branch
```

### **Performance Expectations**

```
API Testing Performance Targets:
🚀 Individual API Test: < 500ms
🚀 Full API Test Suite: < 30 seconds
🚀 API Response Time: < 1000ms
🚀 Memory Usage: < 100MB per test
🚀 Success Rate: 99%+ for stable APIs
```

---

## **BEST PRACTICES API TESTING**

### **1. Test Organization**

```php
// Group tests by API functionality
class BranchAPITest extends APITestCase
{
    // Happy path tests first
    public function test_api_can_list_branches() { }
    public function test_api_can_create_branch() { }
    
    // Error conditions
    public function test_api_validates_required_fields() { }
    public function test_api_handles_non_existent_resources() { }
    
    // Edge cases
    public function test_api_handles_large_datasets() { }
    public function test_api_handles_concurrent_requests() { }
}
```

### **2. Authentication Strategy**

```php
// Consistent authentication across tests
abstract class APITestCase extends TestCase
{
    protected function authenticateAs($user = null): self
    {
        Sanctum::actingAs($user ?? User::factory()->create());
        return $this;
    }
    
    protected function withApiHeaders($user = null): array
    {
        $token = ($user ?? $this->apiUser)->createToken('test')->plainTextToken;
        return ['Authorization' => "Bearer {$token}"];
    }
}
```

### **3. Response Assertion Helpers**

```php
// Custom assertions untuk API responses
protected function assertApiResponseStructure($response, $structure = null)
{
    $response->assertJsonStructure([
        'success',
        'message',
        'data' => $structure
    ]);
}

protected function assertApiPagination($response)
{
    $response->assertJsonStructure([
        'data' => [
            'current_page',
            'per_page', 
            'total',
            'data'
        ]
    ]);
}
```

### **4. Data Management**

```php
// Use factories untuk consistent test data
public function test_api_with_factory_data()
{
    $branches = Branch::factory()->count(5)->create();
    
    $response = $this->getJson('/api/branches');
    
    $this->assertCount(5, $response->json('data.data'));
}

// Clean test data appropriately
protected function tearDown(): void
{
    Branch::query()->forceDelete(); // For soft-deletes
    parent::tearDown();
=======
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
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
}
```

---

<<<<<<< HEAD
## **TROUBLESHOOTING API TESTING**

### **Common Issues & Solutions**

#### **Issue 1: Authentication Failures**
```php
// Problem: 401 Unauthorized errors
// Solution: Verify Sanctum setup dan token generation

// Check sanctum configuration
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"

// Verify API authentication
$response = $this->withHeaders([
    'Authorization' => 'Bearer ' . $user->createToken('test')->plainTextToken
])->getJson('/api/branches');
```

#### **Issue 2: JSON Structure Mismatches**
```php
// Problem: assertJsonStructure failures
// Solution: Debug actual response structure

public function test_debug_response_structure()
{
    $response = $this->getJson('/api/branches');
    
    // Debug actual response
    dump($response->json());
    
    // Then write correct assertion
    $response->assertJsonStructure([...]);
}
```

#### **Issue 3: Database State Issues**
```php
// Problem: Tests affecting each other
// Solution: Proper database cleanup

class APITestCase extends TestCase
{
    use RefreshDatabase; // Complete database refresh
    
    // Or use transactions for speed
    use DatabaseTransactions;
}
```

#### **Issue 4: External Service Mocking**
```php
// Problem: External API calls in tests
// Solution: Use HTTP fake

protected function setUp(): void
{
    parent::setUp();
    
    // Mock all external HTTP calls
    Http::fake([
        'external-api.com/*' => Http::response(['data' => 'mocked'], 200)
    ]);
}
```

---

## **EVALUASI DAN PENILAIAN**

### **Kriteria Penilaian API Testing** (100 poin total)

| Aspek | Bobot | Kriteria Excellence | Kriteria Good | Kriteria Fair |
|-------|-------|-------------------|---------------|---------------|
| **CRUD Coverage** | 25 poin | Complete CRUD dengan edge cases | All CRUD operations tested | Basic CRUD testing |
| **Authentication** | 20 poin | Multiple auth scenarios, proper security testing | Basic auth testing | Minimal auth coverage |
| **Validation Testing** | 20 poin | Comprehensive validation scenarios dengan data providers | Good validation coverage | Basic validation tests |
| **Error Handling** | 15 poin | All error scenarios covered, proper status codes | Major error cases tested | Basic error handling |
| **Contract Testing** | 10 poin | Complete response structure validation | Basic structure testing | Minimal structure checks |
| **Performance** | 10 poin | Response time testing, load testing | Basic performance checks | No performance testing |

### **Additional Assessment Criteria**
- **API Documentation**: Tests serve as living documentation ✅
- **Real-world Scenarios**: Tests cover actual use cases ✅
- **Integration Testing**: External service integration tested ✅
- **Security Testing**: Authentication dan authorization thoroughly tested ✅

### **Deliverables**
- [ ] Complete API test suite untuk Branch CRUD
- [ ] Authentication testing dengan Sanctum
- [ ] Validation testing dengan comprehensive scenarios
- [ ] Contract testing untuk API response consistency
- [ ] Performance testing untuk response times
- [ ] Integration testing untuk external services
- [ ] Documentation README untuk API testing approach

---

## **REFERENSI**

1. **Laravel HTTP Testing**: https://laravel.com/docs/http-tests
2. **Laravel Sanctum Documentation**: https://laravel.com/docs/sanctum
3. **RESTful API Design**: https://restfulapi.net/
4. **API Testing Best Practices**: https://www.postman.com/api-testing/
5. **HTTP Status Codes**: https://httpstatuses.com/
6. **JSON API Specification**: https://jsonapi.org/

---

## **KESIMPULAN: API TESTING DALAM MODERN DEVELOPMENT**

### **Key Takeaways** 🎯

1. **API Testing essential** untuk modern web applications
2. **Complete coverage** mencakup authentication, CRUD, validation, dan error handling
3. **Contract testing** memastikan API consistency untuk clients
4. **Performance testing** critical untuk user experience
5. **Integration testing** dengan external services penting untuk real-world scenarios

### **Industry Reality** 📊

```
Modern Application Architecture:
📱 Mobile Apps        → 100% API dependent
🌐 Single Page Apps   → 90% API dependent  
🔧 Microservices     → 100% API dependent
🔗 Third-party Integrations → API contracts critical
```

### **API Testing Strategy** ✅

```
API Testing Focus Distribution:
🎯 CRUD Operations     (30%)
🔐 Authentication      (25%)
✅ Validation         (20%)
⚡ Performance        (15%)
🔗 Integration        (10%)
```

### **Future Considerations** 🚀

- **GraphQL Testing**: Next evolution of API testing
- **Real-time APIs**: WebSocket dan Server-Sent Events testing
- **API Gateway Testing**: Multiple service integration
- **API Security Testing**: Advanced security scenarios

**🎯 API Testing adalah foundation untuk reliable, scalable, dan maintainable modern applications!**
=======
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
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
