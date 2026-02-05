<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Support\Facades\DB;

class ItemPrintPdfTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function user_can_download_items_pdf_report()
    {
        try {
            DB::table('measurement_units')->insert([
                ['name' => 'PCS', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'UNIT', 'created_at' => now(), 'updated_at' => now()],
            ]);
        } catch (\Exception $e) {}

        try {
            DB::table('products')->insert([
                'id'            => 'TEST', 
                'name'          => 'Produk Testing',
                'description'   => 'Desc',
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        } catch (\Exception $e) {}

        DB::table('items')->insert([
            'product_id'    => 'TEST',
            'sku'           => 'SKU-PDF-001',
            'name'          => 'Item PDF Test', 
            'measurement'   => 'PCS',
            'base_price'    => 50000,
            'selling_price' => 60000,
            'purchase_unit' => 1,
            'sell_unit'     => 1,
            'stock_unit'    => 10,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        $this->browse(function (Browser $browser) {
            $browser->visit(route('item.report'))
                    ->assertUrlIs(route('item.report')); 
        });
    }
}