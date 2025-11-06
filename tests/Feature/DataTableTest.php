<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Resources\Warehouse;

class DataTableTest extends TestCase
{

    /** @test */
    public function data_table_warehouse_list_exist()
    {

        // Akses route warehouse list
        $response = $this->get('/warehouse/list');

        // Validasi response
        $response->assertStatus(200); 

        // (Opsional) cek data kolom tabel
        $response->assertSee('No');
        $response->assertSee('Warehouse Name');
        $response->assertSee('Warehouse Address');
        $response->assertSee('Warehouse Telephone');
        $response->assertSee('is rm warehouse');
        $response->assertSee('is fg warehouse');
        $response->assertSee('Created At');
        $response->assertSee('Updated At');
        $response->assertSee('Actions');
    }
}
