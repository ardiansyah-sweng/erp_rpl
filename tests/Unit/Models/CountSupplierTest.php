<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Supplier;

class CountSupplierTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_counts_suppliers_correctly()
    {
        // Arrange: Buat beberapa supplier
        Supplier::factory()->count(5)->create();

        // Act: Panggil fungsi countSupplier
        $count = Supplier::countSupplier();

        // Assert: Periksa jumlah supplier
        $this->assertEquals(5, $count);
    }

    /** @test */
    public function it_returns_zero_when_no_suppliers_exist()
    {
        // Act: Panggil fungsi countSupplier tanpa data
        $count = Supplier::countSupplier();

        // Assert: Harus 0
        $this->assertEquals(0, $count);
    }

    /** @test */
    public function it_counts_suppliers_after_adding_more()
    {
        // Arrange: Buat 3 supplier awal
        Supplier::factory()->count(3)->create();

        // Act: Tambah 2 supplier lagi
        Supplier::factory()->count(2)->create();

        // Assert: Periksa jumlah total supplier
        $count = Supplier::countSupplier();
        $this->assertEquals(5, $count);
    }
}