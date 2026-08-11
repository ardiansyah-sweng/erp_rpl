<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Warehouse;

class ViewDataTableTest extends TestCase
{

    /** @test */
    public function it_displays_the_warehouse_data_table(): void
    {
        $response = $this->get('/warehouse');

        $response->assertStatus(200);
        $response->assertSee('Warehouse');
    }

    /** @test */
    public function it_displays_correct_warehouse_data_for_valid_id()
    {
        
        $response = $this->get('/warehouse');
        $data = [
            'warehouse_name' => 'Gudang delectus',
            'warehouse_address' => 'Dk. Nanas No. 728, Serang 72882, Pabar',
            'warehouse_telephone' => '(+62) 885 4894 310',
            'is_rm_whouse' => 1,
            'is_fg_whouse' => 0,
            'is_active' => true,
        ];
        
        $response = $this->postJson('/warehouse/add', $data);
        $response->assertStatus(200);

        $response = $this->get(route('warehouse.detail', $data['id'] ?? 1));


        $response->assertSee('Gudang delectus');
        $response->assertSee('Dk. Nanas No. 728, Serang 72882, Pabar');
        $response->assertSee('(+62) 885 4894 310');

        $response->assertSee((string) $data['is_rm_whouse']);
        $response->assertSee((string) $data['is_fg_whouse']);
    }

    /** @test */
    public function it_can_move_to_next_page(){
        $response = $this->get('/warehouse?page=2');
        $response->assertStatus(200);


        $response = $this->get('/warehouse?page=3');
        $response->assertStatus(200);
    }
}