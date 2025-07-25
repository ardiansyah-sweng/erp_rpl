<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\BillOfMaterial;

class BillOfMaterialTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_get_existing_bill_of_material_by_id()
    {
        //ambil satu baris dari data tabel(data dummy)
        $bom = BillOfMaterial::first();

        if(!$bom) {
            $this->markTestSkipped('No Bill of Material data fpund in the database.');
            return;
        }

        $response= $this->get('/bom/'.$bom->id);

        //Tambahkan baris ini untuk melihat isi data
        dump('BOM from DB:', $bom->toArray());
        dump('Response JSON:',$response->json());
        
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id'=> $bom->id,
            'bom_id'=>$bom->bom_id,
            'bom_name'=>$bom->bom_name,
        ]);
    }
    
    public function test_get_nonexistent_bill_of_material_by_id()
    {
        //asumsikan ID 999999 tidak ada
        $response = $this->get('/bom/999999');
        $response->assertStatus(404);
        $response->assertJson(['message'=>'Data not found.']);
    }
}
