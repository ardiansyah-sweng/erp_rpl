<?php

namespace Tests\Feature;

use Tests\TestCase;

class SupplierSearchTest extends TestCase
{
    public function test_can_search_supplier_by_keyword()
    {
        // Kirim request dengan keyword yang umum seperti "CV"
        $response = $this->get('/suppliers/search?keywords=CV');

        // Periksa status HTTP
        $response->assertStatus(200);

        // Periksa bahwa status = success
        $response->assertJsonFragment([
            'status' => 'success',
        ]);

        // Periksa bahwa respons mengandung minimal 1 supplier
        $responseData = $response->json();
        $this->assertIsArray($responseData['data']);
        $this->assertNotEmpty($responseData['data']);

        // Periksa bahwa field-field penting tersedia di satu data (sample)
        $sample = $responseData['data'][0];
        $this->assertArrayHasKey('supplier_id', $sample);
        $this->assertArrayHasKey('company_name', $sample);
        $this->assertArrayHasKey('address', $sample);
        $this->assertArrayHasKey('phone_number', $sample);
        $this->assertArrayHasKey('bank_account', $sample);
    }
}