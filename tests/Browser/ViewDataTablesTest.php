<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ViewDataTablesTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     */
    public function test_warehouse_list_page_loads()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(1) // hapus kalau tidak pakai auth
                    ->visit('/warehouse/list')
                    ->assertSee('Warehouse')
                    ->assertSee('List Warehouse')
                    ->assertPresent('@warehouse-table');
        });
    }
    public function test_click_add_warehouse()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(1)
                    ->visit('/warehouse/list')
                    ->click('btn btn-primary btn-sm')
                    ->assertPathIs('/warehouse/add');
        });
    }

    public function test_click_edit_warehouse()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(1)
                    ->visit('/warehouse/list')
                    ->click('@btn-edit-1');
                    //->assertPathBeginsWith('/warehouse/edit'); path = #
        });
    }

    

    public function test_detail_warehouse()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(1)
                    ->visit('/warehouse/list')
                    ->click('@btn-detail-1')
                    ->assertPathBeginsWith('/warehouse/detail');
        });
    }
}
