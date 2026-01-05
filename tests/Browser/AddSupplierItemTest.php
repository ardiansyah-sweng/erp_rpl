<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class SupplierMaterialTest extends DuskTestCase
{
    public function test_can_add_supplier_material()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/supplier/material/add')
                    ->assertSee('Tambah Supplier Item')

                    ->type('supplier_id', 'SUP001')
                    ->type('supplier_name', 'PT Sumber Makmur')
                    ->type('SKU', 'SKU-001')
                    ->type('nama_item', 'Tepung Terigu')
                    ->type('base_price', '15000')

                    ->press('btn btn-primary')

                    ->assertPathIs('/supplier/material')
                    ->assertSee('Supplier');
        });
    }

    public function test_validation_error_when_required_empty()
{
    $this->browse(function (Browser $browser) {
        $browser->visit('/supplier/material/add')
                ->press('btn btn-primary')
                ->assertSee('Harus diisi');
    });
}

}
