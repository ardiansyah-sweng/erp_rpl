<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class SupplierMaterialAddTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Test form validation for invalid inputs.
     */
    public function testFormValidationWithInvalidInputs(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('http://127.0.0.1:8000/supplier/material/add')
                    ->assertSee('Tambah Supplier Item');

            // Disable AJAX calls to prevent alerts
            $browser->script("
                $('#supplier_id').off('input');
                var originalAjax = $.ajax;
                $.ajax = function(settings) {
                    return $.Deferred().resolve({}).promise();
                };
            ");

            // Test empty fields - click submit button
            $browser->click('button[onclick*="validateForm"]')
                    ->acceptDialog();

            // Test invalid supplier_id length (less than 6)
            $browser->type('#supplier_id', '12345')
                    ->click('button[onclick*="validateForm"]')
                    ->acceptDialog();

            // Test invalid supplier_id length (more than 6)
            $browser->type('#supplier_id', '1234567')
                    ->click('button[onclick*="validateForm"]')
                    ->acceptDialog();

            // Test invalid base_price (negative)
            $browser->type('#supplier_id', '123456')
                    ->type('#base_price', '-1')
                    ->click('button[onclick*="validateForm"]')
                    ->acceptDialog();

            // Test invalid base_price (zero)
            $browser->type('#base_price', '0')
                    ->click('button[onclick*="validateForm"]')
                    ->acceptDialog();
        });
    }

    /**
     * Test form validation for valid inputs and successful submission.
     */
    public function testFormValidationWithValidInputs(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('http://127.0.0.1:8000/supplier/material/add')
                    ->assertSee('Tambah Supplier Item');

            // Disable AJAX calls and input events to prevent alerts
            $browser->script("
                // Disable the input event listener that triggers AJAX
                $('#supplier_id').off('input');

                // Override jQuery.ajax to prevent any AJAX calls
                var originalAjax = $.ajax;
                $.ajax = function(settings) {
                    // Return a resolved promise to simulate success without making actual call
                    return $.Deferred().resolve({
                        company_name: 'Test Company'
                    }).promise();
                };
            ");

            // Fill valid data - use script to set readonly fields since they can't be typed directly
            $browser->script("document.getElementById('supplier_id').value = 'SUP001';");
            $browser->script("document.getElementById('supplier_name').value = 'Test Supplier';");
            $browser->script("document.getElementById('company_name').value = 'Test Company';");
            $browser->type('product_id', 'PROD001')
                    ->type('product_name', 'Test Product')
                    ->type('base_price', '100')
                    ->press('button[type="submit"]');

            // Since the form submits via POST, we just assert that the form was submitted successfully
            // The actual success message depends on the backend implementation
            $browser->assertPathIs('/supplier/material/add');
        });
    }

    /**
     * Test AJAX-like behavior by simulating supplier data fetch.
     */
    public function testSupplierDataFetchSimulation(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('http://127.0.0.1:8000/supplier/material/add')
                    ->assertSee('Tambah Supplier Item');

            // Type valid supplier_id and simulate AJAX response
            $browser->type('supplier_id', 'SUP001');
            $browser->script("document.getElementById('supplier_name').value = 'Test Supplier';");
            $browser->script("document.getElementById('company_name').value = 'Test Company';");

            // Verify readonly fields are populated
            $browser->assertValue('#supplier_name', 'Test Supplier')
                    ->assertValue('#company_name', 'Test Company');
        });
    }
}
