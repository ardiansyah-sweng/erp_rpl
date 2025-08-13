# PRAKTIKUM 02: INTEGRATION TESTING (FEATURE TESTING)
## Laravel HTTP Endpoint Testing dengan Database Integration

### **Informasi Modul**
- **Mata Kuliah**: Rekayasa Perangkat Lunak / Software Testing
- **Topik**: Integration Testing - HTTP Endpoint & Feature Testing
- **Framework**: Laravel 10/11 dengan PHPUnit
- **Durasi**: 90 menit (1.5 jam)
- **Level**: Intermediate

> **📋 NOTE**: Untuk Pure Unit Testing, lihat: **[MODUL_PRAKTIKUM_PURE_UNIT_TESTING.md](./MODUL_PRAKTIKUM_PURE_UNIT_TESTING.md)**  
> **📋 NOTE**: Untuk Model Integration Testing, lihat: **[MODUL_PRAKTIKUM_UNIT_TESTING.md](./MODUL_PRAKTIKUM_UNIT_TESTING.md)**

---

## **TUJUAN PEMBELAJARAN**

Setelah menyelesaikan praktikum integration testing ini, mahasiswa diharapkan mampu:

1. **Memahami konsep Feature/Integration Testing** dalam pengembangan web Laravel
2. **Membedakan Feature Testing dari Unit Testing** dalam konteks praktis  
3. **Mengimplementasikan HTTP Endpoint Testing** untuk complete user workflows
4. **Menggunakan Laravel Test Helpers** untuk authentication, validation, dan response testing
5. **Melakukan end-to-end testing** dari HTTP request hingga database response
6. **Mengoptimasi test suite performance** dengan database strategies
7. **Menguji business workflows** yang mencakup multiple components

---

## **DASAR TEORI INTEGRATION TESTING**

### **1. Apa itu Integration Testing?**

Integration Testing adalah proses testing yang memverifikasi interaksi antara komponen yang berbeda dalam sistem. Dalam konteks Laravel:

- **Target**: HTTP endpoints, Controller workflows, Database interactions
- **Scope**: End-to-end request/response cycle
- **Dependencies**: Real database, actual Laravel application
- **Speed**: Lebih lambat dari unit tests (seconds)

### **2. Feature Testing vs Integration Testing**

Dalam Laravel, Feature Testing adalah bentuk Integration Testing:

```
HTTP Request → Middleware → Controller → Model → Database → Response
     ↑                                                           ↓
   Feature Testing memverifikasi seluruh workflow ini
```

### **3. Boundary Value Analysis dalam Integration Testing**

Teknik testing yang fokus pada nilai-nilai batas untuk validasi input:

```
Untuk field dengan constraint min:3, max:50:
✅ Valid boundaries: 3, 50 (exactly at limits)
❌ Invalid boundaries: 2, 51 (just outside limits)
📊 Typical values: 10, 25, 30 (middle values)
```

**Mengapa Boundary Testing Efektif?**
- 80% bug ditemukan pada nilai-nilai batas
- Efisien: sedikit test case, coverage tinggi
- Mendeteksi validation errors dengan akurat

### **4. Data Provider Pattern**

Pattern untuk menjalankan test yang sama dengan multiple data sets:

```php
#[\PHPUnit\Framework\Attributes\DataProvider]
public static function boundaryTestProvider(): array
{
    return [
        'valid_min_length' => ['ABC', true],
        'invalid_too_short' => ['AB', false],
        'valid_max_length' => [str_repeat('A', 50), true],
        'invalid_too_long' => [str_repeat('A', 51), false],
    ];
}

/**
 * @test
 * @dataProvider boundaryTestProvider
 */
public function test_name_validation($value, $shouldPass)
{
    // Test logic here
}
```

---

## **ALUR INTEGRATION TESTING LENGKAP**

### **FASE 1: PERSIAPAN TESTING ENVIRONMENT** ⏱️ 15 menit

#### **Langkah 1.1: Setup Testing Database**

1. **Konfigurasi Database Testing**
   ```bash
   # Copy .env untuk testing
   cp .env .env.testing
   ```

2. **Edit .env.testing**
   ```env
   DB_CONNECTION=sqlite
   DB_DATABASE=:memory:
   # Alternative: Use separate test database
   # DB_DATABASE=database/testing.sqlite
   ```

3. **Konfigurasi phpunit.xml**
   ```xml
   <env name="APP_ENV" value="testing"/>
   <env name="DB_CONNECTION" value="sqlite"/>
   <env name="DB_DATABASE" value=":memory:"/>
   ```

#### **Langkah 1.2: Verifikasi Setup**

```bash
# Test koneksi dan migration
php artisan migrate --env=testing
php artisan test --testsuite=Feature --stop-on-failure
```

**✅ Checkpoint 1**: Environment integration testing siap

---

### **FASE 2: ANALISIS CONTROLLER REQUIREMENTS** ⏱️ 10 menit

#### **Langkah 2.1: Identifikasi Endpoints**

Berdasarkan `app/Http/Controllers/BranchController.php`, mapping endpoints:

| Method | Route | Purpose | Request Body | Expected Response |
|--------|-------|---------|--------------|-------------------|
| GET | `/api/branches` | List all branches | - | 200, JSON array |
| POST | `/api/branches` | Create new branch | Branch data | 201, Created branch |
| GET | `/api/branches/{id}` | Show specific branch | - | 200, Branch data |
| PUT | `/api/branches/{id}` | Update branch | Updated data | 200, Updated branch |
| DELETE | `/api/branches/{id}` | Delete branch | - | 200, Success message |

#### **Langkah 2.2: Identifikasi Validation Rules**

Dari `app/Http/Requests/StoreBranchRequest.php`:

| Field | Rules | Error Messages |
|-------|-------|----------------|
| name | required\|min:3\|max:50\|unique | "Nama cabang wajib diisi", "Nama cabang minimal 3 karakter" |
| address | required\|min:3\|max:100 | "Alamat cabang wajib diisi", "Alamat cabang minimal 3 karakter" |
| telephone | required\|min:3\|max:30 | "Telepon cabang wajib diisi", "Telepon cabang minimal 3 karakter" |
| status | boolean | - |

---

### **FASE 3: IMPLEMENTASI BASIC CRUD TESTS** ⏱️ 25 menit

#### **Langkah 3.1: Struktur File Test**

Buat file `tests/Feature/Controllers/BranchControllerTest.php`:

```php
<?php

namespace Tests\Feature\Controllers;

use App\Constants\BranchColumns;
use App\Models\Branch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Integration Tests untuk BranchController
 * 
 * Focus: Testing HTTP endpoints dan workflows lengkap
 * Testing: Request → Controller → Model → Database → Response
 */
class BranchControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Setup untuk setiap test
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        // Reset database untuk mencegah konflik data
        Branch::query()->truncate();
    }

    // Tests akan ditambahkan di sini...
}
```

#### **Langkah 3.2: Test INDEX Endpoint**

```php
/** 
 * Test: Index endpoint mengembalikan semua branches
 * @test 
 */
public function test_index_returns_all_branches(): void
{
    // Arrange
    $branches = Branch::factory()->count(3)->create();

    // Act
    $response = $this->getJson('/api/branches');

    // Assert
    $response->assertStatus(200)
             ->assertJsonCount(3, 'data')
             ->assertJsonStructure([
                 'data' => [
                     '*' => [
                         'id',
                         BranchColumns::NAME,
                         BranchColumns::ADDRESS,
                         BranchColumns::TELEPHONE,
                         BranchColumns::STATUS,
                         'created_at',
                         'updated_at'
                     ]
                 ]
             ]);
}

/** 
 * Test: Index endpoint dengan database kosong
 * @test 
 */
public function test_index_with_empty_database(): void
{
    // Act
    $response = $this->getJson('/api/branches');

    // Assert
    $response->assertStatus(200)
             ->assertJsonCount(0, 'data');
}
```

#### **Langkah 3.3: Test STORE Endpoint - Happy Path**

```php
/** 
 * Test: Store dengan data valid
 * @test 
 */
public function test_store_with_valid_data(): void
{
    // Arrange
    $validData = [
        BranchColumns::NAME => 'Cabang Jakarta Selatan',
        BranchColumns::ADDRESS => 'Jl. Fatmawati No. 15, Jakarta Selatan',
        BranchColumns::TELEPHONE => '021-87654321',
        BranchColumns::STATUS => true
    ];

    // Act
    $response = $this->postJson('/api/branches', $validData);

    // Assert
    $response->assertStatus(201)
             ->assertJsonFragment([
                 BranchColumns::NAME => 'Cabang Jakarta Selatan',
                 BranchColumns::STATUS => true
             ]);

    $this->assertDatabaseHas('branches', [
        BranchColumns::NAME => 'Cabang Jakarta Selatan',
        BranchColumns::ADDRESS => 'Jl. Fatmawati No. 15, Jakarta Selatan'
    ]);
}
```

#### **Langkah 3.4: Test SHOW Endpoint**

```php
/** 
 * Test: Show existing branch
 * @test 
 */
public function test_show_existing_branch(): void
{
    // Arrange
    $branch = Branch::factory()->create();

    // Act
    $response = $this->getJson("/api/branches/{$branch->id}");

    // Assert
    $response->assertStatus(200)
             ->assertJsonFragment([
                 'id' => $branch->id,
                 BranchColumns::NAME => $branch->name,
                 BranchColumns::ADDRESS => $branch->address
             ]);
}

/** 
 * Test: Show non-existent branch
 * @test 
 */
public function test_show_nonexistent_branch(): void
{
    // Act
    $response = $this->getJson('/api/branches/999');

    // Assert
    $response->assertStatus(404);
}
```

#### **Langkah 3.5: Test UPDATE dan DELETE Endpoints**

```php
/** 
 * Test: Update dengan data valid
 * @test 
 */
public function test_update_with_valid_data(): void
{
    // Arrange
    $branch = Branch::factory()->create();
    $updateData = [
        BranchColumns::NAME => 'Updated Branch Name',
        BranchColumns::ADDRESS => 'Updated Address',
        BranchColumns::TELEPHONE => '08199999999'
    ];

    // Act
    $response = $this->putJson("/api/branches/{$branch->id}", $updateData);

    // Assert
    $response->assertStatus(200)
             ->assertJsonFragment([
                 BranchColumns::NAME => 'Updated Branch Name'
             ]);

    $this->assertDatabaseHas('branches', [
        'id' => $branch->id,
        BranchColumns::NAME => 'Updated Branch Name',
        BranchColumns::ADDRESS => 'Updated Address'
    ]);
}

/** 
 * Test: Delete existing branch
 * @test 
 */
public function test_delete_existing_branch(): void
{
    // Arrange
    $branch = Branch::factory()->create();

    // Act
    $response = $this->deleteJson("/api/branches/{$branch->id}");

    // Assert
    $response->assertStatus(200);
    $this->assertDatabaseMissing('branches', [
        'id' => $branch->id
    ]);
}
```

**✅ Checkpoint 2**: Basic CRUD endpoints tested

---

### **FASE 4: IMPLEMENTASI BOUNDARY VALUE TESTING** ⏱️ 30 menit

#### **Langkah 4.1: Setup Data Provider untuk Boundary Testing**

```php
/**
 * Test boundary values menggunakan Data Provider
 * 
 * @test
 * @dataProvider essentialBoundaryTestProvider
 */
public function test_store_essential_boundaries(
    string $field, 
    mixed $value, 
    bool $shouldPass, 
    string $expectedError = null
): void {
    // Arrange - Base data yang valid
    $baseData = [
        BranchColumns::NAME => 'Valid Branch Name',
        BranchColumns::ADDRESS => 'Valid Address Here',
        BranchColumns::TELEPHONE => '08123456789',
        BranchColumns::STATUS => true
    ];
    
    // Override field yang akan ditest
    $testData = array_merge($baseData, [$field => $value]);

    // Act
    $response = $this->postJson('/api/branches', $testData);

    // Assert
    if ($shouldPass) {
        $response->assertStatus(201);
        $this->assertDatabaseHas('branches', [$field => $value]);
    } else {
        $response->assertStatus(422)
                 ->assertJsonValidationErrors($field);
        
        if ($expectedError) {
            $response->assertJsonFragment([
                'message' => $expectedError
            ]);
        }
    }
}
```

#### **Langkah 4.2: Data Provider Implementation**

```php
/**
 * Data Provider untuk Essential Boundary Testing
 * 12 test cases yang mencakup 90% kemungkinan bug
 * 
 * Format: [field_name, test_value, should_pass, expected_error_message]
 */
public static function essentialBoundaryTestProvider(): array
{
    return [
        // NAME field boundaries (min: 3, max: 50)
        'name_valid_minimum' => [
            BranchColumns::NAME, 
            'ABC', 
            true
        ],
        'name_invalid_too_short' => [
            BranchColumns::NAME, 
            'AB', 
            false, 
            'Nama cabang minimal 3 karakter'
        ],
        'name_valid_maximum' => [
            BranchColumns::NAME, 
            str_repeat('A', 50), 
            true
        ],
        'name_invalid_too_long' => [
            BranchColumns::NAME, 
            str_repeat('A', 51), 
            false, 
            'Nama cabang maksimal 50 karakter'
        ],
        'name_empty' => [
            BranchColumns::NAME, 
            '', 
            false, 
            'Nama cabang wajib diisi'
        ],

        // ADDRESS field boundaries (min: 3, max: 100)
        'address_valid_minimum' => [
            BranchColumns::ADDRESS, 
            'JL.', 
            true
        ],
        'address_invalid_too_short' => [
            BranchColumns::ADDRESS, 
            'JL', 
            false, 
            'Alamat cabang minimal 3 karakter'
        ],
        'address_valid_maximum' => [
            BranchColumns::ADDRESS, 
            str_repeat('A', 100), 
            true
        ],
        'address_invalid_too_long' => [
            BranchColumns::ADDRESS, 
            str_repeat('A', 101), 
            false, 
            'Alamat cabang maksimal 100 karakter'
        ],

        // TELEPHONE field boundaries (min: 3, max: 30)
        'telephone_valid_minimum' => [
            BranchColumns::TELEPHONE, 
            '123', 
            true
        ],
        'telephone_invalid_too_short' => [
            BranchColumns::TELEPHONE, 
            '12', 
            false, 
            'Telepon cabang minimal 3 karakter'
        ],
        'telephone_invalid_too_long' => [
            BranchColumns::TELEPHONE, 
            str_repeat('1', 31), 
            false, 
            'Telepon cabang maksimal 30 karakter'
        ],
    ];
}
```

#### **Langkah 4.3: Edge Case Testing**

```php
/**
 * Test edge cases real-world scenarios
 * 
 * @test
 * @dataProvider edgeCaseTestProvider
 */
public function test_store_edge_cases(
    string $scenario, 
    array $data, 
    bool $shouldPass, 
    string $expectedError = null
): void {
    // Arrange - Setup duplicate data jika diperlukan
    if ($scenario === 'duplicate_name') {
        Branch::factory()->create([
            BranchColumns::NAME => $data[BranchColumns::NAME]
        ]);
    }

    // Act
    $response = $this->postJson('/api/branches', $data);

    // Assert
    if ($shouldPass) {
        $response->assertStatus(201);
    } else {
        $response->assertStatus(422);
        if ($expectedError) {
            $response->assertJsonValidationErrors([BranchColumns::NAME]);
        }
    }
}

/**
 * Data Provider untuk Edge Cases
 */
public static function edgeCaseTestProvider(): array
{
    return [
        'duplicate_name' => [
            'duplicate_name',
            [
                BranchColumns::NAME => 'Duplicate Branch',
                BranchColumns::ADDRESS => 'Address 1',
                BranchColumns::TELEPHONE => '08111',
            ],
            false,
            'Nama cabang sudah ada'
        ],
        'special_characters_in_name' => [
            'special_chars',
            [
                BranchColumns::NAME => 'Branch & Co.',
                BranchColumns::ADDRESS => 'Address with symbols!',
                BranchColumns::TELEPHONE => '+62-21-123',
            ],
            true
        ],
        'international_phone_format' => [
            'international',
            [
                BranchColumns::NAME => 'International Branch',
                BranchColumns::ADDRESS => 'International Address',
                BranchColumns::TELEPHONE => '+62-812-3456-7890',
            ],
            true
        ],
        'status_false' => [
            'inactive_status',
            [
                BranchColumns::NAME => 'Inactive Branch',
                BranchColumns::ADDRESS => 'Inactive Address',
                BranchColumns::TELEPHONE => '08123',
                BranchColumns::STATUS => false
            ],
            true
        ]
    ];
}
```

**✅ Checkpoint 3**: Boundary value testing implemented

---

### **FASE 5: ADVANCED INTEGRATION TESTING** ⏱️ 15 menit

#### **Langkah 5.1: Validation Error Testing**

```php
/** 
 * Test: Validation messages dalam bahasa Indonesia
 * @test 
 */
public function test_validation_messages_in_indonesian(): void
{
    // Arrange
    $invalidData = [
        BranchColumns::NAME => '',
        BranchColumns::ADDRESS => '',
        BranchColumns::TELEPHONE => ''
    ];

    // Act
    $response = $this->postJson('/api/branches', $invalidData);

    // Assert
    $response->assertStatus(422)
             ->assertJsonValidationErrors([
                 BranchColumns::NAME,
                 BranchColumns::ADDRESS,
                 BranchColumns::TELEPHONE
             ]);

    // Verify Indonesian error messages
    $errors = $response->json('errors');
    $this->assertStringContainsString('wajib diisi', $errors[BranchColumns::NAME][0]);
    $this->assertStringContainsString('wajib diisi', $errors[BranchColumns::ADDRESS][0]);
    $this->assertStringContainsString('wajib diisi', $errors[BranchColumns::TELEPHONE][0]);
}
```

#### **Langkah 5.2: Update Boundary Testing**

```php
/** 
 * Test: Update dengan duplicate name
 * @test 
 */
public function test_update_with_duplicate_name(): void
{
    // Arrange
    $existingBranch = Branch::factory()->create(['name' => 'Existing Branch']);
    $branchToUpdate = Branch::factory()->create(['name' => 'Branch to Update']);

    // Act
    $response = $this->putJson("/api/branches/{$branchToUpdate->id}", [
        BranchColumns::NAME => 'Existing Branch', // Duplicate name
        BranchColumns::ADDRESS => 'Some Address',
        BranchColumns::TELEPHONE => '08123456789'
    ]);

    // Assert
    $response->assertStatus(422)
             ->assertJsonValidationErrors([BranchColumns::NAME]);
}
```

#### **Langkah 5.3: Database Constraint Testing**

```php
/**
 * Test: Database constraint enforcement
 * @test
 */
public function test_database_enforces_unique_constraint(): void
{
    // Arrange
    Branch::factory()->create(['name' => 'Unique Branch']);

    // Act & Assert
    $this->expectException(\Illuminate\Database\QueryException::class);
    
    // Force database constraint violation
    Branch::create([
        'name' => 'Unique Branch', // Same name
        'address' => 'Different Address',
        'telephone' => '08123456789'
    ]);
}
```

**✅ Checkpoint 4**: Advanced integration scenarios covered

---

### **FASE 6: OPTIMASI TEST SUITE** ⏱️ 10 menit

#### **Langkah 6.1: Performance Analysis**

```bash
# Measure execution time
time php artisan test tests/Feature/Controllers/BranchControllerTest.php

# Run with verbose output
php artisan test tests/Feature/Controllers/BranchControllerTest.php --verbose
```

#### **Langkah 6.2: Test Count Optimization**

**Optimization Strategy (80/20 Rule):**

| Category | Original Tests | Optimized Tests | Reasoning |
|----------|----------------|-----------------|-----------|
| CRUD Basic | 6 tests | 5 tests | Combine similar scenarios |
| Boundary Values | 12 tests | 12 tests | Keep all (critical for bugs) |
| Edge Cases | 8 tests | 5 tests | Focus on real-world scenarios |
| Error Handling | 4 tests | 3 tests | Combine similar error cases |
| **Total** | **30 tests** | **25 tests** | **17% reduction, 90% coverage** |

#### **Langkah 6.3: Helper Methods untuk Reusability**

```php
/**
 * Helper method untuk membuat data valid
 */
private function getValidBranchData(): array
{
    return [
        BranchColumns::NAME => 'Test Branch',
        BranchColumns::ADDRESS => 'Test Address',
        BranchColumns::TELEPHONE => '08123456789',
        BranchColumns::STATUS => true
    ];
}

/**
 * Helper method untuk assert successful creation
 */
private function assertBranchCreatedSuccessfully($response, $data): void
{
    $response->assertStatus(201);
    $this->assertDatabaseHas('branches', $data);
}

/**
 * Helper method untuk setup duplicate data
 */
private function createDuplicateBranch(string $name = 'Duplicate Branch'): Branch
{
    return Branch::factory()->create([BranchColumns::NAME => $name]);
}
```

---

## **BEST PRACTICES INTEGRATION TESTING**

### **1. Test Organization**

```php
// ✅ Good - grouped by functionality
// ===========================================
// INDEX TESTS - GET /api/branches  
// ===========================================

// ===========================================
// STORE TESTS - POST /api/branches
// ===========================================

// ===========================================
// BOUNDARY TESTING
// ===========================================
```

### **2. Database Management**

```php
// ✅ Good - consistent cleanup
protected function setUp(): void
{
    parent::setUp();
    Branch::query()->truncate();
}

// ✅ Good - isolated test data
public function test_something(): void
{
    $branch = Branch::factory()->create(); // Fresh data each test
}
```

### **3. Response Assertions**

```php
// ✅ Good - comprehensive assertions
$response->assertStatus(201)
         ->assertJsonFragment(['name' => 'Expected'])
         ->assertJsonStructure(['data' => ['id', 'name']])
         ->assertJsonCount(1, 'data');

// ❌ Bad - weak assertions
$response->assertStatus(200);
```

### **4. Error Testing**

```php
// ✅ Good - specific error testing
$response->assertStatus(422)
         ->assertJsonValidationErrors(['name'])
         ->assertJsonFragment(['message' => 'Specific error message']);

// ❌ Bad - generic error testing
$response->assertStatus(422);
```

---

## **TROUBLESHOOTING INTEGRATION TESTING**

### **Common Issues & Solutions**

#### **Issue 1: Unique Constraint Violations**
```php
// Problem: Tests fail due to duplicate data
// Solution: Use unique data or cleanup between tests
protected function setUp(): void
{
    parent::setUp();
    Branch::query()->truncate();
}
```

#### **Issue 2: Slow Test Execution**
```php
// Problem: Tests are slow
// Solution: Use database transactions instead of RefreshDatabase
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BranchControllerTest extends TestCase
{
    use DatabaseTransactions; // Faster than RefreshDatabase
}
```

#### **Issue 3: Route Not Found Errors**
```bash
# Problem: 404 errors for API routes
# Solution: Check route registration
php artisan route:list | grep branches
```

#### **Issue 4: Validation Messages Not Matching**
```php
// Problem: Expected error message doesn't match
// Solution: Check actual validation rules in Request class
$response = $this->postJson('/api/branches', $invalidData);
dd($response->json()); // Debug actual response
```

---

## **EVALUASI DAN PENILAIAN**

### **Kriteria Penilaian Integration Testing** (90 poin total)

| Aspek | Bobot | Kriteria Excellence | Critial Good | Criteria Fair |
|-------|-------|-------------------|--------------|---------------|
| **CRUD Testing** | 25 poin | 5 endpoints tested, semua pass | 4 endpoints tested | 3 endpoints tested |
| **Boundary Testing** | 30 poin | 12 boundary tests, Data Provider used | 10-11 boundary tests | 8-9 boundary tests |
| **Edge Cases** | 20 poin | 5 real-world scenarios tested | 4 scenarios tested | 3 scenarios tested |
| **Code Quality** | 10 poin | Clean code, helper methods, good organization | Mostly clean, minor issues | Basic quality |
| **Performance** | 5 poin | < 10 seconds execution | 10-15 seconds | 15-20 seconds |

### **Deliverables**
- [ ] File `tests/Feature/Controllers/BranchControllerTest.php` lengkap
- [ ] Semua tests passing (target: 25 tests)
- [ ] Boundary testing dengan Data Provider
- [ ] Edge case coverage
- [ ] Performance metrics documented

---

## **REFERENSI**

1. **Laravel HTTP Testing**: https://laravel.com/docs/http-tests
2. **Feature Testing Guide**: https://laravel.com/docs/testing#testing-json-apis
3. **Boundary Value Analysis**: IEEE Standard 829
4. **Integration Testing Patterns**: Martin Fowler's Testing Strategies

---

**🎯 Integration Testing memastikan aplikasi bekerja dengan benar sebagai satu kesatuan sistem!**
