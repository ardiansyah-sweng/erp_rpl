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

---

## **TUJUAN PEMBELAJARAN**

Setelah menyelesaikan praktikum API testing ini, mahasiswa diharapkan mampu:

1. **Memahami konsep API Testing** dan perbedaannya dengan testing types lain
2. **Mengimplementasikan REST API testing** dengan HTTP requests/responses
3. **Menguji API authentication dan authorization** (Sanctum/Passport)
4. **Melakukan API contract testing** untuk memastikan konsistensi response structure
5. **Menggunakan Laravel HTTP Testing helpers** untuk API endpoint testing
6. **Mengimplementasikan comprehensive API test coverage** untuk CRUD operations
7. **Menguji API error handling dan validation** dengan berbagai scenarios
8. **Menganalisis API performance dan response time** dalam testing

---

## **PENDAHULUAN**

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

```php
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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

```php
<?php

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
    }

    /**
     * Get branch statistics
     */
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

```php
<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BranchResource extends JsonResource
{
    /**
     * Transform the resource into an array
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
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

```php
<?php

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
    }
}
```

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

```php
<?php

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
        ]);
    }

    /**
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
    }
}
```

**✅ Checkpoint 3**: Advanced API testing implemented untuk validation, search, filtering, dan performance

---

### **FASE 4: API CONTRACT & INTEGRATION TESTING** ⏱️ 30 menit

#### **Langkah 4.1: API Contract Testing**

Buat file `tests/Feature/API/BranchContractAPITest.php`:

```php
<?php

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
    }
}
```

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
    }
}
```

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
}
```

---

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
