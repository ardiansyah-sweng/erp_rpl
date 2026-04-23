<?php

namespace Tests\Feature;

use Tests\TestCase;

class SupplierSearchTest extends TestCase
{
    /**
     * Test 1: Cek apakah halaman List Supplier bisa dibuka dengan normal.
     */
    public function test_halaman_supplier_list_bisa_diakses()
    {
        // Coba buka halaman /supplier/list
        $response = $this->get('/supplier/list');

        // Harapannya: Status OK (200)
        $response->assertStatus(200);
    }

    /**
     * Test 2: Cek apakah fitur Search tidak bikin error.
     */
    public function test_fitur_search_tidak_error()
    {
        // Coba buka halaman dengan kata kunci pencarian
        $response = $this->get('/supplier/list?search=papua');

        // Harapannya: Status OK (200) dan tidak error 500
        $response->assertStatus(200);
    }
}