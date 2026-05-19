<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Models\SupplierPic;

class GetSupplierPICAllTest extends TestCase
{
    use WithoutMiddleware;

    /** @test */
    public function it_displays_supplier_pic_list_using_existing_database_data()
    {
        // Pastikan tabel tidak kosong (database harus sudah diisi manual sebelumnya)
        $this->assertTrue(SupplierPic::exists(), 'Tabel supplier_pics kosong, isi dulu datanya.');

        // Panggil endpoint
        $response = $this->get(route('supplier-pic.list')); // sesuaikan route jika beda

        // Pastikan response OK
        $response->assertStatus(200);

        // Pastikan view yang dipakai sesuai
        $response->assertViewIs('supplier.pic.list');

        // Ambil data dari model langsung untuk pembanding
        $expected = SupplierPic::getSupplierPICAll();

        // Pastikan data yang dikirim ke view sesuai
        $response->assertViewHas('pics', function ($pics) use ($expected) {
            return $pics->count() === $expected->count();
        });
    }
}
