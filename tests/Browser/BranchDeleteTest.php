<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\Branch;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class BranchDeleteTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_it_can_delete_unused_branch()
    {
        // Arrange: create a branch to delete
        $branch = Branch::factory()->create([
            'branch_name' => 'Unused Branch',
            'branch_address' => 'Jl. Delete Me',
            'branch_telephone' => '0800-DELETE',
            'is_active' => false
        ]);

        $this->browse(function (Browser $browser) use ($branch) {
            // Go to branch index page
            $browser->visit('/branches')
                // Find the row for the unused branch
                ->assertSee('Unused Branch')
                // Click delete button (assume button has data-id or unique selector)
                ->press('@delete-branch-' . $branch->id)
                // Confirm deletion (if modal, handle it)
                ->whenAvailable('.modal-confirm', function ($modal) {
                    $modal->press('Delete');
                })
                // Assert success message
                ->waitForText('Cabang berhasil dihapus')
                ->assertDontSee('Unused Branch');
        });
    }
}
