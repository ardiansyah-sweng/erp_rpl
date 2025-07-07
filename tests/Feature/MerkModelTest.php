<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Merk;
use Illuminate\Support\Facades\DB;

class MerkModelTest extends TestCase
{
    public function test_it_can_add_new_merk()
    {
        DB::table('merks')->where('merk', 'Contoh Merk')->delete();

        $merk = Merk::create([
            'merk' => 'Contoh Merk'
        ]);

        $this->assertDatabaseHas('merks', [
            'merk' => 'Contoh Merk'
        ]);

        DB::table('merks')->where('merk', 'Contoh Merk')->delete();
    }
}