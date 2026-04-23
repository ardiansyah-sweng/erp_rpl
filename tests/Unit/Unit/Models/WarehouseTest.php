<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class WarehouseTest extends TestCase
{
 
    public function test_tambah_stok_berhasil(): void
    {

        $warehouse = new \App\Models\Warehouse(); 
        $warehouse->stock = 10;

        $warehouse->addStock(5);

        $this->assertEquals(15, $warehouse->stock);
    }
}
