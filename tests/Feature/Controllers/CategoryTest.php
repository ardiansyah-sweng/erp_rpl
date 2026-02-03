<?php

namespace Tests\Feature\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class CategoryTest extends TestCase
{
    use RefreshDatabase; // Pastikan ini ada untuk reset database setiap test

    /** @test */
    public function test_user_can_add_category()
    {
        // Jika route 'categories.store' butuh login, uncomment baris di bawah:
        // $user = User::factory()->create();
        // $this->actingAs($user);

        // 1. Kirim data category
        $response = $this->post(route('categories.store'), [
            'category' => 'Elektronik',
        ]);

        // 2. Assert Redirect (biasanya setelah store akan redirect ke index/create)
        // Status 302 berarti redirect berhasil
        $response->assertStatus(302);

        // 3. Pastikan data masuk ke database
        $this->assertDatabaseHas('categories', [
            'category' => 'Elektronik'
        ]);
    }
}