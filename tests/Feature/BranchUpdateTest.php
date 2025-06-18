<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Branch;

class BranchUpdateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_update_branch_berhasil()
    {
       

        // Data yang akan diupdate
        $dataUpdate = [
            'branch_name' => 'Cabang Baru',
            'branch_address' => 'Alamat Baru',
            'branch_telephone' => '7891011',
        ];

        // Kirim permintaan update via POST
        $response = $this->post("/branch/update/{$branch->id}", $dataUpdate);

        // Ambil data terbaru dari database
        $branchBaru = Branch::find($branch->id);

        // Periksa apakah datanya sudah terupdate
        $this->assertEquals('Cabang Baru', $branchBaru->branch_name);
        $this->assertEquals('Alamat Baru', $branchBaru->branch_address);
        $this->assertEquals('7891011', $branchBaru->branch_telephone);

        // Pastikan redirect berhasil
        $response->assertRedirect(route('branch.list'));
    }
}
