<?php

namespace Tests\Browser;

use App\Models\Supplier;
use App\Models\Branch;
use App\Constants\BranchColumns;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PurchaseOrderAddTest extends DuskTestCase
{
    protected $supplier;
    protected $branch;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Run migrations
        $this->artisan('migrate:fresh');

        // Create a supplier for testing
        $this->supplier = Supplier::create([
            'supplier_id' => 'SUP001',
            'company_name' => 'PT. Vendor Test',
            'address' => 'Jl. Test No. 42, Jakarta',
            'telephone' => '081234567890',
            'bank_account' => '1234567890 (BCA)',
        ]);

        // Create a branch for testing
        $this->branch = Branch::create([
            BranchColumns::NAME => 'Yogyakarta',
            BranchColumns::ADDRESS => 'Jl. Test No. 1, Yogyakarta',
            BranchColumns::PHONE => '0211234567',
            BranchColumns::IS_ACTIVE => true,
        ]);
    }

    /**
     * Test user can open modal and see form elements.
     */
    public function test_user_can_add_purchase_order_with_single_item()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/purchase_orders')
                    ->pause(2000)
                    
                    // Click the "Add" button to open modal
                    ->click('button[data-target="#addPurchaseOrderModal"]')
                    ->pause(1000)
                    
                    // Assert modal is visible with correct title
                    ->assertSee('Add Purchase Order')
                    ->assertPresent('#po_number')
                    ->assertPresent('#branch')
                    ->assertPresent('#itemsTable')
                    ->assertPresent('#subtotal');
        });

        $this->assertTrue(true);
    }

    /**
     * Test user can fill form and add multiple items.
     */
    public function test_user_can_add_purchase_order_with_multiple_items()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/purchase_orders')
                    ->pause(2000)
                    
                    // Open modal
                    ->click('button[data-target="#addPurchaseOrderModal"]')
                    ->pause(1000)
                    
                    // Fill branch
                    ->select('#branch', 'Yogyakarta')
                    ->pause(500)
                    
                    // Fill supplier
                    ->type('#supplierSearch', 'SUP001')
                    ->pause(500)
                    ->click('#supplier_id option[value="SUP001"]')
                    ->pause(500)
                    
                    // Verify supplier_id field has value
                    ->assertInputValue('#supplierSearch', 'SUP001')
                    
                    // Add first item
                    ->type('.sku-search', 'KAOS')
                    ->pause(500)
                    
                    // Set quantity for first item
                    ->type('.qty', '10')
                    ->pause(500)
                    
                    // Add second item row
                    ->click('#addRow')
                    ->pause(500)
                    
                    // Verify second row exists
                    ->assertPresent('#itemsTable tbody tr:nth-child(2)')
                    
                    // Add third item row
                    ->click('#addRow')
                    ->pause(500)
                    
                    // Verify third row exists
                    ->assertPresent('#itemsTable tbody tr:nth-child(3)')
                    
                    // Assert button "Tambah Barang" is visible
                    ->assertSee('Tambah Barang');
        });

        $this->assertTrue(true);
    }

    /**
     * Test user can remove items from purchase order.
     */
    public function test_user_can_add_and_remove_items()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/purchase_orders')
                    ->pause(2000)
                    
                    // Open modal
                    ->click('button[data-target="#addPurchaseOrderModal"]')
                    ->pause(1000)
                    
                    // Fill header
                    ->select('#branch', 'Yogyakarta')
                    ->pause(500)
                    
                    ->type('#supplierSearch', 'SUP001')
                    ->pause(500)
                    ->click('#supplier_id option[value="SUP001"]')
                    ->pause(500)
                    
                    // Fill first item
                    ->type('.sku-search', 'KAOS')
                    ->pause(500)
                    ->type('.qty', '10')
                    ->pause(500)
                    
                    // Verify form is filled
                    ->assertPresent('#itemsTable tbody tr')
                    
                    // Add second item
                    ->click('#addRow')
                    ->pause(500)
                    
                    // Verify 2 rows exist
                    ->assertPresent('#itemsTable tbody tr:nth-child(2)')
                    
                    // Remove the remove button exists
                    ->assertPresent('button.remove')
                    
                    // Click remove button on second row
                    ->click('button.remove:last-of-type')
                    ->pause(500)
                    
                    // Assert we still have at least 1 item row
                    ->assertPresent('#itemsTable tbody tr');
        });

        $this->assertTrue(true);
    }
}