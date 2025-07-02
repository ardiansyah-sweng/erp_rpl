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
        // Ambil branch dengan id yang tersedia
        $branch = Branch::whereNotNull('id')->first();


        // Data yang akan diupdate
        $dataUpdate = [
        'branch_name' => $branch->branch_name,
        'branch_address' => $branch->branch_address,
        'branch_telephone' => $branch->branch_telephone,
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
