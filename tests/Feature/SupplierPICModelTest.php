<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Supplier;
use App\Models\SupplierPICModel;
use Illuminate\Support\Facades\DB;

class SupplierPICModelTest extends TestCase
{
    public function test_search_supplier_pic_returns_results_based_on_keywords()
    {
        DB::beginTransaction();

        try {
            $supplier = Supplier::create([
                'supplier_id'   => 'S001',
                'company_name'  => 'Contoh Supplier',
                'address'       => 'Jalan Testing No. 1',
                'phone_number'  => '0800000001',
                'bank_account'  => '1234567890',
            ]);

            $pic = new SupplierPICModel;
            $pic->supplier_id = 'S001';
            $pic->name = 'Test User';
            $pic->phone_number = '0811111111';
            $pic->email = 'test@example.com';
            $pic->assigned_date = now();
            $pic->save();

            $result = SupplierPICModel::searchSupplierPic('Test');

            $this->assertGreaterThan(0, $result->total());
            $this->assertEquals('Test User', $result->first()->name);

        } finally {
            DB::rollBack();
        }
    }

    public function test_get_supplier_pic_by_supplier_id()
    {
        DB::beginTransaction();

        try {
            $supplier = Supplier::create([
                'supplier_id'   => 'S002',
                'company_name'  => 'Supplier Dua',
                'address'       => 'Jalan Supplier Dua No. 2',
                'phone_number'  => '0800000002',
                'bank_account'  => '9876543210',
            ]);

            $pic = new SupplierPICModel;
            $pic->supplier_id = 'S002';
            $pic->name = 'Second User';
            $pic->phone_number = '0822222222';
            $pic->email = 'second@example.com';
            $pic->assigned_date = now();
            $pic->save();

            $result = SupplierPICModel::getSupplierPic('S002');

            $this->assertGreaterThan(0, $result->total());
            $this->assertEquals('Second User', $result->first()->name);

        } finally {
            DB::rollBack();
        }
    }
}
