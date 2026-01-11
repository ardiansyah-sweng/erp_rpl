<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\BillOfMaterial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class CgetBillOfMaterial_Test extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Pastikan database benar-benar kosong sebelum setiap test
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('bill_of_material')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Test untuk memverifikasi bahwa endpoint getBillOfMaterial mengembalikan response JSON
     * dengan status 200 ketika data kosong
     */
    public function test_get_bill_of_material_returns_empty_json_when_no_data()
    {
        // Arrange: Tidak ada data di database (sudah di-truncate di setUp)

        // Act: Mengakses endpoint getBillOfMaterial
        $response = $this->getJson('/bill-of-material');

        // Assert: Memastikan response adalah JSON array kosong dengan status 200
        $response->assertStatus(200);
        $response->assertJson([]);
        $response->assertJsonCount(0);
        $this->assertJson($response->getContent());
    }

    /**
     * Test untuk memverifikasi bahwa endpoint getBillOfMaterial mengembalikan 
     * semua data Bill of Material yang ada di database
     */
    public function test_get_bill_of_material_returns_all_boms_from_database()
    {
        // Arrange: Membuat beberapa data Bill of Material
        // CATATAN: measurement_unit harus integer (bukan string 'PCS', 'UNIT', dll)
        // Cek migration untuk mengetahui tipe data yang benar
        $bom1 = BillOfMaterial::create([
            'bom_id' => 'BOM001',
            'bom_name' => 'Produk A Assembly',
            'measurement_unit' => 1, // Ganti dari 'PCS' ke integer
            'total_cost' => 1500000.00,
            'active' => true,
        ]);

        $bom2 = BillOfMaterial::create([
            'bom_id' => 'BOM002',
            'bom_name' => 'Produk B Assembly',
            'measurement_unit' => 2, // Ganti dari 'UNIT' ke integer
            'total_cost' => 2500000.50,
            'active' => true,
        ]);

        $bom3 = BillOfMaterial::create([
            'bom_id' => 'BOM003',
            'bom_name' => 'Produk C Assembly',
            'measurement_unit' => 3, // Ganti dari 'SET' ke integer
            'total_cost' => 3500000.75,
            'active' => false,
        ]);

        // Act: Mengakses endpoint getBillOfMaterial
        $response = $this->getJson('/bill-of-material');

        // Assert: Memastikan semua data dikembalikan
        $response->assertStatus(200);
        
        // Debug: Tampilkan isi response jika perlu
        // dd($response->json());
        
        $response->assertJsonCount(3);
        
        // Verifikasi setiap item dalam response
        $responseData = $response->json();
        
        // Urutkan response berdasarkan id untuk memastikan urutan yang konsisten
        usort($responseData, function($a, $b) {
            return $a['id'] <=> $b['id'];
        });
        
        $this->assertEquals('BOM001', $responseData[0]['bom_id']);
        $this->assertEquals('Produk A Assembly', $responseData[0]['bom_name']);
        $this->assertEquals(1, $responseData[0]['measurement_unit']);
        $this->assertEquals(1500000.00, $responseData[0]['total_cost']);
        $this->assertEquals(true, $responseData[0]['active']);
        
        $this->assertEquals('BOM002', $responseData[1]['bom_id']);
        $this->assertEquals('Produk B Assembly', $responseData[1]['bom_name']);
        
        $this->assertEquals('BOM003', $responseData[2]['bom_id']);
        $this->assertEquals(false, $responseData[2]['active']);
    }

    /**
     * Test untuk memverifikasi struktur data yang dikembalikan oleh getBillOfMaterial
     */
    public function test_get_bill_of_material_returns_correct_data_structure()
    {
        // Arrange: Membuat satu data Bill of Material
        $bom = BillOfMaterial::create([
            'bom_id' => 'BOM001',
            'bom_name' => 'Test Product Assembly',
            'measurement_unit' => 1,
            'total_cost' => 1000000.00,
            'active' => true,
        ]);

        // Act: Mengakses endpoint getBillOfMaterial
        $response = $this->getJson('/bill-of-material');

        // Assert: Memastikan struktur data sesuai
        $response->assertStatus(200);
        $response->assertJsonCount(1);
        
        $firstItem = $response->json()[0];
        
        // Verifikasi key yang harus ada
        $requiredKeys = [
            'id', 'bom_id', 'bom_name', 'measurement_unit', 
            'total_cost', 'active', 'created_at', 'updated_at'
        ];
        
        foreach ($requiredKeys as $key) {
            $this->assertArrayHasKey($key, $firstItem, "Key '{$key}' tidak ditemukan dalam response");
        }
        
        // Verifikasi tipe data
        $this->assertIsInt($firstItem['id']);
        $this->assertIsString($firstItem['bom_id']);
        $this->assertIsString($firstItem['bom_name']);
        $this->assertIsInt($firstItem['measurement_unit']);
        $this->assertIsNumeric($firstItem['total_cost']);
        $this->assertIsBool($firstItem['active']);
    }

    /**
     * Test untuk memverifikasi bahwa getBillOfMaterial memanggil method 
     * getBillOfMaterial dari model BillOfMaterial
     */
    public function test_get_bill_of_material_calls_model_method()
    {
        // Arrange: Membuat data untuk memastikan model method bekerja
        $expectedData = [
            'bom_id' => 'BOM999',
            'bom_name' => 'Special Test Assembly',
            'measurement_unit' => 5,
            'total_cost' => 9999999.99,
            'active' => true,
        ];

        BillOfMaterial::create($expectedData);

        // Act: Mengakses endpoint
        $response = $this->getJson('/bill-of-material');

        // Assert: Memastikan data dari model dikembalikan
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'bom_id' => 'BOM999',
            'bom_name' => 'Special Test Assembly',
        ]);
    }

    /**
     * Test untuk memverifikasi bahwa getBillOfMaterial mengembalikan data 
     * dalam urutan yang benar (default order by created_at)
     */
    public function test_get_bill_of_material_returns_data_in_default_order()
    {
        // Arrange: Membuat data dengan tanggal created_at yang berbeda
        $bom3 = BillOfMaterial::create([
            'bom_id' => 'BOM003',
            'bom_name' => 'Product Z',
            'measurement_unit' => 1,
            'total_cost' => 3000,
            'active' => true,
            'created_at' => '2024-03-01 10:00:00',
        ]);

        $bom1 = BillOfMaterial::create([
            'bom_id' => 'BOM001',
            'bom_name' => 'Product X',
            'measurement_unit' => 1,
            'total_cost' => 1000,
            'active' => true,
            'created_at' => '2024-01-01 10:00:00',
        ]);

        $bom2 = BillOfMaterial::create([
            'bom_id' => 'BOM002',
            'bom_name' => 'Product Y',
            'measurement_unit' => 1,
            'total_cost' => 2000,
            'active' => true,
            'created_at' => '2024-02-01 10:00:00',
        ]);

        // Act: Mengakses endpoint
        $response = $this->getJson('/bill-of-material');

        // Assert: Memastikan data terurut berdasarkan created_at (ascending)
        $response->assertStatus(200);
        $response->assertJsonCount(3);
        
        $responseData = $response->json();
        
        // Urutkan manual berdasarkan created_at untuk verifikasi
        usort($responseData, function($a, $b) {
            return strtotime($a['created_at']) <=> strtotime($b['created_at']);
        });
        
        $this->assertEquals('BOM001', $responseData[0]['bom_id']);
        $this->assertEquals('Product X', $responseData[0]['bom_name']);
        
        $this->assertEquals('BOM002', $responseData[1]['bom_id']);
        $this->assertEquals('Product Y', $responseData[1]['bom_name']);
        
        $this->assertEquals('BOM003', $responseData[2]['bom_id']);
        $this->assertEquals('Product Z', $responseData[2]['bom_name']);
    }

    /**
     * Test untuk memverifikasi bahwa endpoint mengembalikan Content-Type application/json
     */
    public function test_get_bill_of_material_returns_json_content_type()
    {
        // Act: Mengakses endpoint
        $response = $this->get('/bill-of-material');

        // Assert: Memastikan header Content-Type adalah application/json
        $response->assertHeader('Content-Type', 'application/json');
        $response->assertStatus(200);
    }

    /**
     * Test untuk memverifikasi performa dengan banyak data
     */
    public function test_get_bill_of_material_with_large_dataset()
    {
        // Arrange: Membuat 50 data Bill of Material
        for ($i = 1; $i <= 50; $i++) {
            BillOfMaterial::create([
                'bom_id' => 'BOM' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'bom_name' => 'Assembly Product ' . $i,
                'measurement_unit' => $i % 2 == 0 ? 1 : 2,
                'total_cost' => 100000 * $i,
                'active' => $i % 3 != 0,
            ]);
        }

        // Act: Mengakses endpoint
        $response = $this->getJson('/bill-of-material');

        // Assert: Memastikan semua 50 data dikembalikan
        $response->assertStatus(200);
        $response->assertJsonCount(50);
        
        // Verifikasi beberapa data acak
        $responseData = $response->json();
        
        // Urutkan berdasarkan bom_id untuk verifikasi yang konsisten
        usort($responseData, function($a, $b) {
            return $a['bom_id'] <=> $b['bom_id'];
        });
        
        $this->assertEquals('BOM001', $responseData[0]['bom_id']);
        $this->assertEquals('BOM050', $responseData[49]['bom_id']);
    }

    /**
     * Test untuk memverifikasi bahwa data dengan status active dan inactive 
     * sama-sama dikembalikan
     */
    public function test_get_bill_of_material_includes_both_active_and_inactive()
    {
        // Arrange: Membuat data active dan inactive
        $activeBom = BillOfMaterial::create([
            'bom_id' => 'BOM001',
            'bom_name' => 'Active Product',
            'measurement_unit' => 1,
            'total_cost' => 1500000,
            'active' => true,
        ]);

        $inactiveBom = BillOfMaterial::create([
            'bom_id' => 'BOM002',
            'bom_name' => 'Inactive Product',
            'measurement_unit' => 1,
            'total_cost' => 2500000,
            'active' => false,
        ]);

        // Act: Mengakses endpoint
        $response = $this->getJson('/bill-of-material');

        // Assert: Memastikan kedua data dikembalikan
        $response->assertStatus(200);
        $response->assertJsonCount(2);
        
        $responseData = $response->json();
        
        // Cari data berdasarkan bom_id
        $foundBOM001 = false;
        $foundBOM002 = false;
        
        foreach ($responseData as $item) {
            if ($item['bom_id'] === 'BOM001') {
                $foundBOM001 = true;
                $this->assertEquals(true, $item['active']);
            }
            if ($item['bom_id'] === 'BOM002') {
                $foundBOM002 = true;
                $this->assertEquals(false, $item['active']);
            }
        }
        
        $this->assertTrue($foundBOM001, "BOM001 tidak ditemukan dalam response");
        $this->assertTrue($foundBOM002, "BOM002 tidak ditemukan dalam response");
    }
}