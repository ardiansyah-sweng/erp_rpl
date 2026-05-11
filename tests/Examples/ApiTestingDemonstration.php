<?php

namespace Tests\Examples;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Branch;
use App\Constants\BranchColumns;

/**
 * API Testing Demonstration Class
 * Menunjukkan berbagai cara testing API dalam Laravel
 */
class ApiTestingDemonstration extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Setup database untuk testing
        $this->artisan('migrate');
    }

    /**
     * Demonstrasi: Cara mengtes API endpoint sederhana
     */
    public function test_demonstration_basic_api_testing()
    {
        // ==== ARRANGE (Persiapan) ====
        // Buat data test menggunakan Factory
        $branch = Branch::factory()->create([
            BranchColumns::NAME => 'Demo Branch Jakarta'
        ]);

        // ==== ACT (Aksi) ====
        // Panggil API endpoint menggunakan Laravel test helpers
        $response = $this->getJson('/api/branches');

        // ==== ASSERT (Periksa Hasil) ====
        // Periksa status HTTP
        $response->assertStatus(200);
        
        // Periksa structure response JSON
        $response->assertJsonStructure([
            'data' => [
                '*' => ['id', 'branch_name', 'is_active']
            ],
            'meta' => ['total', 'active_count']
        ]);
        
        // Periksa data spesifik dalam response
        $response->assertJsonFragment([
            'branch_name' => 'Demo Branch Jakarta'
        ]);

        // Periksa jumlah data
        $this->assertCount(1, $response->json('data'));

        // ==== DEMONSTRASI DEBUGGING ====
        // Dump response untuk debugging (gunakan saat development)
        // dd($response->json()); // Uncomment untuk debug
        
        // Print information untuk learning
        $responseData = $response->json();
        $this->assertNotEmpty($responseData['data']);
        
        echo "\n✅ API Test berhasil! Branch: " . $responseData['data'][0]['branch_name'] . "\n";
    }

    /**
     * Demonstrasi: Testing dengan berbagai HTTP methods
     */
    public function test_demonstration_different_http_methods()
    {
        // 1. TEST POST (Create)
        $createData = [
            BranchColumns::NAME => 'New Test Branch',
            BranchColumns::ADDRESS => 'Test Address 123',
            BranchColumns::PHONE => '021-12345678'
        ];

        $createResponse = $this->postJson('/api/branches', $createData);
        $createResponse->assertStatus(201); // Created
        $branchId = $createResponse->json('data.id');

        // 2. TEST GET (Read)
        $readResponse = $this->getJson("/api/branches/{$branchId}");
        $readResponse->assertStatus(200); // OK

        // 3. TEST PUT (Update)
        $updateData = [
            BranchColumns::NAME => 'Updated Branch Name',
            BranchColumns::ADDRESS => 'Updated Address',
            BranchColumns::PHONE => '021-87654321'
        ];

        $updateResponse = $this->putJson("/api/branches/{$branchId}", $updateData);
        $updateResponse->assertStatus(200); // OK

        // 4. TEST DELETE
        $deleteResponse = $this->deleteJson("/api/branches/{$branchId}");
        $deleteResponse->assertStatus(200); // OK

        // 5. Verify deletion (should be 404)
        $notFoundResponse = $this->getJson("/api/branches/{$branchId}");
        $notFoundResponse->assertStatus(404); // Not Found

        echo "\n✅ Semua HTTP methods (GET, POST, PUT, DELETE) berfungsi!\n";
    }

    /**
     * Demonstrasi: Testing validation errors
     */
    public function test_demonstration_validation_testing()
    {
        // Test 1: Missing required field
        $incompleteData = [
            BranchColumns::ADDRESS => 'Alamat saja' 
            // Missing branch_name and branch_telephone
        ];

        $response = $this->postJson('/api/branches', $incompleteData);
        $response->assertStatus(422); // Unprocessable Entity
        $response->assertJsonValidationErrors([
            BranchColumns::NAME,
            BranchColumns::PHONE
        ]);

        // Test 2: Data too short
        $shortData = [
            BranchColumns::NAME => 'AB', // Too short (min:3)
            BranchColumns::ADDRESS => 'XY', // Too short (min:3)  
            BranchColumns::PHONE => '12' // Too short (min:3)
        ];

        $response2 = $this->postJson('/api/branches', $shortData);
        $response2->assertStatus(422);
        
        // Periksa error messages
        $errors = $response2->json('errors');
        $this->assertArrayHasKey(BranchColumns::NAME, $errors);
        $this->assertArrayHasKey(BranchColumns::ADDRESS, $errors);
        $this->assertArrayHasKey(BranchColumns::PHONE, $errors);

        echo "\n✅ Validation testing berhasil! API menolak data tidak valid.\n";
    }

    /**
     * Demonstrasi: Testing dengan query parameters
     */
    public function test_demonstration_query_parameters()
    {
        // Setup: Buat beberapa branch untuk testing
        Branch::factory()->create([
            BranchColumns::NAME => 'Jakarta Pusat',
            BranchColumns::IS_ACTIVE => true
        ]);

        Branch::factory()->create([
            BranchColumns::NAME => 'Jakarta Utara', 
            BranchColumns::IS_ACTIVE => false
        ]);

        Branch::factory()->create([
            BranchColumns::NAME => 'Surabaya Timur',
            BranchColumns::IS_ACTIVE => true  
        ]);

        // Test 1: Search parameter
        $searchResponse = $this->getJson('/api/branches?search=Jakarta');
        $searchResponse->assertStatus(200);
        $searchData = $searchResponse->json('data');
        $this->assertCount(2, $searchData);
        
        foreach ($searchData as $branch) {
            $this->assertStringContainsString('Jakarta', $branch['branch_name']);
        }

        // Test 2: Status filter parameter
        $activeResponse = $this->getJson('/api/branches?status=active');
        $activeResponse->assertStatus(200);
        $activeData = $activeResponse->json('data');
        $this->assertCount(2, $activeData);
        
        foreach ($activeData as $branch) {
            $this->assertTrue($branch['is_active']);
        }

        // Test 3: Combined parameters
        $combinedResponse = $this->getJson('/api/branches?search=Jakarta&status=active');
        $combinedResponse->assertStatus(200);
        $combinedData = $combinedResponse->json('data');
        $this->assertCount(1, $combinedData);

        echo "\n✅ Query parameter testing berhasil! Search dan filter berfungsi.\n";
    }

    /**
     * Demonstrasi: Testing database interactions
     */
    public function test_demonstration_database_interactions()
    {
        // Test 1: Pastikan data tersimpan ke database
        $branchData = [
            BranchColumns::NAME => 'Database Test Branch',
            BranchColumns::ADDRESS => 'Test Address',
            BranchColumns::PHONE => '021-12345678'
        ];

        $response = $this->postJson('/api/branches', $branchData);
        $response->assertStatus(201);

        // Periksa data di database menggunakan assertDatabaseHas
        $this->assertDatabaseHas('branches', [
            BranchColumns::NAME => 'Database Test Branch'
        ]);

        // Test 2: Pastikan data terhapus dari database
        $branchId = $response->json('data.id');
        $deleteResponse = $this->deleteJson("/api/branches/{$branchId}");
        $deleteResponse->assertStatus(200);

        // Periksa data tidak ada di database menggunakan assertDatabaseMissing
        $this->assertDatabaseMissing('branches', [
            'id' => $branchId
        ]);

        // Test 3: Hitung jumlah record
        $this->assertEquals(0, Branch::count());

        echo "\n✅ Database interaction testing berhasil! Data tersimpan dan terhapus dengan benar.\n";
    }

    /**
     * Demonstrasi: Testing response format dan data types
     */
    public function test_demonstration_response_format()
    {
        $branch = Branch::factory()->create();
        $response = $this->getJson("/api/branches/{$branch->id}");
        
        $response->assertStatus(200);
        
        // Periksa struktur response
        $response->assertJsonStructure([
            'id',
            'branch_name', 
            'branch_address',
            'branch_telephone',
            'is_active',
            'status',
            'display_name',
            'created_at',
            'created_at_human'
        ]);

        // Periksa tipe data dalam response
        $data = $response->json();
        
        $this->assertIsInt($data['id']);
        $this->assertIsString($data['branch_name']);
        $this->assertIsBool($data['is_active']);
        $this->assertIsString($data['status']);
        $this->assertIsString($data['display_name']);

        // Periksa nilai computed fields
        if ($data['is_active']) {
            $this->assertEquals('Aktif', $data['status']);
            $this->assertStringStartsWith('✅', $data['display_name']);
        } else {
            $this->assertEquals('Tidak Aktif', $data['status']);
            $this->assertStringStartsWith('❌', $data['display_name']);
        }

        echo "\n✅ Response format testing berhasil! Format JSON konsisten.\n";
    }

    /**
     * Demonstrasi: Performance testing sederhana
     */
    public function test_demonstration_performance()
    {
        // Buat dataset yang cukup besar
        Branch::factory()->count(50)->create();

        // Ukur waktu response
        $startTime = microtime(true);
        $response = $this->getJson('/api/branches');
        $endTime = microtime(true);

        $responseTime = $endTime - $startTime;
        
        $response->assertStatus(200);
        $this->assertCount(50, $response->json('data'));
        
        // API harus respond dalam waktu yang wajar (< 1 detik)
        $this->assertLessThan(1.0, $responseTime, 'API should respond within 1 second');

        echo "\n✅ Performance testing berhasil! Response time: " . round($responseTime * 1000) . "ms\n";
    }

    /**
     * Helper method untuk debugging response
     */
    private function debugResponse($response, $message = "Response Debug")
    {
        echo "\n" . "="*50 . "\n";
        echo $message . "\n";
        echo "Status: " . $response->getStatusCode() . "\n";
        echo "Response: " . $response->getContent() . "\n";
        echo "="*50 . "\n";
    }
}
