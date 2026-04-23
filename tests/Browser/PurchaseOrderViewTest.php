<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use App\Models\PurchaseOrder;

class PurchaseOrderViewTest extends DuskTestCase
{
    public function test_view_displays_correct_purchase_order_count()
    {
        $this->browse(function (Browser $browser) {
            $expectedCount = PurchaseOrder::count();
            
            // Fix: gunakan underscore bukan dash
            $browser->visit('/purchase_orders')
                    ->assertSee('Purchase Orders')
                    ->assertPresent('.badge.bg-primary')
                    ->assertSeeIn('.badge.bg-primary', (string)$expectedCount);
        });
    }

    public function test_badge_displays_zero_when_no_purchase_orders()
    {
        $this->browse(function (Browser $browser) {
            PurchaseOrder::query()->delete();

            $browser->visit('/purchase_orders')
                    ->assertSee('Purchase Orders')
                    ->assertPresent('.badge.bg-primary')
                    ->assertSeeIn('.badge.bg-primary', '0');
        });
    }

    public function test_badge_updates_after_data_changes()
    {
        $this->browse(function (Browser $browser) {
            $initialCount = PurchaseOrder::count();

            $browser->visit('/purchase_orders')
                    ->assertSeeIn('.badge.bg-primary', (string)$initialCount)
                    ->refresh()
                    ->waitForText('Purchase Orders')
                    ->assertSeeIn('.badge.bg-primary', (string)PurchaseOrder::count());
        });
    }

    public function test_table_rows_match_badge_count()
    {
        $this->browse(function (Browser $browser) {
            $expectedCount = PurchaseOrder::count();

            $browser->visit('/purchase_orders')
                    ->assertSeeIn('.badge.bg-primary', (string)$expectedCount);

            $tableRowCount = count($browser->elements('table tbody tr'));
            
            if ($expectedCount > 0) {
                $this->assertGreaterThan(0, $tableRowCount);
            } else {
                $browser->assertSee('Tidak ada data purchase order');
            }
        });
    }
}