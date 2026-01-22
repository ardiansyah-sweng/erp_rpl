<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    } // Baris 19: Menutup fungsi test_example

    public function test_user_can_add_category()
{
    // 1. Gunakan 'category' sebagai key, bukan 'name'
    // 2. Hapus 'description' karena kolomnya tidak ada di database
    $response = $this->post(route('categories.store'), [
        'category' => 'Elektronik',
    ]);

    $response->assertStatus(302);

    // Pastikan pengecekan database juga menggunakan kolom 'category'
    $this->assertDatabaseHas('categories', [
        'category' => 'Elektronik'
    ]);
}
    }