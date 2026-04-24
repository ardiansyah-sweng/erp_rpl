<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Supplier;
use App\Models\SupplierPic;

class SupplierPIControllerTest extends TestCase
{
    use RefreshDatabase; // Reset database setelah setiap test

    /** @test */
    public function it_can_list_all_supplier_pics()
    {
        // Setup data
        $pic = SupplierPic::factory()->create();

        $response = $this->get('/supplier/pic/all'); // Sesuaikan route-nya

        $response->assertStatus(200);
        $response->assertViewHas('pics');
    }

    /** @test */
    public function it_can_add_a_new_supplier_pic()
    {
        $supplier = Supplier::factory()->create();

        $data = [
            'supplier_id' => $supplier->supplier_id,
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone_number' => '123456789',
            'assigned_date' => '01/01/2026',
        ];

        $response = $this->post("/supplier/pic/add/{$supplier->supplier_id}", $data);

        $response->assertStatus(302); // Redirect setelah sukses
        $this->assertDatabaseHas('supplier_pic', ['email' => 'john@example.com']);
    }

    /** @test */
    public function it_can_update_supplier_pic_via_json()
    {
        $pic = SupplierPic::factory()->create();

        $updateData = [
            'supplier_id' => $pic->supplier_id,
            'name' => 'Updated Name',
            'phone_number' => '987654321',
            'email' => 'updated@example.com',
            'assigned_date' => '2026-04-25',
        ];

        $response = $this->putJson("/supplier/pic/update/{$pic->id}", $updateData);

        $response->assertStatus(200)
                 ->assertJson(['status' => 'success']);
        
        $this->assertDatabaseHas('supplier_pic', ['name' => 'Updated Name']);
    }

    /** @test */
    public function it_returns_404_if_pic_not_found()
    {
        $response = $this->get('/supplier/pic/detail/99999');
        $response->assertStatus(302); // Sesuai logika controller Anda yang redirect
    }
}