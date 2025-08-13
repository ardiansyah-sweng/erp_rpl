# TEMPLATE & CONTOH IMPLEMENTASI
## Unit Testing & Integration Testing Laravel

---

## **TEMPLATE 1: BRANCH MODEL UNIT TEST**

### File: `tests/Unit/Models/BranchTest.php`

```php
<?php

namespace Tests\Unit\Models;

use App\Models\Branch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tests\TestCase;

/**
 * Unit Tests untuk Branch Model
 * 
 * Test Focus:
 * - Model creation dan validation
 * - Default values
 * - Model relationships (jika ada)
 * - Model methods dan computed properties
 */
class BranchTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Setup yang dijalankan sebelum setiap test
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        // Optional: Setup default data atau mock
    }

    /** 
     * Test: Model dapat dibuat dengan data yang valid
     * @test 
     */
    public function it_can_create_branch_with_valid_data(): void
    {
        // Arrange - Siapkan data test
        $branchData = [
            'name' => 'Cabang Jakarta Pusat',
            'address' => 'Jl. MH Thamrin No. 1, Jakarta Pusat',
            'telephone' => '021-12345678',
            'status' => true
        ];

        // Act - Eksekusi operasi yang ditest
        $branch = Branch::create($branchData);

        // Assert - Verifikasi hasil
        $this->assertInstanceOf(Branch::class, $branch);
        $this->assertEquals('Cabang Jakarta Pusat', $branch->name);
        $this->assertEquals('Jl. MH Thamrin No. 1, Jakarta Pusat', $branch->address);
        $this->assertEquals('021-12345678', $branch->telephone);
        $this->assertTrue($branch->status);
        
        // Verifikasi data tersimpan di database
        $this->assertDatabaseHas('branches', [
            'name' => 'Cabang Jakarta Pusat',
            'address' => 'Jl. MH Thamrin No. 1, Jakarta Pusat',
            'telephone' => '021-12345678',
            'status' => true
        ]);
    }

    /** 
     * Test: Model memiliki default value yang benar
     * @test 
     */
    public function it_has_correct_default_values(): void
    {
        // Arrange
        $branch = new Branch([
            'name' => 'Test Branch',
            'address' => 'Test Address',
            'telephone' => '08123456789'
        ]);

        // Act & Assert
        $this->assertTrue($branch->status); // Default status should be true
    }

    /** 
     * Test: Model dapat diupdate
     * @test 
     */
    public function it_can_update_branch_information(): void
    {
        // Arrange
        $branch = Branch::factory()->create([
            'name' => 'Original Name'
        ]);

        // Act
        $branch->update([
            'name' => 'Updated Name',
            'status' => false
        ]);

        // Assert
        $this->assertEquals('Updated Name', $branch->fresh()->name);
        $this->assertFalse($branch->fresh()->status);
    }

    /** 
     * Test: Model dapat dihapus (soft delete jika ada)
     * @test 
     */
    public function it_can_delete_branch(): void
    {
        // Arrange
        $branch = Branch::factory()->create();
        $branchId = $branch->id;

        // Act
        $branch->delete();

        // Assert
        // Jika menggunakan soft delete:
        // $this->assertSoftDeleted('branches', ['id' => $branchId]);
        
        // Jika menggunakan hard delete:
        $this->assertDatabaseMissing('branches', ['id' => $branchId]);
    }

    /** 
     * Test: Model factory berfungsi dengan benar
     * @test 
     */
    public function it_can_be_created_using_factory(): void
    {
        // Act
        $branch = Branch::factory()->create();

        // Assert
        $this->assertInstanceOf(Branch::class, $branch);
        $this->assertNotNull($branch->name);
        $this->assertNotNull($branch->address);
        $this->assertNotNull($branch->telephone);
        $this->assertIsBool($branch->status);
        $this->assertDatabaseHas('branches', ['id' => $branch->id]);
    }

    /**
     * Test: Model dapat membuat multiple instances
     * @test
     */
    public function it_can_create_multiple_branches(): void
    {
        // Act
        $branches = Branch::factory()->count(3)->create();

        // Assert
        $this->assertCount(3, $branches);
        $this->assertEquals(3, Branch::count());
    }

    // TODO: Tambahkan tests untuk:
    // - Model relationships (jika ada)
    // - Custom methods pada model
    // - Computed properties atau accessors
    // - Validation pada model level
}
```

---

## **TEMPLATE 2: BRANCH CONTROLLER FEATURE TEST**

### File: `tests/Feature/Controllers/BranchControllerTest.php`

```php
<?php

namespace Tests\Feature\Controllers;

use App\Constants\BranchColumns;
use App\Models\Branch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Feature Tests untuk BranchController
 * 
 * Test Focus:
 * - HTTP endpoints dan responses
 * - Request validation
 * - Business logic integration
 * - Database operations
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

    // ===========================================
    // INDEX TESTS - GET /api/branches
    // ===========================================

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

    // ===========================================
    // STORE TESTS - POST /api/branches
    // ===========================================

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

    /**
     * Data Provider untuk Essential Boundary Testing
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
            'telephone_valid_maximum' => [
                BranchColumns::TELEPHONE, 
                str_repeat('1', 30), 
                true
            ],
            'telephone_invalid_too_long' => [
                BranchColumns::TELEPHONE, 
                str_repeat('1', 31), 
                false, 
                'Telepon cabang maksimal 30 karakter'
            ],
        ];
    }

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

    // ===========================================
    // SHOW TESTS - GET /api/branches/{id}
    // ===========================================

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

    // ===========================================
    // UPDATE TESTS - PUT/PATCH /api/branches/{id}
    // ===========================================

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

    // ===========================================
    // DELETE TESTS - DELETE /api/branches/{id}
    // ===========================================

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

    /** 
     * Test: Delete non-existent branch
     * @test 
     */
    public function test_delete_nonexistent_branch(): void
    {
        // Act
        $response = $this->deleteJson('/api/branches/999');

        // Assert
        $response->assertStatus(404);
    }

    // ===========================================
    // VALIDATION TESTS
    // ===========================================

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
    }

    // ===========================================
    // HELPER METHODS
    // ===========================================

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
}
```

---

## **TEMPLATE 3: FACTORY DEFINITION**

### File: `database/factories/BranchFactory.php`

```php
<?php

namespace Database\Factories;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory untuk Branch Model
 * 
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Branch>
 */
class BranchFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Branch::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->company() . ' Branch',
            'address' => $this->faker->address(),
            'telephone' => $this->faker->phoneNumber(),
            'status' => true, // Default active
        ];
    }

    /**
     * State untuk branch yang inactive
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => false,
        ]);
    }

    /**
     * State untuk branch dengan nama pendek
     */
    public function shortName(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => $this->faker->lexify('???'), // 3 characters
        ]);
    }

    /**
     * State untuk branch dengan nama maksimal
     */
    public function maxName(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => $this->faker->lexify(str_repeat('?', 50)), // 50 characters
        ]);
    }
}
```

---

## **TEMPLATE 4: CONSTANTS DEFINITION**

### File: `app/Constants/BranchColumns.php`

```php
<?php

namespace App\Constants;

/**
 * Constants untuk kolom-kolom tabel Branch
 * 
 * Menggunakan constants untuk:
 * - Mencegah typo dalam nama kolom
 * - Memudahkan refactoring
 * - Konsistensi across codebase
 */
class BranchColumns
{
    public const NAME = 'name';
    public const ADDRESS = 'address';  
    public const TELEPHONE = 'telephone';
    public const STATUS = 'status';
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = 'updated_at';
    
    /**
     * Get all fillable columns
     */
    public static function getFillable(): array
    {
        return [
            self::NAME,
            self::ADDRESS,
            self::TELEPHONE,
            self::STATUS,
        ];
    }
    
    /**
     * Get validation rules untuk testing
     */
    public static function getValidationRules(): array
    {
        return [
            self::NAME => 'required|string|min:3|max:50|unique:branches,name',
            self::ADDRESS => 'required|string|min:3|max:100',
            self::TELEPHONE => 'required|string|min:3|max:30',
            self::STATUS => 'boolean',
        ];
    }
}
```

---

## **TEMPLATE 5: REQUEST VALIDATION**

### File: `app/Http/Requests/StoreBranchRequest.php`

```php
<?php

namespace App\Http\Requests;

use App\Constants\BranchColumns;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Request validation untuk Store Branch
 * 
 * Centralized validation rules dan messages
 */
class StoreBranchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            BranchColumns::NAME => 'required|string|min:3|max:50|unique:branches,name',
            BranchColumns::ADDRESS => 'required|string|min:3|max:100',
            BranchColumns::TELEPHONE => 'required|string|min:3|max:30',
            BranchColumns::STATUS => 'boolean',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            BranchColumns::NAME . '.required' => 'Nama cabang wajib diisi',
            BranchColumns::NAME . '.min' => 'Nama cabang minimal 3 karakter',
            BranchColumns::NAME . '.max' => 'Nama cabang maksimal 50 karakter',
            BranchColumns::NAME . '.unique' => 'Nama cabang sudah ada, silakan gunakan nama lain',
            
            BranchColumns::ADDRESS . '.required' => 'Alamat cabang wajib diisi',
            BranchColumns::ADDRESS . '.min' => 'Alamat cabang minimal 3 karakter',
            BranchColumns::ADDRESS . '.max' => 'Alamat cabang maksimal 100 karakter',
            
            BranchColumns::TELEPHONE . '.required' => 'Telepon cabang wajib diisi',
            BranchColumns::TELEPHONE . '.min' => 'Telepon cabang minimal 3 karakter',
            BranchColumns::TELEPHONE . '.max' => 'Telepon cabang maksimal 30 karakter',
        ];
    }
}
```

---

## **TEMPLATE 6: PHPUNIT CONFIGURATION**

### File: `phpunit.xml`

```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:noNamespaceSchemaLocation="vendor/phpunit/phpunit/phpunit.xsd"
         bootstrap="vendor/autoload.php"
         colors="true">
    <testsuites>
        <testsuite name="Unit">
            <directory>tests/Unit</directory>
        </testsuite>
        <testsuite name="Feature">
            <directory>tests/Feature</directory>
        </testsuite>
    </testsuites>
    <source>
        <include>
            <directory>app</directory>
        </include>
    </source>
    <php>
        <env name="APP_ENV" value="testing"/>
        <env name="APP_KEY" value="base64:2fl+Ktvkfl+Fuz4Qp/A75G2RTiWVA/ZoKzLp2Lld4Pw="/>
        <env name="BCRYPT_ROUNDS" value="4"/>
        <env name="CACHE_DRIVER" value="array"/>
        <env name="DB_CONNECTION" value="sqlite"/>
        <env name="DB_DATABASE" value=":memory:"/>
        <env name="MAIL_MAILER" value="array"/>
        <env name="QUEUE_CONNECTION" value="sync"/>
        <env name="SESSION_DRIVER" value="array"/>
        <env name="TELESCOPE_ENABLED" value="false"/>
    </php>
</phpunit>
```

---

## **CHEAT SHEET: TESTING COMMANDS**

```bash
# Basic testing commands
php artisan test                              # Run all tests
php artisan test --verbose                   # Verbose output
php artisan test --stop-on-failure          # Stop on first failure

# Run specific test files
php artisan test tests/Unit/Models/BranchTest.php
php artisan test tests/Feature/Controllers/BranchControllerTest.php

# Run specific test methods
php artisan test --filter=test_store_boundary_values
php artisan test --filter=BranchControllerTest::test_store_with_valid_data

# Coverage and reporting
php artisan test --coverage-text            # Text coverage report
php artisan test --coverage-html=coverage   # HTML coverage report
php artisan test --log-junit=results.xml    # JUnit XML for CI/CD

# Performance testing
time php artisan test                        # Measure execution time
php artisan test --profile                  # Profile slow tests
```

---

## **TROUBLESHOOTING GUIDE**

### **Common Error Solutions**

```php
// Error: "Database file does not exist"
// Solution: Check .env.testing configuration
DB_CONNECTION=sqlite
DB_DATABASE=:memory:

// Error: "SQLSTATE[HY000]: General error: 1 no such table"
// Solution: Run migrations in testing environment
php artisan migrate --env=testing

// Error: "Class 'Tests\TestCase' not found"
// Solution: Check autoload and namespace
composer dump-autoload

// Error: "Method [factory] does not exist"
// Solution: Use RefreshDatabase trait
use Illuminate\Foundation\Testing\RefreshDatabase;

// Error: "Unique constraint violation"
// Solution: Add truncate in setUp()
protected function setUp(): void
{
    parent::setUp();
    Branch::query()->truncate();
}
```

---

**🎯 Template ini siap digunakan untuk implementasi testing yang komprehensif!**
