<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\Merk;

class MerkUnitTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_create_merk()
    {
        // Cek dulu struktur model Merk
        $merk = Merk::create([
            'merk' => 'Yamaha',
            // Hapus 'active' atau ganti dengan kolom yang sesuai
            // Jika tidak ada kolom active, cukup gunakan merk saja
        ]);

        // Atau jika ada kolom status/is_active
        // $merk = Merk::create([
        //     'merk' => 'Yamaha',
        //     'status' => 'Aktif', // atau 'is_active' => 1
        // ]);

        $this->assertDatabaseHas('merks', [
            'merk' => 'Yamaha',
        ]);
        
        // Verifikasi objek dibuat
        $this->assertNotNull($merk);
        $this->assertEquals('Yamaha', $merk->merk);
    }
}