<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CountSupplierTest extends TestCase
{
    use RefreshDatabase;

    public function test_count_supplier()
    {
        // Arrange: Buat beberapa data supplier menggunakan factory
        Supplier::factory()->count(50)->create();

        // Act: Hitung jumlah supplier
        $count = Supplier::countSupplier();

        // Assert: Periksa apakah jumlahnya sesuai
        $this->assertEquals(50, $count);
    }

    public function test_count_supplier_with_no_data()
    {
        // Act: Hitung jumlah supplier ketika tidak ada data
        $count = Supplier::countSupplier();

        // Assert: Periksa apakah jumlahnya 0
        $this->assertEquals(0, $count);
    }

    public function test_count_supplier_after_adding_one()
    {
        // Arrange: Tambah satu supplier menggunakan factory
        Supplier::factory()->create();

        // Act: Hitung jumlah supplier
        $count = Supplier::countSupplier();

        // Assert: Periksa apakah jumlahnya 1
        $this->assertEquals(1, $count);
    }
}
