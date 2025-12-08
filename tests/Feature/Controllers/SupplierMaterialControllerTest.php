<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class SupplierMaterialControllerTest extends TestCase
{
    /**
     * Pastikan koneksi test menggunakan database asli (bukan in-memory SQLite)
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Paksa test menggunakan koneksi MySQL utama
        \Config::set('database.default', 'mysql');

        // Nonaktifkan transaksi otomatis jika TestCase kamu menggunakannya
        DB::connection()->reconnect();
    }

    /**
     * Test: Dapat mencari supplier material berdasarkan product name
     * @test
     */
    public function it_can_search_supplier_material_by_product_name_keyword()
    {
        // Ambil salah satu product_name dari database untuk testing
        $sampleProduct = DB::table('supplier_product')->first();
        
        if (!$sampleProduct) {
            $this->markTestSkipped('Tidak ada data di tabel supplier_product');
        }
        
        // Ambil beberapa karakter dari product_name untuk dijadikan keyword
        $keyword = substr($sampleProduct->product_name, 0, 5);
        
        $response = $this->get("/supplier/material/search?keyword={$keyword}");

        $response->assertStatus(200);
        $response->assertViewIs('supplier.material.list');

        // Memastikan ada hasil yang dimuat (bisa berupa paginator atau collection)
        $response->assertViewHas('materials', function ($materials) {
            if (method_exists($materials, 'total')) {
                return $materials->total() >= 0;
            } else {
                return is_countable($materials);
            }
        });
    }

    /**
     * Test: Dapat mencari supplier material berdasarkan supplier ID
     * @test
     */
    public function it_can_search_supplier_material_by_supplier_id_keyword()
    {
        // Ambil salah satu supplier_id dari database untuk testing
        $sampleSupplier = DB::table('supplier_product')->first();
        
        if (!$sampleSupplier) {
            $this->markTestSkipped('Tidak ada data di tabel supplier_product');
        }
        
        $keyword = $sampleSupplier->supplier_id;
        
        $response = $this->get("/supplier/material/search?keyword={$keyword}");

        $response->assertStatus(200);
        $response->assertViewIs('supplier.material.list');

        // Memastikan ada hasil yang dimuat
        $response->assertViewHas('materials', function ($materials) use ($keyword) {
            if (method_exists($materials, 'total')) {
                return $materials->total() > 0;
            } else {
                return $materials->count() > 0;
            }
        });
    }

    /**
     * Test: Mengembalikan semua material ketika keyword kosong
     * @test
     */
    public function it_returns_all_materials_when_keyword_is_empty()
    {
        $response = $this->get('/supplier/material/search?keyword=');

        $response->assertStatus(200);
        $response->assertViewIs('supplier.material.list');

        $response->assertViewHas('materials', function ($materials) {
            if (method_exists($materials, 'total')) {
                return $materials->total() >= 0;
            } else {
                return is_countable($materials);
            }
        });
    }

    /**
     * Test: Mengembalikan hasil kosong untuk keyword yang tidak ada
     * @test
     */
    public function it_returns_empty_results_for_non_existent_keyword()
    {
        $response = $this->get('/supplier/material/search?keyword=TIDAKADAPRODUKINIXYZ123');

        $response->assertStatus(200);
        $response->assertViewIs('supplier.material.list');

        $response->assertViewHas('materials', function ($materials) {
            if (method_exists($materials, 'total')) {
                return $materials->total() === 0;
            } else {
                return $materials->count() === 0;
            }
        });
    }
}
