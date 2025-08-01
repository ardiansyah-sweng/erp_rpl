<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Merk;

class MerkModelTest extends TestCase
{
    /** @test */
    public function it_can_delete_existing_dummy_merk()
    {

        $merk = Merk::first();
        $this->assertNotNull($merk, "Tidak ada data merk di database untuk dites.");
        $id = (string) $merk->id;
        $deleted = Merk::deleteMerk($id);
        $this->assertTrue($deleted, "Data dengan ID $id gagal dihapus");
        $this->assertDatabaseMissing('merks', [
            'id' => $id
        ]);
    }

    /** @test */
    public function it_returns_false_if_dummy_id_not_found()
    {
        $deleted = Merk::deleteMerk('9999');
        $this->assertFalse($deleted);
    }
}
