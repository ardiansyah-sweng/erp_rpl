<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\Branch;
use App\Constants\BranchColumns;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BranchSimpleTest extends DuskTestCase
{
    use RefreshDatabase;

    /**
     * Test user can visit branches index page
     */
    public function test_user_can_visit_branches_index()
    {
        // Arrange - Create some test data
        Branch::factory()->count(2)->create();

        $this->browse(function (Browser $browser) {
            $browser->visit('/branches')
                    ->assertSee('Branch')
                    ->assertPresent('table');
        });
    }

    /**
     * Test user can create a branch through browser
     */
    public function test_user_can_create_branch_through_browser()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/branches/create')
                    ->type('branch_name', 'Test Branch Browser')
                    ->type('branch_address', 'Test Address Browser')
                    ->type('branch_telephone', '021-99999999')
                    ->press('Simpan')
                    ->waitForLocation('/branches', 10)
                    ->assertSee('Test Branch Browser');
        });

        // Verify in database
        $this->assertDatabaseHas('branches', [
            BranchColumns::NAME => 'Test Branch Browser'
        ]);
    }

    /**
     * Test user can see basic page elements
     */
    public function test_user_can_see_basic_page_elements()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/branches')
                    ->assertSee('Branch')
                    ->assertSee('List Table')
                    ->assertPresent('table');
        });
    }

    /**
     * Test create button is present
     */
    public function test_create_button_is_present()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/branches')
                    ->assertPresent('a[href*="create"]')
                    ->assertSee('Tambah');
        });
    }

    /**
     * Test table structure is correct
     */
    public function test_table_structure_is_correct()
    {
        // Arrange
        Branch::factory()->create();

        $this->browse(function (Browser $browser) {
            $browser->visit('/branches')
                    ->assertPresent('table')
                    ->assertPresent('table thead')
                    ->assertPresent('table tbody')
                    ->assertPresent('table tbody tr');
        });
    }
}