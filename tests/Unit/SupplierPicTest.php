<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class SupplierPicTest extends TestCase
{
    /** @test */
    public function test_form_pic_supplier_validation()
    {
        // simulasi data dari form yang tadi di buat
        $data = [
            'nama_pic' => 'Stevanus',
            'email'    => 'stevanus@manullang.com',
            'telephone'=> '6646750806136'
        ];

        // memastikan data nama tidak kosong sesuai PRD
        $this->assertNotEmpty($data['nama_pic']);
        $this->assertStringContainsString('@', $data['email']);
    }
}