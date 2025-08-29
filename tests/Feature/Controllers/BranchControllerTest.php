<?php

namespace Tests\Feature\Controllers;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Branch;
use App\Constants\BranchColumns;

class BranchControllerTest extends TestCase
{
    protected $faker;

    protected function setUp(): void
    {
        parent::setUp();

        // Truncate tabel Branch dan jalankan BranchSeeder sebelum test
        \DB::table('branches')->truncate();
        \Artisan::call('db:seed', [
            '--class' => 'Database\\Seeders\\BranchSeeder',
            '--force' => true
        ]);

        // Setup Faker for Indonesian locale
        $this->faker = fake('id_ID');
    }

    // ========== SHOW METHOD TESTS ==========
    
    /**
     * Test web show method renders correct view with branch data
     */
    public function test_it_displays_branch_show_page()
    {
        // Arrange - Create test branch using Factory
        $branch = Branch::factory()->create([
            BranchColumns::NAME => 'Test Branch Detail ' . uniqid(),
            BranchColumns::ADDRESS => 'Jl. Test Detail No. 123',
            BranchColumns::PHONE => '081234567890'
        ]);

        // Act - Visit the show page
        $response = $this->get(route('branches.show', $branch->id));

        // Assert basic functionality
        $response->assertStatus(200);
        $response->assertViewIs('branches.detail');
        $response->assertViewHas('branch');
        
        // Assert branch data is displayed
        $response->assertSee($branch->branch_name);
        $response->assertSee('Jl. Test Detail No. 123');
        $response->assertSee('081234567890');
        
        // Assert view data
        $viewBranch = $response->viewData('branch');
        $this->assertEquals($branch->id, $viewBranch->id);
        $this->assertEquals($branch->branch_name, $viewBranch->branch_name);
        $this->assertEquals('Jl. Test Detail No. 123', $viewBranch->branch_address);
        $this->assertEquals('081234567890', $viewBranch->branch_telephone);
    }

    /**
     * Test web show method with non-existent branch returns 404
     */
    public function test_it_shows_404_for_non_existent_branch()
    {
        $nonExistentId = 99999;

        // Act - Try to access non-existent branch
        $response = $this->get(route('branches.show', $nonExistentId));

        // Assert 404 response
        $response->assertStatus(404);
    }

    /**
     * Test web show method with invalid ID format
     */
    public function test_it_shows_404_for_invalid_branch_id()
    {
        // Act - Try to access with invalid ID
        $response = $this->get('/branches/invalid-id');

        // Assert 404 response
        $response->assertStatus(404);
    }

    // ========== INDEX METHOD TESTS ==========

    public function test_it_displays_branches_index_page()
    {
        // Arrange - Create test data using Factory (better approach)
        Branch::factory()->count(2)->create();

        // Act - Visit the index page
        $response = $this->get(route('branches.index'));

        // Assert basic functionality
        $response->assertStatus(200);
        $response->assertViewIs('branches.index');
        $response->assertViewHas('branches');
        
        // Assert UI elements yang tampil di browser
        $response->assertSee('Branch');
        $response->assertSee('List Table');
        $response->assertSee('Tambah');
        $response->assertSee('Search Branch');
        $response->assertSee('Cetak Branch');
        
        // Assert data exists in browser
        $branches = $response->viewData('branches');
        $this->assertGreaterThanOrEqual(2, $branches->count());
        
        // Assert that branch data is displayed
        foreach ($branches->take(2) as $branch) {
            $response->assertSee($branch->branch_name);
            $response->assertSee($branch->branch_address);
            $response->assertSee($branch->branch_telephone);
        }
    }

    public function test_it_displays_empty_branches_index_page()
    {
        // Arrange - Kosongkan tabel branches
        Branch::query()->truncate();
        
        // Act - Visit the index page dengan tabel kosong
        $response = $this->get(route('branches.index'));

        // Assert basic functionality
        $response->assertStatus(200);
        $response->assertViewIs('branches.index');
        $response->assertViewHas('branches');
        
        // Assert UI elements yang tampil di browser
        $response->assertSee('Branch');
        $response->assertSee('List Table');
        $response->assertSee('Tambah');
        $response->assertSee('Search Branch');
        $response->assertSee('Cetak Branch');
        
        // Assert empty state - tabel benar-benar kosong
        $branches = $response->viewData('branches');
        $this->assertEquals(0, $branches->count());
        
        // Assert empty state messages
        $response->assertSee('No data available');
        
        // Cleanup - Isi kembali tabel branches dengan seeder
        $this->artisan('db:seed', ['--class' => 'BranchSeeder']);
    }

    /**
     * Test store fails dengan skenario invalid data
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('invalidBranchDataProvider')]
    public function test_store_fails_with_invalid_data($testData, $expectedErrorField, $description)
    {
        // Act - Submit data dengan field kosong
        $response = $this->post(route('branches.store'), $testData);

        // Assert - Validation error
        $response->assertStatus(302); // Redirect back
        $response->assertSessionHasErrors($expectedErrorField);
        
        // Assert specific error message based on field
        $expectedMessages = [
            BranchColumns::NAME => 'Nama cabang wajib diisi.',
            BranchColumns::ADDRESS => 'Alamat cabang wajib diisi.',
            BranchColumns::PHONE => 'Telepon cabang wajib diisi.'
        ];
        
        $response->assertSessionHasErrors([
            $expectedErrorField => $expectedMessages[$expectedErrorField]
        ]);

        // Assert data tidak tersimpan di database
        $this->assertDatabaseMissing('branches', array_filter($testData));
    }

    /**
     * Data provider untuk skenario invalid data (empty fields)
     */
    public static function invalidBranchDataProvider(): array
    {
        $faker = fake('id_ID'); // Use fake() helper for static context
        
        return [
            'empty_name' => [
                [
                    BranchColumns::NAME => '', // Kosong
                    BranchColumns::ADDRESS => $faker->address(),
                    BranchColumns::PHONE => $faker->phoneNumber()
                ],
                BranchColumns::NAME,
                'Nama branch kosong'
            ],
            'empty_address' => [
                [
                    BranchColumns::NAME => 'Cabang ' . $faker->city(),
                    BranchColumns::ADDRESS => '', // Kosong
                    BranchColumns::PHONE => $faker->phoneNumber()
                ],
                BranchColumns::ADDRESS,
                'Alamat branch kosong'
            ],
            'empty_phone' => [
                [
                    BranchColumns::NAME => 'Cabang ' . $faker->city(),
                    BranchColumns::ADDRESS => $faker->address(),
                    BranchColumns::PHONE => '' // Kosong
                ],
                BranchColumns::PHONE,
                'Telepon branch kosong'
            ]
        ];
    }

    /**
     * Test boundary values untuk semua field Branch - OPTIMIZED
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('essentialBoundaryTestProvider')]
    public function test_store_essential_boundaries($field, $value, $shouldPass, $expectedError, $description)
    {
        // Arrange - Clean database untuk menghindari uniqueness conflicts
        Branch::query()->truncate();
        
        // Arrange - Base valid data using Faker dengan unique values
        $testData = [
            BranchColumns::NAME => 'TestBranch' . time() . rand(1000, 9999), // Unique name
            BranchColumns::ADDRESS => 'TestAddress' . time() . rand(1000, 9999), // Unique address
            BranchColumns::PHONE => '021' . rand(10000000, 99999999) // Unique phone
        ];
        // Override specific field dengan boundary value
        $testData[$field] = $value;
        // Pastikan is_active dikirim untuk valid data
        if ($shouldPass) {
            $testData[BranchColumns::IS_ACTIVE] = 1;
        }

        // Act - Submit data
        $response = $this->post(route('branches.store'), $testData);

        if ($shouldPass) {
            // Assert - Should succeed
            $response->assertStatus(302);
            $response->assertRedirect(route('branches.index'));
            $response->assertSessionHasNoErrors();
            
            // Assert - Data tersimpan di database
            $this->assertDatabaseHas('branches', [
                $field => $value,
                BranchColumns::IS_ACTIVE => 1
            ]);
        } else {
            // Assert - Should fail
            $response->assertStatus(302);
            $response->assertSessionHasErrors($field);
            
            if ($expectedError) {
                $response->assertSessionHasErrors([
                    $field => $expectedError
                ]);
            }
            
            // Assert - Data tidak tersimpan di database
            $this->assertDatabaseMissing('branches', [
                $field => $value
            ]);
        }
    }

    /**
     * Essential boundary test provider - Only critical tests (12 tests vs 26)
     * Focus: 80/20 rule - 80% bug detection with 20% effort
     */
    public static function essentialBoundaryTestProvider(): array
    {
        return [
            // ====== CRITICAL MINIMUM BOUNDARIES (Prevent App Crashes) ======
            [BranchColumns::NAME, 'AB', false, 'Nama cabang minimal 3 karakter.', 'CRITICAL: Name below min'],
            [BranchColumns::ADDRESS, 'AB', false, 'Alamat cabang minimal 3 karakter.', 'CRITICAL: Address below min'],
            [BranchColumns::PHONE, '12', false, 'Telepon cabang minimal 3 karakter.', 'CRITICAL: Phone below min'],
            
            // ====== CRITICAL MAXIMUM BOUNDARIES (Prevent Database Overflow) ======
            [BranchColumns::NAME, str_repeat('A', 51), false, 'Nama cabang maksimal 50 karakter.', 'CRITICAL: Name above max'],
            [BranchColumns::ADDRESS, str_repeat('B', 101), false, 'Alamat cabang maksimal 100 karakter.', 'CRITICAL: Address above max'],
            [BranchColumns::PHONE, str_repeat('1', 31), false, 'Telepon cabang maksimal 30 karakter.', 'CRITICAL: Phone above max'],
            
            // ====== ESSENTIAL VALID BOUNDARIES (Happy Path) ======
            [BranchColumns::NAME, 'ABC', true, null, 'ESSENTIAL: Name min valid'],
            [BranchColumns::ADDRESS, 'ABC', true, null, 'ESSENTIAL: Address min valid'],
            [BranchColumns::PHONE, '123', true, null, 'ESSENTIAL: Phone min valid'],
            
            [BranchColumns::NAME, str_repeat('A', 50), true, null, 'ESSENTIAL: Name max valid'],
            [BranchColumns::ADDRESS, str_repeat('B', 100), true, null, 'ESSENTIAL: Address max valid'],
            [BranchColumns::PHONE, str_repeat('1', 30), true, null, 'ESSENTIAL: Phone max valid'],
        ];
    }

    /**
     * Test edge cases untuk real-world scenarios - OPTIONAL
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('edgeCaseTestProvider')]
    public function test_store_edge_cases($field, $value, $shouldPass, $expectedError, $description)
    {
        // Arrange - Clean database
        Branch::query()->truncate();
        
        // Arrange - Base valid data
        $testData = [
            BranchColumns::NAME => 'EdgeCase' . time(),
            BranchColumns::ADDRESS => 'EdgeAddress' . time(),
            BranchColumns::PHONE => '021' . rand(1000000, 9999999)
        ];
        
        $testData[$field] = $value;

        // Act
        $response = $this->post(route('branches.store'), $testData);

        // Assert
        if ($shouldPass) {
            $response->assertStatus(302);
            $response->assertRedirect(route('branches.index'));
            $response->assertSessionHasNoErrors();
        } else {
            $response->assertStatus(302);
            $response->assertSessionHasErrors($field);
        }

        Branch::query()->truncate();

        // Cleanup - Isi kembali tabel branches dengan seeder
        $this->artisan('db:seed', ['--class' => 'BranchSeeder']);        
    }

    /**
     * Edge case provider - Common real-world inputs (5 tests)
     */
    public static function edgeCaseTestProvider(): array
    {
        return [
            // Common user inputs with special characters
            [BranchColumns::NAME, 'Cabang & Co.', true, null, 'EDGE: Name with ampersand'],
            [BranchColumns::ADDRESS, 'Jl. Sudirman No. 123/A', true, null, 'EDGE: Address with slash'],
            [BranchColumns::PHONE, '021-123-4567', true, null, 'EDGE: Phone with dashes'],
            [BranchColumns::PHONE, '+62-21-123456', true, null, 'EDGE: Phone with plus'],
            [BranchColumns::NAME, 'Cabang 123', true, null, 'EDGE: Name with numbers'],
        ];
    }

    public function test_store_succeeds_with_valid_data()
    {
        // Arrange - Data valid untuk branch baru
        $validData = [
            BranchColumns::NAME => 'Cabang ' . $this->faker->city,
            BranchColumns::ADDRESS => $this->faker->address,
            BranchColumns::PHONE => $this->faker->phoneNumber,
            BranchColumns::IS_ACTIVE => 1,
        ];

        // Act - Submit data valid
        $response = $this->post(route('branches.store'), $validData);

        // Assert - Redirect success
        $response->assertStatus(302); // Redirect after successful store
        $response->assertRedirect(route('branches.index')); // Redirect ke halaman list
        $response->assertSessionHas('success', 'Cabang berhasil ditambahkan!');

        // Assert - Data tersimpan di database
        $this->assertDatabaseHas('branches', [
            BranchColumns::NAME => $validData[BranchColumns::NAME],
            BranchColumns::ADDRESS => $validData[BranchColumns::ADDRESS],
            BranchColumns::PHONE => $validData[BranchColumns::PHONE],
            BranchColumns::IS_ACTIVE => 1 // Default status aktif
        ]);

        // Assert - Tidak ada validation errors
        $response->assertSessionHasNoErrors();

        // Additional check - Verify record exists in database
        $branch = Branch::where(BranchColumns::NAME, $validData[BranchColumns::NAME])->first();
        $this->assertNotNull($branch);
        $this->assertEquals($validData[BranchColumns::ADDRESS], $branch->branch_address);
        $this->assertEquals($validData[BranchColumns::PHONE], $branch->branch_telephone);
        $this->assertEquals(1, $branch->is_active);
    }
    
}
