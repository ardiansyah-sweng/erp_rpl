<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\Supplier;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SupplierDatatablesTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Setup dummy data for testing
     */
    protected function createDummySuppliers($count = 5)
    {
        $suppliers = [];
        for ($i = 1; $i <= $count; $i++) {
            $suppliers[] = Supplier::create([
                'supplier_id' => 'SUP00' . $i,
                'company_name' => 'PT Test Company ' . $i,
                'address' => 'Jl. Test No. ' . $i,
                'telephone' => '08123456789' . $i,  // ✅ DIPERBAIKI: telephone
                'bank_account' => '123456789' . $i,
            ]);
        }
        return $suppliers;
    }

    /**
     * Test 1: User can access supplier list page
     */
    public function test_user_can_access_supplier_list_page(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/supplier/list')
                ->pause(1000)
                ->assertSee('Suppliers');
        });
    }

    /**
     * Test 2: Datatable displays correct columns
     */
    public function test_datatable_displays_correct_columns(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/supplier/list')
                ->pause(1000)
                ->assertSee('No')
                ->assertSee('Company Name')
                ->assertSee('Address')
                ->assertSee('Phone Number')
                ->assertSee('Bank Account')
                ->assertSee('Action');
        });
    }

    /**
     * Test 3: Search input is present on the page
     */
    public function test_search_input_is_present(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/supplier/list')
                ->pause(1000)
                ->assertSee('Search')
                ->assertPresent('input#supplierSearch')
                ->assertAttribute('input#supplierSearch', 'type', 'text');
        });
    }

    /**
     * Test 4: New Supplier button is present
     */
    public function test_new_supplier_button_is_present(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/supplier/list')
                ->pause(1000)
                ->assertPresent('a.btn.btn-primary');
        });
    }

    /**
     * Test 5: Action buttons are displayed in each row
     */
    public function test_action_buttons_are_displayed(): void
    {
        // Create dummy data
        $this->createDummySuppliers(1);

        $this->browse(function (Browser $browser) {
            $browser->visit('/supplier/list')
                ->pause(2000)
                ->assertSee('Edit')
                ->assertSee('Detail')
                ->assertSee('Delete');
        });
    }

    /**
     * Test 6: Datatable shows empty state when no data available
     */
    public function test_datatable_shows_empty_state(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/supplier/list')
                ->pause(2000)
                ->assertSee('No data available in table');
        });
    }

    /**
     * Test 7: Pagination navigation is present
     */
    public function test_pagination_navigation_is_present(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/supplier/list')
                ->pause(1000)
                ->assertPresent('ul.pagination')
                ->assertSee('Previous')
                ->assertSee('Next');
        });
    }

    /**
     * Test 8: Showing entries information is displayed
     */
    public function test_showing_entries_information_is_displayed(): void
    {
        // Create dummy data
        $this->createDummySuppliers(3);

        $this->browse(function (Browser $browser) {
            $browser->visit('/supplier/list')
                ->pause(2000)
                ->assertSee('Showing')
                ->assertSee('entries');
        });
    }
}