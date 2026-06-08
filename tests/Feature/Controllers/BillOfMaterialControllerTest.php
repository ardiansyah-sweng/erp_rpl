<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use App\Models\BillOfMaterial;

/**
 * Feature tests untuk BillOfMaterialController
 *
 * Mencakup:
 * - Menampilkan daftar BOM (list)
 * - Menampilkan form edit BOM
 * - Mengupdate data BOM
 * - Validasi input saat update
 * - Cetak PDF daftar BOM
 * - Cetak PDF single BOM
 */
class BillOfMaterialControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Helper: Insert satu BOM ke database dan kembalikan record-nya.
     */
    private function createBOM(array $overrides = []): object
    {
        $tableName = (new BillOfMaterial())->getTable();

        $defaults = [
            'bom_id'           => 'BOM-T01',
            'bom_name'         => 'BOM Test Satu',
            'measurement_unit' => 1,
            'total_cost'       => 500000,
            'active'           => 1,
            'created_at'       => now(),
            'updated_at'       => now(),
        ];

        $data = array_merge($defaults, $overrides);
        $id   = DB::table($tableName)->insertGetId($data);

        return DB::table($tableName)->where('id', $id)->first();
    }

    // =========================================================================
    // LIST BOM
    // =========================================================================

    /**
     * Halaman list BOM harus dapat dibuka dan mengembalikan status 200.
     */
    public function test_bom_list_page_returns_200()
    {
        $response = $this->get('/bom/list');
        $response->assertStatus(200);
    }

    /**
     * Halaman list BOM menampilkan BOM yang sudah ada di database.
     */
    public function test_bom_list_shows_existing_bom_records()
    {
        $this->createBOM([
            'bom_id'     => 'BOM-L01',
            'bom_name'   => 'Resep Kue Coklat',
            'created_at' => now()->subYears(10),
        ]);

        $response = $this->get('/bom/list');

        $response->assertStatus(200);
        $response->assertSee('Resep Kue Coklat');
    }

    /**
     * Fitur pencarian di halaman list BOM harus menyaring hasil.
     */
    public function test_bom_list_search_filters_results()
    {
        $this->createBOM(['bom_id' => 'BOM-S01', 'bom_name' => 'Resep Roti Gandum']);
        $this->createBOM(['bom_id' => 'BOM-S02', 'bom_name' => 'Formula Sambal Matah']);

        $response = $this->get('/bom/list?search=Roti');

        $response->assertStatus(200);
        $response->assertSee('Resep Roti Gandum');
        $response->assertDontSee('Formula Sambal Matah');
    }

    // =========================================================================
    // EDIT BOM
    // =========================================================================

    /**
     * Halaman edit BOM harus dapat dibuka untuk ID yang valid.
     */
    public function test_bom_edit_page_returns_200_for_valid_id()
    {
        $bom = $this->createBOM(['bom_id' => 'BOM-E01', 'bom_name' => 'BOM Edit Test']);

        $response = $this->get("/bill-of-material/{$bom->id}/edit");

        $response->assertStatus(200);
    }

    /**
     * Halaman edit menampilkan data BOM yang benar (terisi di form).
     */
    public function test_bom_edit_page_displays_correct_bom_data()
    {
        $bom = $this->createBOM(['bom_id' => 'BOM-E02', 'bom_name' => 'BOM Tampil Test']);

        $response = $this->get("/bill-of-material/{$bom->id}/edit");

        $response->assertStatus(200);
        $response->assertSee('BOM Tampil Test');
        $response->assertSee('BOM-E02');
    }

    /**
     * Halaman edit harus mengembalikan 404 jika ID tidak ditemukan.
     */
    public function test_bom_edit_page_returns_404_for_invalid_id()
    {
        $response = $this->get('/bill-of-material/9999/edit');
        $response->assertStatus(404);
    }

    // =========================================================================
    // UPDATE BOM
    // =========================================================================

    /**
     * Update BOM berhasil menyimpan data ke database dan redirect dengan pesan sukses.
     */
    public function test_update_bom_saves_data_and_redirects_with_success()
    {
        $bom = $this->createBOM(['bom_id' => 'BOM-U01', 'bom_name' => 'BOM Sebelum Update']);

        $response = $this->put("/bill-of-material/{$bom->id}", [
            'bom_name'         => 'BOM Setelah Update',
            'measurement_unit' => 2,
            'total_cost'       => 750000,
            'active'           => 1,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas((new BillOfMaterial())->getTable(), [
            'id'       => $bom->id,
            'bom_name' => 'BOM Setelah Update',
        ]);
    }

    /**
     * Validasi wajib: update BOM harus gagal jika bom_name kosong.
     */
    public function test_update_bom_fails_validation_when_bom_name_is_empty()
    {
        $bom = $this->createBOM(['bom_id' => 'BOM-V01', 'bom_name' => 'BOM Validasi Test']);

        $response = $this->put("/bill-of-material/{$bom->id}", [
            'bom_name'         => '',
            'measurement_unit' => 1,
            'total_cost'       => 100000,
            'active'           => 1,
        ]);

        $response->assertSessionHasErrors(['bom_name']);
    }

    /**
     * Validasi: total_cost harus berupa angka non-negatif.
     */
    public function test_update_bom_fails_validation_when_total_cost_is_negative()
    {
        $bom = $this->createBOM(['bom_id' => 'BOM-V02', 'bom_name' => 'BOM Cost Test']);

        $response = $this->put("/bill-of-material/{$bom->id}", [
            'bom_name'         => 'BOM Cost Test',
            'measurement_unit' => 1,
            'total_cost'       => -999,
            'active'           => 1,
        ]);

        $response->assertSessionHasErrors(['total_cost']);
    }

    /**
     * Update BOM yang ID-nya tidak ada harus mengembalikan 404.
     */
    public function test_update_bom_returns_404_for_non_existent_id()
    {
        $response = $this->put('/bill-of-material/9999', [
            'bom_name'         => 'Tidak Ada',
            'measurement_unit' => 1,
            'total_cost'       => 100000,
            'active'           => 1,
        ]);

        $response->assertStatus(404);
    }

    // =========================================================================
    // CETAK PDF
    // =========================================================================

    /**
     * Route cetak PDF daftar BOM harus mengembalikan content-type PDF.
     */
    public function test_bom_print_pdf_returns_pdf_response()
    {
        $this->createBOM(['bom_id' => 'BOM-P01', 'bom_name' => 'BOM PDF Test']);

        $response = $this->get('/bill-of-material/print');

        $response->assertStatus(200);
        $this->assertStringContainsString('pdf', strtolower($response->headers->get('Content-Type')));
    }

    /**
     * Route cetak PDF single BOM harus mengembalikan content-type PDF untuk ID yang valid.
     */
    public function test_bom_print_single_pdf_returns_pdf_response()
    {
        $bom = $this->createBOM(['bom_id' => 'BOM-P02', 'bom_name' => 'BOM PDF Single Test']);

        $response = $this->get("/bill-of-material/{$bom->id}/print-single");

        $response->assertStatus(200);
        $this->assertStringContainsString('pdf', strtolower($response->headers->get('Content-Type')));
    }

    /**
     * Route cetak PDF single BOM harus mengembalikan 404 untuk ID yang tidak ada.
     */
    public function test_bom_print_single_pdf_returns_404_for_invalid_id()
    {
        $response = $this->get('/bill-of-material/9999/print-single');
        $response->assertStatus(404);
    }
}
