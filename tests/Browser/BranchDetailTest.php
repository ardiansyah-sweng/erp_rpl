<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\Branch;

class BranchDetailTest extends DuskTestCase
{
    /**
     * Test scenario: View branch detail
     */
    public function test_view_branch_detail(): void
    {
        // Hapus data duplikat sebelum create
        \DB::table('branches')->where('branch_name', 'Cabang Dusk Detail')->delete();
        // Buat data cabang untuk test
        $branch = Branch::factory()->create([
            'branch_name' => 'Cabang Dusk Detail',
            'branch_address' => 'Jl. Dusk Detail No. 1',
            'branch_telephone' => '08123456789',
            'is_active' => true
        ]);

        $this->browse(function (Browser $browser) use ($branch) {
            // Kunjungi halaman detail cabang
            $browser->visit('/branches/' . $branch->id)
                ->assertSee('Detail Cabang')
                ->assertSee($branch->branch_name)
                ->assertSee($branch->branch_address)
                ->assertSee($branch->branch_telephone);
        });
    }
}
