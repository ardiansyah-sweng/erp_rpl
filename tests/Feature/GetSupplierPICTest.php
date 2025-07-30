<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class GetSupplierPICTest extends TestCase
{
    public function test_get_supplier_pic_with_lama_assigned()
    {
        $pic = DB::table('supplier_pic')->where('supplier_id', 'SUP002')->first();

        if (!$pic) {
            $this->markTestSkipped('No PIC data found in the database.');
        }

        $response = $this->get('/supplier-pic/' . $pic->supplier_id);

        dd($response->json());

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'supplier_id',
                        'name',
                        'email',
                        'phone',
                        'position',
                        'assigned_date',
                        'lama_assigned',
                    ]
                ]
            ]);

        $responseData = $response->json('data');

        foreach ($responseData as $item) {
            $this->assertIsInt($item['lama_assigned']);
        }
    }
}
