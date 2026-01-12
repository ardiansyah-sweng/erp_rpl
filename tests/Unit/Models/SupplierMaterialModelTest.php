<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Mockery;
use App\Models\SupplierMaterial;

class SupplierMaterialModelTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_countSupplierMaterialByType_returns_expected_count()
    {
        // 1. Mock Koneksi Database (karena kadang Laravel ngecek driver)
        $connMock = Mockery::mock();
        $connMock->shouldReceive('getDriverName')->andReturn('mysql');
        DB::shouldReceive('connection')->andReturn($connMock);

        // 2. FIX YANG TADI: Izinkan DB::raw dipanggil
        DB::shouldReceive('raw')
            ->andReturnUsing(function ($arg) {
                return $arg;
            });

        // 3. Mock Query Builder
        $query = Mockery::mock();
        $query->shouldReceive('join')->andReturnSelf();
        $query->shouldReceive('where')->andReturnSelf();
        $query->shouldReceive('distinct')->andReturnSelf();
        
        // Kita expect method 'count' bakal dipanggil sekali dan balikin angka 7
        $query->shouldReceive('count')->with(Mockery::any())->andReturn(7);

        // 4. Pasang Mock ke Facade DB
        DB::shouldReceive('table')->with('supplier_product as sp')->andReturn($query);

        // 5. Jalankan method aslinya
        $count = SupplierMaterial::countSupplierMaterialByType('RM', 123);

        // 6. Assert hasilnya bener 7
        $this->assertSame(7, $count);
    }
}