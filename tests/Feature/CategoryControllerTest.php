<?php

namespace Tests\Feature;

use Tests\TestCase;
use Mockery;
use App\Models\Category;

class CategoryControllerTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_get_category_list_displays_correctly()
    {
        $mockedCategories = [
            ['id' => 1, 'category' => 'Elektronik', 'parent_id' => 0, 'active' => true],
            ['id' => 2, 'category' => 'Laptop', 'parent_id' => 1, 'active' => true],
        ];

        // 👇 Ini baris penting: buat mock alias untuk Category
        $mock = Mockery::mock('alias:' . Category::class);
        $mock->shouldReceive('getAllCategory')
            ->once()
            ->andReturn(collect($mockedCategories)); // dikembalikan sebagai koleksi agar menyerupai DB

        // 👇 Ubah response controller sementara jika belum ada view
        $response = $this->get('/category/list');

        $response->assertStatus(200);
        $response->assertJson([
            'category' => $mockedCategories
        ]);
    }
}
