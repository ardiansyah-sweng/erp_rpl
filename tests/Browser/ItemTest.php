<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ItemTest extends DuskTestCase
{
    public function testSuccessfulAdd()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/item/add')
                    ->type('product_id', '1234')
                    ->type('sku', 'testsku')
                    ->type('item_name', 'Test Item')
                    ->select('measurement_unit', '30')
                    ->type('selling_price', '10000')
                    ->press('Add')
                    ->assertPathIs('/item');
        });
    }

    public function testProductIdTooShort()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/item/add')
                    ->type('product_id', '12')
                    ->type('sku', 'testsku')
                    ->type('item_name', 'Test Item')
                    ->select('measurement_unit', '2')
                    ->type('selling_price', '10000')
                    ->press('Add')
                    ->assertSeeIn('.error-message', 'ID Produk harus terdiri dari 4 karakter.');
        });
    }

    public function testSkuEmpty()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/item/add')
                    ->type('product_id', '1234')
                    ->type('sku', '')
                    ->type('item_name', 'Test Item')
                    ->select('measurement_unit', '3')
                    ->type('selling_price', '10000')
                    ->press('Add')
                    ->assertSeeIn('.error-message', 'SKU harus diisi.');
        });
    }

    public function testItemNameEmpty()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/item/add')
                    ->type('product_id', '1234')
                    ->type('sku', 'testsku')
                    ->type('item_name', '')
                    ->select('measurement_unit', '6')
                    ->type('selling_price', '10000')
                    ->press('Add')
                    ->assertSeeIn('.error-message', 'Nama Item Produk harus diisi.');
        });
    }

    public function testMeasurementUnitNotSelected()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/item/add')
                    ->type('product_id', '1234')
                    ->type('sku', 'testsku')
                    ->type('item_name', 'Test Item')
                    ->type('selling_price', '10000')
                    ->press('Add')
                    ->assertSeeIn('.error-message', 'Unit harus dipilih.');
        });
    }

    public function testSellingPriceEmpty()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/item/add')
                    ->type('product_id', '1234')
                    ->type('sku', 'testsku')
                    ->type('item_name', 'Test Item')
                    ->select('measurement_unit', '5')
                    ->type('selling_price', '')
                    ->press('Add')
                    ->assertSeeIn('.error-message', 'Harga Jual harus diisi.');
        });
    }
}
