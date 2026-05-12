<?php

namespace Tests\Feature;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Models\SupplierPic;
use Illuminate\Support\Facades\DB;

class GetSupplierPICAllTest extends TestCase
{
    use WithoutMiddleware;

    protected function setUp(): void
    {
        parent::setUp();

        DB::beginTransaction();
    }

    protected function tearDown(): void
    {
        DB::rollBack();

        parent::tearDown();
    }

    #[Test]
    public function it_displays_supplier_pic_list_using_existing_database_data()
    {
        DB::table(config('db_tables.supplier'))->insert([
            'supplier_id' => 'SPT001',
            'company_name' => 'Supplier PIC Test',
            'address' => 'Jalan Test PIC',
            'phone_number' => '0800000003',
            'bank_account' => '111222333',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table(config('db_tables.supplier_pic'))->insert([
            'supplier_id' => 'SPT001',
            'name' => 'PIC Feature Test',
            'phone_number' => '081234567890',
            'email' => 'pic-feature-test@example.com',
            'assigned_date' => now()->toDateString(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertTrue(SupplierPic::exists(), 'Tabel supplier_pic kosong.');

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
