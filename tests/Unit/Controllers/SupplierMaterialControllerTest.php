<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase; // Kunci perbaikan untuk "TestCase not found"
use App\Models\SupplierMaterial;
use Illuminate\Http\Request;
use Mockery; 
use Illuminate\View\View;

// Catatan: use RefreshDatabase dihapus untuk menghindari error koneksi DB di lingkungan testing.

class SupplierMaterialControllerTest extends TestCase
{
    /**
     * Metode ini akan dijalankan setelah setiap tes untuk membersihkan Mockery.
     */
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * @test
     * Memverifikasi bahwa metode supplierMaterialSearch di controller memanggil
     * metode supplierMaterialSearch dari Model SupplierMaterial (sesuai tugas).
     * * Test ini menggunakan Mocking dan tidak memerlukan koneksi database.
     */
    public function it_calls_supplierMaterialSearch_from_model()
    {
        // 1. Persiapan (Arrange)
        $keyword = 'baja';
        $mockResults = collect([
            (object)['id' => 101, 'name' => 'Baja Ringan'],
            (object)['id' => 102, 'name' => 'Baja Berat'],
        ]);

        // Mocking: Mengganti Model SupplierMaterial dengan mock object (alias)
        // Ini memastikan kita tidak benar-benar menyentuh database.
        $supplierMaterialMock = Mockery::mock('alias:'.SupplierMaterial::class);
        
        // Verifikasi: Memastikan bahwa static method 'supplierMaterialSearch' dipanggil 1 kali 
        // dengan $keyword yang benar, dan mengembalikan data dummy.
        $supplierMaterialMock
            ->shouldReceive('supplierMaterialSearch')
            ->once()
            ->with($keyword)
            ->andReturn($mockResults);

        // Membuat instance Request dengan keyword
        $request = Request::create('/supplier/material/search', 'GET', ['keyword' => $keyword]);

        // Membuat instance Controller
        $controller = new \App\Http\Controllers\SupplierMaterialController();

        // 2. Eksekusi (Act)
        $response = $controller->supplierMaterialSearch($request);

        // 3. Verifikasi (Assert)
        
        // Memverifikasi bahwa respons adalah View
        $this->assertInstanceOf(View::class, $response);

        // Memverifikasi bahwa view yang dikembalikan benar
        $this->assertEquals('supplier.material.list', $response->getName());

        // Memverifikasi bahwa data 'supplierMaterials' di view sesuai dengan mock results
        $this->assertEquals($mockResults, $response->getData()['supplierMaterials']);

        // Memverifikasi bahwa keyword pencarian juga dilewatkan ke view
        $this->assertEquals($keyword, $response->getData()['searchKeyword']);
    }
}
