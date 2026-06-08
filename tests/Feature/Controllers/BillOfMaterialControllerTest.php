<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\BillOfMaterial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class BillOfMaterialControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
    }

    // =========================================================
    // HELPER: menyisipkan data BOM dummy ke database
    // =========================================================
    private function insertBom(string $bomId, string $bomName, int $totalCost = 500000, bool $active = true): void
    {
        DB::table('bill_of_material')->insert([
            'bom_id'           => $bomId,
            'bom_name'         => $bomName,
            'measurement_unit' => 1, // tinyinteger: FK ke tabel measurement_unit
            'total_cost'       => $totalCost,
            'active'           => $active ? 1 : 0,
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);
    }

    // =========================================================
    // TEST 1: Tambah BOM berhasil
    // =========================================================
    /**
     * Skenario: Data BOM baru dikirim lewat POST, harus berhasil disimpan
     * dan redirect balik dengan pesan sukses.
     */
    public function test_tambah_bom_berhasil()
    {
        // ARRANGE
        $payload = [
            'bom_name'         => 'Resep Meja Kayu',
            'measurement_unit' => 1,
            'total_cost'       => 750000,
            'active'           => 1,
        ];

        // ACT
        $response = $this->post('/billofmaterial/add', $payload);

        // ASSERT
        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('bill_of_material', [
            'bom_name'   => 'Resep Meja Kayu',
            'total_cost' => 750000,
        ]);
    }

    // =========================================================
    // TEST 2: Tambah BOM gagal - validasi nama kosong
    // =========================================================
    /**
     * Skenario: Nama BOM tidak diisi, harus gagal validasi (302 redirect back
     * dengan error di session).
     */
    public function test_tambah_bom_gagal_nama_kosong()
    {
        // ARRANGE
        $payload = [
            'bom_name'         => '',   // sengaja kosong
            'measurement_unit' => 1,
            'total_cost'       => 100000,
            'active'           => 1,
        ];

        // ACT
        $response = $this->post('/billofmaterial/add', $payload);

        // ASSERT - validasi Laravel menolak dan redirect balik dengan error
        $response->assertSessionHasErrors('bom_name');
    }

    // =========================================================
    // TEST 3: Tambah BOM gagal - nama duplikat
    // =========================================================
    /**
     * Skenario: Nama BOM yang sama sudah ada di database, harus gagal unique validation.
     */
    public function test_tambah_bom_gagal_nama_duplikat()
    {
        // ARRANGE - sisipkan data terlebih dahulu
        $this->insertBom('BOM001', 'Resep Duplikat');

        $payload = [
            'bom_name'         => 'Resep Duplikat', // nama yang sama
            'measurement_unit' => 1,
            'total_cost'       => 200000,
            'active'           => 1,
        ];

        // ACT
        $response = $this->post('/billofmaterial/add', $payload);

        // ASSERT
        $response->assertSessionHasErrors('bom_name');
    }

    // =========================================================
    // TEST 4: Ambil semua BOM berhasil (JSON)
    // =========================================================
    /**
     * Skenario: GET /bill-of-material harus mengembalikan JSON berisi
     * semua data BOM yang ada.
     */
    public function test_get_semua_bom_mengembalikan_json()
    {
        // ARRANGE
        $this->insertBom('BOM001', 'Resep Kursi Kayu', 300000);
        $this->insertBom('BOM002', 'Resep Meja Besi', 600000);

        // ACT
        $response = $this->get('/bill-of-material');

        // ASSERT
        $response->assertStatus(200);
        $response->assertJsonFragment(['bom_name' => 'Resep Kursi Kayu']);
        $response->assertJsonFragment(['bom_name' => 'Resep Meja Besi']);
    }

    // =========================================================
    // TEST 5: Ambil semua BOM - database kosong
    // =========================================================
    /**
     * Skenario: Tidak ada data BOM, response tetap 200 dengan data kosong.
     */
    public function test_get_semua_bom_saat_database_kosong()
    {
        // ACT
        $response = $this->get('/bill-of-material');

        // ASSERT
        $response->assertStatus(200);
    }

    // =========================================================
    // TEST 6: Ambil detail BOM berdasarkan ID berhasil
    // =========================================================
    /**
     * Skenario: GET /bill-of-material/{id} dengan ID valid harus mengembalikan
     * JSON detail BOM.
     */
    public function test_get_detail_bom_berdasarkan_id_berhasil()
    {
        // ARRANGE
        $this->insertBom('BOM001', 'Resep Lemari Jati');
        $bom = DB::table('bill_of_material')->where('bom_id', 'BOM001')->first();

        // ACT
        $response = $this->get("/bill-of-material/{$bom->id}");

        // ASSERT
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'bom_id'   => 'BOM001',
            'bom_name' => 'Resep Lemari Jati',
        ]);
        $response->assertJsonStructure([
            'id', 'bom_id', 'bom_name', 'measurement_unit',
            'total_cost', 'active', 'created_at', 'updated_at', 'details',
        ]);
    }

    // =========================================================
    // TEST 7: Ambil detail BOM - ID tidak ditemukan (404)
    // =========================================================
    /**
     * Skenario: GET /bill-of-material/{id} dengan ID yang tidak ada harus
     * mengembalikan status 404.
     */
    public function test_get_detail_bom_tidak_ditemukan()
    {
        // ACT
        $response = $this->get('/bill-of-material/9999');

        // ASSERT
        $response->assertStatus(404);
    }

    // =========================================================
    // TEST 8: Pencarian BOM - ditemukan berdasarkan keyword
    // =========================================================
    /**
     * Skenario: GET /bill-of-material/search/{keyword} dengan keyword yang
     * cocok harus mengembalikan data yang relevan.
     */
    public function test_pencarian_bom_ditemukan_berdasarkan_keyword()
    {
        // ARRANGE
        $this->insertBom('BOM001', 'Laptop Gaming Pro');
        $this->insertBom('BOM002', 'Meja Kantor Kayu');

        // ACT
        $response = $this->get('/bill-of-material/search/Laptop');

        // ASSERT
        $response->assertStatus(200);
        $response->assertJsonFragment(['bom_name' => 'Laptop Gaming Pro']);
    }

    // =========================================================
    // TEST 9: Pencarian BOM - tidak ada hasil
    // =========================================================
    /**
     * Skenario: Keyword tidak cocok dengan data apapun, response 200 tetapi
     * data kosong.
     */
    public function test_pencarian_bom_tidak_ada_hasil()
    {
        // ARRANGE
        $this->insertBom('BOM001', 'Laptop Gaming Pro');

        // ACT
        $response = $this->get('/bill-of-material/search/Mobil');

        // ASSERT
        $response->assertStatus(200);
        $data = $response->json('data.data');
        $this->assertEmpty($data);
    }

    // =========================================================
    // TEST 10: Update BOM berhasil
    // =========================================================
    /**
     * Skenario: PUT /bill-of-material/{id} dengan data valid harus memperbarui
     * data BOM di database.
     */
    public function test_update_bom_berhasil()
    {
        // ARRANGE
        $this->insertBom('BOM001', 'Nama Lama');
        $bom = DB::table('bill_of_material')->where('bom_id', 'BOM001')->first();

        $payload = [
            'bom_name'         => 'Nama Baru Setelah Update',
            'measurement_unit' => 2,
            'total_cost'       => 999000,
            'active'           => 1,
        ];

        // ACT
        $response = $this->put("/bill-of-material/{$bom->id}", $payload);

        // ASSERT
        $response->assertStatus(200);
        $response->assertJsonFragment(['message' => 'Bill of Material updated successfully.']);
        $this->assertDatabaseHas('bill_of_material', [
            'bom_name'   => 'Nama Baru Setelah Update',
            'total_cost' => 999000,
        ]);
    }

    // =========================================================
    // TEST 11: Update BOM - ID tidak ditemukan (404)
    // =========================================================
    /**
     * Skenario: PUT /bill-of-material/{id} dengan ID yang tidak ada harus
     * mengembalikan status 404.
     */
    public function test_update_bom_tidak_ditemukan()
    {
        // ACT
        $response = $this->put('/bill-of-material/9999', [
            'bom_name'         => 'Tidak Ada',
            'measurement_unit' => 1,
            'total_cost'       => 0,
            'active'           => 0,
        ]);

        // ASSERT
        $response->assertStatus(404);
        $response->assertJson(['message' => 'Bill of Material not found.']);
    }

    // =========================================================
    // TEST 12: Hapus BOM berhasil
    // =========================================================
    /**
     * Skenario: DELETE /bill-of-material/{id} dengan ID valid harus
     * menghapus data dari database dan mengembalikan pesan sukses.
     */
    public function test_hapus_bom_berhasil()
    {
        // ARRANGE
        $this->insertBom('BOM001', 'BOM yang Akan Dihapus');
        $bom = DB::table('bill_of_material')->where('bom_id', 'BOM001')->first();

        // ACT
        $response = $this->delete("/bill-of-material/{$bom->id}");

        // ASSERT
        $response->assertStatus(200);
        $response->assertJson(['message' => 'Bill of Material deleted successfully.']);
        $this->assertDatabaseMissing('bill_of_material', ['bom_id' => 'BOM001']);
    }

    // =========================================================
    // TEST 13: Hapus BOM - ID tidak ditemukan (404)
    // =========================================================
    /**
     * Skenario: DELETE /bill-of-material/{id} dengan ID yang tidak ada
     * harus mengembalikan status 404.
     */
    public function test_hapus_bom_tidak_ditemukan()
    {
        // ACT
        $response = $this->delete('/bill-of-material/9999');

        // ASSERT
        $response->assertStatus(404);
        $response->assertJson(['message' => 'Bill of Material not found.']);
    }

    // =========================================================
    // TEST 14: Cetak PDF BOM berhasil
    // =========================================================
    /**
     * Skenario: GET /bom/print-pdf harus mengembalikan response bertipe
     * application/pdf (atau inline PDF stream).
     */
    public function test_cetak_pdf_bom_berhasil()
    {
        // ARRANGE - pastikan ada data yang bisa dicetak
        $this->insertBom('BOM001', 'Resep Produk A', 200000, true);
        $this->insertBom('BOM002', 'Resep Produk B', 350000, false);

        // ACT
        $response = $this->get('/bom/print-pdf');

        // ASSERT
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    // =========================================================
    // TEST 15: Cetak PDF BOM saat tidak ada data
    // =========================================================
    /**
     * Skenario: GET /bom/print-pdf saat database kosong tetap menghasilkan
     * PDF (laporan kosong), bukan error.
     */
    public function test_cetak_pdf_bom_saat_data_kosong()
    {
        // ACT
        $response = $this->get('/bom/print-pdf');

        // ASSERT - tetap menghasilkan PDF, tidak boleh error 500
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }
}
