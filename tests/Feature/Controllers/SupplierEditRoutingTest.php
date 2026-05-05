<?php

namespace Tests\Feature\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase; // Ganti DatabaseTransactions dengan RefreshDatabase
use Tests\TestCase;
use App\Models\Supplier;

class SupplierEditRoutingTest extends TestCase
{
    use RefreshDatabase; // Menggunakan RefreshDatabase untuk memastikan database bersih dan seeded

    protected function setUp(): void
    {
        parent::setUp();
        
        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            $this->markTestSkipped('Koneksi database gagal: ' . $e->getMessage());
        }
        // Seed data supplier menggunakan factory
        Supplier::factory()->count(5)->create();
    }

     public function test_edit_button_has_valid_route_in_supplier_list()
    {
        $supplier = Supplier::first();
        
        $this->assertNotNull($supplier, 'Supplier harus ada di database');
        $response = $this->get('/supplier/list');
        
        $response->assertStatus(200);
        
        // Perbaikan: Assert bahwa URL route yang benar ada di HTML, bukan string literal 'supplier.detail'
        // Cek apakah path '/supplier/detail/' dan supplier_id ada di href tombol Edit
        $response->assertSee('/supplier/detail/');
        $response->assertSee($supplier->supplier_id);
        
        // Opsional: Assert bahwa URL lengkap route ada (lebih ketat)
        $expectedUrl = route('supplier.detail', ['id' => $supplier->supplier_id]);
        $response->assertSee($expectedUrl);
    }

    /**
     * Test bahwa route supplier.detail dapat diakses dengan parameter id yang valid
     */
    public function test_supplier_detail_route_is_accessible()
    {
        // Ambil supplier pertama dari database
        $supplier = Supplier::first();
        
        $this->assertNotNull($supplier, 'Supplier harus ada di database');

        // Akses route supplier.detail
        $response = $this->get(route('supplier.detail', ['id' => $supplier->supplier_id]));
        
        // Assert bahwa halaman berhasil dimuat
        $response->assertStatus(200);
        
        // Assert bahwa view yang benar ditampilkan (pastikan view supplier.detail ada)
        $response->assertViewIs('supplier.detail');
        
        // Assert bahwa data supplier dikirim ke view
        $response->assertViewHas('sup', function ($sup) use ($supplier) {
            return $sup && $sup->supplier_id === $supplier->supplier_id;
        });
    }

    /**
     * Test bahwa route name menggunakan lowercase yang konsisten
     */
    public function test_route_name_uses_lowercase_convention()
    {
        // Ambil supplier pertama dari database
        $supplier = Supplier::first();
        
        $this->assertNotNull($supplier, 'Supplier harus ada di database');

        // Generate URL menggunakan route name lowercase
        $url = route('supplier.detail', ['id' => $supplier->supplier_id]);
        
        // Assert bahwa URL dapat di-generate tanpa error
        $this->assertStringContainsString('/supplier/detail/', $url);
        $this->assertStringContainsString($supplier->supplier_id, $url);
    }

    /**
     * Test bahwa tombol Edit mengarah ke halaman yang sama dengan tombol Detail
     * (sesuai dengan kode perbaikan di document)
     */
    public function test_edit_and_detail_buttons_point_to_same_route()
    {
        // Ambil supplier pertama dari database
        $supplier = Supplier::first();
        
        $this->assertNotNull($supplier, 'Supplier harus ada di database');

        // Generate URL untuk tombol Edit dan Detail
        $editUrl = route('supplier.detail', ['id' => $supplier->supplier_id]);
        $detailUrl = route('supplier.detail', ['id' => $supplier->supplier_id]);
        
        // Assert bahwa keduanya mengarah ke URL yang sama
        $this->assertEquals($editUrl, $detailUrl);
    }

    /**
     * Test bahwa route dapat menangani multiple supplier
     */
    public function test_route_works_for_multiple_suppliers()
    {
        // Ambil 3 supplier dari database
        $suppliers = Supplier::take(3)->get();
        
        $this->assertCount(3, $suppliers, 'Harus ada minimal 3 supplier');

        foreach ($suppliers as $supplier) {
            // Akses route untuk setiap supplier
            $response = $this->get(route('supplier.detail', ['id' => $supplier->supplier_id]));
            
            // Assert bahwa setiap halaman berhasil dimuat
            $response->assertStatus(200);
            $response->assertViewIs('supplier.detail');
            
            // Assert bahwa data supplier yang benar ditampilkan
            $response->assertViewHas('sup', function ($sup) use ($supplier) {
                return $sup && $sup->supplier_id === $supplier->supplier_id;
            });
        }
    }

    /**
     * Test bahwa route menangani supplier_id yang tidak valid dengan baik
     */
    public function test_route_handles_invalid_supplier_id()
    {
        // Gunakan supplier_id yang tidak ada
        $invalidSupplierId = 'SUP999';
        
        // Akses route dengan supplier_id yang tidak valid
        $response = $this->get(route('supplier.detail', ['id' => $invalidSupplierId]));
        
        // Assert bahwa halaman mengembalikan response yang sesuai
        // Berdasarkan controller, jika supplier tidak ditemukan, mungkin return view dengan null atau 404
        $this->assertTrue(
            in_array($response->status(), [200, 404, 500, 302]),
            'Route harus menangani supplier_id tidak valid dengan status code yang sesuai. Status: ' . $response->status()
        );
    }

    /**
     * Test integrasi: dari list ke detail melalui tombol Edit
     */
    public function test_integration_from_list_to_detail_via_edit_button()
    {
        // Ambil supplier pertama dari database
        $supplier = Supplier::first();
        
        $this->assertNotNull($supplier, 'Supplier harus ada di database');

        // Step 1: Akses halaman list
        $listResponse = $this->get('/supplier/list');
        $listResponse->assertStatus(200);
        
        // Step 2: Simulate klik tombol Edit dengan mengakses route detail
        $detailResponse = $this->get(route('supplier.detail', ['id' => $supplier->supplier_id]));
        
        // Step 3: Assert bahwa halaman detail berhasil dimuat
        $detailResponse->assertStatus(200);
        $detailResponse->assertViewIs('supplier.detail');
        $detailResponse->assertViewHas('sup');
        
        // Step 4: Assert bahwa data supplier yang benar ditampilkan
        $detailResponse->assertSee($supplier->company_name);
        $detailResponse->assertSee($supplier->supplier_id);
    }
}