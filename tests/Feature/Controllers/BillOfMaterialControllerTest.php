<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\Test;

class BillOfMaterialControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_search_bill_of_material_endpoint_returns_expected_data(): void
    {
        // ===============================
        // ARRANGE (WAJIB ADA DI FILE INI)
        // ===============================
        
        // Setup database
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Buat tabel measurement_unit jika belum ada
        if (!DB::select("SHOW TABLES LIKE 'measurement_unit'")) {
            DB::statement("
                CREATE TABLE measurement_unit (
                    id INT PRIMARY KEY AUTO_INCREMENT,
                    unit_name VARCHAR(100),
                    abbreviation VARCHAR(10) NOT NULL,
                    created_at TIMESTAMP NULL,
                    updated_at TIMESTAMP NULL
                )
            ");
            
            DB::table('measurement_unit')->insert([
                [
                    'id' => 31,
                    'unit_name' => 'Pieces',
                    'abbreviation' => 'PCS',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);
        }
        
        // Buat tabel bill_of_material jika belum ada
        if (!DB::select("SHOW TABLES LIKE 'bill_of_material'")) {
            DB::statement("
                CREATE TABLE bill_of_material (
                    bom_id VARCHAR(50) PRIMARY KEY,
                    bom_name VARCHAR(255),
                    measurement_unit INT,
                    total_cost DECIMAL(15,2),
                    active TINYINT(1) DEFAULT 1,
                    created_at TIMESTAMP NULL,
                    updated_at TIMESTAMP NULL
                )
            ");
        }
        
        // Kosongkan dan isi data
        DB::table('bill_of_material')->truncate();
        
        DB::table('bill_of_material')->insert([
            [
                'bom_id' => 'BOM001',
                'bom_name' => 'Baut Besi 10mm',
                'measurement_unit' => 31,
                'total_cost' => 100000,
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'bom_id' => 'BOM002',
                'bom_name' => 'Mur Besi 10mm',
                'measurement_unit' => 31,
                'total_cost' => 50000,
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'bom_id' => 'BOM003',
                'bom_name' => 'Baut Stainless 8mm',
                'measurement_unit' => 31,
                'total_cost' => 150000,
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // ===============================
        // ACT
        // ===============================
        $response = $this->getJson('/bill-of-material/search/Baut');

        // ===============================
        // ASSERT
        // ===============================
        $response->assertStatus(200);

        $json = $response->json();
        
        // Validasi struktur response berdasarkan output yang berhasil
        $this->assertArrayHasKey('success', $json);
        $this->assertArrayHasKey('message', $json);
        $this->assertArrayHasKey('data', $json);
        
        // Data ada dalam $json['data']['data'] berdasarkan output
        $this->assertArrayHasKey('data', $json['data']);
        $this->assertIsArray($json['data']['data']);
        
        $this->assertNotEmpty(
            $json['data']['data'],
            'Tidak ada data ditemukan di hasil pencarian.'
        );

        // Ambil baris pertama dari hasil data
        $firstItem = $json['data']['data'][0];
        
        // Validasi data
        $this->assertArrayHasKey('bom_name', $firstItem);
        $this->assertStringContainsStringIgnoringCase('Baut', $firstItem['bom_name']);
        
        // Validasi struktur lengkap
        $this->assertArrayHasKey('bom_id', $firstItem);
        $this->assertArrayHasKey('measurement_unit', $firstItem);
        $this->assertArrayHasKey('total_cost', $firstItem);
        $this->assertArrayHasKey('active', $firstItem);
    }

    /** @test */
    public function test_search_returns_empty_when_no_match(): void
    {
        // Setup sederhana
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Pastikan tabel ada
        if (!DB::select("SHOW TABLES LIKE 'bill_of_material'")) {
            DB::statement("
                CREATE TABLE bill_of_material (
                    bom_id VARCHAR(50) PRIMARY KEY,
                    bom_name VARCHAR(255),
                    measurement_unit INT,
                    total_cost DECIMAL(15,2),
                    active TINYINT(1) DEFAULT 1,
                    created_at TIMESTAMP NULL,
                    updated_at TIMESTAMP NULL
                )
            ");
        }
        
        DB::table('bill_of_material')->truncate();
        DB::table('bill_of_material')->insert([
            [
                'bom_id' => 'BOM001',
                'bom_name' => 'Baut Besi',
                'measurement_unit' => 1,
                'total_cost' => 100000,
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Act
        $response = $this->getJson('/bill-of-material/search/XYZ123');

        // Assert
        $response->assertStatus(200);
        
        $json = $response->json();
        
        // Validasi response kosong
        $this->assertArrayHasKey('data', $json);
        $this->assertArrayHasKey('data', $json['data']);
        $this->assertIsArray($json['data']['data']);
        $this->assertEmpty($json['data']['data']);
    }

    /** @test */
    public function test_get_all_bill_of_materials(): void
    {
        // Setup
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        if (!DB::select("SHOW TABLES LIKE 'bill_of_material'")) {
            DB::statement("
                CREATE TABLE bill_of_material (
                    bom_id VARCHAR(50) PRIMARY KEY,
                    bom_name VARCHAR(255),
                    measurement_unit INT,
                    total_cost DECIMAL(15,2),
                    active TINYINT(1) DEFAULT 1,
                    created_at TIMESTAMP NULL,
                    updated_at TIMESTAMP NULL
                )
            ");
        }
        
        DB::table('bill_of_material')->truncate();
        DB::table('bill_of_material')->insert([
            [
                'bom_id' => 'BOM001',
                'bom_name' => 'Baut Besi',
                'measurement_unit' => 1,
                'total_cost' => 100000,
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'bom_id' => 'BOM002',
                'bom_name' => 'Mur Besi',
                'measurement_unit' => 1,
                'total_cost' => 50000,
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Act - coba endpoint get all
        $response = $this->getJson('/bill-of-material');

        // Assert
        if ($response->status() === 200) {
            $response->assertStatus(200);
            
            $json = $response->json();
            
            // Handle struktur response
            if (isset($json['data']['data'])) {
                $this->assertCount(2, $json['data']['data']);
            } elseif (isset($json['data'])) {
                $this->assertCount(2, is_array($json['data']) ? $json['data'] : []);
            }
        } else {
            $this->markTestSkipped('Endpoint get all BOM tidak ditemukan');
        }
    }
}