<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Supplier;
use App\Models\SupplierPic;

class GetSuppPicAllTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_supplier_pic_all_feature_works()
    {
        // 1. Insert supplier: wajib isi kolom non-null
        $supplier = new Supplier();
        $supplier->supplier_id = 'S001';
        $supplier->company_name = 'PT Contoh Supplier';
        $supplier->address = 'Jl. UAD No.1';
        $supplier->phone_number = '08123456789';
        $supplier->bank_account = '1234567890';
        $supplier->save();

        // 2. Insert PIC: wajib isi semua kolom yang NOT NULL
        $pic = SupplierPic::create([
            'supplier_id'   => $supplier->supplier_id, // ← ini key relasi foreign key
            'name'          => 'PIC Test',
            'email'         => 'pic@example.com',
            'phone_number'  => '08123456789',
            'assigned_date' => now()->subDays(5)->format('Y-m-d'),
            'active'        => true, // ← tambahkan kolom jika NOT NULL
            'avatar'        => 'default.jpg', // ← tambahkan jika NOT NULL
        ]);

        // 3. Panggil controller
        $response = $this->get('/supplier/pic/list');

        // 4. Cek hasil
        $response->assertStatus(200);
        $response->assertViewIs('supplier.pic.list');
        $response->assertViewHas('pics', function ($pics) use ($pic) {
            return $pics->contains($pic);
        });
    }
}
