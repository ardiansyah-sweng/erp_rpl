# MODUL PRAKTIKUM: TESTING OVERVIEW
## Laravel Testing dengan PHPUnit - Unit & Integration Testing

### **Informasi Modul**
- **Mata Kuliah**: Rekayasa Perangkat Lunak / Software Testing
- **Topik**: Comprehensive Testing (Unit + Integration)
- **Framework**: Laravel 10/11 dengan PHPUnit
- **Durasi Total**: 150 menit (2.5 jam)
- **Level**: Intermediate

---

## **STRUKTUR MODUL TESTING**

Modul praktikum testing ini dipecah menjadi **2 bagian terpisah** untuk pembelajaran yang lebih fokus:

### **📋 MODUL 1: UNIT TESTING** (60 menit)
**File**: `MODUL_PRAKTIKUM_UNIT_TESTING.md`

**Focus Area:**
- ✅ Testing Model Layer (Branch Model)
- ✅ Isolated Component Testing
- ✅ Factory Pattern Usage
- ✅ AAA Pattern Implementation
- ✅ Database-less Testing Concepts

**Learning Outcomes:**
- Memahami konsep Unit Testing
- Implementasi model testing yang komprehensif
- Penggunaan Factory untuk data generation
- Test isolation dan independence

### **🌐 MODUL 2: INTEGRATION TESTING** (90 menit)
**File**: `MODUL_PRAKTIKUM_INTEGRATION_TESTING.md`

**Focus Area:**
- ✅ HTTP Endpoint Testing (BranchController)
- ✅ Boundary Value Analysis
- ✅ Data Provider Pattern
- ✅ End-to-End Workflow Testing
- ✅ Validation & Error Handling

**Learning Outcomes:**
- Memahami konsep Integration/Feature Testing
- Implementasi boundary value testing
- Optimasi test suite berdasarkan prinsip 80/20
- Real-world scenario testing

---

## **RECOMMENDED LEARNING PATH**

### **🎯 Path 1: Sequential Learning (Recommended)**
```
Day 1: Unit Testing (60 min)
     ↓
Day 2: Integration Testing (90 min)
     ↓
Day 3: Combined Practice & Review
```

**Keuntungan:**
- Progressive complexity
- Solid foundation building
- Better concept absorption

### **🎯 Path 2: Parallel Learning (Advanced)**
```
Week 1: Unit Testing + Integration Testing Theory
Week 2: Hands-on Implementation Both Modules
Week 3: Optimization & Advanced Concepts
```

**Keuntungan:**
- Compare & contrast approach
- Holistic understanding
- Real project simulation
- Mendeteksi error validasi dengan akurat

### **3. Data Provider Pattern**

Pattern untuk menjalankan test yang sama dengan multiple data set:

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
```

---

## **ALUR PENGUJIAN LENGKAP**

### **FASE 1: PERSIAPAN TESTING ENVIRONMENT** ⏱️ 20 menit

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
   ```

3. **Konfigurasi phpunit.xml**
   ```xml
   <env name="APP_ENV" value="testing"/>
   <env name="DB_CONNECTION" value="sqlite"/>
   <env name="DB_DATABASE" value=":memory:"/>
   ```

#### **Langkah 1.2: Verifikasi Setup**

```bash
# Test koneksi database
php artisan test --testsuite=Unit --stop-on-failure
```

**✅ Checkpoint 1**: Database testing terkonfigurasi dengan benar

---

### **FASE 2: UNIT TESTING - BRANCH MODEL** ⏱️ 40 menit

#### **Langkah 2.1: Analisis Model Requirements**

Berdasarkan `app/Models/Branch.php` dan migration, identifikasi:

| Field | Type | Constraints | Validation Rules |
|-------|------|-------------|------------------|
| name | string | max:50, min:3, unique | required\|min:3\|max:50\|unique:branches |
| address | string | max:100, min:3 | required\|min:3\|max:100 |
| telephone | string | max:30, min:3 | required\|min:3\|max:30 |
| status | boolean | default:1 | boolean |

#### **Langkah 2.2: Implementasi Model Unit Tests**

Buat file `tests/Unit/Models/BranchTest.php`:

```php
<?php

namespace Tests\Unit\Models;

use App\Models\Branch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BranchTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_branch_with_valid_data()
    {
        $branchData = [
            'name' => 'Cabang Jakarta',
            'address' => 'Jl. Sudirman No. 1',
            'telephone' => '021-12345678',
            'status' => true
        ];

        $branch = Branch::create($branchData);

        $this->assertInstanceOf(Branch::class, $branch);
        $this->assertEquals('Cabang Jakarta', $branch->name);
        $this->assertTrue($branch->status);
        $this->assertDatabaseHas('branches', $branchData);
    }

    /** @test */
    public function it_has_default_status_active()
    {
        $branch = Branch::factory()->make();
        
        $this->assertTrue($branch->status);
    }

    /** @test */
    public function it_can_update_branch_information()
    {
        $branch = Branch::factory()->create(['name' => 'Old Name']);
        
        $branch->update(['name' => 'New Name']);
        
        $this->assertEquals('New Name', $branch->fresh()->name);
    }

    /** @test */
    public function it_can_soft_delete_branch()
    {
        $branch = Branch::factory()->create();
        
        $branch->delete();
        
        $this->assertSoftDeleted('branches', ['id' => $branch->id]);
    }
}
```

#### **Langkah 2.3: Jalankan Unit Tests**

```bash
# Jalankan unit tests untuk model
php artisan test tests/Unit/Models/BranchTest.php --verbose
```

**✅ Checkpoint 2**: Unit tests untuk Branch model berjalan sukses

---

### **FASE 3: INTEGRATION TESTING - BRANCH CONTROLLER** ⏱️ 60 menit

#### **Langkah 3.1: Analisis Controller Requirements**

Berdasarkan `app/Http/Controllers/BranchController.php`, identifikasi endpoints:

| Method | Route | Purpose | Expected Response |
|--------|-------|---------|-------------------|
| GET | /branches | List all branches | 200, JSON array |
| POST | /branches | Create new branch | 201, Created branch |
| GET | /branches/{id} | Show specific branch | 200, Branch data |
| PUT | /branches/{id} | Update branch | 200, Updated branch |
| DELETE | /branches/{id} | Delete branch | 200, Success message |

#### **Langkah 3.2: Implementasi Feature Tests dengan Boundary Testing**

Buat file `tests/Feature/Controllers/BranchControllerTest.php`:

```php
<?php

namespace Tests\Feature\Controllers;

use App\Constants\BranchColumns;
use App\Models\Branch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BranchControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Reset database untuk setiap test
        Branch::query()->truncate();
    }

    /** @test */
    public function test_index_returns_all_branches()
    {
        // Arrange
        Branch::factory()->count(3)->create();

        // Act
        $response = $this->getJson('/api/branches');

        // Assert
        $response->assertStatus(200)
                 ->assertJsonCount(3, 'data');
    }

    /** @test */
    public function test_store_with_valid_data()
    {
        // Arrange
        $validData = [
            BranchColumns::NAME => 'Cabang Test',
            BranchColumns::ADDRESS => 'Alamat Test',
            BranchColumns::TELEPHONE => '081234567890',
            BranchColumns::STATUS => true
        ];

        // Act
        $response = $this->postJson('/api/branches', $validData);

        // Assert
        $response->assertStatus(201)
                 ->assertJsonFragment([
                     BranchColumns::NAME => 'Cabang Test'
                 ]);
        
        $this->assertDatabaseHas('branches', [
            BranchColumns::NAME => 'Cabang Test'
        ]);
    }

    /**
     * Test boundary values untuk field name, address, telephone
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
        // Arrange
        $baseData = [
            BranchColumns::NAME => 'Valid Branch Name',
            BranchColumns::ADDRESS => 'Valid Address Here',
            BranchColumns::TELEPHONE => '08123456789',
            BranchColumns::STATUS => true
        ];
        
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
                $response->assertJsonFragment(['message' => $expectedError]);
            }
        }
    }

    /**
     * Data Provider untuk Essential Boundary Testing
     * 12 test cases yang mencakup 90% kemungkinan bug
     */
    public static function essentialBoundaryTestProvider(): array
    {
        return [
            // NAME field boundaries
            'name_valid_minimum' => [BranchColumns::NAME, 'ABC', true],
            'name_invalid_too_short' => [BranchColumns::NAME, 'AB', false, 'Nama cabang minimal 3 karakter'],
            'name_valid_maximum' => [BranchColumns::NAME, str_repeat('A', 50), true],
            'name_invalid_too_long' => [BranchColumns::NAME, str_repeat('A', 51), false, 'Nama cabang maksimal 50 karakter'],
            'name_empty' => [BranchColumns::NAME, '', false, 'Nama cabang wajib diisi'],
            
            // ADDRESS field boundaries  
            'address_valid_minimum' => [BranchColumns::ADDRESS, 'JL.', true],
            'address_invalid_too_short' => [BranchColumns::ADDRESS, 'JL', false, 'Alamat cabang minimal 3 karakter'],
            'address_valid_maximum' => [BranchColumns::ADDRESS, str_repeat('A', 100), true],
            'address_invalid_too_long' => [BranchColumns::ADDRESS, str_repeat('A', 101), false, 'Alamat cabang maksimal 100 karakter'],
            
            // TELEPHONE field boundaries
            'telephone_valid_minimum' => [BranchColumns::TELEPHONE, '123', true],
            'telephone_invalid_too_short' => [BranchColumns::TELEPHONE, '12', false, 'Telepon cabang minimal 3 karakter'],
            'telephone_invalid_too_long' => [BranchColumns::TELEPHONE, str_repeat('1', 31), false, 'Telepon cabang maksimal 30 karakter'],
        ];
    }

    /**
     * Test edge cases yang sering terjadi di real-world
     * 
     * @test
     * @dataProvider edgeCaseTestProvider
     */
    public function test_store_edge_cases(string $scenario, array $data, bool $shouldPass): void
    {
        // Act
        $response = $this->postJson('/api/branches', $data);

        // Assert
        if ($shouldPass) {
            $response->assertStatus(201);
        } else {
            $response->assertStatus(422);
        }
    }

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
                false
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
            'numeric_telephone' => [
                'numeric_phone',
                [
                    BranchColumns::NAME => 'Numeric Phone Branch',
                    BranchColumns::ADDRESS => 'Numeric Address',
                    BranchColumns::TELEPHONE => '081234567890',
                ],
                true
            ],
            'international_format' => [
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

    /** @test */
    public function test_show_existing_branch()
    {
        // Arrange
        $branch = Branch::factory()->create();

        // Act
        $response = $this->getJson("/api/branches/{$branch->id}");

        // Assert
        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'id' => $branch->id,
                     BranchColumns::NAME => $branch->name
                 ]);
    }

    /** @test */
    public function test_show_nonexistent_branch()
    {
        // Act
        $response = $this->getJson('/api/branches/999');

        // Assert
        $response->assertStatus(404);
    }

    /** @test */
    public function test_update_with_valid_data()
    {
        // Arrange
        $branch = Branch::factory()->create();
        $updateData = [
            BranchColumns::NAME => 'Updated Branch Name',
            BranchColumns::ADDRESS => 'Updated Address'
        ];

        // Act
        $response = $this->putJson("/api/branches/{$branch->id}", $updateData);

        // Assert
        $response->assertStatus(200);
        $this->assertDatabaseHas('branches', [
            'id' => $branch->id,
            BranchColumns::NAME => 'Updated Branch Name'
        ]);
    }

    /** @test */
    public function test_delete_existing_branch()
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

    /** @test */
    public function test_validation_messages_in_indonesian()
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
    }
}
```

#### **Langkah 3.3: Setup Data untuk Edge Case Testing**

Tambahkan method untuk setup data duplikasi:

```php
protected function createDuplicateBranch(): void
{
    Branch::factory()->create([
        BranchColumns::NAME => 'Duplicate Branch'
    ]);
}
```

#### **Langkah 3.4: Jalankan Feature Tests**

```bash
# Jalankan feature tests
php artisan test tests/Feature/Controllers/BranchControllerTest.php --verbose

# Jalankan dengan coverage report
php artisan test --coverage-text
```

**✅ Checkpoint 3**: Feature tests untuk BranchController berjalan sukses

---

### **FASE 4: OPTIMASI TEST SUITE** ⏱️ 20 menit

#### **Langkah 4.1: Analisis Test Coverage**

Jalankan analisis untuk mengidentifikasi:

```bash
# Test dengan detailed output
php artisan test --verbose --stop-on-failure

# Hitung execution time
time php artisan test tests/Feature/Controllers/BranchControllerTest.php
```

#### **Langkah 4.2: Implementasi Prinsip 80/20**

Dari 26 test cases awal, optimasi menjadi 17 essential tests:

| Category | Original | Optimized | Reasoning |
|----------|----------|-----------|-----------|
| Boundary Tests | 15 | 12 | Fokus pada critical boundaries |
| Edge Cases | 8 | 5 | Pilih real-world scenarios |
| CRUD Tests | 3 | 0 | Sudah tercakup di boundary tests |

#### **Langkah 4.3: Refactoring untuk Performance**

```php
// Gunakan database transactions untuk speed
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BranchControllerTest extends TestCase
{
    use RefreshDatabase, DatabaseTransactions;
    
    // Optimize setup dengan static data
    private static array $validBranchData = [
        BranchColumns::NAME => 'Test Branch',
        BranchColumns::ADDRESS => 'Test Address',
        BranchColumns::TELEPHONE => '08123456789',
    ];
}
```

**✅ Checkpoint 4**: Test suite dioptimasi dengan execution time < 10 detik

---

### **FASE 5: DOKUMENTASI DAN ANALISIS HASIL** ⏱️ 10 menit

#### **Langkah 5.1: Generate Test Report**

```bash
# Generate comprehensive test report
php artisan test --coverage-html=tests/coverage

# Generate JUnit XML untuk CI/CD
php artisan test --log-junit=tests/results.xml
```

#### **Langkah 5.2: Analisis Metrics**

Catat metrics berikut:

| Metric | Target | Hasil |
|--------|--------|-------|
| Test Coverage | > 80% | ___% |
| Execution Time | < 10s | ___s |
| Assertion Count | > 100 | ___ |
| Success Rate | 100% | ___% |

---

## **EVALUASI DAN PENILAIAN**

### **Kriteria Penilaian** (Total: 100 poin)

| Aspek | Bobot | Kriteria Excellent (A) | Kriteria Good (B) | Kriteria Fair (C) |
|-------|-------|------------------------|-------------------|-------------------|
| **Test Implementation** | 40% | Semua 17 tests berjalan sukses, boundary testing lengkap | 15-16 tests sukses, boundary testing hampir lengkap | 12-14 tests sukses, boundary testing basic |
| **Code Quality** | 25% | Clean code, proper AAA pattern, good naming | Code readable, consistent pattern | Code functional, beberapa issue |
| **Understanding** | 20% | Menjelaskan dengan jelas konsep boundary testing dan data provider | Memahami konsep dasar testing | Pemahaman terbatas |
| **Optimization** | 15% | Test suite optimal, execution time < 8s | Test suite cukup optimal, < 12s | Test suite berfungsi, < 20s |

### **Deliverables**

1. **File Test Lengkap**:
   - `tests/Unit/Models/BranchTest.php`
   - `tests/Feature/Controllers/BranchControllerTest.php`

2. **Test Report**:
   - Screenshot test results
   - Coverage report
   - Performance metrics

3. **Reflection Document** (500 kata):
   - Analisis boundary testing effectiveness
   - Challenges encountered
   - Lessons learned

---

## **TROUBLESHOOTING COMMON ISSUES**

### **Issue 1: Database Connection Error**
```bash
# Solution
php artisan config:clear
php artisan cache:clear
```

### **Issue 2: Test Failing karena Data Persistence**
```php
// Solution: Add to setUp()
protected function setUp(): void
{
    parent::setUp();
    Branch::query()->truncate();
}
```

### **Issue 3: Validation Message Mismatch**
```php
// Check validation rules di StoreBranchRequest
// Pastikan message sesuai dengan expected error
```

### **Issue 4: Unique Constraint Violation**
```php
// Use unique data generator
$uniqueName = 'Branch ' . now()->timestamp;
```

---

## **ADVANCED CHALLENGES** (Bonus)

Untuk mahasiswa yang menyelesaikan basic requirements lebih cepat:

### **Challenge 1: Parallel Testing**
Implementasi testing yang bisa berjalan parallel untuk speed optimization.

### **Challenge 2: Custom Assertions**
Buat custom assertion untuk validasi yang lebih readable:

```php
$this->assertBranchValid($branch);
$this->assertValidationFailsFor('name', 'AB');
```

### **Challenge 3: Testing dengan Database Seeding**
Implementasi testing dengan data seeder untuk scenario yang lebih complex.

### **Challenge 4: API Integration Testing**
Extend testing untuk mencakup API authentication dan authorization.

---

## **REFERENSI**

1. **Laravel Testing Documentation**: https://laravel.com/docs/testing
2. **PHPUnit Documentation**: https://phpunit.de/documentation.html
3. **Boundary Value Analysis**: IEEE Standard for Software Testing
4. **Martin Fowler - Test Pyramid**: https://martinfowler.com/articles/practical-test-pyramid.html

---

## **APPENDIX: QUICK REFERENCE**

### **Essential Laravel Test Commands**
```bash
# Jalankan semua tests
php artisan test

# Jalankan specific test file
php artisan test tests/Feature/Controllers/BranchControllerTest.php

# Jalankan dengan verbose output
php artisan test --verbose

# Jalankan dengan coverage
php artisan test --coverage-text

# Stop on first failure
php artisan test --stop-on-failure

# Filter tests by method name
php artisan test --filter=test_store_boundary
```

### **Useful Assertions**
```php
// Database assertions
$this->assertDatabaseHas('branches', ['name' => 'Test']);
$this->assertDatabaseMissing('branches', ['id' => 999]);
$this->assertSoftDeleted('branches', ['id' => 1]);

// Response assertions
$response->assertStatus(200);
$response->assertJsonFragment(['name' => 'Test']);
$response->assertJsonValidationErrors(['name']);

// Model assertions
$this->assertInstanceOf(Branch::class, $branch);
$this->assertEquals('Expected', $actual);
$this->assertTrue($condition);
```

---

**🎯 Selamat Praktikum! Semoga berhasil mengimplementasikan testing yang berkualitas!**
