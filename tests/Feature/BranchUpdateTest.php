<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Branch;

class BranchUpdateTest extends TestCase
{
    /** @test */
    public function test_update_branch_berhasil()
    {
        // Ambil branch yang tersedia
        $branch = Branch::whereNotNull('id')->first();

        // Jika tidak ada data branch, hentikan test dengan gagal
        if (!$branch) {
            $this->fail('Tidak ada data branch yang tersedia di database. Silakan tambahkan dulu.');
        }

        // Data update yang fix
        $dataUpdate = [
            'branch_name' => 'Cabang Baru',
            'branch_address' => 'Alamat Baru',
            'branch_telephone' => '7891011',
        ];

        // Kirim permintaan update via POST
        $response = $this->post("/branch/update/{$branch->id}", $dataUpdate);

        // Ambil data terbaru dari database
        $branchBaru = Branch::find($branch->id);

        // Periksa datanya sudah terupdate
        $this->assertEquals('Cabang Baru', $branchBaru->branch_name);
        $this->assertEquals('Alamat Baru', $branchBaru->branch_address);
        $this->assertEquals('7891011', $branchBaru->branch_telephone);

        // Pastikan redirect berhasil
        $response->assertRedirect(route('branch.list'));
    }
}
