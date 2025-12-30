<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Category;

class CategoryTest extends TestCase
{
    use RefreshDatabase; // Menghapus data setelah test selesai

    /** @test */
public function it_can_insert_category_to_database()
{
    // Ubah 'name' menjadi 'category' sesuai permintaan database Anda
    $categoryData = [
        'category' => 'Elektronik', 
    ];

    // Jalankan aksi
    \App\Models\Category::create($categoryData);

    // Verifikasi di database
    $this->assertDatabaseHas('categories', [
        'category' => 'Elektronik'
    ]);
}
}