<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\Branch;

class BranchUpdateTest extends DuskTestCase
{
    /**
     * Test scenario: Update branch with valid data
     */
    public function test_update_branch_with_valid_data(): void
    {
        // Bersihkan data branch sebelum test
        Branch::query()->delete();

        // Buat data cabang awal
        $branch = Branch::factory()->create([
            'branch_name' => 'Cabang Lama',
            'branch_address' => 'Jl. Lama No. 1',
            'branch_telephone' => '08123456789',
            'is_active' => true
        ]);

        $this->browse(function (Browser $browser) use ($branch) {
            // Kunjungi halaman edit cabang
            $browser->visit('/branches/' . $branch->id . '/edit')
                ->waitFor('form[id="branchForm"]', 10)
                ->type('branch_name', 'Cabang Baru')
                ->type('branch_address', 'Jl. Baru No. 2')
                ->type('branch_telephone', '08987654321')
                ->check('is_active')
                ->press('Simpan')
                ->pause(2000)
                ->assertPathIs('/branches')
                ->assertSee('berhasil diupdate')
                ->assertSee('Cabang Baru');
        });
    }
}
